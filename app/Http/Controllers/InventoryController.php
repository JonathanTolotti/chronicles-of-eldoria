<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    /**
     * Show inventory page
     */
    public function index(): Response|RedirectResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para acessar o inventário.');
        }

        $inventory = $this->inventoryService->getInventoryByTabs($character);
        $equipped = $this->inventoryService->getEquippedItems($character);
        $stats = $this->inventoryService->getInventoryStats($character);

        return Inertia::render('Inventory/Index', [
            'character' => $character->fresh(),
            'inventory' => $inventory,
            'equipped' => $equipped,
            'stats' => $stats,
        ]);
    }

    /**
     * Get inventory data as JSON
     */
    public function getInventory(): JsonResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $inventory = $this->inventoryService->getInventoryByTabs($character);
        $equipped = $this->inventoryService->getEquippedItems($character);
        $stats = $this->inventoryService->getInventoryStats($character);

        return response()->json([
            'success' => true,
            'inventory' => $inventory,
            'equipped' => $equipped,
            'stats' => $stats,
        ]);
    }

    /**
     * Equip an item
     */
    public function equipItem(Request $request): JsonResponse
    {
        $request->validate([
            'character_item_id' => 'required|integer|exists:character_items,id',
            'equipment_slot' => 'required|string|in:weapon,helmet,armor,boots,accessory1,accessory2'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $success = $this->inventoryService->equipItem(
            $character,
            (int) $request->character_item_id,
            $request->equipment_slot
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Item equipado com sucesso' : 'Erro ao equipar item'
        ], $success ? 200 : 400);
    }

    /**
     * Unequip an item
     */
    public function unequipItem(Request $request): JsonResponse
    {
        $request->validate([
            'character_item_id' => 'required|integer|exists:character_items,id'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $success = $this->inventoryService->unequipItem(
            $character,
            (int) $request->character_item_id
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Item desequipado com sucesso' : 'Erro ao desequipar item'
        ], $success ? 200 : 400);
    }
}
