<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar os roles
        $adminRole = Role::where('name', 'admin')->first();
        $moderatorRole = Role::where('name', 'moderator')->first();

        if (!$adminRole || !$moderatorRole) {
            $this->command->error('Roles não encontrados. Execute primeiro o RoleSeeder.');
            return;
        }

        // Permissões do Administrador (todas as permissões)
        $adminPermissions = Permission::all();
        $adminRole->permissions()->sync($adminPermissions->pluck('id'));

        // Permissões do Moderador (apenas visualização e algumas ações básicas)
        $moderatorPermissions = Permission::whereIn('name', [
            'users.view',
            'characters.view',
            'characters.edit',
            'battles.view',
            'roles.view',
        ])->get();
        
        $moderatorRole->permissions()->sync($moderatorPermissions->pluck('id'));

        $this->command->info('Permissões atribuídas aos roles com sucesso!');
        $this->command->info("Admin: {$adminPermissions->count()} permissões");
        $this->command->info("Moderador: {$moderatorPermissions->count()} permissões");
    }
}
