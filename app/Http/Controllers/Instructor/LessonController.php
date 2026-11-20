<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $classIds = ClassRoom::where('instructor_id', $user->id)->pluck('id');

        $query = Lesson::with(['subject', 'classRoom'])
            ->where(function ($q) use ($user, $classIds) {
                $q->where('created_by', $user->id)
                  ->orWhereIn('class_id', $classIds);
            })
            ->latest();

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $lessons = $query->paginate(12)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();

        return view('instructor.materi.index', compact('lessons', 'subjects'));
    }

    public function create()
    {
        $user = Auth::user();
        $subjects = Subject::where('is_active', true)->get();
        $classes  = ClassRoom::where('instructor_id', $user->id)->get();

        return view('instructor.materi.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'       => 'required|exists:subjects,id',
            'class_id'         => 'nullable|exists:classes,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'content'          => 'nullable|string',
            'video_url'        => 'nullable|url',
            'file_attachment'  => 'nullable|file|max:20480',
            'type'             => 'required|in:text,video,audio,mixed',
            'duration_minutes' => 'nullable|integer|min:1',
            'sort_order'       => 'nullable|integer',
            'is_published'     => 'nullable|boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file_attachment')) {
            $filePath = $request->file('file_attachment')->store('lessons', 'public');
        }

        Lesson::create([
            'subject_id'       => $validated['subject_id'],
            'class_id'         => $validated['class_id'] ?? null,
            'created_by'       => Auth::id(),
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'content'          => $validated['content'] ?? null,
            'video_url'        => $validated['video_url'] ?? null,
            'file_attachment'  => $filePath,
            'type'             => $validated['type'],
            'duration_minutes' => $validated['duration_minutes'] ?? 30,
            'sort_order'       => $validated['sort_order'] ?? 0,
            'is_published'     => $request->boolean('is_published', true),
        ]);

        return redirect()->route('instructor.materi.index')
                         ->with('success', 'Materi berhasil diunggah.');
    }

    public function show(Lesson $materi)
    {
        $materi->load(['subject', 'classRoom']);
        return view('instructor.materi.show', ['lesson' => $materi]);
    }

    public function edit(Lesson $materi)
    {
        $user = Auth::user();
        $subjects = Subject::where('is_active', true)->get();
        $classes  = ClassRoom::where('instructor_id', $user->id)->get();

        return view('instructor.materi.edit', [
            'lesson'   => $materi,
            'subjects' => $subjects,
            'classes'  => $classes,
        ]);
    }

    public function update(Request $request, Lesson $materi)
    {
        $validated = $request->validate([
            'subject_id'       => 'required|exists:subjects,id',
            'class_id'         => 'nullable|exists:classes,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'content'          => 'nullable|string',
            'video_url'        => 'nullable|url',
            'file_attachment'  => 'nullable|file|max:20480',
            'type'             => 'required|in:text,video,audio,mixed',
            'duration_minutes' => 'nullable|integer|min:1',
            'sort_order'       => 'nullable|integer',
            'is_published'     => 'nullable|boolean',
        ]);

        $filePath = $materi->file_attachment;
        if ($request->hasFile('file_attachment')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_attachment')->store('lessons', 'public');
        }

        $materi->update([
            'subject_id'       => $validated['subject_id'],
            'class_id'         => $validated['class_id'] ?? null,
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'content'          => $validated['content'] ?? null,
            'video_url'        => $validated['video_url'] ?? null,
            'file_attachment'  => $filePath,
            'type'             => $validated['type'],
            'duration_minutes' => $validated['duration_minutes'] ?? 30,
            'sort_order'       => $validated['sort_order'] ?? 0,
            'is_published'     => $request->boolean('is_published'),
        ]);

        return redirect()->route('instructor.materi.index')
                         ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Lesson $materi)
    {
        if ($materi->file_attachment && Storage::disk('public')->exists($materi->file_attachment)) {
            Storage::disk('public')->delete($materi->file_attachment);
        }
        $materi->delete();

        return redirect()->route('instructor.materi.index')
                         ->with('success', 'Materi berhasil dihapus.');
    }
}
