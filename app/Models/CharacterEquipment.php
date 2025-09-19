<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterEquipment extends Model
{
    protected $fillable = [
        'character_id',
        'slot',
        'equipment_id',
        'current_tier',
        'is_equipped',
        'show_in_quick_inventory',
    ];

    protected $casts = [
        'current_tier' => 'integer',
        'is_equipped' => 'boolean',
        'show_in_quick_inventory' => 'boolean',
    ];

    /**
     * Relacionamento com o personagem
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Relacionamento com o equipamento base
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Obter stats atuais do equipamento baseado no tier
     */
    public function getCurrentStats(): array
    {
        return $this->equipment->getStatsForTier($this->current_tier);
    }

    /**
     * Verificar se o equipamento está equipado
     */
    public function isEquipped(): bool
    {
        return $this->is_equipped && $this->equipment_id !== null;
    }
}
