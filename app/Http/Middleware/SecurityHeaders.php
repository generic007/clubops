<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strict Transport Security — 1 year, include subdomains, preload
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking — same origin only
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Control referrer info
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        // Content Security Policy — tight but allows normal app assets
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' https://cdn.jsdelivr.net https://unpkg.com 'unsafe-inline'; " .
            "style-src 'self' https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com 'unsafe-inline'; " .
            "img-src 'self' data:; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none'; " .
            "form-action 'self'"
        );

        return $response;
    }
}
