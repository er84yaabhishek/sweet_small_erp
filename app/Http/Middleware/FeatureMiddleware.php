<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FeaturePermission;

class FeatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $feature
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $feature)
    {
        // Check if feature is enabled in the database
        if (!FeaturePermission::isEnabled($feature)) {
            abort(403, "Feature '{$feature}' is not enabled in your license package.");
        }

        return $next($request);
    }
}