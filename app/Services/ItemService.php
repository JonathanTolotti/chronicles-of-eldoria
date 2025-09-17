<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\CharacterItem;
use App\Models\ItemEffect;
use App\Repositories\CharacterRepository;

class ItemService
{
    public function __construct(
        private CharacterRepository $characterRepository
    ) {}

    /**
     * Use an item from hotkey slot
     */
    public function useItemFromHotkey(Character $character, int $slotPosition): array
    {
        $characterItem = CharacterItem::where('character_id', $character->id)
            ->where('slot_position', $slotPosition)
            ->where('is_equipped', false)
            ->with(['item.effects'])
            ->first();

        if (!$characterItem) {
            return [
                'success' => false,
                'message' => 'Nenhum item encontrado neste slot'
            ];
        }

        if ($characterItem->quantity <= 0) {
            return [
                'success' => false,
                'message' => 'Item sem quantidade disponível'
            ];
        }

        // Apply item effects
        $results = $this->applyItemEffects($character, $characterItem->item->effects);

        // Use the item (decrease quantity)
        $characterItem->useItem();

        // Refresh character data to get updated values
        $character->refresh();

        return [
            'success' => true,
            'message' => 'Item usado com sucesso',
            'effects' => $results,
            'remaining_quantity' => $characterItem->quantity,
            'character' => [
                'current_hp' => $character->current_hp,
                'max_hp' => $character->max_hp,
                'current_stamina' => $character->current_stamina,
                'max_stamina' => $character->max_stamina,
                'current_mp' => $character->current_mp,
                'max_mp' => $character->max_mp,
            ]
        ];
    }

    /**
     * Apply item effects to character
     */
    private function applyItemEffects(Character $character, $effects): array
    {
        $results = [];
        $updates = [];

        foreach ($effects as $effect) {
            switch ($effect->effect_type) {
                case 'heal_hp':
                    $newHp = min($character->current_hp + $effect->effect_value, $character->max_hp);
                    $healed = $newHp - $character->current_hp;
                    $character->current_hp = $newHp;
                    $updates['current_hp'] = $newHp;
                    $results[] = "Cura: +{$healed} HP";
                    break;

                case 'restore_stamina':
                    $newStamina = min($character->current_stamina + $effect->effect_value, $character->max_stamina);
                    $restored = $newStamina - $character->current_stamina;
                    $character->current_stamina = $newStamina;
                    $updates['current_stamina'] = $newStamina;
                    $results[] = "Energia: +{$restored} Stamina";
                    break;

                case 'restore_mp':
                    // TODO: Implement when mana system is added
                    $results[] = "Mana: +{$effect->effect_value} MP";
                    break;

                case 'buff_strength':
                case 'buff_dexterity':
                case 'buff_constitution':
                case 'buff_intelligence':
                case 'buff_luck':
                    // TODO: Implement buff system
                    $stat = str_replace('buff_', '', $effect->effect_type);
                    $results[] = "Buff: +{$effect->effect_value} {$stat}";
                    break;
            }
        }

        // Update character in database
        if (!empty($updates)) {
            $this->characterRepository->update($character, $updates);
        }

        return $results;
    }

    /**
     * Add item to character inventory
     */
    public function addItemToInventory(Character $character, int $itemId, int $quantity = 1): CharacterItem
    {
        $characterItem = CharacterItem::where('character_id', $character->id)
            ->where('item_id', $itemId)
            ->where('is_equipped', false)
            ->first();

        if ($characterItem) {
            // Item already exists, increase quantity
            $characterItem->quantity += $quantity;
            $characterItem->save();
        } else {
            // Create new item in inventory
            $characterItem = CharacterItem::create([
                'character_id' => $character->id,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'is_equipped' => false,
            ]);
        }

        return $characterItem;
    }

    /**
     * Set item to hotkey slot
     */
    public function setHotkeySlot(Character $character, int $characterItemId, int $slotPosition): bool
    {
        if ($slotPosition < 1 || $slotPosition > 4) {
            return false;
        }

        $characterItem = CharacterItem::where('character_id', $character->id)
            ->where('id', $characterItemId)
            ->where('is_equipped', false)
            ->first();

        if (!$characterItem) {
            return false;
        }

        // Clear any existing item in this slot
        CharacterItem::where('character_id', $character->id)
            ->where('slot_position', $slotPosition)
            ->update(['slot_position' => null]);

        // Set new item to slot
        $characterItem->slot_position = $slotPosition;
        $characterItem->save();

        return true;
    }

    /**
     * Get character's hotkey items
     */
    public function getHotkeyItems(Character $character): array
    {
        $items = CharacterItem::where('character_id', $character->id)
            ->whereNotNull('slot_position')
            ->where('is_equipped', false)
            ->with(['item.effects'])
            ->get()
            ->keyBy('slot_position');

        $hotkeys = [];
        for ($i = 1; $i <= 4; $i++) {
            $hotkeys[$i] = $items->get($i);
        }

        return $hotkeys;
    }
}
