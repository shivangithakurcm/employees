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
        // Sirf 2 roles
        $admin    = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        // Permissions
        $permissions = [
            'view-all-leads',
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

        // Employee ko sirf apni leads
        $employee->syncPermissions([
            'view-own-leads',
        ]);
    }
}