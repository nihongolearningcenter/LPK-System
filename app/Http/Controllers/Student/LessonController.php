<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classIds = $user->classes()->pluck('classes.id');

        $lessons = Lesson::with(['subject', 'author'])
            ->where('is_published', true)
            ->where(function ($q) use ($classIds) {
                $q->whereIn('class_id', $classIds)
                  ->orWhereNull('class_id');
            })
            ->orderBy('sort_order')
            ->get();

        return view('student.materi.index', compact('lessons'));
    }

    public function show(Lesson $lesson)
    {
        $lesson->load(['subject', 'author']);
        return view('student.materi.show', compact('lesson'));
    }
}
