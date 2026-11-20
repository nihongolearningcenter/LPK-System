<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $programs = Cache::remember('home.programs', 600, function () {
            return Program::where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get();
        });

        return view('public.home', compact('programs'));
    }

    public function tentang()
    {
        return view('public.tentang');
    }

    public function pengajar()
    {
        $instructors = Cache::remember('home.instructors', 600, function () {
            return User::where('role', 'instructor')
                ->with('instructorProfile')
                ->get();
        });

        return view('public.pengajar', compact('instructors'));
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function kontak()
    {
        return view('public.kontak');
    }
}
