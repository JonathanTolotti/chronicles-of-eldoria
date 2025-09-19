<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Character;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function __construct(
        private EquipmentService $equipmentService
    ) {}

    /**
     * Obter equipamentos do personagem ativo
     */
    public function index(): JsonResponse
    {
        $character = Auth::user()->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Nenhum personagem ativo encontrado'], 404);
        }

        $equipment = $this->equipmentService->getCharacterEquipment($character);

        return response()->json([
            'success' => true,
            'equipment' => $equipment,
            'character' => $character
        ]);
    }

    /**
     * Equipar um item
     */
    public function equip(Request $request): JsonResponse
    {
        $request->validate([
            'equipment_id' => 'required|integer|exists:equipment,id',
            'slot' => 'required|string|in:helmet,armor,weapon,shield,boots,pants'
        ]);

        $character = Auth::user()->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Nenhum personagem ativo encontrado'], 404);
        }

        try {
            $result = $this->equipmentService->equipItem(
                $character,
                $request->equipment_id,
                $request->slot
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Desequipar um item
     */
    public function unequip(Request $request): JsonResponse
    {
        $request->validate([
            'slot' => 'required|string|in:helmet,armor,weapon,shield,boots,pants'
        ]);

        $character = Auth::user()->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Nenhum personagem ativo encontrado'], 404);
        }

        try {
            $result = $this->equipmentService->unequipItem($character, $request->slot);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obter equipamentos disponíveis para um slot
     */
    public function available(Request $request): JsonResponse
    {
        $request->validate([
            'slot' => 'required|string|in:helmet,armor,weapon,shield,boots,pants'
        ]);

        $equipment = $this->equipmentService->getAvailableEquipmentForSlot($request->slot);

        return response()->json([
            'success' => true,
            'equipment' => $equipment
        ]);
    }

    /**
     * Recalcular stats do personagem (útil para debug)
     */
    public function recalculate(): JsonResponse
    {
        $character = Auth::user()->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Nenhum personagem ativo encontrado'], 404);
        }

        $this->equipmentService->recalculateCharacterStats($character);

        return response()->json([
            'success' => true,
            'message' => 'Stats recalculados com sucesso!',
            'character' => $character->fresh()
        ]);
    }

    /**
     * Toggle equipment visibility in quick inventory
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

        $characterEquipment = $character->equipment()
            ->where('id', $request->item_id)
            ->first();

        if (!$characterEquipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipamento não encontrado no inventário'
            ], 404);
        }

        $characterEquipment->update([
            'show_in_quick_inventory' => $request->show_in_quick_inventory
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->show_in_quick_inventory 
                ? 'Equipamento adicionado ao inventário rápido' 
                : 'Equipamento removido do inventário rápido'
        ]);
    }
}
