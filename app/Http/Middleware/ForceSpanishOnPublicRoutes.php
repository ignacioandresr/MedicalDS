<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceSpanishOnPublicRoutes
{
    /**
     * Handle an incoming request.
     * If the current path is a public/auth route, set session locale to 'es'.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Si es una ruta de visitante, no forzar español
            if ($request->is('visitor/*') || $request->is('lang/*')) {
                return $next($request);
            }

            $path = $request->path();
            // Normalize empty path
            if ($path === '/') {
                // Solo forzar español si no hay idioma en sesión
                if (!session()->has('locale')) {
                    session()->put('locale', 'es');
                }
                if (function_exists('app')) {
                    app()->setLocale(session()->get('locale', 'es'));
                }
            }

            // List of patterns that should force Spanish
            $publicPatterns = [
                '/',
                'login',
                'register',
                'home',
            ];

            foreach ($publicPatterns as $p) {
                if ($p === '/') continue;
                if ($request->is($p) || $request->is($p.'/*')) {
                    // Solo forzar español si no hay idioma en sesión
                    if (!session()->has('locale')) {
                        session()->put('locale', 'es');
                    }
                    if (function_exists('app')) {
                        app()->setLocale(session()->get('locale', 'es'));
                    }
                    break;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return $next($request);
    }
}
