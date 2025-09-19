<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\User;
use App\Repositories\CharacterRepository;
use Illuminate\Support\Str;

class CharacterService
{
    public function __construct(
        private CharacterRepository $characterRepository,
        private EquipmentService $equipmentService
    ) {}

    public function createCharacter(User $user, array $data): Character
    {
        $characterData = [
            'uuid' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'name' => $data['name'],
            'class' => $data['class'] ?? 'warrior',
            'level' => 1,
            'experience' => 0,
            'experience_to_next_level' => 100,
            'strength' => $this->getBaseStat('strength', $data['class']),
            'dexterity' => $this->getBaseStat('dexterity', $data['class']),
            'constitution' => $this->getBaseStat('constitution', $data['class']),
            'intelligence' => $this->getBaseStat('intelligence', $data['class']),
            'luck' => $this->getBaseStat('luck', $data['class']),
            'gold' => 100,
            'stat_points' => 0,
        ];

        $character = $this->characterRepository->create($characterData);
        
        // Calculate initial stats
        $this->recalculateStats($character, false); // false = não restaurar vida e stamina
        
        return $character;
    }

    public function addExperience(Character $character, int $amount): Character
    {
        $character->experience += $amount;
        
        while ($this->canLevelUp($character)) {
            $this->levelUp($character);
        }
        
        $this->characterRepository->update($character, [
            'experience' => $character->experience,
            'level' => $character->level,
            'experience_to_next_level' => $character->experience_to_next_level,
            'stat_points' => $character->stat_points,
            'max_hp' => $character->max_hp,
            'current_hp' => $character->current_hp,
            'max_stamina' => $character->max_stamina,
            'current_stamina' => $character->current_stamina,
            'power' => $character->power,
        ]);
        
        return $character;
    }

    public function distributeStatPoints(Character $character, string $stat, int $points): void
    {
        if ($character->stat_points < $points) {
            throw new \InvalidArgumentException('Not enough stat points available');
        }

        // Add points to the stat
        $character->{$stat} += $points;
        $character->stat_points -= $points;

        // Recalculate stats based on new attributes
        $this->recalculateStats($character, false); // false = não restaurar vida e stamina

        // Update in database
        $this->characterRepository->update($character, [
            $stat => $character->{$stat},
            'stat_points' => $character->stat_points,
            'max_hp' => $character->max_hp,
            'current_hp' => $character->current_hp,
            'max_stamina' => $character->max_stamina,
            'current_stamina' => $character->current_stamina,
            'power' => $character->power,
        ]);
    }

    public function levelUp(Character $character): void
    {
        $character->level++;
        $character->experience -= $character->experience_to_next_level;
        $character->experience_to_next_level = (int) ($character->experience_to_next_level * 1.2);
        $character->stat_points += 5; // 5 points per level
        
        $this->recalculateStats($character, true); // true = restaurar vida e stamina
    }

    public function canLevelUp(Character $character): bool
    {
        return $character->experience >= $character->experience_to_next_level;
    }

    public function calculatePower(Character $character): int
    {
        $basePower = ($character->strength * 2) + 
                    ($character->dexterity * 1.5) + 
                    ($character->constitution * 1.8) + 
                    ($character->intelligence * 2.2) + 
                    ($character->level * 10);
        
        // Add equipment power
        $equipmentBonuses = $this->equipmentService->getEquipmentBonuses($character);
        $equipmentPower = array_sum($equipmentBonuses);
        
        return (int) ($basePower + $equipmentPower);
    }

    public function calculateDefense(Character $character): int
    {
        // Defesa base baseada em constituição
        $baseDefense = $character->constitution * 0.5;
        
        // Adicionar defesa dos equipamentos (armor, shield, helmet)
        $equipmentBonuses = $this->equipmentService->getEquipmentBonuses($character);
        
        // Defesa dos equipamentos é baseada principalmente em armor e shield
        $equipmentDefense = $equipmentBonuses['constitution'] * 0.3; // Armor/shield dão defesa
        
        return (int) ($baseDefense + $equipmentDefense);
    }

    public function calculateMaxHp(Character $character): int
    {
        return 100 + ($character->constitution * 10);
    }

    public function calculateMaxStamina(Character $character): int
    {
        return 50 + ($character->intelligence * 5);
    }

    public function recalculateStats(Character $character, bool $restoreHealthAndStamina = false): void
    {
        $character->max_hp = $this->calculateMaxHp($character);
        $character->max_stamina = $this->calculateMaxStamina($character);
        $character->power = $this->calculatePower($character);
        
        if ($restoreHealthAndStamina) {
            // Restaurar vida e stamina quando sobe de nível
            $character->current_hp = $character->max_hp;
            $character->current_stamina = $character->max_stamina;
        } else {
            // Manter valores atuais quando não é level up
            $character->current_hp = $character->current_hp;
            $character->current_stamina = $character->current_stamina;
        }
    }

    public function startTraining(Character $character, string $stat, int $duration): void
    {
        $trainingPoints = $this->calculateTrainingPoints($duration);
        
        $this->characterRepository->update($character, [
            'training_stat' => $stat,
            'training_started_at' => now(),
            'training_ends_at' => now()->addMinutes($duration),
            'training_points' => $trainingPoints,
        ]);
    }

    public function completeTraining(Character $character): void
    {
        if (!$this->isTrainingComplete($character)) {
            return;
        }

        // Get the base stat value (without equipment bonuses)
        $baseStats = $this->getBaseCharacterStats($character);
        $currentValue = $baseStats[$character->training_stat];
        $newValue = $currentValue + $character->training_points;
        $trainingStat = $character->training_stat;
        
        // Update the character with new stat value
        $this->characterRepository->update($character, [
            $trainingStat => $newValue,
            'training_stat' => null,
            'training_started_at' => null,
            'training_ends_at' => null,
            'training_points' => 0,
        ]);
        
        // Update the character object for recalculation
        $character->{$trainingStat} = $newValue;
        $this->recalculateStats($character, false); // false = não restaurar vida e stamina
        
        // Save the recalculated stats
        $this->characterRepository->update($character, [
            'max_hp' => $character->max_hp,
            'current_hp' => $character->current_hp,
            'max_stamina' => $character->max_stamina,
            'current_stamina' => $character->current_stamina,
            'power' => $character->power,
        ]);
    }

    public function isTraining(Character $character): bool
    {
        return $character->training_stat !== null && 
               $character->training_ends_at !== null && 
               $character->training_ends_at->isFuture();
    }
    
    public function isTrainingComplete(Character $character): bool
    {
        return $character->training_stat !== null && 
               $character->training_ends_at !== null && 
               $character->training_ends_at->isPast();
    }

    private function getBaseStat(string $stat, string $class): int
    {
        $baseStats = [
            'warrior' => [
                'strength' => 15,
                'dexterity' => 8,
                'constitution' => 12,
                'intelligence' => 5,
                'luck' => 8,
            ],
            'mage' => [
                'strength' => 5,
                'dexterity' => 8,
                'constitution' => 8,
                'intelligence' => 15,
                'luck' => 10,
            ],
            'archer' => [
                'strength' => 8,
                'dexterity' => 15,
                'constitution' => 10,
                'intelligence' => 8,
                'luck' => 12,
            ],
            'rogue' => [
                'strength' => 8,
                'dexterity' => 15,
                'constitution' => 8,
                'intelligence' => 10,
                'luck' => 15,
            ],
        ];

        return $baseStats[$class][$stat] ?? 10;
    }

    private function calculateTrainingPoints(int $duration): int
    {
        // 2 points per hour of training (1 point per 30 minutes)
        return max(1, (int) ($duration / 30));
    }

    /**
     * Obter stats base do personagem (sem equipamentos)
     * Usa os stats atuais do personagem, removendo apenas os bônus de equipamentos
     */
    private function getBaseCharacterStats(Character $character): array
    {
        // Obter bônus atuais de equipamentos
        $currentEquipmentBonuses = $this->equipmentService->getEquipmentBonuses($character);

        // Calcular stats base removendo os bônus de equipamentos
        // Garantir que não fique negativo
        return [
            'strength' => max(0, $character->strength - $currentEquipmentBonuses['strength']),
            'dexterity' => max(0, $character->dexterity - $currentEquipmentBonuses['dexterity']),
            'constitution' => max(0, $character->constitution - $currentEquipmentBonuses['constitution']),
            'intelligence' => max(0, $character->intelligence - $currentEquipmentBonuses['intelligence']),
            'luck' => max(0, $character->luck - $currentEquipmentBonuses['luck']),
        ];
    }
}
