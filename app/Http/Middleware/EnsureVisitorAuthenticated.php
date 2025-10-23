<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVisitorAuthenticated
{
    /**
     * Ensure the user has authenticated as a visitor (visitor session flag present).
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            try {
                if (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin')) {
                    return $next($request);
                }
            } catch (\Throwable $e) {
            }
        }

        if ($request->session()->get('visitor_authenticated') && auth()->check()) {
            try {
                if (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('visitor')) {
                    return $next($request);
                }
            } catch (\Throwable $e) {
            }
        }

        return redirect()->route('visitor.login.form')->withErrors(['auth' => 'Debes iniciar sesión como visitante para acceder a esa página.']);
    }
}
