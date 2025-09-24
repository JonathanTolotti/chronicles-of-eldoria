<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    items: Object,
    filters: Object,
    types: Array,
    rarities: Array,
});

const search = ref(props.filters?.search || '');
const filterType = ref(props.filters?.filter_type || '');
const filterRarity = ref(props.filters?.filter_rarity || '');
const sortBy = ref(props.filters?.sort_by || 'name');
const sortOrder = ref(props.filters?.sort_order || 'asc');

const updateFilters = () => {
    const filters = {};
    if (search.value) filters.search = search.value;
    if (filterType.value) filters.filter_type = filterType.value;
    if (filterRarity.value) filters.filter_rarity = filterRarity.value;
    if (sortBy.value) filters.sort_by = sortBy.value;
    if (sortOrder.value) filters.sort_order = sortOrder.value;
    
    router.get(route('admin.items.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

watch([search, filterType, filterRarity, sortBy, sortOrder], updateFilters, { deep: true });

const getTypeLabel = (type) => {
    const labels = {
        potion: 'Poção',
        weapon: 'Arma',
        armor: 'Armadura',
        accessory: 'Acessório',
        material: 'Material',
    };
    return labels[type] || type;
};

const getRarityLabel = (rarity) => {
    const labels = {
        common: 'Comum',
        uncommon: 'Incomum',
        rare: 'Raro',
        epic: 'Épico',
        legendary: 'Lendário',
    };
    return labels[rarity] || rarity;
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
                                <i class="fas fa-gem mr-3"></i>
                                Gerenciamento de Itens
                            </h1>
                            <p class="text-gray-600">Gerencie todos os itens do jogo</p>
                        </div>
                        <Link
                            :href="route('admin.items.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Item
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Busca -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nome ou descrição..."
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
                                    <option v-for="type in types" :key="type" :value="type">
                                        {{ getTypeLabel(type) }}
                                    </option>
                                </select>
                            </div>

                            <!-- Raridade -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Raridade</label>
                                <select
                                    v-model="filterRarity"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todas as raridades</option>
                                    <option v-for="rarity in rarities" :key="rarity" :value="rarity">
                                        {{ getRarityLabel(rarity) }}
                                    </option>
                                </select>
                            </div>

                            <!-- Ordenar por -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="name">Nome</option>
                                    <option value="type">Tipo</option>
                                    <option value="rarity">Raridade</option>
                                    <option value="created_at">Data de Criação</option>
                                </select>
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                <select
                                    v-model="sortOrder"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="asc">Crescente</option>
                                    <option value="desc">Decrescente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Itens -->
                <div class="card-medieval">
                    <div class="p-6">
                        <div v-if="items.data && items.data.length > 0" class="space-y-4">
                            <div
                                v-for="item in items.data"
                                :key="item.id"
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50"
                            >
                                <div class="flex items-center space-x-4">
                                    <!-- Imagem -->
                                    <div class="flex-shrink-0">
                                        <div v-if="item.image_path" class="h-16 w-16 rounded-full overflow-hidden">
                                            <img :src="item.image_path" :alt="item.name" class="h-full w-full object-cover" />
                                        </div>
                                        <div v-else class="h-16 w-16 rounded-full bg-medieval-bronze flex items-center justify-center">
                                            <i class="fas fa-gem text-white text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ item.name }}</h3>
                                            <span :class="getRarityColor(item.rarity)" class="text-sm font-medium">
                                                {{ getRarityLabel(item.rarity) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ getTypeLabel(item.type) }}</p>
                                        <p v-if="item.description" class="text-sm text-gray-500 mt-1">
                                            {{ item.description.substring(0, 100) }}{{ item.description.length > 100 ? '...' : '' }}
                                        </p>
                                        <div v-if="item.effects && item.effects.length > 0" class="mt-2">
                                            <span class="text-xs text-gray-500">
                                                {{ item.effects.length }} efeito(s)
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex space-x-2">
                                    <Link
                                        :href="route('admin.items.show', item.uuid)"
                                        class="text-medieval-bronze hover:text-medieval-gold"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                    <Link
                                        :href="route('admin.items.edit', item.uuid)"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Estado vazio -->
                        <div v-else class="text-center py-12">
                            <i class="fas fa-gem text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum item encontrado</h3>
                            <p class="text-gray-500 mb-4">Comece criando seu primeiro item.</p>
                            <Link
                                :href="route('admin.items.create')"
                                class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Criar Item
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="items.links && items.links.length > 1" class="mt-6">
                    <nav class="flex justify-center">
                        <div class="flex space-x-1">
                            <Link
                                v-for="link in items.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-2 text-sm border rounded-md',
                                    link.active
                                        ? 'bg-medieval-bronze text-white border-medieval-bronze'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50',
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
