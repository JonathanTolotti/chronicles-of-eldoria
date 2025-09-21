<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

// Maintenance mode route (uncomment to enable maintenance mode)
Route::get('/maintenance', function () {
    return Inertia::render('Maintenance', [
        'message' => 'Nossos artesãos estão trabalhando arduamente para melhorar sua experiência!',
        'estimatedTime' => '15-30 minutos'
    ]);
})->name('maintenance');

/*
|--------------------------------------------------------------------------
| ESTRUTURA DE ROTAS - CHRONICLES OF ELDORIA
|--------------------------------------------------------------------------
|
| 1. ROTAS PÚBLICAS: Acesso sem autenticação
|    - / (welcome) - Página de boas-vindas
|    - /login, /register - Autenticação
|
| 2. ROTAS AUTENTICADAS: Acesso com login (sem verificação de email)
|    - /profile - Perfil do usuário
|    - /email/verify - Verificação de email
|    - /email/resend - Reenvio de email
|
| 3. ROTAS DO JOGO: Acesso com login + email verificado
|    - /home - Página inicial do jogo
|    - /dashboard - Dashboard principal
|    - /characters/* - Criação e gerenciamento de personagens
|    - /game/* - Todas as funcionalidades do jogo
|
| MIDDLEWARE GLOBAL: EnsureEmailIsVerified
| - Aplicado automaticamente a TODAS as rotas autenticadas
| - Redireciona para /email/verify se email não verificado
| - Exceções: rotas de verificação, perfil e logout
|
*/

// Página de boas-vindas pública
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'user' => auth()->user(),
        'stats' => [
            'totalPlayers' => 0,
            'totalCharacters' => 0,
            'totalBattles' => 0,
            'highestLevel' => 0,
        ]
    ]);
})->name('welcome');


// Página inicial do jogo - apenas para usuários verificados
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

// Dashboard do jogo - precisa de verificação de email
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Email verification
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail', [
        'user' => auth()->user()
    ]);
})->middleware('auth')->name('verification.notice');

// Rota removida - usando a do auth.php

Route::get('/email/verified', function () {
    return Inertia::render('Auth/EmailVerified');
})->name('email.verified');

// Rotas que NÃO precisam de verificação de email
Route::middleware('auth')->group(function () {
    // Email verification resend
    Route::post('/email/verification-notification', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Rotas do JOGO - TODAS precisam de verificação de email (middleware global cuida disso)
Route::middleware('auth')->group(function () {
    // Character routes
    Route::get('/characters/select', [App\Http\Controllers\CharacterController::class, 'select'])->name('characters.select');
    Route::get('/characters/create', [App\Http\Controllers\CharacterController::class, 'create'])->name('characters.create');
    Route::post('/characters', [App\Http\Controllers\CharacterController::class, 'store'])->name('characters.store');
    Route::post('/characters/select', [App\Http\Controllers\CharacterController::class, 'confirmSelection'])->name('characters.confirm');
    
    // Training routes
    Route::get('/training', [App\Http\Controllers\TrainingController::class, 'index'])->name('training.index');
    Route::post('/training/start', [App\Http\Controllers\TrainingController::class, 'start'])->name('training.start');
    Route::post('/training/complete', [App\Http\Controllers\TrainingController::class, 'complete'])->name('training.complete');
    
    // Character stats routes
    Route::get('/character/stats', [App\Http\Controllers\CharacterStatsController::class, 'index'])->name('character.stats.index');
    Route::post('/character/stats/distribute', [App\Http\Controllers\CharacterStatsController::class, 'distributePoints'])->name('character.stats.distribute');

    // Battle routes
    Route::get('/battle', [App\Http\Controllers\BattleController::class, 'index'])->name('battle.index');
    Route::post('/battle/start', [App\Http\Controllers\BattleController::class, 'startBattle'])->name('battle.start');
    Route::post('/battle/attack', [App\Http\Controllers\BattleController::class, 'attack'])->name('battle.attack');
    Route::post('/battle/flee', [App\Http\Controllers\BattleController::class, 'flee'])->name('battle.flee');
    
    // Item routes (hotkeys)
    Route::post('/api/items/use-hotkey', [App\Http\Controllers\ItemController::class, 'useHotkeyItem'])->name('items.use-hotkey');
    Route::post('/api/items/set-hotkey', [App\Http\Controllers\ItemController::class, 'setHotkeySlot'])->name('items.set-hotkey');
    Route::get('/api/items/hotkeys', [App\Http\Controllers\ItemController::class, 'getHotkeyItems'])->name('items.hotkeys');
    Route::post('/api/items/add', [App\Http\Controllers\ItemController::class, 'addItem'])->name('items.add');
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update-profile');
    Route::post('/profile/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    
    
    // Sistema de Equipamentos
    Route::prefix('equipment')->group(function () {
        Route::get('/', [App\Http\Controllers\EquipmentController::class, 'index'])->name('equipment.index');
        Route::post('/equip', [App\Http\Controllers\EquipmentController::class, 'equip'])->name('equipment.equip');
        Route::post('/unequip', [App\Http\Controllers\EquipmentController::class, 'unequip'])->name('equipment.unequip');
        Route::get('/available', [App\Http\Controllers\EquipmentController::class, 'available'])->name('equipment.available');
        Route::post('/recalculate', [App\Http\Controllers\EquipmentController::class, 'recalculate'])->name('equipment.recalculate');
    });
    
    // Sistema de Inventário
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    
    // Sistema de Ranking
    Route::get('/ranking', [App\Http\Controllers\RankingController::class, 'index'])->name('ranking.index');
    Route::get('/api/ranking', [App\Http\Controllers\RankingController::class, 'getRankings'])->name('ranking.api');
    
    // Toggle inventário rápido
    Route::post('/items/toggle-quick-inventory', [App\Http\Controllers\ItemController::class, 'toggleQuickInventory'])->name('items.toggle-quick-inventory');
    Route::post('/equipment/toggle-quick-inventory', [App\Http\Controllers\EquipmentController::class, 'toggleQuickInventory'])->name('equipment.toggle-quick-inventory');
    
    // TODO: Adicionar outras rotas do jogo aqui conforme implementadas
    // Route::get('/game/dashboard', [GameController::class, 'dashboard'])->name('game.dashboard');
    // Route::get('/game/shop', [ShopController::class, 'index'])->name('game.shop');
    // Route::get('/game/guild', [GuildController::class, 'index'])->name('game.guild');
});

require __DIR__.'/auth.php';
