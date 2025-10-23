<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // Mostrar formulario de registro para admin (solo si se autorizó en sesión)
    public function showRegistrationForm(Request $request)
    {
        if (! $request->session()->get('allow_admin_create')) {
            return redirect()->route('admin.gate.form')->withErrors(['secret' => 'Se requiere autorización para crear administradores.']);
        }

        return view('auth.register_admin');
    }

    // Registrar admin
    public function register(Request $request)
    {
        if (! $request->session()->pull('allow_admin_create')) {
            return redirect()->route('admin.gate.form')->withErrors(['secret' => 'Se requiere autorización para crear administradores.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        try {
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }
        } catch (\Throwable $e) {
            // ignore
        }

        auth()->login($user);

        $request->session()->flash('status', 'Administrador creado correctamente.');
        return redirect()->route('home');
    }
}
