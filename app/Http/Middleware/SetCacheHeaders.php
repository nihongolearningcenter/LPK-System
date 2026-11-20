<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Set HTTP cache headers for static-ish GET pages (logged-in dashboard pages use
 * no-store; public pages use short-lived cache + ETag validation).
 */
class SetCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply to successful GET responses
        if (!$request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Authenticated users: private cache only
        if ($request->user()) {
            $response->headers->set('Cache-Control', 'no-cache, private');
            return $response;
        }

        // Exclude pages with forms, CSRF tokens, authentication, or session alerts from public cache
        if (
            $request->is('login', 'register', 'password*', 'daftar*', 'admin*', 'siswa*', 'pengajar*') ||
            ($request->hasSession() && (
                $request->session()->has('errors') ||
                $request->session()->has('success') ||
                $request->session()->has('error') ||
                $request->session()->has('status')
            ))
        ) {
            $response->headers->set('Cache-Control', 'no-cache, private');
            return $response;
        }

        // Truly static public pages: 5-minute shared cache + ETag
        $etag = '"' . md5((string) $response->getContent()) . '"';
        $response->headers->set('ETag', $etag);
        $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate');

        // 304 Not Modified if ETag matches
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304)->withHeaders([
                'ETag'          => $etag,
                'Cache-Control' => 'public, max-age=300, must-revalidate',
            ]);
        }

        return $response;
    }
}

