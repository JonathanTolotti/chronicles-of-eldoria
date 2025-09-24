<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Listar usuários com filtros e paginação
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'characters']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('is_staff')) {
            $query->where('is_staff', $request->boolean('is_staff'));
        }

        if ($request->filled('is_vip')) {
            $query->where('is_vip', $request->boolean('is_vip'));
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::active()->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $request->get('search', ''),
                'role' => $request->get('role', ''),
                'is_staff' => $request->get('is_staff', ''),
                'is_vip' => $request->get('is_vip', ''),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ],
        ]);
    }

    /**
     * Mostrar detalhes de um usuário
     */
    public function show(User $user)
    {
        $user->load(['roles', 'characters', 'characters.equipment']);
        
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        $roles = Role::active()->get();
        
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Criar novo usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_staff' => 'boolean',
            'is_vip' => 'boolean',
            'vip_expires_at' => 'nullable|date|after:now',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => \Illuminate\Support\Str::uuid(),
            'is_staff' => $request->boolean('is_staff'),
            'is_vip' => $request->boolean('is_vip'),
            'vip_expires_at' => $request->vip_expires_at,
        ]);

        // Atribuir roles
        if ($request->filled('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            foreach ($roles as $role) {
                $user->assignRole($role, auth()->user());
            }
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::active()->get();
        
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'is_staff' => 'boolean',
            'is_vip' => 'boolean',
            'vip_expires_at' => 'nullable|date',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_staff' => $request->boolean('is_staff'),
            'is_vip' => $request->boolean('is_vip'),
            'vip_expires_at' => $request->vip_expires_at,
        ];

        $user->update($updateData);

        // Atualizar roles
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário atualizado com sucesso!');
    }


    /**
     * Atribuir role a usuário
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role, auth()->user());

        return back()->with('success', "Role '{$role->display_name}' atribuído com sucesso!");
    }

    /**
     * Remover role de usuário
     */
    public function removeRole(User $user, Role $role)
    {
        $user->removeRole($role);

        return back()->with('success', "Role '{$role->display_name}' removido com sucesso!");
    }

    /**
     * Alternar status VIP
     */
    public function toggleVip(User $user)
    {
        $user->update([
            'is_vip' => !$user->is_vip,
            'vip_expires_at' => $user->is_vip ? null : now()->addYear(),
        ]);

        $status = $user->is_vip ? 'ativado' : 'desativado';
        return back()->with('success', "Status VIP {$status} com sucesso!");
    }

    /**
     * Alternar status Staff
     */
    public function toggleStaff(User $user)
    {
        // Não permitir remover staff de si mesmo
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode alterar seu próprio status de staff!');
        }

        $user->update(['is_staff' => !$user->is_staff]);

        $status = $user->is_staff ? 'concedido' : 'removido';
        return back()->with('success', "Status Staff {$status} com sucesso!");
    }
}
