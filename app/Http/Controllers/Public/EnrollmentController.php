<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\ClassRoom;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function create(Request $request)
    {
        $programs = Program::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $classes = ClassRoom::whereIn('status', ['active', 'ongoing', 'upcoming'])
            ->with('program')
            ->orderBy('start_date')
            ->get();

        $selectedProgram = $request->query('program');

        return view('public.enrollment.create', compact('programs', 'classes', 'selectedProgram'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'           => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'phone'               => 'required|string|max:20',
            'birth_date'          => 'nullable|date',
            'gender'              => 'nullable|in:L,P',
            'address'             => 'nullable|string|max:1000',
            'education_level'     => 'nullable|string|max:20',
            'program_id'          => 'required|exists:programs,id',
            'class_id'            => 'nullable|exists:classes,id',
            'japanese_experience' => 'nullable|string|max:100',
            'schedule_preference' => 'nullable|string|max:100',
            'notes'               => 'nullable|string|max:2000',
            'photo'               => 'nullable|image|max:2048',
        ], [
            'full_name.required'  => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'phone.required'      => 'Nomor WhatsApp wajib diisi.',
            'program_id.required' => 'Pilih program yang diminati.',
            'program_id.exists'   => 'Program yang dipilih tidak valid.',
            'photo.image'         => 'File foto harus berupa gambar.',
            'photo.max'           => 'Ukuran foto maksimal 2MB.',
        ]);

        // Generate nomor pendaftaran unik
        $validated['registration_number'] = 'NLC-' . date('Ym') . '-' . strtoupper(Str::random(5));

        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('enrollment-photos', 'public');
        }

        // 1. Dapatkan atau buat data akun siswa (status non-aktif hingga diverifikasi admin)
        $user = auth()->user();

        if (!$user) {
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name'      => $validated['full_name'],
                    'email'     => $validated['email'],
                    'phone'     => $validated['phone'],
                    'password'  => Hash::make(Str::random(16)), // Password acak awal, diset saat diverifikasi admin
                    'role'      => 'student',
                    'is_active' => false, // Non-aktif: wajib diverifikasi admin terlebih dahulu
                ]);
            }
        }

        if ($user) {
            StudentProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'registration_number' => $validated['registration_number'],
                    'birth_date'          => $validated['birth_date'] ?? null,
                    'gender'              => $validated['gender'] ?? null,
                    'address'             => $validated['address'] ?? null,
                    'education_level'     => $validated['education_level'] ?? null,
                    'japanese_level'      => $validated['japanese_experience'] ?? 'Dasar',
                    'whatsapp'            => $validated['phone'],
                    'status'              => 'inactive', // inactive until admin verifies
                ]
            );
            $validated['user_id'] = $user->id;
        }

        $validated['status'] = 'pending';

        $enrollment = Enrollment::create($validated);

        // 2. Buat Tagihan Pembayaran sesuai kelas / program yang didaftar
        $program = Program::find($validated['program_id']);
        $class = !empty($validated['class_id']) ? ClassRoom::find($validated['class_id']) : null;
        $amount = $program ? ($program->price ?? 0) : 0;

        $desc = 'Biaya Pendaftaran & Pelatihan ' . ($program->name ?? 'Program Bahasa Jepang');
        if ($class) {
            $desc .= ' (' . $class->name . ')';
        }

        Payment::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'student_id'     => $user->id,
            'enrollment_id'  => $enrollment->id,
            'class_id'       => $class?->id,
            'description'    => $desc,
            'amount'         => $amount,
            'amount_paid'    => 0,
            'discount'       => 0,
            'due_date'       => now()->addDays(7),
            'status'         => 'unpaid',
            'notes'          => 'Tagihan otomatis pendaftaran online #' . $enrollment->registration_number,
        ]);

        return redirect()->route('enrollment.success', $enrollment->registration_number);
    }

    public function success(string $registration_number)
    {
        $enrollment = Enrollment::where('registration_number', $registration_number)
            ->with(['program', 'classRoom', 'user'])
            ->firstOrFail();

        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->orWhere(function ($q) use ($enrollment) {
                if ($enrollment->user_id) {
                    $q->where('student_id', $enrollment->user_id);
                }
            })
            ->latest()
            ->first();

        return view('public.enrollment.success', compact('enrollment', 'payment'));
    }

    public function uploadPaymentProof(Request $request, string $registration_number)
    {
        $enrollment = Enrollment::where('registration_number', $registration_number)->firstOrFail();

        $request->validate([
            'payment_proof'  => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:3072',
            'payment_method' => 'nullable|in:transfer,tunai,lainnya',
            'sender_name'    => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:500',
        ], [
            'payment_proof.required' => 'File bukti pembayaran wajib diunggah.',
            'payment_proof.file'     => 'Bukti pembayaran harus berupa berkas file.',
            'payment_proof.mimes'    => 'Format file bukti harus berupa JPG, PNG, WEBP, atau PDF.',
            'payment_proof.max'      => 'Ukuran berkas maksimal 3 MB.',
        ]);

        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->orWhere(function ($q) use ($enrollment) {
                if ($enrollment->user_id) {
                    $q->where('student_id', $enrollment->user_id);
                }
            })
            ->latest()
            ->first();

        if (!$payment) {
            $program = $enrollment->program;
            $class = $enrollment->classRoom;
            $amount = $program ? ($program->price ?? 0) : 0;
            $desc = 'Biaya Pendaftaran & Pelatihan ' . ($program->name ?? 'Program Bahasa Jepang');
            if ($class) {
                $desc .= ' (' . $class->name . ')';
            }

            $payment = Payment::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'student_id'     => $enrollment->user_id ?? 1,
                'enrollment_id'  => $enrollment->id,
                'class_id'       => $class?->id,
                'description'    => $desc,
                'amount'         => $amount,
                'amount_paid'    => 0,
                'discount'       => 0,
                'due_date'       => now()->addDays(7),
                'status'         => 'unpaid',
                'notes'          => 'Tagihan pendaftaran online #' . $enrollment->registration_number,
            ]);
        }

        $proofPath = $request->file('payment_proof')->store('payments', 'public');

        $notesLog = 'Bukti diunggah pendaftar pada ' . now()->format('d M Y, H:i');
        if ($request->filled('sender_name')) {
            $notesLog .= ' | Pengirim: ' . $request->sender_name;
        }
        if ($request->filled('notes')) {
            $notesLog .= ' | Catatan: ' . $request->notes;
        }

        $payment->update([
            'payment_proof'  => $proofPath,
            'payment_method' => $request->input('payment_method', 'transfer'),
            'notes'          => $notesLog,
        ]);

        return redirect()
            ->route('enrollment.success', $enrollment->registration_number)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Tim Admin LPK akan segera memverifikasi pembayaran Anda.');
    }
}
