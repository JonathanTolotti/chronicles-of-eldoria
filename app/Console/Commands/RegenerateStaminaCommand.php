<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Character;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateStaminaCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'game:regenerate-stamina';

    /**
     * The console command description.
     */
    protected $description = 'Regenera stamina dos personagens a cada minuto';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $characters = Character::where('current_stamina', '<', \DB::raw('max_stamina'))->get();
        
        $regenerated = 0;
        
        foreach ($characters as $character) {
            // Regenera 1 ponto de stamina por minuto
            $character->current_stamina = min($character->current_stamina + 1, $character->max_stamina);
            $character->save();
            $regenerated++;
        }
        
        $this->info("Regenerada stamina para {$regenerated} personagens.");
        
        return Command::SUCCESS;
    }
}