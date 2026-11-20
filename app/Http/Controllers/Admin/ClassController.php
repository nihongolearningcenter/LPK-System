<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRoom::with(['program', 'instructor'])
            ->withCount('classStudents');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $classes = $query->latest()->paginate(10)->withQueryString();
        $programs = Program::active()->get();

        return view('admin.kelas.index', compact('classes', 'programs'));
    }

    public function create()
    {
        $programs = Program::active()->get();
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.kelas.create', compact('programs', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'instructor_id' => 'nullable|exists:users,id',
            'name'          => 'required|string|max:255',
            'code'          => 'nullable|string|max:50',
            'status'        => 'required|in:active,upcoming,completed,cancelled',
            'max_students'  => 'nullable|integer|min:1',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'meeting_link'  => 'nullable|url',
            'notes'         => 'nullable|string',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = 'CLS-' . strtoupper(substr(uniqid(), -6));
        }

        $class = ClassRoom::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil dibuat!');
    }

    public function show(ClassRoom $kelas)
    {
        $kelas->load(['program', 'instructor', 'schedules', 'classStudents.student.studentProfile']);
        $allStudents = User::where('role', 'student')->where('is_active', true)->get();

        return view('admin.kelas.show', [
            'class'       => $kelas,
            'allStudents' => $allStudents,
        ]);
    }

    public function edit(ClassRoom $kelas)
    {
        $programs = Program::active()->get();
        $instructors = User::where('role', 'instructor')->where('is_active', true)->get();

        return view('admin.kelas.edit', [
            'class'       => $kelas,
            'programs'    => $programs,
            'instructors' => $instructors,
        ]);
    }

    public function update(Request $request, ClassRoom $kelas)
    {
        $validated = $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'instructor_id' => 'nullable|exists:users,id',
            'name'          => 'required|string|max:255',
            'code'          => 'nullable|string|max:50',
            'status'        => 'required|in:active,upcoming,completed,cancelled',
            'max_students'  => 'nullable|integer|min:1',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'meeting_link'  => 'nullable|url',
            'notes'         => 'nullable|string',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(ClassRoom $kelas)
    {
        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function addStudent(Request $request, ClassRoom $kelas)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $kelas->classStudents()->firstOrCreate(
            ['student_id' => $request->student_id],
            ['joined_at' => now(), 'status' => 'active']
        );

        return redirect()->back()->with('success', 'Siswa berhasil dimasukkan ke kelas!');
    }

    public function removeStudent(ClassRoom $kelas, User $student)
    {
        $kelas->classStudents()->where('student_id', $student->id)->delete();
        return redirect()->back()->with('success', 'Siswa dikeluarkan dari kelas.');
    }

    public function addSchedule(Request $request, ClassRoom $kelas)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'room'        => 'nullable|string|max:100',
            'notes'       => 'nullable|string',
        ]);

        $kelas->schedules()->create($validated);

        return redirect()->back()->with('success', 'Jadwal sesi kelas berhasil ditambahkan!');
    }

    public function removeSchedule(ClassRoom $kelas, \App\Models\Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Jadwal sesi kelas berhasil dihapus.');
    }
}
