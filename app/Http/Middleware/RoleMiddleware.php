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
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect to appropriate dashboard based on user's actual role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                case 'murid':
                    return redirect()->route('murid.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                case 'pelatih':
                    return redirect()->route('pelatih.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                default:
                    return redirect()->route('login')->with('error', 'Role tidak valid.');
            }
        }

        return $next($request);
    }
}