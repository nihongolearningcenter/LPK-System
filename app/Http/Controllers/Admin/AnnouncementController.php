<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['creator', 'classRoom'])->latest();

        if ($request->filled('target')) {
            $query->where('target', $request->target);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->paginate(10)->withQueryString();

        return view('admin.pengumuman.index', compact('announcements'));
    }

    public function create()
    {
        $classes = ClassRoom::where('status', 'active')->get();
        return view('admin.pengumuman.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'type'         => 'required|in:pengumuman,artikel,berita',
            'target'       => 'required|in:all,students,instructors,specific_class',
            'class_id'     => 'nullable|exists:classes,id',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['created_by']   = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;
        $validated['slug']         = Str::slug($validated['title']) . '-' . Str::random(5);

        Announcement::create($validated);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    public function edit(Announcement $pengumuman)
    {
        $classes = ClassRoom::where('status', 'active')->get();
        return view('admin.pengumuman.edit', compact('pengumuman', 'classes'));
    }

    public function update(Request $request, Announcement $pengumuman)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'type'         => 'required|in:pengumuman,artikel,berita',
            'target'       => 'required|in:all,students,instructors,specific_class',
            'class_id'     => 'nullable|exists:classes,id',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published'] && !$pengumuman->published_at) {
            $validated['published_at'] = now();
        }

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
