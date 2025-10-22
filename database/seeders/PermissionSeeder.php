<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permission for deleting symptoms
        Permission::firstOrCreate(['name' => 'delete symptoms']);

        // Optionally assign to admin role if exists
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo('delete symptoms');
        }
    }
}
