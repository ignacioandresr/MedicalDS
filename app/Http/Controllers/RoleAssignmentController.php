<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleAssignmentController extends Controller
{
    public function index(Request $request)
    {
        // Exclude currently authenticated user to avoid confusion
        if (auth()->check()) {
            $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        } else {
            $users = User::orderBy('name')->get();
        }
        $roles = Role::pluck('name');

        $selectedUser = null;
        $currentRole = null;
        if ($request->filled('user_id')) {
            $maybe = User::with('roles')->find($request->get('user_id'));
            // don't allow selecting the current authenticated user
            if ($maybe && (!auth()->check() || $maybe->id !== auth()->id())) {
                $selectedUser = $maybe;
                if ($selectedUser->roles->isNotEmpty()) {
                    $currentRole = $selectedUser->roles->pluck('name')->first();
                }
            }
        }

        return view('roles.assign', compact('users', 'roles', 'selectedUser', 'currentRole'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|exists:roles,name'
        ]);

        $user = User::findOrFail($data['user_id']);
        // Prevent changing role of the currently authenticated user
        if (auth()->check() && $user->id === auth()->id()) {
            return redirect()->route('roles.assign', ['user_id' => $user->id])->with('error', 'No puedes cambiar el rol de tu propio usuario.');
        }
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('roles.assign')->with('status', 'Rol actualizado correctamente.');
    }

    public function users()
    {
        $users = User::with('roles')->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users')->with('status', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('status', 'Usuario eliminado correctamente.');
    }
}
