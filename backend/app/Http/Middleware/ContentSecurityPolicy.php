<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy
        // Note: Allowing 'unsafe-inline' and 'unsafe-eval' for local Vite dev and Livewire/Filament to work properly.
        // Google Fonts whitelisted.
        $csp = "default-src 'self' http://localhost:5173 http://localhost:8000; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com http://localhost:5173; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: http://localhost:5173; " .
               "frame-ancestors 'none';"; // Prevent clickjacking

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        return $response;
    }
}
