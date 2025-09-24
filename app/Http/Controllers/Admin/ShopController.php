<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopItem;
use App\Models\Item;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of shop items.
     */
    public function index(Request $request)
    {
        $query = ShopItem::with(['item', 'equipment']);

        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('equipment', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filter_type')) {
            if ($request->filter_type === 'item') {
                $query->whereNotNull('item_id');
            } elseif ($request->filter_type === 'equipment') {
                $query->whereNotNull('equipment_id');
            }
        }

        if ($request->filled('filter_availability')) {
            $query->where('is_available', $request->filter_availability === 'available');
        }

        if ($request->filled('filter_featured')) {
            $query->where('is_featured', $request->filter_featured === 'featured');
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        $shopItems = $query->paginate(15);

        return inertia('Admin/Shop/Index', [
            'shopItems' => $shopItems,
            'filters' => $request->only(['search', 'filter_type', 'filter_availability', 'filter_featured', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show the form for creating a new shop item.
     */
    public function create()
    {
        return inertia('Admin/Shop/Create', [
            'items' => Item::select('id', 'uuid', 'name', 'type', 'rarity')->get(),
            'equipment' => Equipment::select('id', 'uuid', 'name', 'type')->get(),
        ]);
    }

    /**
     * Store a newly created shop item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'gold_price' => 'required|integer|min:0',
            'coin_price' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'daily_limit' => 'nullable|integer|min:0',
            'min_level' => 'required|integer|min:1',
            'max_level' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_limited_time' => 'boolean',
            'available_until' => 'nullable|date|after:now',
            'discount_percentage' => 'integer|min:0|max:100',
        ]);

        // Validar que exatamente um item ou equipamento foi selecionado
        if (!$validated['item_id'] && !$validated['equipment_id']) {
            return back()->withErrors(['item_id' => 'Selecione um item ou equipamento.']);
        }
        
        if ($validated['item_id'] && $validated['equipment_id']) {
            return back()->withErrors(['item_id' => 'Selecione apenas um item OU um equipamento, não ambos.']);
        }

        // Gerar UUID
        $validated['uuid'] = Str::uuid();

        // Criar o item da loja
        $shopItem = ShopItem::create($validated);

        return redirect()->route('admin.shop.index')
            ->with('success', 'Item da loja criado com sucesso!');
    }

    /**
     * Display the specified shop item.
     */
    public function show(ShopItem $shopItem)
    {
        $shopItem->load(['item', 'equipment', 'purchases.character.user']);
        
        return inertia('Admin/Shop/Show', [
            'shopItem' => $shopItem,
        ]);
    }

    /**
     * Show the form for editing the specified shop item.
     */
    public function edit(ShopItem $shopItem)
    {
        $shopItem->load(['item', 'equipment']);
        
        return inertia('Admin/Shop/Edit', [
            'shopItem' => $shopItem,
            'items' => Item::select('id', 'uuid', 'name', 'type', 'rarity')->get(),
            'equipment' => Equipment::select('id', 'uuid', 'name', 'type')->get(),
        ]);
    }

    /**
     * Update the specified shop item.
     */
    public function update(Request $request, ShopItem $shopItem)
    {
        $validated = $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'gold_price' => 'required|integer|min:0',
            'coin_price' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'daily_limit' => 'nullable|integer|min:0',
            'min_level' => 'required|integer|min:1',
            'max_level' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_limited_time' => 'boolean',
            'available_until' => 'nullable|date|after:now',
            'discount_percentage' => 'integer|min:0|max:100',
        ]);

        // Validar que exatamente um item ou equipamento foi selecionado
        if (!$validated['item_id'] && !$validated['equipment_id']) {
            return back()->withErrors(['item_id' => 'Selecione um item ou equipamento.']);
        }
        
        if ($validated['item_id'] && $validated['equipment_id']) {
            return back()->withErrors(['item_id' => 'Selecione apenas um item OU um equipamento, não ambos.']);
        }

        // Atualizar o item da loja
        $shopItem->update($validated);

        return redirect()->route('admin.shop.show', ['shopItem' => $shopItem->uuid])
            ->with('success', 'Item da loja atualizado com sucesso!');
    }

    /**
     * Remove the specified shop item.
     */
    public function destroy(ShopItem $shopItem)
    {
        $shopItem->delete();

        return redirect()->route('admin.shop.index')
            ->with('success', 'Item da loja removido com sucesso!');
    }

    /**
     * Toggle availability of shop item.
     */
    public function toggleAvailability(ShopItem $shopItem)
    {
        $shopItem->update(['is_available' => !$shopItem->is_available]);

        return back()->with('success', 'Disponibilidade do item atualizada!');
    }

    /**
     * Toggle featured status of shop item.
     */
    public function toggleFeatured(ShopItem $shopItem)
    {
        $shopItem->update(['is_featured' => !$shopItem->is_featured]);

        return back()->with('success', 'Status de destaque atualizado!');
    }
}
