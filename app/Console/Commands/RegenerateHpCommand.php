<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Character;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateHpCommand extends Command
{
    protected $signature = 'game:regenerate-hp';

    protected $description = 'Regenera HP dos personagens a cada minuto';

    public function handle(): int
    {
        $characters = Character::where('current_hp', '<', \DB::raw('max_hp'))->get();
        
        $regenerated = 0;
        
        foreach ($characters as $character) {
            $character->current_hp = min($character->current_hp + 10, $character->max_hp);
            $character->save();
            $regenerated++;
        }

        $this->info("Regenerada stamina para {$regenerated} personagens.");
        
        return Command::SUCCESS;
    }
}