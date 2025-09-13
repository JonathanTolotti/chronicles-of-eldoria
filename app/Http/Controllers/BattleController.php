<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Monster;
use App\Services\BattleService;
use App\Services\CharacterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BattleController extends Controller
{
    protected BattleService $battleService;
    protected CharacterService $characterService;

    public function __construct()
    {
        $this->battleService = app(BattleService::class);
        $this->characterService = app(CharacterService::class);
    }
    /**
     * Show the battle selection page.
     */
    public function index()
    {
        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para batalhar.');
        }

        $monsters = Monster::where('is_active', true)
            ->orderBy('level')
            ->get();

        return Inertia::render('Battle/Index', [
            'character' => $character,
            'monsters' => $monsters,
        ]);
    }


    /**
     * Execute a character attack.
     */
    public function attack(Request $request)
    {
        \Log::info('Battle attack called', $request->all());
        
        $request->validate([
            'monster_id' => 'required|exists:monsters,id',
        ]);

        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            \Log::error('No active character found for user', ['user_id' => $user->id]);
            return response()->json([
                'error' => 'Você precisa selecionar um personagem para batalhar.'
            ], 400);
        }

        $monster = Monster::findOrFail($request->monster_id);
        \Log::info('Battle starting', [
            'character_id' => $character->id,
            'monster_id' => $monster->id
        ]);

        // Execute character attack
        $attackResult = $this->battleService->executeAttack($character, $monster);

        // Check if battle is over
        if ($this->battleService->isBattleOver($character, $monster)) {
            $battleResult = $this->battleService->getBattleResult($character, $monster);
            
            // If character won, give rewards
            if ($battleResult['winner'] === 'character') {
                $character->gold += $monster->gold_reward;
                $this->characterService->addExperience($character, $monster->exp_reward);
            }

            return response()->json([
                'battle_over' => true,
                'result' => $battleResult,
                'character' => $character->fresh(),
                'monster' => $monster->fresh(),
                'attack_result' => $attackResult
            ]);
        }

        // Monster attacks back
        $monsterAttackResult = $this->battleService->executeMonsterAttack($monster, $character);

        // Check if battle is over after monster attack
        if ($this->battleService->isBattleOver($character, $monster)) {
            $battleResult = $this->battleService->getBattleResult($character, $monster);
            
            // If character won, give rewards
            if ($battleResult['winner'] === 'character') {
                $character->gold += $monster->gold_reward;
                $this->characterService->addExperience($character, $monster->exp_reward);
            }

            return response()->json([
                'battle_over' => true,
                'result' => $battleResult,
                'character' => $character->fresh(),
                'monster' => $monster->fresh(),
                'attack_result' => $attackResult,
                'monster_attack_result' => $monsterAttackResult
            ]);
        }

        return response()->json([
            'battle_over' => false,
            'character' => $character->fresh(),
            'monster' => $monster->fresh(),
            'attack_result' => $attackResult,
            'monster_attack_result' => $monsterAttackResult
        ]);
    }

    /**
     * Run away from battle.
     */
    public function flee()
    {
        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para batalhar.');
        }

        return response()->json([
            'battle_over' => true,
            'result' => [
                'winner' => 'fled',
                'message' => 'Você fugiu da batalha!'
            ],
            'character' => $character
        ]);
    }
}
