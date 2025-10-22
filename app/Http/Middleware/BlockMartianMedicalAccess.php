<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockMartianMedicalAccess
{
    /**
     * Block access to medical routes for visitors using the Russian "marciano" home.
     * If the session locale is 'ru' redirect back to the visitor home with a message.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->get('locale') === 'ru') {
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
