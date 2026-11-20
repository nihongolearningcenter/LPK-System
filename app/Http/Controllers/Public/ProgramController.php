<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Support\Facades\Cache;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Cache::remember('programs.all', 600, function () {
            return Program::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('public.program.index', compact('programs'));
    }

    public function show(string $slug)
    {
        $program = Program::where('slug', $slug)
            ->where('is_active', true)
            ->with(['subjects' => fn($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        $classes = $program->classes()
            ->whereIn('status', ['active', 'ongoing', 'upcoming'])
            ->orderBy('start_date')
            ->get();

        $otherPrograms = Program::where('is_active', true)
            ->where('id', '!=', $program->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('public.program.show', compact('program', 'classes', 'otherPrograms'));
    }
}
