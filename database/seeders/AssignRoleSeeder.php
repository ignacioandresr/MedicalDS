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
    }
}
