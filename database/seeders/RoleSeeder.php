<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            'admin' => 'Administrator with full access',
            'user' => 'Regular user with standard access',
            'api_user' => 'API-only user for programmatic access',
            'manager' => 'Manager with limited admin access',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
        }

        // Create permissions (optional for future use)
        $permissions = [
            'manage users',
            'manage tenants',
            'manage providers',
            'view reports',
            'manage pricing',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Assign all permissions to admin role
        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(Permission::all());
    }
}
