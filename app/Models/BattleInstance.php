<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleInstance extends Model
{
    protected $fillable = [
        'character_id',
        'monster_id',
        'monster_current_hp',
        'character_current_hp',
        'character_current_stamina',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function monster(): BelongsTo
    {
        return $this->belongsTo(Monster::class);
    }

    /**
     * Check if monster is defeated.
     */
    public function isMonsterDefeated(): bool
    {
        return $this->monster_current_hp <= 0;
    }

    /**
     * End the battle.
     */
    public function endBattle(): void
    {
        $this->is_active = false;
        $this->save();
    }
}