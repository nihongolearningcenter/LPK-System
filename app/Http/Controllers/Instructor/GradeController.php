<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index(Request $request, ClassRoom $kelas)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $subjects = Subject::where('program_id', $kelas->program_id)
            ->orWhereNull('program_id')
            ->orderBy('name')
            ->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name')->get();
        }

        $selectedSubjectId = $request->get('subject_id', $subjects->first()?->id);
        $kelas->load('students.studentProfile');
        $students = $kelas->students;

        $grades = collect();
        if ($selectedSubjectId) {
            $grades = Grade::where('class_id', $kelas->id)
                ->where('subject_id', $selectedSubjectId)
                ->get()
                ->keyBy('student_id');
        }

        return view('instructor.nilai.index', [
            'class'             => $kelas,
            'subjects'          => $subjects,
            'students'          => $students,
            'grades'            => $grades,
            'selectedSubjectId' => $selectedSubjectId,
        ]);
    }

    public function store(Request $request, ClassRoom $kelas)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $validated = $request->validate([
            'subject_id'                 => 'required|exists:subjects,id',
            'grades'                     => 'required|array',
            'grades.*.student_id'        => 'required|exists:users,id',
            'grades.*.score_attendance'  => 'nullable|numeric|min:0|max:100',
            'grades.*.score_assignments' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_midterm'     => 'nullable|numeric|min:0|max:100',
            'grades.*.score_final'       => 'nullable|numeric|min:0|max:100',
            'grades.*.notes'             => 'nullable|string|max:500',
        ]);

        foreach ($validated['grades'] as $item) {
            $attendance  = isset($item['score_attendance']) && $item['score_attendance'] !== '' ? (float)$item['score_attendance'] : null;
            $assignments = isset($item['score_assignments']) && $item['score_assignments'] !== '' ? (float)$item['score_assignments'] : null;
            $midterm     = isset($item['score_midterm']) && $item['score_midterm'] !== '' ? (float)$item['score_midterm'] : null;
            $final       = isset($item['score_final']) && $item['score_final'] !== '' ? (float)$item['score_final'] : null;

            $finalGrade = null;
            $gradeLetter = null;

            if ($attendance !== null || $assignments !== null || $midterm !== null || $final !== null) {
                $att = $attendance ?? 0;
                $asg = $assignments ?? 0;
                $mid = $midterm ?? 0;
                $fin = $final ?? 0;

                // 15% Attendance, 25% Assignment, 25% Midterm, 35% Final
                $finalGrade = round(($att * 0.15) + ($asg * 0.25) + ($mid * 0.25) + ($fin * 0.35), 2);

                if ($finalGrade >= 85) {
                    $gradeLetter = 'A';
                } elseif ($finalGrade >= 75) {
                    $gradeLetter = 'B';
                } elseif ($finalGrade >= 65) {
                    $gradeLetter = 'C';
                } elseif ($finalGrade >= 50) {
                    $gradeLetter = 'D';
                } else {
                    $gradeLetter = 'E';
                }
            }

            Grade::updateOrCreate(
                [
                    'class_id'   => $kelas->id,
                    'subject_id' => $validated['subject_id'],
                    'student_id' => $item['student_id'],
                ],
                [
                    'score_attendance'  => $attendance,
                    'score_assignments' => $assignments,
                    'score_midterm'     => $midterm,
                    'score_final'       => $final,
                    'final_grade'       => $finalGrade,
                    'grade_letter'      => $gradeLetter,
                    'notes'             => $item['notes'] ?? null,
                    'recorded_by'       => Auth::id(),
                ]
            );
        }

        return redirect()->route('instructor.nilai.index', [
            'kelas'      => $kelas->id,
            'subject_id' => $validated['subject_id'],
        ])->with('success', 'Nilai akademik siswa berhasil disimpan.');
    }
}
