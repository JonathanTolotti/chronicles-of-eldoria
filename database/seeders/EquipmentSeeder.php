<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Character;
use App\Models\CharacterEquipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar equipamentos base
        $equipments = [
            [
                'name' => 'Soulbiter',
                'description' => 'Uma espada sombria que drena a energia vital dos inimigos.',
                'type' => 'weapon',
                'image' => '/images/equipments/Soulbiter.gif',
                'strength_bonus' => 15,
                'dexterity_bonus' => 5,
                'constitution_bonus' => 0,
                'intelligence_bonus' => 0,
                'luck_bonus' => 3,
                'hp_bonus' => 0,
                'mp_bonus' => 0,
            ],
            [
                'name' => 'Soulbastion',
                'description' => 'Um escudo místico que protege contra ataques sombrios.',
                'type' => 'shield',
                'image' => '/images/equipments/Soulbastion.gif',
                'strength_bonus' => 0,
                'dexterity_bonus' => 0,
                'constitution_bonus' => 12,
                'intelligence_bonus' => 0,
                'luck_bonus' => 2,
                'hp_bonus' => 25,
                'mp_bonus' => 0,
            ],
            [
                'name' => 'Soulsoles',
                'description' => 'Botas que permitem movimento silencioso e ágil.',
                'type' => 'boots',
                'image' => '/images/equipments/Soulsoles.gif',
                'strength_bonus' => 0,
                'dexterity_bonus' => 10,
                'constitution_bonus' => 3,
                'intelligence_bonus' => 0,
                'luck_bonus' => 1,
                'hp_bonus' => 10,
                'mp_bonus' => 0,
            ],
            [
                'name' => 'Soulshanks',
                'description' => 'Calças resistentes que protegem as pernas em combate.',
                'type' => 'pants',
                'image' => '/images/equipments/Soulshanks.gif',
                'strength_bonus' => 0,
                'dexterity_bonus' => 0,
                'constitution_bonus' => 8,
                'intelligence_bonus' => 0,
                'luck_bonus' => 1,
                'hp_bonus' => 15,
                'mp_bonus' => 0,
            ],
            [
                'name' => 'Soulmantle',
                'description' => 'Uma armadura espiritual que absorve dano mágico.',
                'type' => 'armor',
                'image' => '/images/equipments/Soulmantle.gif',
                'strength_bonus' => 0,
                'dexterity_bonus' => 0,
                'constitution_bonus' => 15,
                'intelligence_bonus' => 5,
                'luck_bonus' => 2,
                'hp_bonus' => 40,
                'mp_bonus' => 10,
            ],
            [
                'name' => 'Spiritthorn Helmet',
                'description' => 'Um elmo com espinhos espirituais que protege a mente.',
                'type' => 'helmet',
                'image' => '/images/equipments/Spiritthorn_Helmet.gif',
                'strength_bonus' => 0,
                'dexterity_bonus' => 0,
                'constitution_bonus' => 6,
                'intelligence_bonus' => 8,
                'luck_bonus' => 1,
                'hp_bonus' => 20,
                'mp_bonus' => 15,
            ],
        ];

        // Criar equipamentos na base de dados
        foreach ($equipments as $equipmentData) {
            Equipment::create($equipmentData);
        }

        // Adicionar um equipamento de cada tipo para todos os personagens existentes
        $characters = Character::all();
        $equipmentTypes = ['weapon', 'shield', 'boots', 'pants', 'armor', 'helmet'];

        foreach ($characters as $character) {
            foreach ($equipmentTypes as $type) {
                // Buscar o equipamento do tipo específico
                $equipment = Equipment::where('type', $type)->first();
                
                if ($equipment) {
                    // Verificar se o personagem já tem equipamento neste slot
                    $existingEquipment = CharacterEquipment::where('character_id', $character->id)
                        ->where('slot', $type)
                        ->first();

                    if (!$existingEquipment) {
                        // Criar equipamento para o personagem
                        CharacterEquipment::create([
                            'character_id' => $character->id,
                            'slot' => $type,
                            'equipment_id' => $equipment->id,
                            'current_tier' => 0, // Tier inicial
                            'is_equipped' => true, // Equipado por padrão
                        ]);
                    }
                }
            }
        }

        $this->command->info('Equipamentos criados e distribuídos para todos os personagens!');
    }
}
