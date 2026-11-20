<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'overview');

        // Key Metrics
        $stats = [
            'total_students'     => User::where('role', 'student')->count(),
            'total_instructors'  => User::where('role', 'instructor')->count(),
            'total_enrollments'  => Enrollment::count(),
            'total_revenue'      => (float)(Payment::where('status', 'paid')->sum('amount_paid') ?? 0),
            'pending_payments'   => (float)(Payment::where('status', 'pending')->sum('amount') ?? 0),
            'total_certificates' => Certificate::count(),
            'average_grade'      => round(Grade::avg('final_grade') ?? 0, 1),
            'total_attendance'   => Attendance::where('status', 'present')->count(),
        ];

        // Monthly Revenue for the current year
        $monthlyRevenue = Payment::where('status', 'paid')
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount_paid) as total')
            ->whereYear('paid_at', now()->year)
            ->groupByRaw('MONTH(paid_at), YEAR(paid_at)')
            ->orderByRaw('MONTH(paid_at)')
            ->get();

        // Enrollments per program
        $enrollmentsByProgram = Enrollment::with('program')
            ->selectRaw('program_id, COUNT(*) as total')
            ->groupBy('program_id')
            ->get();

        // Enrollments per status
        $enrollmentStatuses = [
            'pending'  => Enrollment::where('status', 'pending')->count(),
            'accepted' => Enrollment::where('status', 'accepted')->count(),
            'rejected' => Enrollment::where('status', 'rejected')->count(),
        ];

        // Recent Payments
        $recentPayments = Payment::with(['student', 'enrollment.program'])
            ->latest()
            ->take(10)
            ->get();

        // Grade distribution
        $gradeDistribution = [
            'A' => Grade::where('grade_letter', 'A')->count(),
            'B' => Grade::where('grade_letter', 'B')->count(),
            'C' => Grade::where('grade_letter', 'C')->count(),
            'D' => Grade::where('grade_letter', 'D')->count(),
            'E' => Grade::where('grade_letter', 'E')->count(),
        ];

        return view('admin.laporan.index', compact(
            'activeTab',
            'stats',
            'monthlyRevenue',
            'enrollmentsByProgram',
            'enrollmentStatuses',
            'recentPayments',
            'gradeDistribution'
        ));
    }

    public function students(Request $request)
    {
        $request->merge(['tab' => 'students']);
        return $this->index($request);
    }

    public function financial(Request $request)
    {
        $request->merge(['tab' => 'financial']);
        return $this->index($request);
    }

    public function academic(Request $request)
    {
        $request->merge(['tab' => 'academic']);
        return $this->index($request);
    }
}
