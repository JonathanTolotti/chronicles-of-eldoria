<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Models\ShopPurchase;
use App\Models\Character;
use App\Models\CharacterItem;
use App\Models\CharacterEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    /**
     * Exibir a página da loja
     */
    public function index(Request $request): Response
    {
        try {
            $user = $request->user();
            $character = $user->activeCharacter;
            
            if (!$character) {
                return redirect()->route('characters.select')
                    ->with('error', 'Você precisa selecionar um personagem para acessar a loja.');
            }

            // Filtros
            $category = $request->get('category', 'all');
            $rarity = $request->get('rarity', 'all');
            $sortBy = $request->get('sort', 'featured');
            $search = $request->get('search', '');

            // Query base simples
            $query = ShopItem::available()->forLevel($character->level);

            // Ordenação simples
            switch ($sortBy) {
                case 'price_low':
                    $query->orderByRaw('(gold_price + coin_price) ASC');
                    break;
                case 'price_high':
                    $query->orderByRaw('(gold_price + coin_price) DESC');
                    break;
                case 'level':
                    $query->orderBy('min_level', 'ASC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                default: // featured
                    $query->orderBy('is_featured', 'DESC')
                          ->orderBy('created_at', 'DESC');
            }

            $shopItems = $query->paginate(12);

            // Adicionar informações extras para cada item
            $shopItems->getCollection()->transform(function ($shopItem) use ($character, $user) {
                try {
                    $shopItem->can_buy = $shopItem->canCharacterBuy($character) && $shopItem->canUserAfford($user) && !$shopItem->hasCharacterPurchased($character);
                    $shopItem->name = $shopItem->getName();
                    $shopItem->description = $shopItem->getDescription();
                    $shopItem->image_path = $shopItem->getImagePath();
                    $shopItem->category = $shopItem->getCategory();
                    $shopItem->rarity = $shopItem->getRarity();
                    $shopItem->discounted_gold_price = $shopItem->getDiscountedGoldPrice();
                    $shopItem->discounted_coin_price = $shopItem->getDiscountedCoinPrice();
                    $shopItem->already_purchased = $shopItem->hasCharacterPurchased($character);
                } catch (\Exception $e) {
                    // Se houver erro, usar valores padrão
                    $shopItem->can_buy = false;
                    $shopItem->name = 'Item Desconhecido';
                    $shopItem->description = '';
                    $shopItem->image_path = null;
                    $shopItem->category = 'unknown';
                    $shopItem->rarity = 'common';
                    $shopItem->discounted_gold_price = $shopItem->gold_price;
                    $shopItem->discounted_coin_price = $shopItem->coin_price;
                }
                
                return $shopItem;
            });

            // Itens em destaque
            $featuredItems = ShopItem::available()
                ->featured()
                ->forLevel($character->level)
                ->limit(6)
                ->get()
                ->transform(function ($shopItem) use ($character, $user) {
                    try {
                        $shopItem->can_buy = $shopItem->canCharacterBuy($character) && $shopItem->canUserAfford($user) && !$shopItem->hasCharacterPurchased($character);
                        $shopItem->name = $shopItem->getName();
                        $shopItem->description = $shopItem->getDescription();
                        $shopItem->image_path = $shopItem->getImagePath();
                        $shopItem->category = $shopItem->getCategory();
                        $shopItem->rarity = $shopItem->getRarity();
                        $shopItem->discounted_gold_price = $shopItem->getDiscountedGoldPrice();
                        $shopItem->discounted_coin_price = $shopItem->getDiscountedCoinPrice();
                        $shopItem->already_purchased = $shopItem->hasCharacterPurchased($character);
                    } catch (\Exception $e) {
                        // Se houver erro, usar valores padrão
                        $shopItem->can_buy = false;
                        $shopItem->name = 'Item Desconhecido';
                        $shopItem->description = '';
                        $shopItem->image_path = null;
                        $shopItem->category = 'unknown';
                        $shopItem->rarity = 'common';
                        $shopItem->discounted_gold_price = $shopItem->gold_price;
                        $shopItem->discounted_coin_price = $shopItem->coin_price;
                    }
                    
                    return $shopItem;
                });

            return Inertia::render('Shop/Index', [
                'shopItems' => $shopItems,
                'featuredItems' => $featuredItems,
                'character' => $character,
                'user' => $user,
                'filters' => [
                    'category' => $category,
                    'rarity' => $rarity,
                    'sort' => $sortBy,
                    'search' => $search,
                ],
                'categories' => [
                    'all' => 'Todos',
                    'weapon' => 'Armas',
                    'armor' => 'Armaduras',
                    'accessory' => 'Acessórios',
                    'potion' => 'Poções',
                    'material' => 'Materiais',
                    'cosmetic' => 'Cosméticos',
                    'special' => 'Especiais',
                ],
                'rarities' => [
                    'all' => 'Todas',
                    'common' => 'Comum',
                    'uncommon' => 'Incomum',
                    'rare' => 'Raro',
                    'epic' => 'Épico',
                    'legendary' => 'Lendário',
                ],
            ]);
        } catch (\Exception $e) {
            // Log do erro e retorno de fallback
            \Log::error('Erro na loja: ' . $e->getMessage());
            
            return Inertia::render('Shop/Index', [
                'shopItems' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12),
                'featuredItems' => [],
                'character' => $request->user()->activeCharacter,
                'user' => $request->user(),
                'filters' => [
                    'category' => 'all',
                    'rarity' => 'all',
                    'sort' => 'featured',
                    'search' => '',
                ],
                'categories' => [
                    'all' => 'Todos',
                    'weapon' => 'Armas',
                    'armor' => 'Armaduras',
                    'accessory' => 'Acessórios',
                    'potion' => 'Poções',
                    'material' => 'Materiais',
                    'cosmetic' => 'Cosméticos',
                    'special' => 'Especiais',
                ],
                'rarities' => [
                    'all' => 'Todas',
                    'common' => 'Comum',
                    'uncommon' => 'Incomum',
                    'rare' => 'Raro',
                    'epic' => 'Épico',
                    'legendary' => 'Lendário',
                ],
            ]);
        }
    }

    /**
     * Comprar um item da loja
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'shop_item_id' => 'required|exists:shop_items,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = $request->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Personagem ativo não encontrado'], 400);
        }

        $shopItem = ShopItem::with(['item', 'equipment'])->findOrFail($request->shop_item_id);

        // Validações
        if (!$shopItem->isAvailable()) {
            return response()->json(['error' => 'Item não está disponível'], 400);
        }

        if (!$shopItem->canCharacterBuy($character)) {
            return response()->json(['error' => 'Você não atende aos requisitos de nível para este item'], 400);
        }

        if (!$shopItem->canUserAfford($user)) {
            return response()->json(['error' => 'Moedas insuficientes'], 400);
        }

        // Verificar se já foi comprado (para compras únicas)
        if ($shopItem->hasCharacterPurchased($character)) {
            return response()->json(['error' => 'Este item já foi comprado e não pode ser comprado novamente'], 400);
        }

        // Verificar estoque
        if ($shopItem->stock_quantity !== null && $shopItem->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Estoque insuficiente'], 400);
        }

        // Verificar limite diário
        if ($shopItem->daily_limit) {
            $todayPurchases = ShopPurchase::where('character_id', $character->id)
                ->where('shop_item_id', $shopItem->id)
                ->whereDate('created_at', today())
                ->sum('quantity');

            if ($todayPurchases + $request->quantity > $shopItem->daily_limit) {
                return response()->json(['error' => 'Limite diário excedido'], 400);
            }
        }

        try {
            DB::beginTransaction();

            // Log para debug
            \Log::info('Iniciando compra', [
                'shop_item_id' => $shopItem->id,
                'quantity' => $request->quantity,
                'character_id' => $character->id,
                'user_id' => $user->id
            ]);

            // Calcular custos
            $totalGoldCost = $shopItem->getDiscountedGoldPrice() * $request->quantity;
            $totalCoinCost = $shopItem->getDiscountedCoinPrice() * $request->quantity;

            // Debitar moedas
            $character->decrement('gold', $totalGoldCost);
            $user->decrement('coin', $totalCoinCost);

            // Registrar compra
            $purchase = ShopPurchase::create([
                'character_id' => $character->id,
                'shop_item_id' => $shopItem->id,
                'quantity' => $request->quantity,
                'gold_paid' => $totalGoldCost,
                'coin_paid' => $totalCoinCost,
                'discount_applied' => $shopItem->discount_percentage,
                'status' => 'completed',
            ]);

            // Adicionar item ao inventário
            $relatedItem = $shopItem->getRelatedItem();
            
            if (!$relatedItem) {
                throw new \Exception('Item relacionado não encontrado');
            }
            
            if ($shopItem->getType() === 'item') {
                // Adicionar item ao inventário
                $characterItem = CharacterItem::where('character_id', $character->id)
                    ->where('item_id', $relatedItem->id)
                    ->first();

                if ($characterItem) {
                    $characterItem->increment('quantity', $request->quantity);
                } else {
                    CharacterItem::create([
                        'character_id' => $character->id,
                        'item_id' => $relatedItem->id,
                        'quantity' => $request->quantity,
                    ]);
                }
            } else {
                // Adicionar equipamento ao inventário
                for ($i = 0; $i < $request->quantity; $i++) {
                    CharacterEquipment::create([
                        'character_id' => $character->id,
                        'equipment_id' => $relatedItem->id,
                    ]);
                }
            }

            // Atualizar estoque
            if ($shopItem->stock_quantity !== null) {
                $shopItem->decrement('stock_quantity', $request->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra realizada com sucesso!',
                'purchase' => $purchase,
                'character' => $character->fresh(),
                'user' => $user->fresh(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro na compra: ' . $e->getMessage(), [
                'shop_item_id' => $request->shop_item_id,
                'quantity' => $request->quantity,
                'character_id' => $character->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erro ao processar compra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obter histórico de compras
     */
    public function purchaseHistory(Request $request)
    {
        $user = $request->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Personagem não encontrado'], 404);
        }

        $purchases = ShopPurchase::with(['shopItem.item', 'shopItem.equipment'])
            ->forCharacter($character->id)
            ->completed()
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        $purchases->getCollection()->transform(function ($purchase) {
            $purchase->item_name = $purchase->getItemName();
            return $purchase;
        });

        return response()->json($purchases);
    }

    /**
     * Obter detalhes de um item da loja
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return response()->json(['error' => 'Personagem não encontrado'], 404);
        }

        $shopItem = ShopItem::with(['item', 'equipment'])->findOrFail($id);
        
        $shopItem->can_buy = $shopItem->canCharacterBuy($character) && $shopItem->canUserAfford($user);
        $shopItem->name = $shopItem->getName();
        $shopItem->description = $shopItem->getDescription();
        $shopItem->image_path = $shopItem->getImagePath();
        $shopItem->category = $shopItem->getCategory();
        $shopItem->rarity = $shopItem->getRarity();
        $shopItem->discounted_gold_price = $shopItem->getDiscountedGoldPrice();
        $shopItem->discounted_coin_price = $shopItem->getDiscountedCoinPrice();

        return response()->json($shopItem);
    }
}
