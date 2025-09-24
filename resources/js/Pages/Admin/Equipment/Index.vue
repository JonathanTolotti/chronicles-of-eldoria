<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    equipment: Object,
    filters: Object,
    types: Array,
});

const search = ref(props.filters?.search || '');
const filterType = ref(props.filters?.filter_type || '');
const sortBy = ref(props.filters?.sort_by || 'name');
const sortOrder = ref(props.filters?.sort_order || 'asc');

const updateFilters = () => {
    const filters = {};
    if (search.value) filters.search = search.value;
    if (filterType.value) filters.filter_type = filterType.value;
    if (sortBy.value) filters.sort_by = sortBy.value;
    if (sortOrder.value) filters.sort_order = sortOrder.value;
    
    router.get(route('admin.equipment.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

watch([search, filterType, sortBy, sortOrder], updateFilters, { deep: true });

const getTypeLabel = (type) => {
    const labels = {
        weapon: 'Arma',
        armor: 'Armadura',
        helmet: 'Capacete',
        shield: 'Escudo',
        boots: 'Botas',
        pants: 'Calças',
    };
    return labels[type] || type;
};

const getTypeIcon = (type) => {
    const icons = {
        weapon: 'fas fa-sword',
        armor: 'fas fa-shield-alt',
        helmet: 'fas fa-hard-hat',
        shield: 'fas fa-shield',
        boots: 'fas fa-shoe-prints',
        pants: 'fas fa-user-tie',
    };
    return icons[type] || 'fas fa-shield-alt';
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
                                <i class="fas fa-shield-alt mr-3"></i>
                                Gerenciamento de Equipamentos
                            </h1>
                            <p class="text-gray-600">Gerencie todos os equipamentos do jogo</p>
                        </div>
                        <Link
                            :href="route('admin.equipment.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Equipamento
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                            <!-- Ordenar por -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="name">Nome</option>
                                    <option value="type">Tipo</option>
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

                <!-- Lista de Equipamentos -->
                <div class="card-medieval">
                    <div class="p-6">
                        <div v-if="equipment.data && equipment.data.length > 0" class="space-y-4">
                            <div
                                v-for="equip in equipment.data"
                                :key="equip.id"
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50"
                            >
                                <div class="flex items-center space-x-4">
                                    <!-- Imagem -->
                                    <div class="flex-shrink-0">
                                        <div v-if="equip.image" class="h-16 w-16 rounded-full overflow-hidden">
                                            <img :src="equip.image" :alt="equip.name" class="h-full w-full object-cover" />
                                        </div>
                                        <div v-else class="h-16 w-16 rounded-full bg-medieval-bronze flex items-center justify-center">
                                            <i :class="getTypeIcon(equip.type)" class="text-white text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ equip.name }}</h3>
                                            <span class="text-sm text-gray-600">{{ getTypeLabel(equip.type) }}</span>
                                        </div>
                                        <p v-if="equip.description" class="text-sm text-gray-500 mt-1">
                                            {{ equip.description.substring(0, 100) }}{{ equip.description.length > 100 ? '...' : '' }}
                                        </p>
                                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                            <span><i class="fas fa-fist-raised mr-1"></i> Força: {{ equip.strength_bonus }}</span>
                                            <span><i class="fas fa-running mr-1"></i> Destreza: {{ equip.dexterity_bonus }}</span>
                                            <span><i class="fas fa-heart mr-1"></i> HP: {{ equip.hp_bonus }}</span>
                                            <span><i class="fas fa-magic mr-1"></i> MP: {{ equip.mp_bonus }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex space-x-2">
                                    <Link
                                        :href="route('admin.equipment.show', { equipment: equip.uuid })"
                                        class="text-medieval-bronze hover:text-medieval-gold"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                    <Link
                                        :href="route('admin.equipment.edit', { equipment: equip.uuid })"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Estado vazio -->
                        <div v-else class="text-center py-12">
                            <i class="fas fa-shield-alt text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum equipamento encontrado</h3>
                            <p class="text-gray-500 mb-4">Comece criando seu primeiro equipamento.</p>
                            <Link
                                :href="route('admin.equipment.create')"
                                class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Criar Equipamento
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="equipment.links && equipment.links.length > 1" class="mt-6">
                    <nav class="flex justify-center">
                        <div class="flex space-x-1">
                            <Link
                                v-for="link in equipment.links"
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
