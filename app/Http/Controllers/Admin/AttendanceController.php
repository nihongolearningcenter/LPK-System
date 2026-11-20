<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassRoom::where('status', 'active')->get();
        $selectedClassId = $request->get('class_id', $classes->first()?->id);
        $selectedDate = $request->get('date', date('Y-m-d'));

        $selectedClass = $classes->firstWhere('id', $selectedClassId);

        $students = [];
        $existingAttendance = [];

        if ($selectedClass) {
            $students = $selectedClass->classStudents()->with('student.studentProfile')->get();
            $existingAttendance = Attendance::where('class_id', $selectedClass->id)
                ->where('date', $selectedDate)
                ->pluck('status', 'student_id')
                ->toArray();
        }

        return view('admin.absensi.index', compact('classes', 'selectedClass', 'selectedDate', 'students', 'existingAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'date'       => 'required|date',
            'attendance' => 'required|array',
        ]);

        $statusMap = [
            'present'    => 'hadir',
            'permission' => 'izin',
            'sick'       => 'sakit',
            'absent'     => 'alpha',
            'hadir'      => 'hadir',
            'izin'       => 'izin',
            'sakit'      => 'sakit',
            'alpha'      => 'alpha',
        ];

        foreach ($request->attendance as $studentId => $rawStatus) {
            $status = $statusMap[$rawStatus] ?? 'hadir';
            Attendance::updateOrCreate(
                [
                    'class_id'   => $request->class_id,
                    'student_id' => $studentId,
                    'date'       => $request->date,
                ],
                [
                    'status'      => $status,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.absensi.index', [
            'class_id' => $request->class_id,
            'date'     => $request->date,
        ])->with('success', 'Presensi kehadiran berhasil disimpan!');
    }
}
