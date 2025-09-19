<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $itemService
    ) {}

    /**
     * Use item from hotkey slot (F1-F4)
     */
    public function useHotkeyItem(Request $request): JsonResponse
    {
        $request->validate([
            'slot' => 'required|integer|min:1|max:4'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $result = $this->itemService->useItemFromHotkey($character, (int) $request->slot);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Set item to hotkey slot
     */
    public function setHotkeySlot(Request $request): JsonResponse
    {
        $request->validate([
            'character_item_id' => 'required|integer|exists:character_items,id',
            'slot' => 'required|integer|min:1|max:4'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $success = $this->itemService->setHotkeySlot(
            $character,
            (int) $request->character_item_id,
            (int) $request->slot
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Item definido no hotkey com sucesso' : 'Erro ao definir item no hotkey'
        ], $success ? 200 : 400);
    }

    /**
     * Get hotkey items
     */
    public function getHotkeyItems(): JsonResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $hotkeys = $this->itemService->getHotkeyItems($character);

        return response()->json([
            'success' => true,
            'hotkeys' => $hotkeys
        ]);
    }

    /**
     * Add item to inventory (for testing/admin)
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa selecionar um personagem'
            ], 400);
        }

        $characterItem = $this->itemService->addItemToInventory(
            $character,
            (int) $request->item_id,
            (int) $request->quantity
        );

        return response()->json([
            'success' => true,
            'message' => 'Item adicionado ao inventário',
            'character_item' => $characterItem->load('item.effects')
        ]);
    }

    /**
     * Toggle item visibility in quick inventory
     */
    public function toggleQuickInventory(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer',
            'show_in_quick_inventory' => 'required|boolean'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'Personagem não encontrado'
            ], 404);
        }

        $characterItem = $character->items()
            ->where('id', $request->item_id)
            ->first();

        if (!$characterItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item não encontrado no inventário'
            ], 404);
        }

        $characterItem->update([
            'show_in_quick_inventory' => $request->show_in_quick_inventory
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->show_in_quick_inventory 
                ? 'Item adicionado ao inventário rápido' 
                : 'Item removido do inventário rápido'
        ]);
    }
}
