<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized - Please login.');
        }

        $user = Auth::user();

        // Check if user has the required role
        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized - Insufficient role privileges.');
        }

        return $next($request);
    }
}