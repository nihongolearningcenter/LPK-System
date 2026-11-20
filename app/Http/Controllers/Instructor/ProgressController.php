<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index(ClassRoom $kelas)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $kelas->load(['students.studentProfile', 'program']);

        $studentStats = [];
        foreach ($kelas->students as $student) {
            $totalAttendance = Attendance::where('class_id', $kelas->id)->where('student_id', $student->id)->count();
            $presentCount    = Attendance::where('class_id', $kelas->id)->where('student_id', $student->id)->where('status', 'present')->count();
            $attendanceRate  = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 100;

            $averageGrade = Grade::where('class_id', $kelas->id)->where('student_id', $student->id)->avg('final_grade');

            $studentStats[$student->id] = [
                'attendance_rate' => $attendanceRate,
                'average_grade'   => $averageGrade !== null ? round($averageGrade, 1) : null,
            ];
        }

        return view('instructor.progres.index', [
            'class'        => $kelas,
            'students'     => $kelas->students,
            'studentStats' => $studentStats,
        ]);
    }

    public function show(ClassRoom $kelas, User $student)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $student->load('studentProfile');

        // Attendance records
        $attendances = Attendance::where('class_id', $kelas->id)
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $attendanceSummary = [
            'total'      => $attendances->count(),
            'present'    => $attendances->where('status', 'present')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'sick'       => $attendances->where('status', 'sick')->count(),
            'absent'     => $attendances->where('status', 'absent')->count(),
        ];

        // Assignment submissions
        $submissions = Submission::with('assignment')
            ->where('student_id', $student->id)
            ->whereHas('assignment', fn($q) => $q->where('class_id', $kelas->id))
            ->latest()
            ->get();

        // Grades
        $grades = Grade::with('subject')
            ->where('class_id', $kelas->id)
            ->where('student_id', $student->id)
            ->get();

        // Exam attempts
        $examAttempts = ExamAttempt::with('exam')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('instructor.progres.show', [
            'class'             => $kelas,
            'student'           => $student,
            'attendances'       => $attendances,
            'attendanceSummary' => $attendanceSummary,
            'submissions'       => $submissions,
            'grades'            => $grades,
            'examAttempts'      => $examAttempts,
        ]);
    }
}
