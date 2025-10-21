<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;

class VisitorController extends Controller
{
    public function create(Request $request)
    {
        // Set locale to Russian for this session
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

        // Aquí puedes guardar el visitante en la tabla users o en otra tabla si lo deseas
        // Ejemplo usando el modelo User:
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Puedes autenticar al visitante si lo deseas:
        auth()->login($user);

        $request->session()->flash('status', 'Регистрация прошла успешно!');
        return redirect()->route('visitor.home.ru');
    }

    /**
     * Create a simple visitor session so the user is treated as a visitor (no auth)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('locale', 'ru');
            $request->session()->flash('status', '¡Has iniciado sesión como marciano!');
            return redirect()->route('visitor.home.ru');
        }

        // Si falla, volver al login con error
        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->withInput();
    }
}
