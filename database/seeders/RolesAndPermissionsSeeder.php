<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
        public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Load all permissions from config
        $allPermissions = config('data.permissions.all', []);
        
        // Define which config keys correspond to which roles
        $roleConfigMap = [
            'admin' => 'data.permissions.admin',
            'user' => 'data.permissions.user',
            'sub_user' => 'data.permissions.sub_account_user',
        ];

        // Create all permissions first
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions from config
        foreach ($roleConfigMap as $roleName => $configPath) {
            $rolePermissions = config($configPath, []);
            
            if (!empty($rolePermissions)) {
                $role = Role::firstOrCreate(['name' => $roleName]);
                $role->syncPermissions($rolePermissions);
            }
        }
    }
}
