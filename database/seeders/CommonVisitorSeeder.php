<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CommonVisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['user', 'visitor'] as $r) {
            if (! Role::where('name', $r)->exists()) {
                Role::create(['name' => $r]);
            }
        }

        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Usuario Comun',
            'password' => Hash::make('password'),
        ]);
        try { if (method_exists($user, 'assignRole')) $user->assignRole('user'); } catch (\Throwable $e) {}

        $visitor = User::firstOrCreate([
            'email' => 'visitor@example.com'
        ], [
            'name' => 'Visitante',
            'password' => Hash::make('password'),
        ]);
        try { if (method_exists($visitor, 'assignRole')) $visitor->assignRole('visitor'); } catch (\Throwable $e) {}
    }
}
