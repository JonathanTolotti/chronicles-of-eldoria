<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Character extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'class',
        'level',
        'experience',
        'experience_to_next_level',
        'strength',
        'dexterity',
        'constitution',
        'intelligence',
        'luck',
        'max_hp',
        'current_hp',
        'max_stamina',
        'current_stamina',
        'power',
        'gold',
        'stat_points',
        'training_stat',
        'training_ends_at',
        'training_points',
    ];

    protected function casts(): array
    {
        return [
            'training_ends_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($character) {
            if (empty($character->uuid)) {
                $character->uuid = Str::uuid()->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if character is currently training.
     */
    public function isTraining(): bool
    {
        return $this->training_stat !== null && 
               $this->training_ends_at !== null && 
               $this->training_ends_at->isFuture();
    }
}
