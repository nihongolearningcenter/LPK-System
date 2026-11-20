<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentActive
{
    /**
     * Handle an incoming request.
     * Memastikan siswa sudah mengikuti program dan melunasi pembayaran sebelum dapat mengakses materi, jadwal, latihan, dan ujian.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isStudent()) {
            if (!$user->canAccessLearningFeatures()) {
                return redirect()->route('student.dashboard')->with('error', 'Akses fitur pembelajaran (jadwal, materi, latihan, dan ujian) belum aktif. Pastikan Anda telah terdaftar dalam program dan telah menyelesaikan pembayaran.');
            }
        }

        return $next($request);
    }
}

