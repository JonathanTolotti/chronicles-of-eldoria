<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Services\InventoryService;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService,
        private EquipmentService $equipmentService
    ) {}

    public function index(Request $request): Response|RedirectResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;
        
        // Se não há personagem ativo, redirecionar para seleção
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para acessar o inventário.');
        }

        // Carregar todos os itens do inventário
        $inventory = $this->inventoryService->getInventoryByTabs($character);
        
        // Carregar equipamentos (incluindo equipados)
        $equipment = $this->equipmentService->getCharacterEquipment($character);
        
        // Separar por tipo e ajustar estrutura
        $potions = collect($inventory['potions'])->map(function ($item) {
            if (!is_array($item) || !isset($item['item'])) {
                return null;
            }
            
            return [
                'id' => $item['id'] ?? 0,
                'name' => $item['item']['name'] ?? 'Item Desconhecido',
                'description' => $item['item']['description'] ?? '',
                'image' => $item['item']['image_path'] ?? '/images/items/default.png',
                'quantity' => $item['quantity'] ?? 0,
                'type' => $item['item']['type'] ?? 'unknown',
                'rarity' => $item['item']['rarity'] ?? 'common',
                'effects' => $item['item']['effects'] ?? null,
                'show_in_quick_inventory' => $item['show_in_quick_inventory'] ?? false,
            ];
        })->filter(); // Remove valores null
        
        $materials = collect($inventory['materials'])->map(function ($item) {
            if (!is_array($item) || !isset($item['item'])) {
                return null;
            }
            
            return [
                'id' => $item['id'] ?? 0,
                'name' => $item['item']['name'] ?? 'Item Desconhecido',
                'description' => $item['item']['description'] ?? '',
                'image' => $item['item']['image_path'] ?? '/images/items/default.png',
                'quantity' => $item['quantity'] ?? 0,
                'type' => $item['item']['type'] ?? 'unknown',
                'rarity' => $item['item']['rarity'] ?? 'common',
                'show_in_quick_inventory' => $item['show_in_quick_inventory'] ?? false,
            ];
        })->filter(); // Remove valores null
        
        $equipmentItems = collect($equipment['inventory'])->flatten(1)->map(function ($item) {
            if (!is_array($item) || !isset($item['equipment'])) {
                return null;
            }
            
            return [
                'id' => $item['id'] ?? 0,
                'equipment' => [
                    'id' => $item['equipment']['id'] ?? 0,
                    'name' => $item['equipment']['name'] ?? 'Equipamento Desconhecido',
                    'description' => $item['equipment']['description'] ?? '',
                    'image' => $item['equipment']['image'] ?? '/images/equipments/default.png',
                    'type' => $item['equipment']['type'] ?? 'unknown',
                    'rarity' => $item['equipment']['rarity'] ?? 'common',
                    'strength_bonus' => $item['equipment']['strength_bonus'] ?? 0,
                    'dexterity_bonus' => $item['equipment']['dexterity_bonus'] ?? 0,
                    'constitution_bonus' => $item['equipment']['constitution_bonus'] ?? 0,
                    'intelligence_bonus' => $item['equipment']['intelligence_bonus'] ?? 0,
                    'luck_bonus' => $item['equipment']['luck_bonus'] ?? 0,
                ],
                'current_tier' => $item['current_tier'] ?? 0,
                'is_equipped' => $item['is_equipped'] ?? false,
                'show_in_quick_inventory' => $item['show_in_quick_inventory'] ?? false,
            ];
        })->filter(); // Remove valores null
        
        // Não adicionar hotkeys - apenas o inventário rápido lida com isso

        return Inertia::render('Inventory/Index', [
            'character' => $character,
            'potions' => $potions,
            'equipment' => $equipmentItems,
            'materials' => $materials,
        ]);
    }

}