<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage everything',

            // Artist permissions
            'upload artwork',
            'edit artwork',
            'delete artwork',
            'view sales reports',

            // Sales permissions
            'create sales',
            'process payments',
            'view sales dashboard',
            'generate invoices',

            // Accounting permissions
            'view financial reports',
            'process payroll',
            'manage expenses',
            'export financial data'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles with specific permissions
        $superAdmin = Role::create(['name' => 'superadmin']);
        $superAdmin->givePermissionTo(Permission::all());

        $artist = Role::create(['name' => 'artist']);
        $artist->givePermissionTo([
            'upload artwork',
            'edit artwork',
            'delete artwork',
            'view sales reports'
        ]);

        $sales = Role::create(['name' => 'sales']);
        $sales->givePermissionTo([
            'create sales',
            'process payments',
            'view sales dashboard',
            'generate invoices'
        ]);

        $accounting = Role::create(['name' => 'accounting']);
        $accounting->givePermissionTo([
            'view financial reports',
            'process payroll',
            'manage expenses',
            'export financial data'
        ]);

        // Create a superadmin user (if you want)
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'mbadis@hafezgallery.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('superadmin');
    }
}
