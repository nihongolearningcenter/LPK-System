<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_published', true)
            ->whereIn('target', ['all', 'students'])
            ->latest('published_at')
            ->paginate(10);

        return view('student.pengumuman.index', compact('announcements'));
    }
}
