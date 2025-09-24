<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Acesso total ao painel administrativo',
                'color' => '#DC2626', // Vermelho
                'icon' => 'fas fa-crown',
                'level' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderador',
                'description' => 'Acesso moderado ao painel administrativo',
                'color' => '#7C3AED', // Roxo
                'icon' => 'fas fa-shield-alt',
                'level' => 50,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
