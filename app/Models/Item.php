<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'type',
        'rarity',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            if (empty($item->uuid)) {
                $item->uuid = Str::uuid()->toString();
            }
        });
    }

    public function effects(): HasMany
    {
        return $this->hasMany(ItemEffect::class);
    }

    public function characterItems(): HasMany
    {
        return $this->hasMany(CharacterItem::class);
    }

    /**
     * Check if item is a potion
     */
    public function isPotion(): bool
    {
        return $this->type === 'potion';
    }

    /**
     * Check if item is equipment
     */
    public function isEquipment(): bool
    {
        return in_array($this->type, ['weapon', 'armor', 'accessory']);
    }

    /**
     * Check if item is a material
     */
    public function isMaterial(): bool
    {
        return $this->type === 'material';
    }
}
