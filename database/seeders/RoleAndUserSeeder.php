<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::updateOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrator',
            'description' => 'Full access to all modules'
        ]);
        
        Role::updateOrCreate(['name' => 'manager'], [
            'display_name' => 'Manager',
            'description' => 'Manage daily operations'
        ]);
        
        Role::updateOrCreate(['name' => 'cashier'], [
            'display_name' => 'Cashier',
            'description' => 'Only POS billing'
        ]);

        // Assign all permissions to admin role
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Create admin user
        $admin = User::updateOrCreate(['email' => 'admin@sweetshop.com'], [
            'name' => 'Super Admin',
            'phone' => '9999999999',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->roles()->sync([$adminRole->id]);

        $this->command->info('Roles and users seeded successfully!');
    }
}