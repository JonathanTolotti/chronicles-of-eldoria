<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Services\CharacterService;
use Illuminate\Console\Command;

class AddExperienceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'character:add-exp {character_id} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add experience to a character for testing level up system';

    /**
     * Execute the console command.
     */
    public function handle(CharacterService $characterService): int
    {
        $characterId = $this->argument('character_id');
        $amount = (int) $this->argument('amount');

        $character = Character::find($characterId);
        
        if (!$character) {
            $this->error("Character with ID {$characterId} not found.");
            return 1;
        }

        $this->info("Adding {$amount} experience to character: {$character->name}");
        $this->info("Current level: {$character->level}");
        $this->info("Current experience: {$character->experience}/{$character->experience_to_next_level}");
        $this->info("Current stat points: {$character->stat_points}");

        $characterService->addExperience($character, $amount);

        // Refresh character data
        $character->refresh();

        $this->info("After adding experience:");
        $this->info("New level: {$character->level}");
        $this->info("New experience: {$character->experience}/{$character->experience_to_next_level}");
        $this->info("New stat points: {$character->stat_points}");

        return 0;
    }
}