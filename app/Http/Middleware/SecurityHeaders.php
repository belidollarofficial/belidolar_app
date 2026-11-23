<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Tambahkan header keamanan pada setiap response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval'";
        $styleSrc = "'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.bunny.net";
        $connectSrc = "'self' ws: wss:";

        // Izinkan Vite dev server saat pengembangan lokal.
        if (app()->environment('local')) {
            $vite = ' http://localhost:5173 http://[::1]:5173';
            $scriptSrc .= $vite;
            $styleSrc .= $vite;
            $connectSrc .= $vite;
        }

        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "base-uri 'self'",
                "frame-ancestors 'self'",
                "object-src 'none'",
                "script-src {$scriptSrc}",
                "style-src {$styleSrc}",
                "img-src 'self' data: blob: https:",
                "font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com https://fonts.bunny.net",
                "connect-src {$connectSrc}",
                "form-action 'self'",
            ]),
        ];

        // HSTS hanya dikirim lewat HTTPS agar tidak mengunci akses HTTP lokal.
        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
