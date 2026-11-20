<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassRoom::with('program')->orderBy('name')->get();
        $selectedClassId = $request->get('class_id', $classes->first()?->id);
        
        $selectedClass = null;
        $subjects = collect();
        $students = collect();
        $grades = collect();

        if ($selectedClassId) {
            $selectedClass = ClassRoom::with(['program', 'students'])->find($selectedClassId);
            if ($selectedClass) {
                $subjects = Subject::where('program_id', $selectedClass->program_id)
                    ->orWhereNull('program_id')
                    ->orderBy('name')
                    ->get();
                if ($subjects->isEmpty()) {
                    $subjects = Subject::orderBy('name')->get();
                }

                $selectedSubjectId = $request->get('subject_id', $subjects->first()?->id);
                $students = $selectedClass->students;

                if ($selectedSubjectId) {
                    $grades = Grade::where('class_id', $selectedClassId)
                        ->where('subject_id', $selectedSubjectId)
                        ->get()
                        ->keyBy('student_id');
                }
            }
        } else {
            $selectedSubjectId = null;
        }

        // Summary stats
        $stats = [
            'total_graded' => Grade::count(),
            'grade_a'      => Grade::where('grade_letter', 'A')->count(),
            'grade_b'      => Grade::where('grade_letter', 'B')->count(),
            'average'      => Grade::avg('final_grade') ?? 0,
        ];

        return view('admin.nilai.index', compact(
            'classes',
            'selectedClass',
            'subjects',
            'students',
            'grades',
            'selectedClassId',
            'selectedSubjectId',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades'     => 'required|array',
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

            // Calculate final grade if at least one score exists
            $finalGrade = null;
            $gradeLetter = null;

            if ($attendance !== null || $assignments !== null || $midterm !== null || $final !== null) {
                $att = $attendance ?? 0;
                $asg = $assignments ?? 0;
                $mid = $midterm ?? 0;
                $fin = $final ?? 0;

                // Standard weighting: 15% Attendance, 25% Assignments, 25% Midterm, 35% Final
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
                    'class_id'   => $validated['class_id'],
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

        return redirect()->route('admin.nilai.index', [
            'class_id'   => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
        ])->with('success', 'Nilai akademik berhasil disimpan.');
    }
}
