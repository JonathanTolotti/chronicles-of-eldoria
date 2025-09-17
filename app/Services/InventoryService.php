<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\CharacterItem;

class InventoryService
{
    /**
     * Get character's inventory organized by tabs
     */
    public function getInventoryByTabs(Character $character): array
    {
        $items = CharacterItem::where('character_id', $character->id)
            ->where('is_equipped', false)
            ->with(['item.effects'])
            ->get();

        return [
            'potions' => $items->filter(fn($item) => $item->item->type === 'potion')->values()->toArray(),
            'equipment' => $items->filter(fn($item) => in_array($item->item->type, ['weapon', 'armor', 'accessory']))->values()->toArray(),
            'materials' => $items->filter(fn($item) => $item->item->type === 'material')->values()->toArray(),
            'other' => $items->filter(fn($item) => !in_array($item->item->type, ['potion', 'weapon', 'armor', 'accessory', 'material']))->values()->toArray(),
        ];
    }

    /**
     * Get character's equipped items
     */
    public function getEquippedItems(Character $character): array
    {
        return CharacterItem::where('character_id', $character->id)
            ->where('is_equipped', true)
            ->with(['item.effects'])
            ->get()
            ->keyBy('equipment_slot')
            ->toArray();
    }

    /**
     * Equip an item
     */
    public function equipItem(Character $character, int $characterItemId, string $equipmentSlot): bool
    {
        $characterItem = CharacterItem::where('character_id', $character->id)
            ->where('id', $characterItemId)
            ->where('is_equipped', false)
            ->first();

        if (!$characterItem) {
            return false;
        }

        // Check if item can be equipped in this slot
        if (!$this->canEquipInSlot($characterItem->item->type, $equipmentSlot)) {
            return false;
        }

        // Unequip current item in this slot
        CharacterItem::where('character_id', $character->id)
            ->where('is_equipped', true)
            ->where('equipment_slot', $equipmentSlot)
            ->update(['is_equipped' => false, 'equipment_slot' => null]);

        // Equip new item
        $characterItem->is_equipped = true;
        $characterItem->equipment_slot = $equipmentSlot;
        $characterItem->save();

        return true;
    }

    /**
     * Unequip an item
     */
    public function unequipItem(Character $character, int $characterItemId): bool
    {
        $characterItem = CharacterItem::where('character_id', $character->id)
            ->where('id', $characterItemId)
            ->where('is_equipped', true)
            ->first();

        if (!$characterItem) {
            return false;
        }

        $characterItem->is_equipped = false;
        $characterItem->equipment_slot = null;
        $characterItem->save();

        return true;
    }

    /**
     * Check if item type can be equipped in slot
     */
    private function canEquipInSlot(string $itemType, string $equipmentSlot): bool
    {
        $slotMapping = [
            'weapon' => ['weapon'],
            'armor' => ['helmet', 'armor', 'boots'],
            'accessory' => ['accessory1', 'accessory2'],
        ];

        return in_array($equipmentSlot, $slotMapping[$itemType] ?? []);
    }

    /**
     * Get inventory statistics
     */
    public function getInventoryStats(Character $character): array
    {
        $items = CharacterItem::where('character_id', $character->id)
            ->where('is_equipped', false)
            ->with('item')
            ->get();

        return [
            'potions_count' => $items->where('item.type', 'potion')->count(),
            'equipment_count' => $items->whereIn('item.type', ['weapon', 'armor', 'accessory'])->count(),
            'materials_count' => $items->where('item.type', 'material')->count(),
            'hotkey_items' => $items->whereNotNull('slot_position')->count(),
        ];
    }
}
