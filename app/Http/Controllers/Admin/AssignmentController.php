<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['classRoom', 'subject', 'author'])
            ->withCount('submissions')
            ->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $assignments = $query->paginate(15)->withQueryString();
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.tugas.index', compact('assignments', 'classes'));
    }

    public function create()
    {
        $classes  = ClassRoom::with('program')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.tugas.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id'              => 'required|exists:classes,id',
            'subject_id'            => 'nullable|exists:subjects,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'file_attachment'       => 'nullable|file|max:10240', // 10MB
            'due_date'              => 'nullable|date',
            'max_score'             => 'required|numeric|min:1|max:100',
            'allow_late_submission' => 'boolean',
            'is_published'          => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file_attachment')) {
            $filePath = $request->file('file_attachment')->store('assignments', 'public');
        }

        Assignment::create([
            'class_id'              => $validated['class_id'],
            'subject_id'            => $validated['subject_id'] ?? null,
            'created_by'            => Auth::id(),
            'title'                 => $validated['title'],
            'description'           => $validated['description'] ?? null,
            'file_attachment'       => $filePath,
            'due_date'              => $validated['due_date'] ?? null,
            'max_score'             => $validated['max_score'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
            'is_published'          => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.tugas.index')
                         ->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Assignment $tuga)
    {
        $tuga->load(['classRoom.students', 'subject', 'author', 'submissions.student']);
        
        // Group submissions by student ID
        $submissions = $tuga->submissions->keyBy('student_id');
        $students = $tuga->classRoom?->students ?? collect();

        return view('admin.tugas.show', [
            'assignment'  => $tuga,
            'submissions' => $submissions,
            'students'    => $students,
        ]);
    }

    public function edit(Assignment $tuga)
    {
        $classes  = ClassRoom::with('program')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.tugas.edit', [
            'assignment' => $tuga,
            'classes'    => $classes,
            'subjects'   => $subjects,
        ]);
    }

    public function update(Request $request, Assignment $tuga)
    {
        $validated = $request->validate([
            'class_id'              => 'required|exists:classes,id',
            'subject_id'            => 'nullable|exists:subjects,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'file_attachment'       => 'nullable|file|max:10240',
            'due_date'              => 'nullable|date',
            'max_score'             => 'required|numeric|min:1|max:100',
            'allow_late_submission' => 'boolean',
            'is_published'          => 'boolean',
        ]);

        $filePath = $tuga->file_attachment;
        if ($request->hasFile('file_attachment')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_attachment')->store('assignments', 'public');
        }

        $tuga->update([
            'class_id'              => $validated['class_id'],
            'subject_id'            => $validated['subject_id'] ?? null,
            'title'                 => $validated['title'],
            'description'           => $validated['description'] ?? null,
            'file_attachment'       => $filePath,
            'due_date'              => $validated['due_date'] ?? null,
            'max_score'             => $validated['max_score'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
            'is_published'          => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.tugas.index')
                         ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $tuga)
    {
        if ($tuga->file_attachment && Storage::disk('public')->exists($tuga->file_attachment)) {
            Storage::disk('public')->delete($tuga->file_attachment);
        }
        $tuga->delete();

        return redirect()->route('admin.tugas.index')
                         ->with('success', 'Tugas berhasil dihapus.');
    }

    public function gradeSubmission(Request $request, Assignment $tuga, Submission $submission)
    {
        $validated = $request->validate([
            'score'    => 'required|numeric|min:0|max:' . $tuga->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score'     => $validated['score'],
            'feedback'  => $validated['feedback'],
            'graded_by' => Auth::id(),
            'graded_at' => now(),
            'status'    => 'graded',
        ]);

        return back()->with('success', 'Nilai tugas siswa berhasil disimpan.');
    }
}
