<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ShopItem extends Model
{
    protected $fillable = [
        'uuid',
        'item_id',
        'equipment_id',
        'gold_price',
        'coin_price',
        'is_available',
        'stock_quantity',
        'daily_limit',
        'min_level',
        'max_level',
        'is_featured',
        'is_limited_time',
        'is_one_time_purchase',
        'available_until',
        'discount_percentage',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_limited_time' => 'boolean',
        'is_one_time_purchase' => 'boolean',
        'available_until' => 'datetime',
        'gold_price' => 'integer',
        'coin_price' => 'integer',
        'stock_quantity' => 'integer',
        'daily_limit' => 'integer',
        'min_level' => 'integer',
        'max_level' => 'integer',
        'discount_percentage' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($shopItem) {
            if (empty($shopItem->uuid)) {
                $shopItem->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relacionamento com Item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relacionamento com Equipment
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Relacionamento com compras
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(ShopPurchase::class);
    }

    /**
     * Obter o item ou equipamento relacionado
     */
    public function getRelatedItem()
    {
        if ($this->item_id) {
            return $this->item;
        }
        
        if ($this->equipment_id) {
            return $this->equipment;
        }
        
        return null;
    }

    /**
     * Obter o tipo do item (item ou equipment)
     */
    public function getType(): string
    {
        if ($this->item_id) {
            return 'item';
        }
        
        if ($this->equipment_id) {
            return 'equipment';
        }
        
        return 'unknown';
    }

    /**
     * Obter nome do item
     */
    public function getName(): string
    {
        $relatedItem = $this->getRelatedItem();
        return $relatedItem ? $relatedItem->name : 'Item Desconhecido';
    }

    /**
     * Obter descrição do item
     */
    public function getDescription(): ?string
    {
        $relatedItem = $this->getRelatedItem();
        return $relatedItem ? $relatedItem->description : null;
    }

    /**
     * Obter imagem do item
     */
    public function getImagePath(): ?string
    {
        $relatedItem = $this->getRelatedItem();
        
        if ($relatedItem instanceof Item) {
            return $relatedItem->image_path;
        }
        
        if ($relatedItem instanceof Equipment) {
            return $relatedItem->image;
        }
        
        return null;
    }

    /**
     * Obter categoria do item
     */
    public function getCategory(): string
    {
        $relatedItem = $this->getRelatedItem();
        
        if ($relatedItem instanceof Item) {
            return $relatedItem->type;
        }
        
        if ($relatedItem instanceof Equipment) {
            return $relatedItem->type;
        }
        
        return 'unknown';
    }

    /**
     * Obter raridade do item
     */
    public function getRarity(): string
    {
        $relatedItem = $this->getRelatedItem();
        
        if ($relatedItem instanceof Item) {
            return $relatedItem->rarity ?? 'common';
        }
        
        // Equipment não tem raridade, usar comum
        return 'common';
    }

    /**
     * Calcular preço com desconto
     */
    public function getDiscountedGoldPrice(): int
    {
        if ($this->discount_percentage > 0) {
            return (int) round($this->gold_price * (1 - $this->discount_percentage / 100));
        }
        
        return $this->gold_price;
    }

    /**
     * Calcular preço com desconto para coin
     */
    public function getDiscountedCoinPrice(): int
    {
        if ($this->discount_percentage > 0) {
            return (int) round($this->coin_price * (1 - $this->discount_percentage / 100));
        }
        
        return $this->coin_price;
    }

    /**
     * Verificar se o item está disponível
     */
    public function isAvailable(): bool
    {
        if (!$this->is_available) {
            return false;
        }

        // Verificar se tem tempo limitado
        if ($this->is_limited_time && $this->available_until && $this->available_until->isPast()) {
            return false;
        }

        // Verificar estoque
        if ($this->stock_quantity !== null && $this->stock_quantity <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Verificar se o personagem pode comprar (nível)
     */
    public function canCharacterBuy(Character $character): bool
    {
        if ($character->level < $this->min_level) {
            return false;
        }

        if ($this->max_level && $character->level > $this->max_level) {
            return false;
        }

        return true;
    }

    /**
     * Verificar se o usuário tem moedas suficientes
     */
    public function canUserAfford(User $user): bool
    {
        $goldNeeded = $this->getDiscountedGoldPrice();
        $coinNeeded = $this->getDiscountedCoinPrice();

        // Gold vem do personagem ativo, coin vem do usuário
        $activeCharacter = $user->activeCharacter;
        if (!$activeCharacter) {
            return false;
        }

        return $activeCharacter->gold >= $goldNeeded && $user->coin >= $coinNeeded;
    }

    /**
     * Scope para itens disponíveis
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where(function ($q) {
                        $q->whereNull('available_until')
                          ->orWhere('available_until', '>', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('stock_quantity')
                          ->orWhere('stock_quantity', '>', 0);
                    });
    }

    /**
     * Scope para itens em destaque
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para filtrar por nível do personagem
     */
    public function scopeForLevel($query, int $level)
    {
        return $query->where('min_level', '<=', $level)
                    ->where(function ($q) use ($level) {
                        $q->whereNull('max_level')
                          ->orWhere('max_level', '>=', $level);
                    });
    }

    /**
     * Verifica se o personagem já comprou este item (para compras únicas)
     */
    public function hasCharacterPurchased(Character $character): bool
    {
        if (!$this->is_one_time_purchase) {
            return false; // Se não é compra única, sempre pode comprar
        }

        return $this->purchases()
            ->where('character_id', $character->id)
            ->where('status', 'completed')
            ->exists();
    }
}
