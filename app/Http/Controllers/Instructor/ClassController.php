<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classes = ClassRoom::where('instructor_id', $user->id)
            ->with(['program', 'schedules'])
            ->withCount(['students', 'lessons', 'assignments'])
            ->latest()
            ->paginate(12);

        return view('instructor.kelas.index', compact('classes'));
    }

    public function show(ClassRoom $kelas)
    {
        $this->authorizeInstructor($kelas);

        $kelas->load([
            'program',
            'schedules',
            'students.studentProfile',
            'lessons' => fn($q) => $q->latest()->take(5),
            'assignments' => fn($q) => $q->latest()->take(5),
        ]);

        return view('instructor.kelas.show', ['class' => $kelas]);
    }

    public function students(ClassRoom $kelas)
    {
        $this->authorizeInstructor($kelas);

        $kelas->load(['students.studentProfile', 'program']);

        return view('instructor.kelas.students', ['class' => $kelas]);
    }

    private function authorizeInstructor(ClassRoom $classRoom): void
    {
        if ($classRoom->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan instruktur untuk kelas ini.');
        }
    }
}
