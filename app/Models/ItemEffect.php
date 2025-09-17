<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemEffect extends Model
{
    protected $fillable = [
        'item_id',
        'effect_type',
        'effect_value',
        'effect_duration',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Check if effect is a healing effect
     */
    public function isHealing(): bool
    {
        return $this->effect_type === 'heal_hp';
    }

    /**
     * Check if effect restores stamina
     */
    public function restoresStamina(): bool
    {
        return $this->effect_type === 'restore_stamina';
    }

    /**
     * Check if effect restores mana
     */
    public function restoresMana(): bool
    {
        return $this->effect_type === 'restore_mp';
    }

    /**
     * Check if effect is a buff
     */
    public function isBuff(): bool
    {
        return str_starts_with($this->effect_type, 'buff_');
    }
}
