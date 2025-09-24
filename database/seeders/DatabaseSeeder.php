<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Executar seeders na ordem correta
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Criar usuário de teste com role de admin
        $adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'uuid' => \Illuminate\Support\Str::uuid(),
            'is_staff' => true,
        ]);

        // Atribuir role de admin ao usuário de teste
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }
    }
}
