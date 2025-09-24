<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentTier;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment.
     */
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_type')) {
            $query->where('type', $request->filter_type);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);

        $equipment = $query->paginate(15);

        return inertia('Admin/Equipment/Index', [
            'equipment' => $equipment,
            'filters' => $request->only(['search', 'filter_type', 'sort_by', 'sort_order']),
            'types' => ['weapon', 'armor', 'helmet', 'shield', 'boots', 'pants'],
        ]);
    }

    /**
     * Show the form for creating a new equipment.
     */
    public function create()
    {
        return inertia('Admin/Equipment/Create', [
            'types' => ['weapon', 'armor', 'helmet', 'shield', 'boots', 'pants'],
        ]);
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:weapon,armor,helmet,shield,boots,pants',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'strength_bonus' => 'required|integer|min:0',
            'dexterity_bonus' => 'required|integer|min:0',
            'constitution_bonus' => 'required|integer|min:0',
            'intelligence_bonus' => 'required|integer|min:0',
            'luck_bonus' => 'required|integer|min:0',
            'hp_bonus' => 'required|integer|min:0',
            'mp_bonus' => 'required|integer|min:0',
        ]);

        // Gerar UUID
        $validated['uuid'] = Str::uuid();

        // Processar upload da imagem
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('equipment', $imageName, 'public');
            $validated['image'] = '/storage/' . $imagePath;
        } else {
            $validated['image'] = null;
        }

        // Criar o equipamento
        $equipment = Equipment::create($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipamento criado com sucesso!');
    }

    /**
     * Display the specified equipment.
     */
    public function show(Equipment $equipment)
    {
        return inertia('Admin/Equipment/Show', [
            'equipment' => $equipment,
        ]);
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit(Equipment $equipment)
    {
        return inertia('Admin/Equipment/Edit', [
            'equipment' => $equipment,
            'types' => ['weapon', 'armor', 'helmet', 'shield', 'boots', 'pants'],
        ]);
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:weapon,armor,helmet,shield,boots,pants',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'strength_bonus' => 'required|integer|min:0',
            'dexterity_bonus' => 'required|integer|min:0',
            'constitution_bonus' => 'required|integer|min:0',
            'intelligence_bonus' => 'required|integer|min:0',
            'luck_bonus' => 'required|integer|min:0',
            'hp_bonus' => 'required|integer|min:0',
            'mp_bonus' => 'required|integer|min:0',
        ]);

        // Processar upload da imagem
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('equipment', $imageName, 'public');
            $validated['image'] = '/storage/' . $imagePath;
        } else {
            // Se não enviou nova imagem, manter a atual
            unset($validated['image']);
        }

        // Atualizar o equipamento
        $equipment->update($validated);

        return redirect()->route('admin.equipment.show', ['equipment' => $equipment->uuid])
            ->with('success', 'Equipamento atualizado com sucesso!');
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipamento removido com sucesso!');
    }
}
