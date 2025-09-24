<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,
    roles: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');
const staffFilter = ref(props.filters?.is_staff || '');
const vipFilter = ref(props.filters?.is_vip || '');
const sortBy = ref(props.filters?.sort_by || 'created_at');
const sortOrder = ref(props.filters?.sort_order || 'desc');

// Debounced search
let searchTimeout;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateFilters();
    }, 300);
});

// Watch other filters
watch([roleFilter, staffFilter, vipFilter, sortBy, sortOrder], () => {
    updateFilters();
});

function updateFilters() {
    const filters = {};
    
    if (search.value && search.value.trim() !== '') filters.search = search.value.trim();
    if (roleFilter.value && roleFilter.value !== '') filters.role = roleFilter.value;
    if (staffFilter.value !== '' && staffFilter.value !== null && staffFilter.value !== undefined) filters.is_staff = staffFilter.value;
    if (vipFilter.value !== '' && vipFilter.value !== null && vipFilter.value !== undefined) filters.is_vip = vipFilter.value;
    if (sortBy.value && sortBy.value !== '') filters.sort_by = sortBy.value;
    if (sortOrder.value && sortOrder.value !== '') filters.sort_order = sortOrder.value;
    
    router.get(route('admin.users.index'), filters, {
        preserveState: true,
        replace: true,
    });
}

function toggleSort(field) {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortOrder.value = 'asc';
    }
}

</script>

<template>
    <Head title="Gerenciar Usuários" />

    <AdminLayout>
        <div class="py-6 dashboard-admin">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-medieval-bronze">
                                <i class="fas fa-users mr-3"></i>
                                Gerenciar Usuários
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Gerencie usuários, roles e permissões do sistema
                            </p>
                        </div>
                        <Link
                            :href="route('admin.users.create')"
                            class="btn-medieval"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Usuário
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                            <!-- Busca -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-search mr-1"></i>
                                    Buscar
                                </label>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Nome ou email..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                />
                            </div>

                            <!-- Filtro por Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    Role
                                </label>
                                <select
                                    v-model="roleFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos os roles</option>
                                    <option
                                        v-for="role in (roles || [])"
                                        :key="role.id"
                                        :value="role.name"
                                    >
                                        {{ role.display_name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Filtro Staff -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-crown mr-1"></i>
                                    Staff
                                </label>
                                <select
                                    v-model="staffFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option value="1">Staff</option>
                                    <option value="0">Não Staff</option>
                                </select>
                            </div>

                            <!-- Filtro VIP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-star mr-1"></i>
                                    VIP
                                </label>
                                <select
                                    v-model="vipFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option value="1">VIP</option>
                                    <option value="0">Não VIP</option>
                                </select>
                            </div>

                            <!-- Ordenação -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-sort mr-1"></i>
                                    Ordenar por
                                </label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="created_at">Data de Criação</option>
                                    <option value="name">Nome</option>
                                    <option value="email">Email</option>
                                    <option value="last_seen_at">Última Atividade</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Usuários -->
                <div class="card-medieval">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="toggleSort('name')">
                                        <i class="fas fa-user mr-1"></i>
                                        Usuário
                                        <i v-if="sortBy === 'name'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        Roles
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="toggleSort('created_at')">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Criado em
                                        <i v-if="sortBy === 'created_at'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-1"></i>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-cog mr-1"></i>
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in (users.data || [])" :key="user.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white text-sm font-bold">
                                                        {{ user.name.charAt(0).toUpperCase() }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                                <div class="text-sm text-gray-500">{{ user.email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            <span
                                                v-for="role in (user.roles || [])"
                                                :key="role.id"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                :style="{ backgroundColor: role.color + '20', color: role.color }"
                                            >
                                                <i :class="role.icon" class="mr-1"></i>
                                                {{ role.display_name }}
                                            </span>
                                            <span v-if="(user.roles || []).length === 0" class="text-gray-400 text-sm">
                                                Sem roles
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            <span
                                                v-if="user.is_staff"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                                            >
                                                <i class="fas fa-crown mr-1"></i>
                                                Staff
                                            </span>
                                            <span
                                                v-if="user.is_vip"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                            >
                                                <i class="fas fa-star mr-1"></i>
                                                VIP
                                            </span>
                                            <span
                                                v-if="!user.is_staff && !user.is_vip"
                                                class="text-gray-400 text-sm"
                                            >
                                                Usuário comum
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <Link
                                                :href="route('admin.users.show', user)"
                                                class="text-medieval-bronze hover:text-medieval-gold"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="route('admin.users.edit', user)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Mostrando {{ users.from ?? 0 }} a {{ users.to ?? 0 }} de {{ users.total ?? 0 }} resultados
                            </div>
                            <div class="flex space-x-2">
                                <Link
                                    v-for="link in (users.links || [])"
                                    :key="link.label"
                                    :href="link.url || '#'"
                                    v-html="link.label"
                                    :class="[
                                        'px-3 py-2 text-sm font-medium rounded-md',
                                        link.active
                                            ? 'bg-medieval-bronze text-white'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Aplicar fonte Cinzel em todo o dashboard, exceto ícones */
.dashboard-admin *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
