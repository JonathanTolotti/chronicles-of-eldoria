<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\InventoryService;
use App\Services\EventService;
use App\Services\EquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService,
        private EventService $eventService,
        private EquipmentService $equipmentService
    ) {}

    /**
     * Show the dashboard
     */
    public function index(): Response|RedirectResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;
        
        // Se não há personagem ativo, redirecionar para seleção
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para acessar o dashboard.');
        }
        
        // Carregar dados do inventário rápido
        $inventory = $this->inventoryService->getQuickInventoryByTabs($character);
        $equipped = $this->inventoryService->getEquippedItems($character);
        $stats = $this->inventoryService->getInventoryStats($character);
        
        // Carregar dados dos equipamentos
        $equipment = $this->equipmentService->getCharacterEquipment($character);
        $equipmentBonuses = $this->equipmentService->getEquipmentBonuses($character);
        
        // Carregar eventos ativos
        $activeEvents = $this->eventService->getActiveEvents();
        $hasActiveEvents = $this->eventService->hasActiveEvents();
        
        return Inertia::render('Dashboard', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_vip' => $user->isVip(),
                'is_staff' => $user->is_staff,
                'coin' => $user->coin,
            ],
            'character' => [
                'id' => $character->id,
                'name' => $character->name,
                'class' => $character->class,
                'level' => $character->level,
                'experience' => $character->experience,
                'experience_to_next_level' => $character->experience_to_next_level,
                'strength' => $character->strength,
                'dexterity' => $character->dexterity,
                'constitution' => $character->constitution,
                'intelligence' => $character->intelligence,
                'luck' => $character->luck,
                'max_hp' => $character->max_hp,
                'current_hp' => $character->current_hp,
                'max_stamina' => $character->max_stamina,
                'current_stamina' => $character->current_stamina,
                'power' => $character->power,
                'gold' => $character->gold,
                'stat_points' => $character->stat_points,
                'avatar' => $character->avatar,
                'avatar_url' => $character->getAvatarUrl(),
                'biography' => $character->biography,
                'profile_public' => $character->profile_public,
            ],
            'inventory' => $inventory,
            'equipped' => $equipped,
            'stats' => $stats,
            'equipment' => $equipment,
            'equipmentBonuses' => $equipmentBonuses,
            'activeEvents' => $activeEvents,
            'hasActiveEvents' => $hasActiveEvents,
        ]);
    }
}
