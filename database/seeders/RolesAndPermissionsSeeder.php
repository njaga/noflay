<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define all the permissions
        $permissions = [
            'create companies',
            'view companies',
            'update companies',
            'delete companies',
            'disable companies',
            'manage users',
            'manage roles',
            'create properties',
            'view properties',
            'update properties',
            'delete properties',
            'create landlords',
            'view landlords',
            'update landlords',
            'delete landlords',
            'create tenants',
            'view tenants',
            'update tenants',
            'delete tenants',
            'view reports',
            'manage finances',
        ];

        // Create each permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define the roles
        $roles = [
            'super_admin',
            'admin_entreprise',
            'user_entreprise',
            'bailleur',
            'locataire',
        ];

        // Create each role and assign permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            switch ($roleName) {
                case 'super_admin':
                    $role->syncPermissions(Permission::all());
                    break;
                case 'admin_entreprise':
                    $role->syncPermissions([
                        'create companies',
                        'view companies',
                        'update companies',
                        'delete companies',
                        'manage users',
                        'manage roles',
                        'create properties',
                        'view properties',
                        'update properties',
                        'delete properties',
                        'create landlords',
                        'view landlords',
                        'update landlords',
                        'delete landlords',
                        'create tenants',
                        'view tenants',
                        'update tenants',
                        'delete tenants',
                        'view reports',
                        'manage finances',
                    ]);
                    break;
                case 'user_entreprise':
                    $role->syncPermissions([
                        'view companies',
                        'view properties',
                        'create properties',
                        'update properties',
                        'view landlords',
                        'create landlords',
                        'update landlords',
                        'view tenants',
                        'create tenants',
                        'update tenants',
                        'view reports',
                    ]);
                    break;
                case 'bailleur':
                    $role->syncPermissions([
                        'view properties',
                        'view tenants',
                        'view reports',
                    ]);
                    break;
                case 'locataire':
                    $role->syncPermissions([
                        'view properties',
                        'view reports',
                    ]);
                    break;
            }
        }

        // Create a user for each role and assign roles
        foreach ($roles as $roleName) {
            $user = User::firstOrCreate(
                ['email' => $roleName . '@example.com'],
                [
                    'name' => ucfirst($roleName),
                    'password' => bcrypt('password')
                ]
            );

            // Assign the role to the user
            $role = Role::where('name', $roleName)->first();

            // Directly insert into model_has_roles table
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => User::class,
                'model_id' => $user->id
            ]);
        }
    }
}
