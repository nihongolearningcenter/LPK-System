<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstructor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (! $request->user()->isInstructor() && ! $request->user()->isAdmin())) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Pengajar.');
        }

        return $next($request);
    }
}
