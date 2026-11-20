<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::with('program')
            ->withCount(['lessons', 'questions', 'vocabularies'])
            ->orderBy('sort_order')
            ->paginate(15);

        $programs = Program::active()->get();

        return view('admin.mata-pelajaran.index', compact('subjects', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;

        Subject::create($validated);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function update(Request $request, Subject $mata_pelajaran)
    {
        $validated = $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $mata_pelajaran->update($validated);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy(Subject $mata_pelajaran)
    {
        $mata_pelajaran->delete();
        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
