<?php

namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ambil Content-Type dari response
        $contentType = (string) $response->headers->get('Content-Type', '');

        // Beri header anti-cache HANYA untuk halaman HTML/redirect,
        // jangan untuk file (pdf, image, binary) agar download tidak error.
        if ($request->isMethod('GET') && (
                $contentType === '' ||          // beberapa redirect kosong
                str_contains($contentType, 'text/html')
            )) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $response;
    }
}
