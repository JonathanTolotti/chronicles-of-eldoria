<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterItem extends Model
{
    protected $fillable = [
        'character_id',
        'item_id',
        'quantity',
        'is_equipped',
        'equipment_slot',
        'slot_position',
    ];

    protected function casts(): array
    {
        return [
            'is_equipped' => 'boolean',
        ];
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Check if item is equipped
     */
    public function isEquipped(): bool
    {
        return $this->is_equipped;
    }

    /**
     * Check if item is in hotkey slot
     */
    public function isInHotkey(): bool
    {
        return $this->slot_position !== null;
    }

    /**
     * Check if item is a potion
     */
    public function isPotion(): bool
    {
        return $this->item->isPotion();
    }

    /**
     * Check if item is equipment
     */
    public function isEquipment(): bool
    {
        return $this->item->isEquipment();
    }

    /**
     * Use one item (decrease quantity)
     */
    public function useItem(): bool
    {
        if ($this->quantity <= 0) {
            return false;
        }

        $this->quantity--;
        
        if ($this->quantity <= 0) {
            $this->delete();
            return true;
        }

        $this->save();
        return true;
    }
}
