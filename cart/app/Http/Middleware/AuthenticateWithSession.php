<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithSession
{
    /**
     * Handle an incoming request - checks session authentication for API routes
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure session is started
        if (!$request->session()) {
            return response()->json([
                'message' => 'Session not available'
            ], 401);
        }

        // Try to authenticate using session
        $user = Auth::guard('web')->user();

        if ($user) {
            // Set the authenticated user
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return $next($request);
        }

        // Not authenticated
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }
}
