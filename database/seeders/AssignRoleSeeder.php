<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'adminmds@example.com')->first();
        $user->assignRole('admin');
        try {
            $token = $user->createToken('seeder-token')->plainTextToken;
            $path = storage_path('token_admin.txt');
            file_put_contents($path, $token);
            if ($this->command) {
                $this->command->info('Admin token created and saved to: ' . $path);
            }
        } catch (\Exception $e) {
            if ($this->command) {
                $this->command->error('Failed to create token: ' . $e->getMessage());
            }
        }
    }
}
