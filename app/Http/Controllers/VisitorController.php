<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;

class VisitorController extends Controller
{
    public function create(Request $request)
    {
        $request->session()->put('locale', 'ru');
        Application::setLocale('ru');

        return view('visitors.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Assign visitor role so visitor accounts are distinct from regular users
        try {
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('visitor');
            }
        } catch (\Throwable $e) {
        }

        auth()->login($user);

    $request->session()->put('visitor_authenticated', true);
    $request->session()->put('locale', 'ru');
    Application::setLocale('ru');

        $request->session()->flash('status', 'Регистрация прошла успешно!');
        return redirect()->route('visitor.home.ru');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($credentials)) {
            // Only allow visitor-role accounts to login through visitor login
            try {
                $user = auth()->user();
                if (method_exists($user, 'hasRole') && ! $user->hasRole('visitor')) {
                    auth()->logout();
                    return back()->withErrors([
                        'email' => 'Esta cuenta no tiene permiso para acceder como visitante.',
                    ])->withInput();
                }
            } catch (\Throwable $e) {
                // If role check fails for any reason, deny visitor login
                auth()->logout();
                return back()->withErrors([
                    'email' => 'No se pudo verificar el rol de la cuenta.',
                ])->withInput();
            }

            $request->session()->regenerate();
            $request->session()->put('locale', 'ru');
            $request->session()->put('visitor_authenticated', true);
            Application::setLocale('ru');
            $request->session()->flash('status', '¡Has iniciado sesión como marciano!');
            return redirect()->route('visitor.home.ru');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->withInput();
    }
}
