<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'adminmds@example.com'],
            [
                'name' => 'AdminMDS',
                'password' => Hash::make('password'),
            ]
        );

        $adminRole = Role::firstOrCreate(['name' => 'admin']); // guard web (default)
        if (!$user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
        // Nota: No crear rol duplicado para guard 'sanctum' (produce GuardDoesNotMatch). Solo guard 'web'.

        $token = $user->createToken('admin-seeder')->plainTextToken;
        $tokenPath = storage_path('token_admin.txt');
        File::put($tokenPath, $token . PHP_EOL);
    }
}
