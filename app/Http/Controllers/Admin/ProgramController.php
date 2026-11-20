<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('sort_order')->paginate(15);
        return view('admin.program.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.program.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'type'             => 'required|in:dasar,jlpt_n5,jlpt_n4,kaiwa,kerja,tokutei_ginou',
            'short_description'=> 'nullable|string|max:500',
            'description'      => 'nullable|string',
            'price'            => 'nullable|numeric|min:0',
            'duration_weeks'   => 'nullable|integer|min:1',
            'session_per_week' => 'nullable|string|max:100',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'boolean',
        ]);

        $validated['slug']      = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['price']     = $validated['price'] ?? 0;

        Program::create($validated);

        return redirect()->route('admin.program.index')
                         ->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('admin.program.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'type'             => 'required|in:dasar,jlpt_n5,jlpt_n4,kaiwa,kerja,tokutei_ginou',
            'short_description'=> 'nullable|string|max:500',
            'description'      => 'nullable|string',
            'price'            => 'nullable|numeric|min:0',
            'duration_weeks'   => 'nullable|integer|min:1',
            'session_per_week' => 'nullable|string|max:100',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['price']     = $validated['price'] ?? 0;

        $program->update($validated);

        return redirect()->route('admin.program.index')
                         ->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.program.index')
                         ->with('success', 'Program berhasil dihapus.');
    }
}
