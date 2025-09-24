<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Character;
use App\Models\BattleInstance;
use App\Models\Monster;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    // Middleware aplicado nas rotas, não no controller

    /**
     * Dashboard principal do painel administrativo
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Estatísticas gerais do jogo
        $stats = [
            'total_users' => User::count(),
            'total_characters' => Character::count(),
            'total_battles' => BattleInstance::count(),
            'total_monsters' => Monster::count(),
            'active_monsters' => Monster::where('is_active', true)->count(),
            'total_items' => Item::count(),
            'items_by_type' => Item::selectRaw('type, COUNT(*) as count')->groupBy('type')->get(),
            'staff_members' => User::where('is_staff', true)->count(),
            'vip_users' => User::where('is_vip', true)->count(),
            'active_users_today' => User::whereDate('last_seen_at', today())->count(),
        ];

        // Usuários recentes
        $recent_users = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at', 'is_staff', 'is_vip']);

        // Personagens de maior nível
        $top_characters = Character::with('user:id,name')
            ->orderBy('level', 'desc')
            ->take(5)
            ->get(['id', 'name', 'level', 'class', 'user_id']);

        return Inertia::render('Admin/Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recent_users' => $recent_users,
            'top_characters' => $top_characters,
        ]);
    }

    /**
     * Verifica se o usuário tem nível de acesso admin
     */
    protected function isAdmin(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * Verifica se o usuário tem nível de acesso moderador ou superior
     */
    protected function isModerator(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'moderator']);
    }

    /**
     * Middleware para verificar acesso de admin
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            abort(403, 'Acesso negado. Requer privilégios de administrador.');
        }
    }

    /**
     * Middleware para verificar acesso de moderador
     */
    protected function requireModerator()
    {
        if (!$this->isModerator()) {
            abort(403, 'Acesso negado. Requer privilégios de moderador.');
        }
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    protected function hasPermission(string $permission): bool
    {
        return auth()->user()->hasPermission($permission);
    }
}
