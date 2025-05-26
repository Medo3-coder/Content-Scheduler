<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SiteAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            Log::info('User not authenticated, redirecting to login.');
            return redirect()->route('login')->with('error', 'Please login');
        }

        return $next($request);
    }
}
