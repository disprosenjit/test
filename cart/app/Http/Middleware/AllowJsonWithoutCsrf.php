<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class AllowJsonWithoutCsrf
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip CSRF validation for JSON API requests
        // JSON requests from APIs don't need CSRF tokens since they use session auth
        if ($request->isJson() || $request->expectsJson()) {
            // Store the original request in a special flag
            $request->attributes->set('skipCsrfVerification', true);
        }

        return $next($request);
    }
}
