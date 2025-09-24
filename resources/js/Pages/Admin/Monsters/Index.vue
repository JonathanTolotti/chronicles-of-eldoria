<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    monsters: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const level = ref(props.filters?.level || '');
const status = ref(props.filters?.status || '');
const sortBy = ref(props.filters?.sort_by || 'level');
const sortOrder = ref(props.filters?.sort_order || 'asc');

const updateFilters = () => {
    const filters = {};
    if (search.value) filters.search = search.value;
    if (level.value) filters.level = level.value;
    if (status.value) filters.status = status.value;
    if (sortBy.value) filters.sort_by = sortBy.value;
    if (sortOrder.value) filters.sort_order = sortOrder.value;

    router.get(route('admin.monsters.index'), filters, {
        preserveState: true,
        replace: true,
    });
};

const toggleActive = (monster) => {
    router.post(route('admin.monsters.toggle-active', monster.uuid), {}, {
        preserveScroll: true,
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
                            <h1 class="text-3xl font-bold text-medieval-bronze mb-2">Gerenciar Monstros</h1>
                            <p class="text-gray-600">Gerencie todos os monstros do jogo</p>
                        </div>
                        <Link
                            :href="route('admin.monsters.create')"
                            class="bg-medieval-bronze text-white px-4 py-2 rounded-md hover:bg-medieval-gold transition-colors duration-200 flex items-center"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Novo Monstro
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-medieval mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Busca -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                                <input
                                    v-model="search"
                                    @input="updateFilters"
                                    type="text"
                                    placeholder="Nome ou descrição..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                />
                            </div>

                            <!-- Nível -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nível</label>
                                <select
                                    v-model="level"
                                    @change="updateFilters"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option v-for="i in 100" :key="i" :value="i">{{ i }}</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select
                                    v-model="status"
                                    @change="updateFilters"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                            </div>

                            <!-- Ordenar por -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                                <select
                                    v-model="sortBy"
                                    @change="updateFilters"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="level">Nível</option>
                                    <option value="name">Nome</option>
                                    <option value="max_hp">HP Máximo</option>
                                    <option value="attack_power">Ataque</option>
                                    <option value="defense">Defesa</option>
                                    <option value="speed">Velocidade</option>
                                    <option value="gold_reward">Recompensa Ouro</option>
                                    <option value="exp_reward">Recompensa EXP</option>
                                    <option value="created_at">Data de Criação</option>
                                </select>
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                <select
                                    v-model="sortOrder"
                                    @change="updateFilters"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                >
                                    <option value="asc">Crescente</option>
                                    <option value="desc">Decrescente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Monstros -->
                <div class="card-medieval">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monstro
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nível
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stats
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recompensas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="monster in monsters.data || []" :key="monster.id" class="hover:bg-amber-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full overflow-hidden">
                                                <img v-if="monster.image_path" :src="monster.image_path" :alt="monster.name" class="w-full h-full object-cover" />
                                                <div v-else class="w-full h-full bg-medieval-bronze flex items-center justify-center text-white text-lg">🐉</div>
                                            </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ monster.name }}</div>
                                                <div class="text-sm text-gray-500" v-if="monster.description">
                                                    {{ monster.description.substring(0, 50) }}{{ monster.description.length > 50 ? '...' : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Nível {{ monster.level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="space-y-1">
                                            <div>HP: {{ monster.current_hp }}/{{ monster.max_hp }}</div>
                                            <div>ATK: {{ monster.attack_power }}</div>
                                            <div>DEF: {{ monster.defense }}</div>
                                            <div>SPD: {{ monster.speed }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="space-y-1">
                                            <div class="flex items-center">
                                                <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                                {{ monster.gold_reward }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-star text-purple-500 mr-1"></i>
                                                {{ monster.exp_reward }} EXP
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-bolt text-red-500 mr-1"></i>
                                                {{ monster.stamina_cost }} Stamina
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            :class="monster.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        >
                                            <i :class="monster.is_active ? 'fas fa-check-circle mr-1' : 'fas fa-times-circle mr-1'"></i>
                                            {{ monster.is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <Link
                                                :href="route('admin.monsters.show', monster.uuid)"
                                                class="text-medieval-bronze hover:text-medieval-gold"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                            <Link
                                                :href="route('admin.monsters.edit', monster.uuid)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </Link>
                                            <button
                                                @click="toggleActive(monster)"
                                                :class="monster.is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'"
                                                class="transition-colors duration-200"
                                            >
                                                <i :class="monster.is_active ? 'fas fa-pause' : 'fas fa-play'"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6" v-if="monsters.links && monsters.links.length > 3">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link
                                    v-if="monsters.prev_page_url"
                                    :href="monsters.prev_page_url"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-amber-50"
                                >
                                    Anterior
                                </Link>
                                <Link
                                    v-if="monsters.next_page_url"
                                    :href="monsters.next_page_url"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-amber-50"
                                >
                                    Próximo
                                </Link>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Mostrando
                                        <span class="font-medium">{{ monsters.from || 0 }}</span>
                                        até
                                        <span class="font-medium">{{ monsters.to || 0 }}</span>
                                        de
                                        <span class="font-medium">{{ monsters.total || 0 }}</span>
                                        resultados
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <template v-for="(link, index) in monsters.links" :key="index">
                                            <Link
                                                v-if="link.url"
                                                :href="link.url"
                                                v-html="link.label"
                                                :class="[
                                                    link.active 
                                                        ? 'z-10 bg-medieval-bronze border-medieval-bronze text-white' 
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-amber-50',
                                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                                ]"
                                            />
                                            <span
                                                v-else
                                                v-html="link.label"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                            />
                                        </template>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Importar Font Awesome para os ícones */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Aplicar fonte Cinzel em todo o componente, exceto ícones */
.monsters-admin *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}

.card-medieval {
    @apply bg-white rounded-lg shadow-lg border border-medieval-bronze;
}
</style>
