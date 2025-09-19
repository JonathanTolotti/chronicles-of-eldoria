<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentTier extends Model
{
    protected $fillable = [
        'tier',
        'item_id',
        'quantity_required',
        'upgrade_cost_gold',
        'success_rate',
        'failure_penalty',
    ];

    protected $casts = [
        'tier' => 'integer',
        'quantity_required' => 'integer',
        'upgrade_cost_gold' => 'integer',
        'success_rate' => 'decimal:2',
    ];

    /**
     * Relacionamento com o item necessário
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Verificar se o personagem tem os requisitos para upgrade
     */
    public function canUpgrade(Character $character): bool
    {
        // Verificar se tem o item necessário
        $characterItem = $character->items()->where('item_id', $this->item_id)->first();
        
        if (!$characterItem || $characterItem->quantity < $this->quantity_required) {
            return false;
        }

        // Verificar se tem gold suficiente
        if ($character->gold < $this->upgrade_cost_gold) {
            return false;
        }

        return true;
    }

    /**
     * Obter requisitos formatados para exibição
     */
    public function getRequirementsFormatted(): array
    {
        return [
            'item' => $this->item->name,
            'quantity' => $this->quantity_required,
            'gold' => $this->upgrade_cost_gold,
            'success_rate' => $this->success_rate . '%',
        ];
    }
}
