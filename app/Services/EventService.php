<?php

declare(strict_types=1);

namespace App\Services;

class EventService
{
    /**
     * Get current event multipliers
     */
    public function getEventMultipliers(): array
    {
        return [
            'exp' => config('battle.events.exp_multiplier'),
            'gold' => config('battle.events.gold_multiplier'),
            'damage' => config('battle.events.damage_multiplier'),
            'drop_rate' => config('battle.events.drop_rate_multiplier'),
        ];
    }

    /**
     * Check if any events are active
     */
    public function hasActiveEvents(): bool
    {
        $multipliers = $this->getEventMultipliers();
        
        foreach ($multipliers as $multiplier) {
            if ($multiplier !== 1.0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get active events description
     */
    public function getActiveEvents(): array
    {
        $events = [];
        $multipliers = $this->getEventMultipliers();

        if ($multipliers['exp'] !== 1.0) {
            $events[] = [
                'type' => 'exp',
                'name' => 'Evento de EXP',
                'multiplier' => $multipliers['exp'],
                'description' => "EXP {$multipliers['exp']}x"
            ];
        }

        if ($multipliers['gold'] !== 1.0) {
            $events[] = [
                'type' => 'gold',
                'name' => 'Evento de Ouro',
                'multiplier' => $multipliers['gold'],
                'description' => "Ouro {$multipliers['gold']}x"
            ];
        }

        if ($multipliers['damage'] !== 1.0) {
            $events[] = [
                'type' => 'damage',
                'name' => 'Evento de Dano',
                'multiplier' => $multipliers['damage'],
                'description' => "Dano {$multipliers['damage']}x"
            ];
        }

        if ($multipliers['drop_rate'] !== 1.0) {
            $events[] = [
                'type' => 'drop_rate',
                'name' => 'Evento de Drops',
                'multiplier' => $multipliers['drop_rate'],
                'description' => "Drops {$multipliers['drop_rate']}x"
            ];
        }

        return $events;
    }

    /**
     * Apply event multiplier to a value
     */
    public function applyMultiplier(float $value, string $type): float
    {
        $multipliers = $this->getEventMultipliers();
        $multiplier = $multipliers[$type] ?? 1.0;
        
        return $value * $multiplier;
    }

    /**
     * Set event multiplier (for admin/event management)
     */
    public function setEventMultiplier(string $type, float $multiplier): void
    {
        // This would typically update a database or cache
        // For now, we'll use environment variables
        $envKey = 'EVENT_' . strtoupper($type) . '_MULTIPLIER';
        
        // In a real implementation, you might want to:
        // 1. Store in database
        // 2. Use Redis cache
        // 3. Update config dynamically
        
        // For now, this is a placeholder for the concept
        config(['battle.events.' . $type . '_multiplier' => $multiplier]);
    }
}
