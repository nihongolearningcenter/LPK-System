<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->studentProfile ?? new StudentProfile();

        return view('student.profil', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'nullable|in:L,P',
            'address'         => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:50',
            'whatsapp'        => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'birth_date'      => $request->birth_date,
                'gender'          => $request->gender,
                'address'         => $request->address,
                'education_level' => $request->education_level,
                'whatsapp'        => $request->whatsapp ?? $request->phone,
            ]
        );

        return redirect()->route('student.profil.edit')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
