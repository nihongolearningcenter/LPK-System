<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache stats for 5 minutes — these are read-heavy, rarely change mid-session
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'total_students'     => User::where('role', 'student')->where('is_active', true)->count(),
                'total_instructors'  => User::where('role', 'instructor')->where('is_active', true)->count(),
                'total_programs'     => Program::where('is_active', true)->count(),
                'total_classes'      => ClassRoom::whereIn('status', ['active', 'ongoing', 'upcoming'])->count(),
                'pending_enrollments'=> Enrollment::where('status', 'pending')->count(),
                'monthly_revenue'    => Payment::where('status', 'paid')
                                               ->whereMonth('paid_at', now()->month)
                                               ->whereYear('paid_at', now()->year)
                                               ->sum('amount_paid'),
            ];
        });

        // Eager-load relations to avoid N+1 queries
        $recentEnrollments = Enrollment::with('program')
            ->latest()
            ->limit(8)
            ->get();

        $activeClasses = ClassRoom::with(['program', 'instructor'])
            ->whereIn('status', ['active', 'ongoing'])
            ->withCount(['classStudents', 'students'])
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'activeClasses'));
    }
}
