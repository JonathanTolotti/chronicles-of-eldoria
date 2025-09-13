<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level',
        'max_hp',
        'current_hp',
        'attack_power',
        'defense',
        'speed',
        'gold_reward',
        'exp_reward',
        'stamina_cost',
        'image',
        'image_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if monster is alive.
     */
    public function isAlive(): bool
    {
        return $this->current_hp > 0;
    }

    /**
     * Reset monster HP to max.
     */
    public function resetHp(): void
    {
        $this->current_hp = $this->max_hp;
        $this->save();
    }

    /**
     * Take damage and return if still alive.
     */
    public function takeDamage(int $damage): bool
    {
        $this->current_hp = max(0, $this->current_hp - $damage);
        $this->save();
        
        return $this->isAlive();
    }
}
