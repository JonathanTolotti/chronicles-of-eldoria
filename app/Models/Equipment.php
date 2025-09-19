<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'image',
        'strength_bonus',
        'dexterity_bonus',
        'constitution_bonus',
        'intelligence_bonus',
        'luck_bonus',
        'hp_bonus',
        'mp_bonus',
    ];

    protected $casts = [
        'strength_bonus' => 'integer',
        'dexterity_bonus' => 'integer',
        'constitution_bonus' => 'integer',
        'intelligence_bonus' => 'integer',
        'luck_bonus' => 'integer',
        'hp_bonus' => 'integer',
        'mp_bonus' => 'integer',
    ];

    /**
     * Relacionamento com equipamentos dos personagens
     */
    public function characterEquipment(): HasMany
    {
        return $this->hasMany(CharacterEquipment::class);
    }

    /**
     * Relacionamento com tiers do equipamento
     */
    public function tiers(): HasMany
    {
        return $this->hasMany(EquipmentTier::class);
    }

    /**
     * Calcular stats baseados no tier
     */
    public function getStatsForTier(int $tier): array
    {
        $multiplier = 1 + ($tier * 0.1); // 10% por tier
        
        return [
            'strength' => (int) round($this->strength_bonus * $multiplier),
            'dexterity' => (int) round($this->dexterity_bonus * $multiplier),
            'constitution' => (int) round($this->constitution_bonus * $multiplier),
            'intelligence' => (int) round($this->intelligence_bonus * $multiplier),
            'luck' => (int) round($this->luck_bonus * $multiplier),
            'hp' => (int) round($this->hp_bonus * $multiplier),
            'mp' => (int) round($this->mp_bonus * $multiplier),
        ];
    }
}
