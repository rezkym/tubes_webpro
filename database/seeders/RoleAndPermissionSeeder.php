<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Student permissions
            'view attendance',
            'mark attendance',
            
            // Teacher permissions
            'manage attendance',
            'view students',
            'manage subjects',
            
            // Admin permissions
            'manage users',
            'manage roles',
            'manage classes',
            'view reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $teacherRole->givePermissionTo([
            'manage attendance',
            'view students',
            'manage subjects',
            'view reports'
        ]);
        
        $studentRole->givePermissionTo([
            'view attendance',
            'mark attendance'
        ]);
    }
}