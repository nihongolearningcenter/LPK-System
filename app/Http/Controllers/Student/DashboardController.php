<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load all needed user relations in one go (avoids multiple lazy-load hits)
        $user->loadMissing(['payments']);

        // Kelas yang diikuti — eager load all nested relations
        $myClasses = $user->classes()
            ->with(['program', 'instructor', 'schedules'])
            ->wherePivot('status', 'active')
            ->get();

        // Pengumuman terbaru — select only needed columns
        $announcements = Announcement::select(['id', 'title', 'body', 'target', 'published_at'])
            ->where('is_published', true)
            ->whereIn('target', ['all', 'students'])
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Statistik — use a single aggregation query instead of two
        $examStats = ExamAttempt::where('student_id', $user->id)
            ->where('status', 'submitted')
            ->selectRaw('COUNT(*) as total, AVG(score) as avg_score')
            ->first();

        $stats = [
            'classes'       => $myClasses->count(),
            'exam_attempts' => $examStats->total ?? 0,
            'avg_score'     => round($examStats->avg_score ?? 0, 1),
        ];

        $canAccessLearning = $user->canAccessLearningFeatures();

        // Use preloaded payments relation instead of issuing new queries
        $pendingPayment = $user->payments
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->sortByDesc('created_at')
            ->first();

        $latestPaidPayment = $user->payments
            ->where('status', 'paid')
            ->sortByDesc('created_at')
            ->first();

        return view('student.dashboard', compact(
            'myClasses',
            'announcements',
            'stats',
            'canAccessLearning',
            'pendingPayment',
            'latestPaidPayment'
        ));
    }

    public function program()
    {
        $user = Auth::user();
        $myClasses = $user->classes()->with(['program', 'instructor', 'schedules'])->get();
        return view('student.program', compact('myClasses'));
    }

    public function jadwal()
    {
        $user = Auth::user();
        $schedules = $user->classes()
            ->with(['schedules', 'program'])
            ->wherePivot('status', 'active')
            ->get()
            ->pluck('schedules')
            ->flatten();
        return view('student.jadwal', compact('schedules'));
    }
}
