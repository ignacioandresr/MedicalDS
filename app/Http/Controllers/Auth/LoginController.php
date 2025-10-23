<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * After authentication, prevent visitor-role accounts from using the regular login.
     * Redirect them to the visitor login form with an error.
     */
    protected function authenticated($request, $user)
    {
        try {
            if (method_exists($user, 'hasRole') && $user->hasRole('visitor')) {
                auth()->logout();
                return redirect()->route('visitor.login.form')->withErrors([
                    'email' => 'Debe iniciar sesión desde la entrada de visitantes.',
                ]);
            }
        } catch (\Throwable $e) {
            // If role check fails, be conservative and log out
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'No se pudo verificar el rol de la cuenta.',
            ]);
        }

        // Otherwise continue normal flow
        return null;
    }
}
