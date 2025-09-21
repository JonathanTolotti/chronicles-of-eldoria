<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BattleInstance;
use App\Models\Monster;
use App\Services\BattleService;
use App\Services\CharacterService;
use App\Services\EquipmentService;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BattleController extends Controller
{
    protected BattleService $battleService;
    protected CharacterService $characterService;
    protected EquipmentService $equipmentService;
    protected EventService $eventService;

    public function __construct()
    {
        $this->battleService = app(BattleService::class);
        $this->characterService = app(CharacterService::class);
        $this->equipmentService = app(EquipmentService::class);
        $this->eventService = app(EventService::class);
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
            'csrf_token' => csrf_token(),
        ]);
    }


    /**
     * Start a new battle.
     */
    public function startBattle(Request $request)
    {
        $request->validate([
            'monster_id' => 'required|exists:monsters,id',
        ]);

        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'error' => 'Você precisa selecionar um personagem para batalhar.'
            ], 400);
        }

        $monster = Monster::findOrFail($request->monster_id);

        // Verificar se tem vida suficiente
        if ($character->current_hp <= 0) {
            return response()->json([
                'error' => 'Você está morto! Precisa ser revivido para batalhar.',
                'current_hp' => $character->current_hp
            ], 400);
        }

        // Verificar se tem stamina suficiente
        if ($character->current_stamina < $monster->stamina_cost) {
            return response()->json([
                'error' => "Stamina insuficiente! Você precisa de {$monster->stamina_cost} pontos de stamina para batalhar este monstro.",
                'stamina_required' => $monster->stamina_cost,
                'current_stamina' => $character->current_stamina
            ], 400);
        }

        // Verificar se já existe uma batalha ativa com este monstro
        $existingBattle = BattleInstance::where('character_id', $character->id)
            ->where('monster_id', $monster->id)
            ->where('is_active', true)
            ->first();

        \Log::info('Battle check', [
            'character_id' => $character->id,
            'monster_id' => $monster->id,
            'existing_battle' => $existingBattle ? $existingBattle->id : 'none',
            'monster_hp' => $existingBattle ? $existingBattle->monster_current_hp : 'N/A',
            'monster_max_hp' => $monster->max_hp,
            'is_continuing' => $existingBattle ? true : false
        ]);

        if ($existingBattle) {
            // Continuar batalha existente
            $battleInstance = $existingBattle;
            \Log::info('Continuing existing battle', [
                'battle_instance_id' => $battleInstance->id,
                'character_id' => $character->id,
                'monster_id' => $monster->id,
                'monster_hp' => $battleInstance->monster_current_hp
            ]);
        } else {
            // Finalizar outras batalhas ativas
            BattleInstance::where('character_id', $character->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Consumir stamina do personagem
            $character->current_stamina -= $monster->stamina_cost;
            $character->save();

            // Criar nova instância de batalha (apenas HP do monstro)
            $battleInstance = BattleInstance::create([
                'character_id' => $character->id,
                'monster_id' => $monster->id,
                'monster_current_hp' => $monster->max_hp,
                'character_current_hp' => $character->current_hp, // Apenas para referência inicial
                'character_current_stamina' => $character->current_stamina, // Apenas para referência inicial
            ]);

            \Log::info('New battle started', [
                'battle_instance_id' => $battleInstance->id,
                'character_id' => $character->id,
                'monster_id' => $monster->id,
                'stamina_consumed' => $monster->stamina_cost
            ]);
        }

        return response()->json([
            'battle_instance_id' => $battleInstance->id,
            'character' => $character->fresh(),
            'monster' => [
                'id' => $monster->id,
                'name' => $monster->name,
                'current_hp' => $battleInstance->monster_current_hp,
                'max_hp' => $monster->max_hp,
                'level' => $monster->level,
                'attack_power' => $monster->attack_power,
                'defense' => $monster->defense,
                'speed' => $monster->speed,
                'image' => $monster->image,
                'image_path' => $monster->image_path,
            ],
            'is_continuing_battle' => $existingBattle ? true : false
        ]);
    }

    /**
     * Execute a character attack.
     */
    public function attack(Request $request)
    {
        \Log::info('Battle attack called', $request->all());
        
        $request->validate([
            'battle_instance_id' => 'required|exists:battle_instances,id',
        ]);

        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            \Log::error('No active character found for user', ['user_id' => $user->id]);
            return response()->json([
                'error' => 'Você precisa selecionar um personagem para batalhar.'
            ], 400);
        }

        // Buscar instância de batalha
        $battleInstance = BattleInstance::where('id', $request->battle_instance_id)
            ->where('character_id', $character->id)
            ->where('is_active', true)
            ->with(['monster'])
            ->first();

        if (!$battleInstance) {
            return response()->json([
                'error' => 'Batalha não encontrada ou já finalizada.'
            ], 400);
        }

        $monster = $battleInstance->monster;

        // Verificar se tem vida suficiente
        if ($battleInstance->character_current_hp <= 0) {
            return response()->json([
                'error' => 'Você está morto! Precisa ser revivido para batalhar.',
                'current_hp' => $battleInstance->character_current_hp
            ], 400);
        }

        \Log::info('Battle continuing', [
            'battle_instance_id' => $battleInstance->id,
            'character_id' => $character->id,
            'monster_id' => $monster->id,
            'character_hp' => $battleInstance->character_current_hp,
            'monster_hp' => $battleInstance->monster_current_hp
        ]);

        // Criar monstro temporário para cálculos (usando HP da instância)
        $tempMonster = clone $monster;
        $tempMonster->current_hp = $battleInstance->monster_current_hp;

        // Criar personagem temporário para cálculos (usando dados atuais do personagem)
        $tempCharacter = clone $character;
        
        // Aplicar bônus de equipamentos ao personagem temporário (sem salvar no banco)
        $this->equipmentService->applyEquipmentBonusesToCharacter($tempCharacter);

        // Execute character attack
        $attackResult = $this->battleService->executeAttack($tempCharacter, $tempMonster);

        // Atualizar apenas HP do monstro na instância
        $battleInstance->monster_current_hp = $tempMonster->current_hp;

        // Check if battle is over (monster defeated)
        if ($tempMonster->current_hp <= 0) {
            // Character won - give rewards with event multipliers
            $goldReward = (int) round($this->eventService->applyMultiplier($monster->gold_reward, 'gold'));
            $expReward = (int) round($this->eventService->applyMultiplier($monster->exp_reward, 'exp'));
            
            // Store level before adding experience
            $levelBefore = $character->level;
            
            $character->gold += $goldReward;
            $this->characterService->addExperience($character, $expReward);
            $character->save();
            
            // Check if character leveled up
            $leveledUp = $character->level > $levelBefore;

            // Finalizar batalha
            $battleInstance->endBattle();

            return response()->json([
                'battle_over' => true,
                'result' => [
                    'winner' => 'character',
                    'message' => "Você derrotou {$monster->name}!",
                    'gold_reward' => $goldReward,
                    'exp_reward' => $expReward,
                    'base_gold_reward' => $monster->gold_reward,
                    'base_exp_reward' => $monster->exp_reward,
                    'active_events' => $this->eventService->getActiveEvents()
                ],
                'character' => $character->fresh(),
                'monster' => [
                    'id' => $monster->id,
                    'name' => $monster->name,
                    'current_hp' => 0,
                    'max_hp' => $monster->max_hp,
                    'level' => $monster->level,
                    'attack_power' => $monster->attack_power,
                    'defense' => $monster->defense,
                    'speed' => $monster->speed,
                    'image' => $monster->image,
                    'image_path' => $monster->image_path,
                ],
                'attack_result' => $attackResult,
                'leveled_up' => $leveledUp,
                'new_level' => $leveledUp ? $character->level : null
            ]);
        }

        // Monster attacks back
        $monsterAttackResult = $this->battleService->executeMonsterAttack($tempMonster, $tempCharacter);

        // Atualizar personagem real com dano recebido
        $character->current_hp = $tempCharacter->current_hp;
        $character->save();

        // Atualizar HP do monstro na instância
        $battleInstance->monster_current_hp = $tempMonster->current_hp;
        $battleInstance->save();

        // Check if battle is over after monster attack (character defeated)
        if ($character->current_hp <= 0) {
            // Finalizar batalha
            $battleInstance->endBattle();

            return response()->json([
                'battle_over' => true,
                'result' => [
                    'winner' => 'monster',
                    'message' => "Você foi derrotado por {$monster->name}!"
                ],
                'character' => $character->fresh(),
                'monster' => [
                    'id' => $monster->id,
                    'name' => $monster->name,
                    'current_hp' => $battleInstance->monster_current_hp,
                    'max_hp' => $monster->max_hp,
                    'level' => $monster->level,
                    'attack_power' => $monster->attack_power,
                    'defense' => $monster->defense,
                    'speed' => $monster->speed,
                    'image' => $monster->image,
                    'image_path' => $monster->image_path,
                ],
                'attack_result' => $attackResult,
                'monster_attack_result' => $monsterAttackResult
            ]);
        }

        return response()->json([
            'battle_over' => false,
            'character' => $character->fresh(),
            'monster' => [
                'id' => $monster->id,
                'name' => $monster->name,
                'current_hp' => $battleInstance->monster_current_hp,
                'max_hp' => $monster->max_hp,
                'level' => $monster->level,
                'attack_power' => $monster->attack_power,
                'defense' => $monster->defense,
                'speed' => $monster->speed,
                'image' => $monster->image,
                'image_path' => $monster->image_path,
            ],
            'attack_result' => $attackResult,
            'monster_attack_result' => $monsterAttackResult
        ]);
    }

    /**
     * Run away from battle.
     */
    public function flee(Request $request)
    {
        $request->validate([
            'battle_instance_id' => 'required|exists:battle_instances,id',
        ]);

        $user = Auth::user();
        $character = $user->activeCharacter;

        if (!$character) {
            return response()->json([
                'error' => 'Você precisa selecionar um personagem para batalhar.'
            ], 400);
        }

        // Buscar instância de batalha
        $battleInstance = BattleInstance::where('id', $request->battle_instance_id)
            ->where('character_id', $character->id)
            ->where('is_active', true)
            ->first();

        if (!$battleInstance) {
            return response()->json([
                'error' => 'Batalha não encontrada ou já finalizada.'
            ], 400);
        }

        // Finalizar batalha (personagem mantém estado atual)
        $battleInstance->endBattle();

        return response()->json([
            'battle_over' => true,
            'result' => [
                'winner' => 'fled',
                'message' => 'Você fugiu da batalha!'
            ],
            'character' => $character->fresh()
        ]);
    }
}
