<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with(['subject', 'classRoom', 'author'])->latest();

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $lessons = $query->paginate(12)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.materi.index', compact('lessons', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        $classes = ClassRoom::where('status', 'active')->get();

        return view('admin.materi.create', compact('subjects', 'classes'));
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
            'file_attachment'  => 'nullable|file|max:20480', // Max 20MB
            'type'             => 'required|in:text,video,audio,mixed',
            'duration_minutes' => 'nullable|integer|min:1',
            'sort_order'       => 'nullable|integer',
            'is_published'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('file_attachment')) {
            $validated['file_attachment'] = $request->file('file_attachment')->store('lessons', 'public');
        }

        $validated['created_by']   = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        $validated['slug']         = Str::slug($validated['title']) . '-' . Str::random(5);

        Lesson::create($validated);

        return redirect()->route('admin.materi.index')->with('success', 'Materi pelajaran berhasil ditambahkan!');
    }

    public function edit(Lesson $materi)
    {
        $subjects = Subject::where('is_active', true)->get();
        $classes = ClassRoom::where('status', 'active')->get();

        return view('admin.materi.edit', [
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

        if ($request->hasFile('file_attachment')) {
            $validated['file_attachment'] = $request->file('file_attachment')->store('lessons', 'public');
        }

        $validated['is_published'] = $request->has('is_published');
        $materi->update($validated);

        return redirect()->route('admin.materi.index')->with('success', 'Materi pelajaran berhasil diperbarui!');
    }

    public function destroy(Lesson $materi)
    {
        $materi->delete();
        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
