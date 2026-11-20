<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request, ClassRoom $kelas)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $selectedDate = $request->get('date', date('Y-m-d'));
        $kelas->load('students.studentProfile');

        $existingAttendance = Attendance::where('class_id', $kelas->id)
            ->where('date', $selectedDate)
            ->pluck('status', 'student_id')
            ->toArray();

        return view('instructor.absensi.index', [
            'class'              => $kelas,
            'selectedDate'       => $selectedDate,
            'students'           => $kelas->students,
            'existingAttendance' => $existingAttendance,
        ]);
    }

    public function store(Request $request, ClassRoom $kelas)
    {
        if ($kelas->instructor_id !== Auth::id()) {
            abort(403, 'Anda bukan pengajar kelas ini.');
        }

        $request->validate([
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
                    'class_id'   => $kelas->id,
                    'student_id' => $studentId,
                    'date'       => $request->date,
                ],
                [
                    'status'      => $status,
                    'recorded_by' => Auth::id(),
                ]
            );
        }

        return redirect()->route('instructor.absensi.index', [
            'kelas' => $kelas->id,
            'date'  => $request->date,
        ])->with('success', 'Presensi kehadiran berhasil disimpan!');
    }
}
