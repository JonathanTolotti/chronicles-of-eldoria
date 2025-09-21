<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ShopPurchase extends Model
{
    protected $fillable = [
        'uuid',
        'character_id',
        'shop_item_id',
        'quantity',
        'gold_paid',
        'coin_paid',
        'discount_applied',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'gold_paid' => 'integer',
        'coin_paid' => 'integer',
        'discount_applied' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($purchase) {
            if (empty($purchase->uuid)) {
                $purchase->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relacionamento com Character
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Relacionamento com ShopItem
     */
    public function shopItem(): BelongsTo
    {
        return $this->belongsTo(ShopItem::class);
    }

    /**
     * Obter o item comprado
     */
    public function getPurchasedItem()
    {
        return $this->shopItem->getRelatedItem();
    }

    /**
     * Obter nome do item comprado
     */
    public function getItemName(): string
    {
        return $this->shopItem->getName();
    }

    /**
     * Calcular total pago
     */
    public function getTotalPaid(): int
    {
        return $this->gold_paid + $this->coin_paid;
    }

    /**
     * Scope para compras de um personagem
     */
    public function scopeForCharacter($query, int $characterId)
    {
        return $query->where('character_id', $characterId);
    }

    /**
     * Scope para compras completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para compras recentes
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
