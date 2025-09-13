<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\Monster;

class BattleService
{
    /**
     * Calculate damage dealt by attacker to defender.
     */
    public function calculateDamage(int $attackPower, int $defense): int
    {
        $baseDamage = max(1, $attackPower - $defense);
        
        // Add some randomness (±20%)
        $randomFactor = 0.8 + (mt_rand(0, 40) / 100); // 0.8 to 1.2
        $damage = (int) round($baseDamage * $randomFactor);
        
        return max(1, $damage);
    }

    /**
     * Check if attack hits (based on speed difference).
     */
    public function doesAttackHit(int $attackerSpeed, int $defenderSpeed): bool
    {
        $hitChance = 0.8; // Base 80% hit chance
        
        // Speed difference affects hit chance
        $speedDiff = $attackerSpeed - $defenderSpeed;
        $hitChance += $speedDiff * 0.05; // 5% per speed point difference
        
        // Clamp between 10% and 95%
        $hitChance = max(0.1, min(0.95, $hitChance));
        
        return mt_rand(1, 100) <= ($hitChance * 100);
    }

    /**
     * Check if attack is critical (based on luck).
     */
    public function isCriticalHit(int $luck): bool
    {
        $critChance = $luck * 0.01; // 1% per luck point
        $critChance = min(0.5, $critChance); // Max 50% crit chance
        
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
            $damage = (int) round($damage * 1.5);
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
        if (mt_rand(1, 100) <= 5) { // 5% crit chance for monsters
            $damage = (int) round($damage * 1.5);
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
