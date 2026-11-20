<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with('studentProfile');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        return view('admin.siswa.index', compact('students'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'phone'                 => 'nullable|string|max:20',
            'nik'                   => 'nullable|string|max:20|unique:student_profiles,nik',
            'gender'                => 'nullable|in:male,female',
            'birth_place'           => 'nullable|string|max:100',
            'birth_date'            => 'nullable|date',
            'address'               => 'nullable|string',
            'japanese_level_target' => 'nullable|string|max:10',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'student',
            'is_active' => true,
        ]);

        StudentProfile::create([
            'user_id'               => $user->id,
            'phone'                 => $request->phone,
            'nik'                   => $request->nik,
            'gender'                => $request->gender,
            'birth_place'           => $request->birth_place,
            'birth_date'            => $request->birth_date,
            'address'               => $request->address,
            'japanese_level_target' => $request->japanese_level_target ?? 'N4',
        ]);

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(User $siswa)
    {
        $siswa->load([
            'studentProfile',
            'enrollments.program',
            'classes.program',
            'examAttempts.exam',
            'grades.subject',
            'certificates',
        ]);

        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(User $siswa)
    {
        $siswa->load('studentProfile');
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, User $siswa)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $siswa->id,
            'password'              => 'nullable|string|min:8|confirmed',
            'phone'                 => 'nullable|string|max:20',
            'nik'                   => 'nullable|string|max:20|unique:student_profiles,nik,' . optional($siswa->studentProfile)->id,
            'gender'                => 'nullable|in:male,female',
            'birth_place'           => 'nullable|string|max:100',
            'birth_date'            => 'nullable|date',
            'address'               => 'nullable|string',
            'japanese_level_target' => 'nullable|string|max:10',
            'is_active'             => 'nullable|boolean',
        ]);

        $siswa->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if (!empty($validated['password'])) {
            $siswa->update(['password' => Hash::make($validated['password'])]);
        }

        StudentProfile::updateOrCreate(
            ['user_id' => $siswa->id],
            [
                'phone'                 => $request->phone,
                'nik'                   => $request->nik,
                'gender'                => $request->gender,
                'birth_place'           => $request->birth_place,
                'birth_date'            => $request->birth_date,
                'address'               => $request->address,
                'japanese_level_target' => $request->japanese_level_target,
            ]
        );

        return redirect()->route('admin.siswa.show', $siswa)
                         ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(User $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil dihapus.');
    }
}
