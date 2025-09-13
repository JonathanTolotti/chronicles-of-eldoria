<?php

namespace Database\Seeders;

use App\Models\Monster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monsters = [
            [
                'name' => 'Goblin Fraco',
                'description' => 'Um goblin pequeno e fraco, perfeito para iniciantes.',
                'level' => 1,
                'max_hp' => 30,
                'current_hp' => 30,
                'attack_power' => 8,
                'defense' => 2,
                'speed' => 5,
                'gold_reward' => 15,
                'exp_reward' => 25,
                'image' => '👹',
                'is_active' => true,
            ],
            [
                'name' => 'Orc Guerreiro',
                'description' => 'Um orc musculoso com experiência em combate.',
                'level' => 3,
                'max_hp' => 80,
                'current_hp' => 80,
                'attack_power' => 18,
                'defense' => 8,
                'speed' => 4,
                'gold_reward' => 45,
                'exp_reward' => 75,
                'image' => '👹',
                'is_active' => true,
            ],
            [
                'name' => 'Esqueleto Maldito',
                'description' => 'Um esqueleto reanimado com poderes sombrios.',
                'level' => 5,
                'max_hp' => 120,
                'current_hp' => 120,
                'attack_power' => 25,
                'defense' => 12,
                'speed' => 6,
                'gold_reward' => 80,
                'exp_reward' => 150,
                'image' => '💀',
                'is_active' => true,
            ],
            [
                'name' => 'Dragão Jovem',
                'description' => 'Um dragão ainda jovem, mas já perigoso.',
                'level' => 8,
                'max_hp' => 200,
                'current_hp' => 200,
                'attack_power' => 40,
                'defense' => 20,
                'speed' => 8,
                'gold_reward' => 200,
                'exp_reward' => 400,
                'image' => '🐉',
                'is_active' => true,
            ],
        ];

        foreach ($monsters as $monster) {
            Monster::create($monster);
        }
    }
}
