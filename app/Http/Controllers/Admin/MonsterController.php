<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MonsterController extends Controller
{
    /**
     * Display a listing of monsters.
     */
    public function index(Request $request)
    {
        $query = Monster::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->get('level'));
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'level');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['name', 'level', 'max_hp', 'attack_power', 'defense', 'speed', 'gold_reward', 'exp_reward', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $monsters = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Monsters/Index', [
            'monsters' => $monsters,
            'filters' => [
                'search' => $request->get('search', ''),
                'level' => $request->get('level', ''),
                'status' => $request->get('status', ''),
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ]);
    }

    /**
     * Show the form for creating a new monster.
     */
    public function create()
    {
        return Inertia::render('Admin/Monsters/Create');
    }

    /**
     * Store a newly created monster.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|integer|min:1|max:100',
            'max_hp' => 'required|integer|min:1',
            'attack_power' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0',
            'speed' => 'required|integer|min:1',
            'gold_reward' => 'required|integer|min:0',
            'exp_reward' => 'required|integer|min:0',
            'stamina_cost' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Validar imagem separadamente se foi enviada
        if ($request->hasFile('image_path')) {
            $request->validate([
                'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
        }

        // Definir current_hp igual ao max_hp e gerar UUID
        $validated['current_hp'] = $validated['max_hp'];
        $validated['uuid'] = Str::uuid();

        // Processar upload da imagem
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('monsters', $imageName, 'public');
            $validated['image_path'] = '/storage/' . $imagePath;
        } else {
            $validated['image_path'] = null;
        }

        Monster::create($validated);

        return redirect()->route('admin.monsters.index')
            ->with('success', 'Monstro criado com sucesso!');
    }

    /**
     * Display the specified monster.
     */
    public function show(Monster $monster)
    {
        return Inertia::render('Admin/Monsters/Show', [
            'monster' => $monster,
        ]);
    }

    /**
     * Show the form for editing the specified monster.
     */
    public function edit(Monster $monster)
    {
        return Inertia::render('Admin/Monsters/Edit', [
            'monster' => $monster,
        ]);
    }

    /**
     * Update the specified monster.
     */
    public function update(Request $request, Monster $monster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|integer|min:1|max:100',
            'max_hp' => 'required|integer|min:1',
            'attack_power' => 'required|integer|min:1',
            'defense' => 'required|integer|min:0',
            'speed' => 'required|integer|min:1',
            'gold_reward' => 'required|integer|min:0',
            'exp_reward' => 'required|integer|min:0',
            'stamina_cost' => 'required|integer|min:1',
            'image_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Se o max_hp mudou, ajustar o current_hp proporcionalmente
        if ($validated['max_hp'] != $monster->max_hp) {
            $ratio = $validated['max_hp'] / $monster->max_hp;
            $validated['current_hp'] = (int) round($monster->current_hp * $ratio);
        }

        // Processar upload da imagem
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('monsters', $imageName, 'public');
            $validated['image_path'] = '/storage/' . $imagePath;
        } else {
            // Se não enviou nova imagem, manter a atual
            unset($validated['image_path']);
        }

        $monster->update($validated);

        return redirect()->route('admin.monsters.show', $monster->uuid)
            ->with('success', 'Monstro atualizado com sucesso!');
    }

    /**
     * Remove the specified monster.
     */
    public function destroy(Monster $monster)
    {
        $monster->delete();

        return redirect()->route('admin.monsters.index')
            ->with('success', 'Monstro removido com sucesso!');
    }

    /**
     * Toggle monster active status.
     */
    public function toggleActive(Monster $monster)
    {
        $monster->update(['is_active' => !$monster->is_active]);

        return redirect()->back()
            ->with('success', 'Status do monstro alterado com sucesso!');
    }

    /**
     * Reset monster HP to max.
     */
    public function resetHp(Monster $monster)
    {
        $monster->resetHp();

        return redirect()->back()
            ->with('success', 'HP do monstro resetado com sucesso!');
    }
}
