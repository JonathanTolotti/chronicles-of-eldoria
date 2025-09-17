<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Console\Command;

class AddTestPotionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'potions:add-test {character_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona poções de teste ao inventário do personagem';

    /**
     * Execute the console command.
     */
    public function handle(ItemService $itemService)
    {
        $characterId = $this->argument('character_id');
        
        if (!$characterId) {
            $character = Character::first();
            if (!$character) {
                $this->error('Nenhum personagem encontrado no banco de dados.');
                return;
            }
        } else {
            $character = Character::find($characterId);
            if (!$character) {
                $this->error("Personagem com ID {$characterId} não encontrado.");
                return;
            }
        }

        $this->info("Adicionando poções ao personagem: {$character->name}");

        // Buscar poções disponíveis
        $potions = Item::where('type', 'potion')->get();

        foreach ($potions as $potion) {
            // Adicionar algumas poções de cada tipo
            $quantity = match($potion->rarity) {
                'common' => 5,
                'uncommon' => 3,
                'rare' => 1,
                default => 2
            };

            $itemService->addItemToInventory($character, $potion->id, $quantity);
            $this->line("✓ Adicionado {$quantity}x {$potion->name}");
        }

        $this->info('Poções adicionadas com sucesso!');
    }
}
