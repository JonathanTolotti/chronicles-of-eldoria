<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemEffect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     */
    public function index(Request $request)
    {
        $query = Item::with('effects');

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_type')) {
            $query->where('type', $request->filter_type);
        }

        if ($request->filled('filter_rarity')) {
            $query->where('rarity', $request->filter_rarity);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);

        $items = $query->paginate(15);

        return inertia('Admin/Items/Index', [
            'items' => $items,
            'filters' => $request->only(['search', 'filter_type', 'filter_rarity', 'sort_by', 'sort_order']),
            'types' => ['potion', 'weapon', 'armor', 'accessory', 'material'],
            'rarities' => ['common', 'uncommon', 'rare', 'epic', 'legendary'],
        ]);
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return inertia('Admin/Items/Create', [
            'types' => ['potion', 'weapon', 'armor', 'accessory', 'material'],
            'rarities' => ['common', 'uncommon', 'rare', 'epic', 'legendary'],
            'effect_types' => [
                'heal_hp' => 'Curar HP',
                'restore_stamina' => 'Restaurar Stamina',
                'restore_mp' => 'Restaurar MP',
                'buff_attack' => 'Buff de Ataque',
                'buff_defense' => 'Buff de Defesa',
                'buff_speed' => 'Buff de Velocidade',
            ],
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:potion,weapon,armor,accessory,material',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'image_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'effects' => 'nullable|array',
            'effects.*.effect_type' => 'required_with:effects|string',
            'effects.*.effect_value' => 'required_with:effects|integer|min:1',
            'effects.*.effect_duration' => 'nullable|integer|min:0',
        ]);

        // Gerar UUID
        $validated['uuid'] = Str::uuid();

        // Processar upload da imagem
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('items', $imageName, 'public');
            $validated['image_path'] = '/storage/' . $imagePath;
        } else {
            $validated['image_path'] = null;
        }

        // Criar o item
        $item = Item::create($validated);

        // Criar efeitos se fornecidos
        if ($request->filled('effects')) {
            foreach ($request->effects as $effectData) {
                if (!empty($effectData['effect_type']) && !empty($effectData['effect_value'])) {
                    $item->effects()->create([
                        'effect_type' => $effectData['effect_type'],
                        'effect_value' => $effectData['effect_value'],
                        'effect_duration' => $effectData['effect_duration'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'Item criado com sucesso!');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $item->load('effects');
        
        return inertia('Admin/Items/Show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $item->load('effects');
        
        return inertia('Admin/Items/Edit', [
            'item' => $item,
            'types' => ['potion', 'weapon', 'armor', 'accessory', 'material'],
            'rarities' => ['common', 'uncommon', 'rare', 'epic', 'legendary'],
            'effect_types' => [
                'heal_hp' => 'Curar HP',
                'restore_stamina' => 'Restaurar Stamina',
                'restore_mp' => 'Restaurar MP',
                'buff_attack' => 'Buff de Ataque',
                'buff_defense' => 'Buff de Defesa',
                'buff_speed' => 'Buff de Velocidade',
            ],
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:potion,weapon,armor,accessory,material',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'image_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'effects' => 'nullable|array',
            'effects.*.effect_type' => 'required_with:effects|string',
            'effects.*.effect_value' => 'required_with:effects|integer|min:1',
            'effects.*.effect_duration' => 'nullable|integer|min:0',
        ]);

        // Processar upload da imagem
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('items', $imageName, 'public');
            $validated['image_path'] = '/storage/' . $imagePath;
        } else {
            // Se não enviou nova imagem, manter a atual
            unset($validated['image_path']);
        }

        // Atualizar o item
        $item->update($validated);

        // Atualizar efeitos
        if ($request->has('effects')) {
            // Remover efeitos existentes
            $item->effects()->delete();
            
            // Criar novos efeitos
            foreach ($request->effects as $effectData) {
                if (!empty($effectData['effect_type']) && !empty($effectData['effect_value'])) {
                    $item->effects()->create([
                        'effect_type' => $effectData['effect_type'],
                        'effect_value' => $effectData['effect_value'],
                        'effect_duration' => $effectData['effect_duration'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.items.show', $item->uuid)
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Item removido com sucesso!');
    }
}
