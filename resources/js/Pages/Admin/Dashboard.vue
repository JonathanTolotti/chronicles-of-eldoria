<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: Object,
    stats: Object,
    recent_users: Array,
    top_characters: Array,
});
</script>

<template>
    <Head title="Painel Administrativo" />

    <AdminLayout>
        <div class="py-6 dashboard-admin">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Estatísticas Principais -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-medieval-bronze mb-4">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Estatísticas do Reino
                    </h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Total de Usuários -->
                        <div class="card-medieval p-6 border-l-4 border-medieval-bronze">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-3xl text-medieval-bronze"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total de Usuários</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.total_users }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Personagens -->
                        <div class="card-medieval p-6 border-l-4 border-medieval-gold">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-ninja text-3xl text-medieval-gold"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total de Personagens</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.total_characters }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Batalhas -->
                        <div class="card-medieval p-6 border-l-4 border-red-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sword text-3xl text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total de Batalhas</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.total_battles }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Membros da Equipe -->
                        <div class="card-medieval p-6 border-l-4 border-medieval-gold">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-crown text-3xl text-medieval-gold"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Membros da Equipe</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.staff_members }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Usuários VIP -->
                        <div class="card-medieval p-6 border-l-4 border-yellow-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-star text-3xl text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Usuários VIP</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.vip_users }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Usuários Ativos Hoje -->
                        <div class="card-medieval p-6 border-l-4 border-gray-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-3xl text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Ativos Hoje</p>
                                    <p class="text-2xl font-bold text-medieval-bronze">{{ stats.active_users_today }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Usuários Recentes -->
                    <div class="card-medieval">
                        <div class="px-6 py-4 border-b border-medieval-bronze">
                            <h3 class="text-lg font-bold text-medieval-bronze">
                                <i class="fas fa-user-plus mr-2"></i>
                                Usuários Recentes
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="user in recent_users"
                                    :key="user.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">
                                                    {{ user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                            <p class="text-xs text-gray-500">{{ user.email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
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
                                        <span class="text-xs text-gray-500">
                                            {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Personagens -->
                    <div class="card-medieval">
                        <div class="px-6 py-4 border-b border-medieval-bronze">
                            <h3 class="text-lg font-bold text-medieval-bronze">
                                <i class="fas fa-trophy mr-2"></i>
                                Top Personagens
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="(character, index) in top_characters"
                                    :key="character.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">
                                                    {{ index + 1 }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ character.name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ character.user?.name }} • {{ character.class }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Nível {{ character.level }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-medieval-bronze mb-4">
                        <i class="fas fa-bolt mr-2"></i>
                        Ações Rápidas
                    </h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <button class="btn-medieval">
                            <i class="fas fa-users mr-2"></i>
                            Gerenciar Usuários
                        </button>
                        
                        <button class="btn-medieval">
                            <i class="fas fa-user-ninja mr-2"></i>
                            Gerenciar Personagens
                        </button>
                        
                        <button class="btn-medieval">
                            <i class="fas fa-sword mr-2"></i>
                            Gerenciar Batalhas
                        </button>
                        
                        <button class="btn-medieval">
                            <i class="fas fa-cog mr-2"></i>
                            Configurações
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Importar Font Awesome para os ícones */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Aplicar fonte Cinzel em todo o dashboard, exceto ícones */
.dashboard-admin *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>