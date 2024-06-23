<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'super_admin',
            'admin_entreprise',
            'user_entreprise',
            'bailleur',
            'locataire',
        ];

        foreach ($roles as $roleName) {
            // Créer le rôle s'il n'existe pas déjà
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            // Créer un utilisateur pour chaque rôle
            $user = User::create([
                'name' => ucfirst($roleName),
                'email' => $roleName . '@example.com',
                'password' => Hash::make('password123'), // Utiliser un mot de passe sécurisé
                'is_active' => true,
            ]);

            // Assigner le rôle à l'utilisateur
            $user->assignRole($role);
        }
    }
}
