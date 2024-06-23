<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'create companies',
            'view companies',
            'update companies',
            'delete companies',
            'disable companies',
            // Ajoutez d'autres permissions ici
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin',
            'admin_entreprise',
            'user_entreprise',
            'bailleur',
            'locataire',
        ];

        foreach ($roles as $role) {
            $role = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

            // Assigner toutes les permissions au super_admin
            if ($role->name == 'super_admin') {
                $role->syncPermissions(Permission::all());
            }
        }
    }
}
