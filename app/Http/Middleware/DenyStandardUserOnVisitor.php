<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DenyStandardUserOnVisitor
{
    /**
     * If an authenticated user has role 'user' (and not admin or visitor), deny access to visitor pages.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            try {
                $hasRoleMethod = method_exists($user, 'hasRole');
                $isAdmin = $hasRoleMethod && $user->hasRole('admin');
                $isVisitor = $hasRoleMethod && $user->hasRole('visitor');
                $isStandard = $hasRoleMethod && $user->hasRole('user');
                if ($isStandard && ! $isAdmin && ! $isVisitor) {
                    return redirect('/home')->withErrors(['auth' => 'No tienes permiso para acceder a la interfaz marciana.']);
                }
            } catch (\Throwable $e) {
                // Fall back to allowing if role system fails
            }
        }
        return $next($request);
    }
}
