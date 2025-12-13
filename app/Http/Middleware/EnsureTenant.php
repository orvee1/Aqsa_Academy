<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->is_super_admin) {
            app()->forgetInstance('tenant');
            return $next($request);
        }

        if (!$user->tenant) {
            abort(403, 'No tenant assigned to this user.');
        }

        app()->instance('tenant', $user->tenant);

        return $next($request);
    }
}
