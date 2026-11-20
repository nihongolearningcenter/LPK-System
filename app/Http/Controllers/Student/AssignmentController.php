<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classIds = $user->classes()->pluck('classes.id');

        $assignments = Assignment::with(['classRoom', 'subject', 'submissions' => function ($q) use ($user) {
                $q->where('student_id', $user->id);
            }])
            ->where('is_published', true)
            ->whereIn('class_id', $classIds)
            ->latest('due_date')
            ->get();

        return view('student.tugas.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $user = Auth::user();
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        return view('student.tugas.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'answer_text'     => 'nullable|string',
            'file_submission' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_submission')) {
            $filePath = $request->file('file_submission')->store('submissions', 'public');
        }

        $isLate = $assignment->due_date && now()->isAfter($assignment->due_date);

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id'    => Auth::id(),
            ],
            [
                'answer_text'     => $request->answer_text,
                'file_submission' => $filePath ?? optional(Submission::where('assignment_id', $assignment->id)->where('student_id', Auth::id())->first())->file_submission,
                'submitted_at'    => now(),
                'is_late'         => $isLate,
                'status'          => 'submitted',
            ]
        );

        return redirect()->route('student.tugas.show', $assignment)->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
