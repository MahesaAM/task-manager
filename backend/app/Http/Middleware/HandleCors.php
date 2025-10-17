<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS requests first
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', 'http://localhost:5174')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With, Accept, Origin')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $response = $next($request);

        // Add CORS headers for all requests
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5174');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With, Accept, Origin');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
