<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myClasses = ClassRoom::where('instructor_id', $user->id)
            ->whereIn('status', ['active', 'ongoing', 'upcoming'])
            ->with(['program', 'schedules'])
            ->withCount(['classStudents', 'students'])
            ->latest()
            ->get();

        $stats = [
            'active_classes'  => $myClasses->count(),
            'total_students'  => $myClasses->sum('students_count') ?: $myClasses->sum('class_students_count'),
        ];

        return view('instructor.dashboard', compact('myClasses', 'stats'));
    }

    public function jadwal()
    {
        $user = Auth::user();
        $classes = ClassRoom::where('instructor_id', $user->id)
            ->with('schedules', 'program')
            ->get();
        return view('instructor.jadwal', compact('classes'));
    }
}
