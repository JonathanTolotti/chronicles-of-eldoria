<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
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
        
        // Carregar dados do inventário
        $inventory = $this->inventoryService->getInventoryByTabs($character);
        $equipped = $this->inventoryService->getEquippedItems($character);
        $stats = $this->inventoryService->getInventoryStats($character);
        
        return Inertia::render('Dashboard', [
            'user' => $user,
            'character' => $character,
            'inventory' => $inventory,
            'equipped' => $equipped,
            'stats' => $stats,
        ]);
    }
}
