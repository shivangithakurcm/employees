<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Roles banana
        $admin   = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $sales   = Role::firstOrCreate(['name' => 'sales']);

        // Permissions banana
        $permissions = [
            'view-all-leads',
            'view-team-leads',
            'view-own-leads',
            'assign-leads',
            'delete-leads',
            'manage-employees',
            'view-reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin ko sab permissions
        $admin->syncPermissions($permissions);

        // Manager ko limited permissions
        $manager->syncPermissions([
            'view-team-leads',
            'view-own-leads',
            'assign-leads',
            'view-reports',
        ]);

        // Sales ko sirf apne leads
        $sales->syncPermissions([
            'view-own-leads',
        ]);
    }
}