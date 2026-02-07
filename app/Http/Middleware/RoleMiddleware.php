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
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Normalisasi role (trim dan lowercase untuk menghindari masalah case sensitivity)
        $userRole = strtolower(trim($user->role ?? ''));
        
        // Pastikan user memiliki role yang valid
        if (empty($userRole) || !in_array($userRole, ['admin', 'petugas', 'user'])) {
            abort(403, 'Tindakan yang tidak sah. Akun Anda tidak memiliki role yang valid.');
        }
        
        if ($role === 'admin' && $userRole !== 'admin') {
            abort(403, 'Tindakan yang tidak sah.');
        }

        if ($role === 'petugas' && !in_array($userRole, ['admin', 'petugas'])) {
            abort(403, 'Tindakan yang tidak sah.');
        }

        if ($role === 'user' && !in_array($userRole, ['admin', 'petugas', 'user'])) {
            abort(403, 'Tindakan yang tidak sah.');
        }

        return $next($request);
    }
}

