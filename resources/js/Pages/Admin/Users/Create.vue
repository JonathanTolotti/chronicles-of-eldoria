<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    roles: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    is_staff: false,
    is_vip: false,
    vip_expires_at: '',
    roles: [],
});

const showPassword = ref(false);

function submit() {
    form.post(route('admin.users.store'));
}
</script>

<template>
    <Head title="Criar Usuário" />

    <AdminLayout>
        <div class="py-6 dashboard-admin">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
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
                                <i class="fas fa-user-plus mr-3"></i>
                                Criar Novo Usuário
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Adicione um novo usuário ao sistema
                            </p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Formulário Principal -->
                        <div class="lg:col-span-2">
                            <div class="card-medieval">
                                <div class="px-6 py-4 border-b border-medieval-bronze">
                                    <h3 class="text-lg font-bold text-medieval-bronze">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informações Básicas
                                    </h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    <!-- Nome -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-user mr-1"></i>
                                            Nome Completo *
                                        </label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.name }"
                                        />
                                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.name }}
                                        </p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope mr-1"></i>
                                            Email *
                                        </label>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.email }"
                                        />
                                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.email }}
                                        </p>
                                    </div>

                                    <!-- Senha -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>
                                            Senha *
                                        </label>
                                        <div class="relative">
                                            <input
                                                id="password"
                                                v-model="form.password"
                                                :type="showPassword ? 'text' : 'password'"
                                                required
                                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.password }"
                                            />
                                            <button
                                                type="button"
                                                @click="showPassword = !showPassword"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                            >
                                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-400"></i>
                                            </button>
                                        </div>
                                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.password }}
                                        </p>
                                    </div>

                                    <!-- Confirmação de Senha -->
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>
                                            Confirmar Senha *
                                        </label>
                                        <input
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            type="password"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.password_confirmation }"
                                        />
                                        <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.password_confirmation }}
                                        </p>
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
                                        <label for="is_staff" class="text-sm font-medium text-gray-700">
                                            <i class="fas fa-crown mr-1"></i>
                                            Staff
                                        </label>
                                        <input
                                            id="is_staff"
                                            v-model="form.is_staff"
                                            type="checkbox"
                                            class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                        />
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <label for="is_vip" class="text-sm font-medium text-gray-700">
                                            <i class="fas fa-star mr-1"></i>
                                            VIP
                                        </label>
                                        <input
                                            id="is_vip"
                                            v-model="form.is_vip"
                                            type="checkbox"
                                            class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                        />
                                    </div>
                                    <div v-if="form.is_vip">
                                        <label for="vip_expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-calendar mr-1"></i>
                                            VIP expira em
                                        </label>
                                        <input
                                            id="vip_expires_at"
                                            v-model="form.vip_expires_at"
                                            type="date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.vip_expires_at }"
                                        />
                                        <p v-if="form.errors.vip_expires_at" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.vip_expires_at }}
                                        </p>
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
                                    <div v-if="(roles || []).length === 0" class="text-center text-gray-500 py-4">
                                        <i class="fas fa-user-tag text-2xl mb-2"></i>
                                        <p class="text-sm">Nenhum role disponível</p>
                                    </div>
                                    <div v-else class="space-y-3">
                                        <div
                                            v-for="role in (roles || [])"
                                            :key="role.id"
                                            class="flex items-center"
                                        >
                                            <input
                                                :id="`role_${role.id}`"
                                                v-model="form.roles"
                                                :value="role.id"
                                                type="checkbox"
                                                class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                            />
                                            <label
                                                :for="`role_${role.id}`"
                                                class="ml-3 flex items-center text-sm"
                                            >
                                                <i :class="role.icon" class="mr-2" :style="{ color: role.color }"></i>
                                                <span :style="{ color: role.color }">{{ role.display_name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.roles" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.roles }}
                                    </p>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex justify-end space-x-4">
                                <Link
                                    :href="route('admin.users.index')"
                                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 bg-medieval-bronze text-white rounded-md hover:bg-medieval-gold transition-colors duration-200 disabled:opacity-50"
                                >
                                    <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
                                    <i v-else class="fas fa-save mr-2"></i>
                                    {{ form.processing ? 'Criando...' : 'Criar' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
