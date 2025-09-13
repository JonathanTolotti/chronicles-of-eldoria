<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CharacterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CharacterStatsController extends Controller
{
    public function __construct(
        private CharacterService $characterService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para gerenciar atributos.');
        }

        return Inertia::render('Character/Stats', [
            'character' => $character,
        ]);
    }

    public function distributePoints(Request $request): RedirectResponse
    {
        $request->validate([
            'distributions' => 'required|array|min:1',
            'distributions.*.stat' => 'required|in:strength,dexterity,constitution,intelligence,luck',
            'distributions.*.points' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem.');
        }

        // Calculate total points needed
        $totalPointsNeeded = array_sum(array_column($request->distributions, 'points'));
        
        if ($character->stat_points < $totalPointsNeeded) {
            return back()->with('error', 'Você não tem pontos suficientes para distribuir.');
        }

        // Apply all distributions
        $distributedStats = [];
        foreach ($request->distributions as $distribution) {
            $this->characterService->distributeStatPoints(
                $character, 
                $distribution['stat'], 
                $distribution['points']
            );
            $distributedStats[] = "+{$distribution['points']} {$this->getStatName($distribution['stat'])}";
        }

        $message = "Pontos distribuídos com sucesso! " . implode(', ', $distributedStats);
        return back()->with('success', $message);
    }

    private function getStatName(string $stat): string
    {
        return match($stat) {
            'strength' => 'Força',
            'dexterity' => 'Destreza',
            'constitution' => 'Constituição',
            'intelligence' => 'Inteligência',
            'luck' => 'Sorte',
            default => $stat
        };
    }
}
