<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockMartianMedicalAccess
{
    /**
     * Block access to medical routes for visitors using the Russian "marciano" home.
     * If the user is a visitor or session locale is 'ru', redirect back to the visitor home with a message.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is a visitor (either by session flag or by role)
        $isVisitor = $request->session()->get('visitor_authenticated') || $request->routeIs('visitor.*');
        
        if (!$isVisitor && auth()->check()) {
            // Check if user has visitor role
            try {
                if (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('visitor')) {
                    $isVisitor = true;
                }
            } catch (\Throwable $e) {
                // Role check failed, continue
            }
        }

        // Block visitors or users with 'ru' locale
        if ($isVisitor || $request->session()->get('locale') === 'ru') {
            // Allow admins to access medical routes even if they are in visitor mode
            try {
                if (auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin')) {
                    return $next($request);
                }
            } catch (\Throwable $e) {
                // If role check fails, fall through to block access
            }
            
            // If request is AJAX/JSON, return 403 JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Access restricted for visitor.'], 403);
            }

            // Redirect back to the Russian visitor home with a flash message
            return redirect()->route('visitor.home.ru')->with('error', 'Доступ к медицинским разделам ограничен для этого пользователя.');
        }

        return $next($request);
    }
}
