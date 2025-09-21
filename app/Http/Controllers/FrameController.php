<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FrameController extends Controller
{
    /**
     * Aplicar uma moldura ao personagem
     */
    public function apply(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'frame_id' => 'required|integer|exists:items,id',
            ]);

            $user = $request->user();
            $character = $user->activeCharacter;
            
            if (!$character) {
                return response()->json(['error' => 'Personagem ativo não encontrado'], 400);
            }

            $frameId = $request->frame_id;
            $frame = Item::findOrFail($frameId);

            // Verificar se é um item cosmético
            if ($frame->type !== 'cosmetic') {
                return response()->json(['error' => 'Este item não é uma moldura'], 400);
            }

            // Verificar se o personagem possui este item
            $characterItem = CharacterItem::where('character_id', $character->id)
                ->where('item_id', $frame->id)
                ->where('quantity', '>', 0)
                ->first();

            if (!$characterItem) {
                return response()->json(['error' => 'Você não possui esta moldura'], 400);
            }

            // Aplicar a moldura
            $character->active_frame_id = $frame->id;
            $character->save();

            return response()->json([
                'success' => true,
                'message' => 'Moldura aplicada com sucesso!',
                'frame' => $frame,
                'character' => $character->fresh(['activeFrame']),
                'active_frame_id' => $character->active_frame_id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao aplicar moldura: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Remover moldura do personagem
     */
    public function remove(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $character = $user->activeCharacter;
            
            if (!$character) {
                return response()->json(['error' => 'Personagem ativo não encontrado'], 400);
            }

            if (!$character->active_frame_id) {
                return response()->json(['error' => 'Nenhuma moldura está ativa'], 400);
            }

            // Remover a moldura
            $character->active_frame_id = null;
            $character->save();

            return response()->json([
                'success' => true,
                'message' => 'Moldura removida com sucesso!',
                'character' => $character->fresh(),
                'active_frame_id' => null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao remover moldura: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Obter molduras disponíveis do personagem
     */
    public function available(Request $request): JsonResponse
    {
        $user = $request->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Personagem ativo não encontrado'], 400);
        }

        // Buscar itens cosméticos que o personagem possui
        $frames = CharacterItem::where('character_id', $character->id)
            ->where('quantity', '>', 0)
            ->whereHas('item', function ($query) {
                $query->where('type', 'cosmetic');
            })
            ->with('item')
            ->get()
            ->map(function ($characterItem) {
                return [
                    'id' => $characterItem->item->id,
                    'name' => $characterItem->item->name,
                    'description' => $characterItem->item->description,
                    'image_path' => $characterItem->item->image_path,
                    'rarity' => $characterItem->item->rarity,
                    'quantity' => $characterItem->quantity,
                    'is_active' => $characterItem->character->active_frame_id === $characterItem->item->id,
                ];
            });

        return response()->json([
            'success' => true,
            'frames' => $frames,
            'active_frame' => $character->activeFrame,
        ]);
    }
}