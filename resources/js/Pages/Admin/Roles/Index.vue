<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    roles: Object,
    filters: Object,
    levels: Array,
});

const search = ref(props.filters?.search || '');
const filterLevel = ref(props.filters?.filter_level || '');
const sortBy = ref(props.filters?.sort_by || 'level');
const sortOrder = ref(props.filters?.sort_order || 'asc');

const updateFilters = () => {
    const filters = {};
    if (search.value) filters.search = search.value;
    if (filterLevel.value) filters.filter_level = filterLevel.value;
    if (sortBy.value) filters.sort_by = sortBy.value;
    if (sortOrder.value) filters.sort_order = sortOrder.value;
    
    router.get(route('admin.roles.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

watch([search, filterLevel, sortBy, sortOrder], updateFilters, { deep: true });

const toggleSort = (field) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortOrder.value = 'asc';
    }
};

const getLevelColor = (level) => {
    if (level >= 90) return 'bg-red-100 text-red-800';
    if (level >= 70) return 'bg-orange-100 text-orange-800';
    if (level >= 50) return 'bg-yellow-100 text-yellow-800';
    if (level >= 30) return 'bg-blue-100 text-blue-800';
    return 'bg-green-100 text-green-800';
};

const getStatusColor = (isActive) => {
    return isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
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
                                <i class="fas fa-user-shield mr-3"></i>
                                Gerenciamento de Roles
                            </h1>
                            <p class="text-gray-600">Gerencie os grupos de usuários e suas permissões</p>
                        </div>
                        <Link
                            :href="route('admin.roles.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Role
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Busca -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-search mr-1"></i>
                                    Buscar
                                </label>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nome, display name ou descrição..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                />
                            </div>

                            <!-- Filtro por Nível -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    Nível
                                </label>
                                <select
                                    v-model="filterLevel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos os níveis</option>
                                    <option v-for="level in levels" :key="level" :value="level">
                                        Nível {{ level }}
                                    </option>
                                </select>
                            </div>

                            <!-- Ordenar por -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-sort mr-1"></i>
                                    Ordenar por
                                </label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="level">Nível</option>
                                    <option value="name">Nome</option>
                                    <option value="display_name">Display Name</option>
                                    <option value="created_at">Data de Criação</option>
                                </select>
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-sort-alpha-down mr-1"></i>
                                    Ordem
                                </label>
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

                <!-- Lista de Roles -->
                <div class="card-medieval">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-amber-100" @click="toggleSort('name')">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Role
                                        <i v-if="sortBy === 'name'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-amber-100" @click="toggleSort('level')">
                                        <i class="fas fa-layer-group mr-1"></i>
                                        Nível
                                        <i v-if="sortBy === 'level'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-users mr-1"></i>
                                        Usuários
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-key mr-1"></i>
                                        Permissões
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
                                <tr v-for="role in roles.data || []" :key="role.id" class="hover:bg-amber-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center" :style="{ backgroundColor: role.color + '20', color: role.color }">
                                                    <i :class="role.icon || 'fas fa-user-shield'"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ role.display_name }}</div>
                                                <div class="text-sm text-gray-500">{{ role.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getLevelColor(role.level)" class="px-2 py-1 text-xs rounded-full">
                                            Nível {{ role.level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ role.users_count || 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ role.permissions_count || 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusColor(role.is_active)" class="px-2 py-1 text-xs rounded-full">
                                            {{ role.is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <Link
                                                :href="route('admin.roles.show', role.id)"
                                                class="text-medieval-bronze hover:text-medieval-gold"
                                                title="Ver detalhes"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="route('admin.roles.edit', role.id)"
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
                <div v-if="!roles.data || roles.data.length === 0" class="card-medieval">
                    <div class="text-center py-12">
                        <i class="fas fa-user-shield text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum role encontrado</h3>
                        <p class="text-gray-500 mb-4">Comece criando seu primeiro role.</p>
                        <Link
                            :href="route('admin.roles.create')"
                            class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Criar Role
                        </Link>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="roles.links && roles.links.length > 1" class="mt-6">
                    <nav class="flex justify-center">
                        <div class="flex space-x-1">
                            <Link
                                v-for="link in roles.links"
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
