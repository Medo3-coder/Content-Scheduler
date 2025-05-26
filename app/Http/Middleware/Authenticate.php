<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Don't redirect for API requests
        if ($request->expectsJson()) {
            return null;
        }

        // Don't redirect for dashboard during testing
        if ($request->is('dashboard')) {
            return null;
        }

        return route('login');
    }
}
