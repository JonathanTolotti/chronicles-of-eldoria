<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $filterLevel = $request->get('filter_level', '');
        $sortBy = $request->get('sort_by', 'level');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Role::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filterLevel) {
            $query->where('level', $filterLevel);
        }

        $query->orderBy($sortBy, $sortOrder);

        $roles = $query->paginate(15)->withQueryString();

        $levels = Role::distinct()->pluck('level')->sort()->values();

        return inertia('Admin/Roles/Index', [
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'filter_level' => $filterLevel,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'levels' => $levels,
        ]);
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        
        return inertia('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    public function create()
    {
        $permissions = Permission::active()->ordered()->get();
        
        return inertia('Admin/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'level' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($validated);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.show', $role)
            ->with('success', 'Role criado com sucesso!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::active()->ordered()->get();
        $role->load('permissions');
        
        return inertia('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'level' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update($validated);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.show', $role)
            ->with('success', 'Role atualizado com sucesso!');
    }

    public function destroy(Role $role)
    {
        // Verificar se há usuários com este role
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir um role que possui usuários associados.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role excluído com sucesso!');
    }

    public function toggleActive(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        $status = $role->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "Role {$status} com sucesso!");
    }
}
