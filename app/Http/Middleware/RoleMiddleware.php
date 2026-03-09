<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        $userRole = is_string($userRole) ? strtolower(trim($userRole)) : null;

        $allowed = array_map(fn ($r) => strtolower(trim($r)), $roles);

        if (!$userRole || !in_array($userRole, $allowed, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}