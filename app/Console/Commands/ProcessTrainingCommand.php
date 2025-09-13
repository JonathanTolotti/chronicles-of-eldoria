<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Services\CharacterService;
use Illuminate\Console\Command;

class ProcessTrainingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'training:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process completed training sessions and apply stat bonuses';

    /**
     * Execute the console command.
     */
    public function handle(CharacterService $characterService): int
    {
        $characters = Character::whereNotNull('training_stat')
            ->whereNotNull('training_ends_at')
            ->where('training_ends_at', '<=', now())
            ->get();

        if ($characters->isEmpty()) {
            $this->info('No completed training sessions found.');
            return 0;
        }

        $this->info("Processing {$characters->count()} completed training sessions...");

        foreach ($characters as $character) {
            $this->info("Processing training for character: {$character->name}");
            
            $characterService->completeTraining($character);
            
            $this->info("Training completed for {$character->name}. Added {$character->training_points} points to {$character->training_stat}.");
        }

        $this->info('All training sessions processed successfully!');
        return 0;
    }
}