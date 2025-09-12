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
        private CharacterRepository $characterRepository
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
        $this->recalculateStats($character);
        
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
            'max_mp' => $character->max_mp,
            'current_mp' => $character->current_mp,
            'power' => $character->power,
        ]);
        
        return $character;
    }

    public function levelUp(Character $character): void
    {
        $character->level++;
        $character->experience -= $character->experience_to_next_level;
        $character->experience_to_next_level = (int) ($character->experience_to_next_level * 1.2);
        $character->stat_points += 5; // 5 points per level
        
        $this->recalculateStats($character);
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
        
        // TODO: Add equipment power when equipment system is implemented
        $equipmentPower = 0;
        
        return (int) ($basePower + $equipmentPower);
    }

    public function calculateMaxHp(Character $character): int
    {
        return 100 + ($character->constitution * 10);
    }

    public function calculateMaxMp(Character $character): int
    {
        return 50 + ($character->intelligence * 5);
    }

    public function recalculateStats(Character $character): void
    {
        $character->max_hp = $this->calculateMaxHp($character);
        $character->current_hp = $character->max_hp;
        $character->max_mp = $this->calculateMaxMp($character);
        $character->current_mp = $character->max_mp;
        $character->power = $this->calculatePower($character);
    }

    public function startTraining(Character $character, string $stat, int $duration): void
    {
        $trainingPoints = $this->calculateTrainingPoints($duration);
        
        $this->characterRepository->update($character, [
            'training_stat' => $stat,
            'training_ends_at' => now()->addMinutes($duration),
            'training_points' => $trainingPoints,
        ]);
    }

    public function completeTraining(Character $character): void
    {
        if (!$this->isTraining($character)) {
            return;
        }

        $character->{$character->training_stat} += $character->training_points;
        
        $this->characterRepository->update($character, [
            $character->training_stat => $character->{$character->training_stat},
            'training_stat' => null,
            'training_ends_at' => null,
            'training_points' => 0,
        ]);
        
        $this->recalculateStats($character);
    }

    public function isTraining(Character $character): bool
    {
        return $character->training_stat !== null && 
               $character->training_ends_at !== null && 
               $character->training_ends_at->isFuture();
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
        // 1 point per 30 minutes of training
        return (int) ($duration / 30);
    }
}
