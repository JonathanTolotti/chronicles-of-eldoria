<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemEffect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Poção de Cura Menor
        $healPotionMinor = Item::create([
            'name' => 'Poção de Cura Menor',
            'description' => 'Uma poção básica que restaura 50 pontos de vida.',
            'type' => 'potion',
            'rarity' => 'common',
            'image_path' => '/images/items/potions/Health_Potion.gif',
        ]);

        ItemEffect::create([
            'item_id' => $healPotionMinor->id,
            'effect_type' => 'heal_hp',
            'effect_value' => 50,
            'effect_duration' => null,
        ]);

        // Poção de Cura
        $healPotion = Item::create([
            'name' => 'Poção de Cura',
            'description' => 'Uma poção que restaura 100 pontos de vida.',
            'type' => 'potion',
            'rarity' => 'common',
            'image_path' => '/images/items/potions/Strong_Health_Potion.gif',
        ]);

        ItemEffect::create([
            'item_id' => $healPotion->id,
            'effect_type' => 'heal_hp',
            'effect_value' => 100,
            'effect_duration' => null,
        ]);

        // Poção de Cura Maior
        $healPotionMajor = Item::create([
            'name' => 'Poção de Cura Maior',
            'description' => 'Uma poção poderosa que restaura 200 pontos de vida.',
            'type' => 'potion',
            'rarity' => 'uncommon',
            'image_path' => '/images/items/potions/Ultimate_Health_Potion.gif',
        ]);

        ItemEffect::create([
            'item_id' => $healPotionMajor->id,
            'effect_type' => 'heal_hp',
            'effect_value' => 200,
            'effect_duration' => null,
        ]);

        // Poção de Energia Menor
        $staminaPotionMinor = Item::create([
            'name' => 'Poção de Energia',
            'description' => 'Uma poção básica que restaura 25 pontos de stamina.',
            'type' => 'potion',
            'rarity' => 'common',
            'image_path' => '/images/items/potions/Stamina_Extension.gif',
        ]);

        ItemEffect::create([
            'item_id' => $staminaPotionMinor->id,
            'effect_type' => 'restore_stamina',
            'effect_value' => 25,
            'effect_duration' => null,
        ]);

        $this->command->info('Poções criadas com sucesso!');
    }
}
