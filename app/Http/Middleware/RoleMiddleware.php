<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // If user is not authenticated or their role is not in the allowed roles
        if (!auth()->check() || (!in_array(auth()->user()->role, $roles) && auth()->user()->role !== 'admin')) {
            abort(403, 'Unauthorized Access. You do not have the required permissions.');
        }

        return $next($request);
    }
}
