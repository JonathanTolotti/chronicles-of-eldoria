<?php

namespace Database\Seeders;

use App\Models\ShopItem;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar itens existentes da loja
        ShopItem::query()->delete();

        // Obter todos os itens existentes
        $items = Item::all();

        // Criar itens da loja baseados nos itens existentes
        foreach ($items as $index => $item) {
            ShopItem::create([
                'item_id' => $item->id,
                'gold_price' => rand(50, 500), // Preços baixos em gold
                'coin_price' => 0, // Neste seeder não usamos coin
                'is_available' => true,
                'stock_quantity' => rand(10, 100), // Estoque alto
                'daily_limit' => rand(5, 20), // Limite diário alto
                'min_level' => 1, // Disponível desde o nível 1
                'max_level' => null,
                'is_featured' => rand(0, 1) === 1,
                'is_limited_time' => false,
                'is_one_time_purchase' => $index < 3, // Primeiros 3 itens são compra única
                'available_until' => null,
                'discount_percentage' => rand(0, 1) === 1 ? rand(5, 15) : 0,
            ]);
        }


        $this->command->info('Shop items seeded successfully!');
    }
}
