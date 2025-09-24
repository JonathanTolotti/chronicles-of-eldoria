<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    shopItems: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const filterType = ref(props.filters?.filter_type || '');
const filterAvailability = ref(props.filters?.filter_availability || '');
const filterFeatured = ref(props.filters?.filter_featured || '');
const sortBy = ref(props.filters?.sort_by || 'created_at');
const sortOrder = ref(props.filters?.sort_order || 'desc');

const updateFilters = () => {
    const filters = {};
    if (search.value) filters.search = search.value;
    if (filterType.value) filters.filter_type = filterType.value;
    if (filterAvailability.value) filters.filter_availability = filterAvailability.value;
    if (filterFeatured.value) filters.filter_featured = filterFeatured.value;
    if (sortBy.value) filters.sort_by = sortBy.value;
    if (sortOrder.value) filters.sort_order = sortOrder.value;
    
    router.get(route('admin.shop.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

watch([search, filterType, filterAvailability, filterFeatured, sortBy, sortOrder], updateFilters, { deep: true });

const getItemName = (shopItem) => {
    return shopItem.item?.name || shopItem.equipment?.name || 'Item não encontrado';
};

const getItemType = (shopItem) => {
    if (shopItem.item) {
        return shopItem.item.type;
    }
    if (shopItem.equipment) {
        return shopItem.equipment.type;
    }
    return 'unknown';
};

const getItemImage = (shopItem) => {
    return shopItem.item?.image_path || shopItem.equipment?.image || null;
};

const getItemRarity = (shopItem) => {
    return shopItem.item?.rarity || 'common';
};

const getRarityColor = (rarity) => {
    const colors = {
        common: 'text-gray-600',
        uncommon: 'text-green-600',
        rare: 'text-blue-600',
        epic: 'text-purple-600',
        legendary: 'text-yellow-600',
    };
    return colors[rarity] || 'text-gray-600';
};

const formatPrice = (goldPrice, coinPrice) => {
    const parts = [];
    if (goldPrice > 0) parts.push(`${goldPrice.toLocaleString()} ouro`);
    if (coinPrice > 0) parts.push(`${coinPrice.toLocaleString()} coins`);
    return parts.join(' + ') || 'Gratuito';
};

const toggleAvailability = (shopItem) => {
    router.post(route('admin.shop.toggle-availability', { shopItem: shopItem.uuid }), {}, {
        preserveState: true,
    });
};

const toggleFeatured = (shopItem) => {
    router.post(route('admin.shop.toggle-featured', { shopItem: shopItem.uuid }), {}, {
        preserveState: true,
    });
};
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-medieval-bronze mb-2">
                                <i class="fas fa-store mr-3"></i>
                                Gerenciamento da Loja
                            </h1>
                            <p class="text-gray-600">Gerencie os itens disponíveis na loja do jogo</p>
                        </div>
                        <Link
                            :href="route('admin.shop.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Item da Loja
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <!-- Busca -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nome do item..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                />
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                                <select
                                    v-model="filterType"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos os tipos</option>
                                    <option value="item">Itens</option>
                                    <option value="equipment">Equipamentos</option>
                                </select>
                            </div>

                            <!-- Disponibilidade -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Disponibilidade</label>
                                <select
                                    v-model="filterAvailability"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todas</option>
                                    <option value="available">Disponível</option>
                                    <option value="unavailable">Indisponível</option>
                                </select>
                            </div>

                            <!-- Destaque -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Destaque</label>
                                <select
                                    v-model="filterFeatured"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option value="featured">Em Destaque</option>
                                    <option value="normal">Normal</option>
                                </select>
                            </div>

                            <!-- Ordenar por -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="created_at">Data de Criação</option>
                                    <option value="gold_price">Preço em Ouro</option>
                                    <option value="coin_price">Preço em Coins</option>
                                    <option value="min_level">Nível Mínimo</option>
                                </select>
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                <select
                                    v-model="sortOrder"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="desc">Decrescente</option>
                                    <option value="asc">Crescente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Itens da Loja -->
                <div class="card-medieval">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-amber-100" @click="toggleSort('name')">
                                        <i class="fas fa-store mr-1"></i>
                                        Item
                                        <i v-if="sortBy === 'name'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-amber-100" @click="toggleSort('gold_price')">
                                        <i class="fas fa-coins mr-1"></i>
                                        Preço
                                        <i v-if="sortBy === 'gold_price'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-amber-100" @click="toggleSort('min_level')">
                                        <i class="fas fa-level-up-alt mr-1"></i>
                                        Nível
                                        <i v-if="sortBy === 'min_level'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-1"></i>
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="shopItem in shopItems.data || []" :key="shopItem.id" class="hover:bg-amber-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div v-if="getItemImage(shopItem)" class="h-10 w-10 rounded-full overflow-hidden">
                                                    <img :src="getItemImage(shopItem)" :alt="getItemName(shopItem)" class="w-full h-full object-cover" />
                                                </div>
                                                <div v-else class="h-10 w-10 rounded-full bg-medieval-bronze flex items-center justify-center">
                                                    <i class="fas fa-store text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ getItemName(shopItem) }}</div>
                                                <div class="text-sm text-gray-500">{{ getItemRarity(shopItem) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ formatPrice(shopItem.gold_price, shopItem.coin_price) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            Nível {{ shopItem.min_level }}{{ shopItem.max_level ? '-' + shopItem.max_level : '+' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <span :class="shopItem.is_available ? 'text-green-600' : 'text-red-600'" class="text-xs">
                                                <i :class="shopItem.is_available ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1"></i>
                                                {{ shopItem.is_available ? 'Disponível' : 'Indisponível' }}
                                            </span>
                                            <span v-if="shopItem.is_featured" class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                <i class="fas fa-star mr-1"></i> Destaque
                                            </span>
                                            <span v-if="shopItem.is_limited_time" class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                                <i class="fas fa-clock mr-1"></i> Tempo Limitado
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <Link
                                                :href="route('admin.shop.show', { shopItem: shopItem.uuid })"
                                                class="text-medieval-bronze hover:text-medieval-gold"
                                                title="Ver detalhes"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="route('admin.shop.edit', { shopItem: shopItem.uuid })"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="Editar"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Estado vazio -->
                <div v-if="!shopItems.data || shopItems.data.length === 0" class="card-medieval">
                    <div class="text-center py-12">
                        <i class="fas fa-store text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum item da loja encontrado</h3>
                        <p class="text-gray-500 mb-4">Comece criando seu primeiro item da loja.</p>
                        <Link
                            :href="route('admin.shop.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Criar Item da Loja
                        </Link>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="shopItems.links && shopItems.links.length > 1" class="mt-6">
                    <nav class="flex justify-center">
                        <div class="flex space-x-1">
                            <Link
                                v-for="link in shopItems.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-2 text-sm border rounded-md',
                                    link.active
                                        ? 'bg-medieval-bronze text-white border-medieval-bronze'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-amber-50',
                                    !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:border-medieval-bronze'
                                ]"
                            />
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.admin-layout *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
