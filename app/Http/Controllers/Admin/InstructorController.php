<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InstructorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'instructor')->with(['instructorProfile', 'taughtClasses']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $instructors = $query->latest()->paginate(15)->withQueryString();
        return view('admin.pengajar.index', compact('instructors'));
    }

    public function create()
    {
        return view('admin.pengajar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'speciality'  => 'nullable|string|max:255',
            'bio'         => 'nullable|string',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'instructor',
            'is_active' => true,
        ]);

        InstructorProfile::create([
            'user_id'    => $user->id,
            'phone'      => $request->phone,
            'speciality' => $request->speciality,
            'bio'        => $request->bio,
        ]);

        return redirect()->route('admin.pengajar.index')
                         ->with('success', 'Pengajar berhasil ditambahkan.');
    }

    public function edit(User $pengajar)
    {
        $pengajar->load('instructorProfile');
        return view('admin.pengajar.edit', compact('pengajar'));
    }

    public function update(Request $request, User $pengajar)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $pengajar->id,
            'password'    => 'nullable|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'speciality'  => 'nullable|string|max:255',
            'bio'         => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $pengajar->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if (!empty($validated['password'])) {
            $pengajar->update(['password' => Hash::make($validated['password'])]);
        }

        InstructorProfile::updateOrCreate(
            ['user_id' => $pengajar->id],
            [
                'phone'      => $request->phone,
                'speciality' => $request->speciality,
                'bio'        => $request->bio,
            ]
        );

        return redirect()->route('admin.pengajar.index')
                         ->with('success', 'Data pengajar berhasil diperbarui.');
    }

    public function destroy(User $pengajar)
    {
        $pengajar->delete();
        return redirect()->route('admin.pengajar.index')
                         ->with('success', 'Data pengajar berhasil dihapus.');
    }
}
