<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $filterCategory = $request->get('filter_category', '');
        $sortBy = $request->get('sort_by', 'category');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Permission::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filterCategory) {
            $query->where('category', $filterCategory);
        }

        $query->orderBy($sortBy, $sortOrder);

        $permissions = $query->paginate(15)->withQueryString();

        $categories = Permission::distinct()->pluck('category')->sort()->values();

        return inertia('Admin/Permissions/Index', [
            'permissions' => $permissions,
            'filters' => [
                'search' => $search,
                'filter_category' => $filterCategory,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'categories' => $categories,
        ]);
    }

    public function show(Permission $permission)
    {
        $permission->load(['roles']);
        
        return inertia('Admin/Permissions/Show', [
            'permission' => $permission,
        ]);
    }

    public function create()
    {
        $categories = Permission::distinct()->pluck('category')->sort()->values();
        
        return inertia('Admin/Permissions/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $permission = Permission::create($validated);

        return redirect()->route('admin.permissions.show', $permission)
            ->with('success', 'Permissão criada com sucesso!');
    }

    public function edit(Permission $permission)
    {
        $categories = Permission::distinct()->pluck('category')->sort()->values();
        
        return inertia('Admin/Permissions/Edit', [
            'permission' => $permission,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.show', $permission)
            ->with('success', 'Permissão atualizada com sucesso!');
    }

    public function destroy(Permission $permission)
    {
        // Verificar se há roles com esta permissão
        if ($permission->roles()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir uma permissão que está sendo usada por roles.']);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissão excluída com sucesso!');
    }

    public function toggleActive(Permission $permission)
    {
        $permission->update(['is_active' => !$permission->is_active]);

        $status = $permission->is_active ? 'ativada' : 'desativada';
        
        return back()->with('success', "Permissão {$status} com sucesso!");
    }
}
