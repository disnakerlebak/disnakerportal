<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // 🧩 Super Admin boleh akses semua kecuali area pencaker
        if ($user->role === 'superadmin') {
            // Jika mencoba masuk ke area pencaker (route dengan prefix 'pencaker')
            if ($request->is('pencaker/*')) {
                return response()->view('errors.unauthorized', [], 403);
            }
            return $next($request);
        }

        // Role cocok → lanjut
        if (! empty($roles) && in_array($user->role, $roles, true)) {
            return $next($request);
        }

        return response()->view('errors.unauthorized', [], 403);
    }
}
