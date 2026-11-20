<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Program;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['program'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        $enrollments = $query->paginate(15)->withQueryString();
        $statusCounts = [
            'pending'  => Enrollment::where('status', 'pending')->count(),
            'accepted' => Enrollment::where('status', 'accepted')->count(),
            'rejected' => Enrollment::where('status', 'rejected')->count(),
        ];

        return view('admin.pendaftaran.index', compact('enrollments', 'statusCounts'));
    }

    public function show(Enrollment $pendaftaran)
    {
        $pendaftaran->load(['program', 'classRoom', 'user.studentProfile', 'reviewer', 'payments']);
        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    public function accept(Enrollment $pendaftaran)
    {
        // 1. Cek atau buat user siswa
        $user = $pendaftaran->user;
        $isNewUser = false;
        $plainPassword = null;

        if (!$user) {
            $user = User::where('email', $pendaftaran->email)->first();

            if (!$user) {
                $plainPassword = 'siswa' . rand(100, 999) . '!';
                $user = User::create([
                    'name'      => $pendaftaran->full_name,
                    'email'     => $pendaftaran->email,
                    'phone'     => $pendaftaran->phone,
                    'password'  => Hash::make($plainPassword),
                    'role'      => 'student',
                    'is_active' => true,
                ]);
                $isNewUser = true;
            }
        }

        // 2. Aktifkan akun dan buat/update profil siswa
        if ($user) {
            $user->update(['is_active' => true]);

            StudentProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'registration_number' => $pendaftaran->registration_number,
                    'birth_date'          => $pendaftaran->birth_date,
                    'gender'              => $pendaftaran->gender,
                    'address'             => $pendaftaran->address,
                    'education_level'     => $pendaftaran->education_level,
                    'japanese_level'      => $pendaftaran->japanese_experience ?? 'Dasar',
                    'whatsapp'            => $pendaftaran->phone,
                    'status'              => 'active',
                ]
            );

            if ($user->studentProfile) {
                $user->studentProfile->update(['status' => 'active']);
            }
        }

        // 3. Masukkan siswa ke kelas jika ada class_id
        if ($user && $pendaftaran->class_id) {
            \App\Models\ClassStudent::firstOrCreate(
                ['class_id' => $pendaftaran->class_id, 'student_id' => $user->id],
                ['joined_at' => now(), 'status' => 'active']
            );
        }

        // 4. Update status pendaftaran
        $pendaftaran->update([
            'status'      => 'accepted',
            'user_id'     => $user ? $user->id : $pendaftaran->user_id,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $msg = 'Pendaftaran berhasil diterima dan akun siswa telah aktif!';
        if ($isNewUser && $plainPassword) {
            $msg .= ' Akun siswa baru berhasil dibuat (Email: ' . $user->email . ', Password: ' . $plainPassword . ').';
        }

        return redirect()->route('admin.pendaftaran.show', $pendaftaran)->with('success', $msg);
    }

    public function reject(Request $request, Enrollment $pendaftaran)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $pendaftaran->update([
            'status'      => 'rejected',
            'admin_notes' => $request->reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.pendaftaran.show', $pendaftaran)->with('success', 'Pendaftaran telah ditolak.');
    }
}
