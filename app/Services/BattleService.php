<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\Monster;

class BattleService
{
    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Calculate damage dealt by attacker to defender.
     */
    public function calculateDamage(int $attackPower, int $defense): int
    {
        // Get configuration values
        $attackMultiplier = config('battle.damage.attack_multiplier');
        $defenseReduction = config('battle.damage.defense_reduction');
        $minRandom = config('battle.damage.randomization_min');
        $maxRandom = config('battle.damage.randomization_max');
        $minDamage = config('battle.damage.minimum_damage');
        
        // Calculate base damage
        $baseDamage = ($attackPower * $attackMultiplier) - ($defense * $defenseReduction);
        
        // Ensure minimum damage
        $baseDamage = max($minDamage, $baseDamage);
        
        // Add randomness
        $randomFactor = $minRandom + (mt_rand(0, (int)(($maxRandom - $minRandom) * 100)) / 100);
        $damage = (int) round($baseDamage * $randomFactor);
        
        // Apply event multiplier
        $damage = $this->eventService->applyMultiplier($damage, 'damage');
        
        return max($minDamage, (int) round($damage));
    }

    /**
     * Check if attack hits (based on speed difference).
     */
    public function doesAttackHit(int $attackerSpeed, int $defenderSpeed): bool
    {
        $baseChance = config('battle.hit_chance.base_chance');
        $speedMultiplier = config('battle.hit_chance.speed_multiplier');
        $minChance = config('battle.hit_chance.min_chance');
        $maxChance = config('battle.hit_chance.max_chance');
        
        $hitChance = $baseChance;
        
        // Speed difference affects hit chance
        $speedDiff = $attackerSpeed - $defenderSpeed;
        $hitChance += $speedDiff * $speedMultiplier;
        
        // Clamp between min and max
        $hitChance = max($minChance, min($maxChance, $hitChance));
        
        return mt_rand(1, 100) <= ($hitChance * 100);
    }

    /**
     * Check if attack is critical (based on luck).
     */
    public function isCriticalHit(int $luck): bool
    {
        $luckMultiplier = config('battle.critical_hit.luck_multiplier');
        $maxChance = config('battle.critical_hit.max_chance');
        
        $critChance = $luck * $luckMultiplier;
        $critChance = min($maxChance, $critChance);
        
        return mt_rand(1, 100) <= ($critChance * 100);
    }

    /**
     * Execute a single attack turn.
     */
    public function executeAttack(Character $character, Monster $monster): array
    {
        $result = [
            'attacker' => 'character',
            'hit' => false,
            'critical' => false,
            'damage' => 0,
            'defender_hp_after' => $monster->current_hp,
            'message' => ''
        ];

        // Check if character hits
        if (!$this->doesAttackHit($character->dexterity, $monster->speed)) {
            $result['message'] = "{$character->name} errou o ataque!";
            return $result;
        }

        $result['hit'] = true;

        // Calculate damage
        $damage = $this->calculateDamage($character->power, $monster->defense);
        
        // Check for critical hit
        if ($this->isCriticalHit($character->luck)) {
            $critMultiplier = config('battle.critical_hit.multiplier');
            $damage = (int) round($damage * $critMultiplier);
            $result['critical'] = true;
        }

        $result['damage'] = $damage;

        // Apply damage (don't save to database)
        $monster->current_hp = max(0, $monster->current_hp - $damage);
        $result['defender_hp_after'] = $monster->current_hp;

        // Create message
        if ($result['critical']) {
            $result['message'] = "{$character->name} acertou um golpe crítico! Causou {$damage} de dano!";
        } else {
            $result['message'] = "{$character->name} atacou e causou {$damage} de dano!";
        }

        return $result;
    }

    /**
     * Execute monster attack on character.
     */
    public function executeMonsterAttack(Monster $monster, Character $character): array
    {
        $result = [
            'attacker' => 'monster',
            'hit' => false,
            'critical' => false,
            'damage' => 0,
            'defender_hp_after' => $character->current_hp,
            'message' => ''
        ];

        // Check if monster hits
        if (!$this->doesAttackHit($monster->speed, $character->dexterity)) {
            $result['message'] = "{$monster->name} errou o ataque!";
            return $result;
        }

        $result['hit'] = true;

        // Calculate damage
        $damage = $this->calculateDamage($monster->attack_power, 0); // Characters don't have defense yet
        
        // Check for critical hit (monsters have low crit chance)
        $monsterCritChance = config('battle.critical_hit.monster_chance');
        if (mt_rand(1, 100) <= ($monsterCritChance * 100)) {
            $critMultiplier = config('battle.critical_hit.multiplier');
            $damage = (int) round($damage * $critMultiplier);
            $result['critical'] = true;
        }

        $result['damage'] = $damage;

        // Apply damage to character (don't save to database)
        $character->current_hp = max(0, $character->current_hp - $damage);
        $result['defender_hp_after'] = $character->current_hp;

        // Create message
        if ($result['critical']) {
            $result['message'] = "{$monster->name} acertou um golpe crítico! Causou {$damage} de dano!";
        } else {
            $result['message'] = "{$monster->name} atacou e causou {$damage} de dano!";
        }

        return $result;
    }

    /**
     * Check if battle is over.
     */
    public function isBattleOver(Character $character, Monster $monster): bool
    {
        return $character->current_hp <= 0 || $monster->current_hp <= 0;
    }

    /**
     * Get battle result.
     */
    public function getBattleResult(Character $character, Monster $monster): array
    {
        if ($character->current_hp <= 0) {
            return [
                'winner' => 'monster',
                'message' => "Você foi derrotado por {$monster->name}!"
            ];
        }

        if ($monster->current_hp <= 0) {
            return [
                'winner' => 'character',
                'message' => "Você derrotou {$monster->name}!",
                'gold_reward' => $monster->gold_reward,
                'exp_reward' => $monster->exp_reward
            ];
        }

        return [
            'winner' => null,
            'message' => 'Batalha em andamento...'
        ];
    }
}
