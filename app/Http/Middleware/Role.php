<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Not Logged
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Not allowed
        if (session('role') == $role) {
            return $next($request);
        }

        abort(404);
    }
}
