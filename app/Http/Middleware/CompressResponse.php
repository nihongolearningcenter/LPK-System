<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gzip-compress HTML responses when the client supports it.
 * Reduces page size by ~60-70% for typical HTML content.
 */
class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only compress plain HTML responses — skip binary/streamed/JSON
        $acceptEncoding = $request->header('Accept-Encoding', '');

        if (
            function_exists('gzencode') &&
            str_contains($acceptEncoding, 'gzip') &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html') &&
            !$response->headers->has('Content-Encoding') &&
            strlen((string) $response->getContent()) > 1024 // skip tiny responses
        ) {
            $compressed = gzencode((string) $response->getContent(), 6);

            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Content-Length', (string) strlen($compressed));
                $response->headers->set('Vary', 'Accept-Encoding');
            }
        }

        return $response;
    }
}

