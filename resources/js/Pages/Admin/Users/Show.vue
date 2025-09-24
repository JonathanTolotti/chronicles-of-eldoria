<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
});

function toggleVip() {
    if (props.user?.uuid) {
        router.post(route('admin.users.toggle-vip', props.user));
    }
}

function toggleStaff() {
    if (props.user?.uuid) {
        router.post(route('admin.users.toggle-staff', props.user));
    }
}
</script>

<template>
    <Head :title="`Usuário: ${user.name}`" />

    <AdminLayout>
        <div class="py-6 dashboard-admin">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Link
                                :href="route('admin.users.index')"
                                class="text-medieval-bronze hover:text-medieval-gold mr-4"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <div>
                                <h1 class="text-3xl font-bold text-medieval-bronze">
                                    <i class="fas fa-user mr-3"></i>
                                    {{ user.name }}
                                </h1>
                                <p class="mt-2 text-gray-600">{{ user.email }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('admin.users.edit', user)"
                                class="btn-medieval bg-transparent"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Informações Principais -->
                    <div class="lg:col-span-2">
                        <div class="card-medieval mb-6">
                            <div class="px-6 py-4 border-b border-medieval-bronze">
                                <h3 class="text-lg font-bold text-medieval-bronze">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Informações do Usuário
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                                        <p class="text-lg text-gray-900">{{ user.name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <p class="text-lg text-gray-900">{{ user.email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">UUID</label>
                                        <p class="text-sm text-gray-600 font-mono">{{ user.uuid }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Moedas</label>
                                        <p class="text-lg text-gray-900">{{ user.coin ?? 0 }} <i class="fas fa-coins text-yellow-500"></i></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Membro desde</label>
                                        <p class="text-lg text-gray-900">{{ new Date(user.created_at).toLocaleDateString('pt-BR') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Última atividade</label>
                                        <p class="text-lg text-gray-900">
                                            {{ user.last_seen_at ? new Date(user.last_seen_at).toLocaleDateString('pt-BR') : 'Nunca' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personagens -->
                        <div class="card-medieval">
                            <div class="px-6 py-4 border-b border-medieval-bronze">
                                <h3 class="text-lg font-bold text-medieval-bronze">
                                    <i class="fas fa-user-ninja mr-2"></i>
                                    Personagens ({{ (user.characters || []).length }})
                                </h3>
                            </div>
                            <div class="p-6">
                                <div v-if="(user.characters || []).length === 0" class="text-center text-gray-500 py-8">
                                    <i class="fas fa-user-ninja text-4xl mb-4"></i>
                                    <p>Este usuário ainda não criou personagens</p>
                                </div>
                                <div v-else class="space-y-4">
                                    <div
                                        v-for="character in (user.characters || [])"
                                        :key="character.id"
                                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                    >
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                                                <span class="text-white text-lg font-bold">
                                                    {{ character.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900">{{ character.name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ character.class }} • Nível {{ character.level }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                Criado em {{ new Date(character.created_at).toLocaleDateString('pt-BR') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Status -->
                        <div class="card-medieval">
                            <div class="px-6 py-4 border-b border-medieval-bronze">
                                <h3 class="text-lg font-bold text-medieval-bronze">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Status
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Staff</span>
                                    <button
                                        @click="toggleStaff"
                                        :class="[
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:ring-offset-2',
                                            user.is_staff ? 'bg-medieval-bronze' : 'bg-gray-200'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                                user.is_staff ? 'translate-x-6' : 'translate-x-1'
                                            ]"
                                        />
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">VIP</span>
                                    <button
                                        @click="toggleVip"
                                        :class="[
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:ring-offset-2',
                                            user.is_vip ? 'bg-medieval-bronze' : 'bg-gray-200'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                                user.is_vip ? 'translate-x-6' : 'translate-x-1'
                                            ]"
                                        />
                                    </button>
                                </div>
                                <div v-if="user.is_vip && user.vip_expires_at">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">VIP expira em</label>
                                    <p class="text-sm text-gray-600">{{ new Date(user.vip_expires_at).toLocaleDateString('pt-BR') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="card-medieval">
                            <div class="px-6 py-4 border-b border-medieval-bronze">
                                <h3 class="text-lg font-bold text-medieval-bronze">
                                    <i class="fas fa-user-tag mr-2"></i>
                                    Roles
                                </h3>
                            </div>
                            <div class="p-6">
                                <div v-if="(user.roles || []).length === 0" class="text-center text-gray-500 py-4">
                                    <i class="fas fa-user-tag text-2xl mb-2"></i>
                                    <p class="text-sm">Sem roles atribuídos</p>
                                </div>
                                <div v-else class="space-y-2">
                                    <div
                                        v-for="role in (user.roles || [])"
                                        :key="role.id"
                                        class="flex items-center justify-between p-2 rounded-lg"
                                        :style="{ backgroundColor: role.color + '10' }"
                                    >
                                        <div class="flex items-center">
                                            <i :class="role.icon" class="mr-2" :style="{ color: role.color }"></i>
                                            <span class="text-sm font-medium" :style="{ color: role.color }">
                                                {{ role.display_name }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            Nível {{ role.level }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div class="card-medieval">
                            <div class="px-6 py-4 border-b border-medieval-bronze">
                                <h3 class="text-lg font-bold text-medieval-bronze">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Estatísticas
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Personagens</span>
                                    <span class="text-sm font-medium">{{ (user.characters || []).length }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Moedas</span>
                                    <span class="text-sm font-medium">{{ user.coin ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Membro há</span>
                                    <span class="text-sm font-medium">{{ Math.floor((new Date() - new Date(user.created_at)) / (1000 * 60 * 60 * 24)) }} dias</span>
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
/* Aplicar fonte Cinzel em todo o dashboard, exceto ícones */
.dashboard-admin *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
