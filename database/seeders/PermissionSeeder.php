<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Permissões de Usuários
            [
                'name' => 'users.view',
                'display_name' => 'Visualizar Usuários',
                'description' => 'Pode visualizar a lista de usuários',
                'category' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'users.create',
                'display_name' => 'Criar Usuários',
                'description' => 'Pode criar novos usuários',
                'category' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'users.edit',
                'display_name' => 'Editar Usuários',
                'description' => 'Pode editar informações de usuários',
                'category' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'Deletar Usuários',
                'description' => 'Pode deletar usuários',
                'category' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'users.manage_roles',
                'display_name' => 'Gerenciar Roles de Usuários',
                'description' => 'Pode atribuir e remover roles de usuários',
                'category' => 'users',
                'is_active' => true,
            ],

            // Permissões de Personagens
            [
                'name' => 'characters.view',
                'display_name' => 'Visualizar Personagens',
                'description' => 'Pode visualizar a lista de personagens',
                'category' => 'characters',
                'is_active' => true,
            ],
            [
                'name' => 'characters.create',
                'display_name' => 'Criar Personagens',
                'description' => 'Pode criar novos personagens',
                'category' => 'characters',
                'is_active' => true,
            ],
            [
                'name' => 'characters.edit',
                'display_name' => 'Editar Personagens',
                'description' => 'Pode editar informações de personagens',
                'category' => 'characters',
                'is_active' => true,
            ],
            [
                'name' => 'characters.delete',
                'display_name' => 'Deletar Personagens',
                'description' => 'Pode deletar personagens',
                'category' => 'characters',
                'is_active' => true,
            ],

            // Permissões de Batalhas
            [
                'name' => 'battles.view',
                'display_name' => 'Visualizar Batalhas',
                'description' => 'Pode visualizar histórico de batalhas',
                'category' => 'battles',
                'is_active' => true,
            ],
            [
                'name' => 'battles.manage',
                'display_name' => 'Gerenciar Batalhas',
                'description' => 'Pode gerenciar batalhas do sistema',
                'category' => 'battles',
                'is_active' => true,
            ],

            // Permissões de Sistema
            [
                'name' => 'system.settings',
                'display_name' => 'Configurações do Sistema',
                'description' => 'Pode alterar configurações do sistema',
                'category' => 'system',
                'is_active' => true,
            ],
            [
                'name' => 'system.maintenance',
                'display_name' => 'Modo de Manutenção',
                'description' => 'Pode ativar/desativar modo de manutenção',
                'category' => 'system',
                'is_active' => true,
            ],
            [
                'name' => 'system.logs',
                'display_name' => 'Visualizar Logs',
                'description' => 'Pode visualizar logs do sistema',
                'category' => 'system',
                'is_active' => true,
            ],

            // Permissões de Roles e Permissões
            [
                'name' => 'roles.view',
                'display_name' => 'Visualizar Roles',
                'description' => 'Pode visualizar roles do sistema',
                'category' => 'roles',
                'is_active' => true,
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'Criar Roles',
                'description' => 'Pode criar novos roles',
                'category' => 'roles',
                'is_active' => true,
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'Editar Roles',
                'description' => 'Pode editar roles existentes',
                'category' => 'roles',
                'is_active' => true,
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'Deletar Roles',
                'description' => 'Pode deletar roles',
                'category' => 'roles',
                'is_active' => true,
            ],
            [
                'name' => 'permissions.manage',
                'display_name' => 'Gerenciar Permissões',
                'description' => 'Pode gerenciar permissões do sistema',
                'category' => 'permissions',
                'is_active' => true,
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }
    }
}
