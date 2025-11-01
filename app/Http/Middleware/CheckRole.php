<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as RouteFacade;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        if ($user && (empty($roles) || in_array($user->role, $roles, true))) {
            return $next($request);
        }

        if (RouteFacade::has('unauthorized')) {
            return redirect()->route('unauthorized');
        }

        abort(403, 'Access Denied.');
    }
}

