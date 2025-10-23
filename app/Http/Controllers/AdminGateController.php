<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminGateController extends Controller
{
    public function showGateForm()
    {
        return view('auth.admin_gate');
    }

    public function validateGate(Request $request)
    {
        $data = $request->validate([
            'secret' => ['required', 'string'],
        ]);

        $secret = env('ADMIN_CREATION_SECRET', '');

        if ($secret && hash_equals($secret, $data['secret'])) {
            $request->session()->put('allow_admin_create', true);
            return redirect()->route('register.admin.form')->with('status', 'Ingreso aprobado. Ahora puedes crear un administrador.');
        }

        return back()->withErrors(['secret' => 'Clave inválida.']);
    }
}
