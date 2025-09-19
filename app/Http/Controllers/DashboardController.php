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
            'user' => $user,
            'character' => $character,
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
