<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
Route::get('/dashboard', function () {
    $user = auth()->user();
    $character = $user->activeCharacter;
    
    // Se não há personagem ativo, redirecionar para seleção
    if (!$character) {
        return redirect()->route('characters.select')
            ->with('error', 'Você precisa selecionar um personagem para acessar o dashboard.');
    }
    
    return Inertia::render('Dashboard', [
        'user' => $user,
        'character' => $character,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Email verification
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail', [
        'user' => auth()->user()
    ]);
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/email/verified', function () {
    return Inertia::render('Auth/EmailVerified');
})->name('email.verified');

// Reenvio de email
Route::post('/email/resend', function (Request $request) {
    $user = $request->user();
    
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('dashboard');
    }
    
    $user->sendEmailVerificationNotification();
    return back()->with('message', 'Email de verificação reenviado!');
})->middleware('auth')->name('verification.resend');

// Rotas que NÃO precisam de verificação de email
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
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
    Route::post('/battle/attack', [App\Http\Controllers\BattleController::class, 'attack'])->name('battle.attack');
    Route::post('/battle/flee', [App\Http\Controllers\BattleController::class, 'flee'])->name('battle.flee');
    
    // TODO: Adicionar outras rotas do jogo aqui conforme implementadas
    // Route::get('/game/dashboard', [GameController::class, 'dashboard'])->name('game.dashboard');
    // Route::get('/game/battle', [BattleController::class, 'index'])->name('game.battle');
    // Route::get('/game/inventory', [InventoryController::class, 'index'])->name('game.inventory');
    // Route::get('/game/shop', [ShopController::class, 'index'])->name('game.shop');
    // Route::get('/game/guild', [GuildController::class, 'index'])->name('game.guild');
});

require __DIR__.'/auth.php';
