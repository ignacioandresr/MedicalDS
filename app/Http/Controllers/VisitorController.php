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
        return redirect()->route('visitor.register');
    }

    /**
     * Create a simple visitor session so the user is treated as a visitor (no auth)
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Store visitor info in session (simple, non-auth)
        $request->session()->put('visitor', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Keep Russian locale like create()
        $request->session()->put('locale', 'ru');

        $request->session()->flash('status', 'Вы вошли как посетитель.');

        // Redirect back to the register page (or home) — mirror create behavior
        return redirect()->route('visitor.register');
    }
}
