<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\ExamAttempt;
use App\Models\Submission;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $grades = Grade::with(['classRoom', 'subject'])
            ->where('student_id', $user->id)
            ->get();

        $examAttempts = ExamAttempt::with('exam')
            ->where('student_id', $user->id)
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->get();

        $submissions = Submission::with('assignment.classRoom')
            ->where('student_id', $user->id)
            ->where('status', 'graded')
            ->latest('graded_at')
            ->get();

        $avgScore = $examAttempts->avg('score') ?? 0;

        return view('student.hasil.index', compact('grades', 'examAttempts', 'submissions', 'avgScore'));
    }

    public function progress()
    {
        $user = Auth::user();

        $myClasses = $user->classes()->with(['program', 'instructor'])->get();

        // Absensi
        $totalAttendance = Attendance::where('student_id', $user->id)->count();
        $presentCount = Attendance::where('student_id', $user->id)->where('status', 'hadir')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 100;

        // Ujian
        $passedExams = ExamAttempt::where('student_id', $user->id)->where('is_passed', true)->count();
        $totalExams = ExamAttempt::where('student_id', $user->id)->count();

        // Tugas
        $submittedTasks = Submission::where('student_id', $user->id)->count();

        return view('student.progres', compact('myClasses', 'attendanceRate', 'totalAttendance', 'presentCount', 'passedExams', 'totalExams', 'submittedTasks'));
    }
}
