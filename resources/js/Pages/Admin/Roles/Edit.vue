<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    role: Object,
    permissions: Array,
});

const form = useForm({
    name: props.role?.name || '',
    display_name: props.role?.display_name || '',
    description: props.role?.description || '',
    color: props.role?.color || '#8B4513',
    icon: props.role?.icon || 'fas fa-user-shield',
    level: props.role?.level || 1,
    is_active: props.role?.is_active || true,
    permissions: props.role?.permissions?.map(p => p.id) || [],
});

const submit = () => {
    form.put(route('admin.roles.update', props.role?.id));
};

const predefinedColors = [
    { name: 'Bronze', value: '#8B4513' },
    { name: 'Gold', value: '#FFD700' },
    { name: 'Silver', value: '#C0C0C0' },
    { name: 'Red', value: '#DC2626' },
    { name: 'Blue', value: '#2563EB' },
    { name: 'Green', value: '#16A34A' },
    { name: 'Purple', value: '#9333EA' },
    { name: 'Orange', value: '#EA580C' },
];

const predefinedIcons = [
    'fas fa-user-shield',
    'fas fa-crown',
    'fas fa-star',
    'fas fa-gem',
    'fas fa-shield-alt',
    'fas fa-key',
    'fas fa-lock',
    'fas fa-unlock',
    'fas fa-user-tie',
    'fas fa-user-cog',
];

const groupedPermissions = computed(() => {
    const groups = {};
    props.permissions.forEach(permission => {
        if (!groups[permission.category]) {
            groups[permission.category] = [];
        }
        groups[permission.category].push(permission);
    });
    return groups;
});

</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <Link
                                :href="route('admin.roles.index')"
                                class="text-medieval-bronze hover:text-medieval-gold mr-4"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <div>
                                <h1 class="text-3xl font-bold text-medieval-bronze mb-2">
                                    <i class="fas fa-edit mr-3"></i>
                                    Editar Role
                                </h1>
                                <p class="text-gray-600">Edite as informações do role</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulário -->
                <div class="card-medieval">
                    <div class="p-8">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informações Básicas -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informações Básicas
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Nome -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nome *
                                            </label>
                                            <input
                                                v-model="form.name"
                                                type="text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.name }"
                                                placeholder="admin, moderator, etc."
                                            />
                                            <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.name }}
                                            </div>
                                        </div>

                                        <!-- Display Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nome de Exibição *
                                            </label>
                                            <input
                                                v-model="form.display_name"
                                                type="text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.display_name }"
                                                placeholder="Administrador, Moderador, etc."
                                            />
                                            <div v-if="form.errors.display_name" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.display_name }}
                                            </div>
                                        </div>

                                        <!-- Nível -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nível *
                                            </label>
                                            <input
                                                v-model="form.level"
                                                type="number"
                                                min="1"
                                                max="100"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.level }"
                                            />
                                            <div v-if="form.errors.level" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.level }}
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Status
                                            </label>
                                            <label class="flex items-center">
                                                <input
                                                    v-model="form.is_active"
                                                    type="checkbox"
                                                    class="rounded border-gray-300 text-medieval-bronze focus:ring-medieval-bronze"
                                                />
                                                <span class="ml-2 text-sm text-gray-700">Ativo</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Descrição -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Descrição
                                        </label>
                                        <textarea
                                            v-model="form.description"
                                            rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.description }"
                                            placeholder="Descreva o propósito deste role..."
                                        ></textarea>
                                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.description }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Aparência -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-palette mr-2"></i>
                                        Aparência
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Cor -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Cor *
                                            </label>
                                            <div class="space-y-3">
                                                <input
                                                    v-model="form.color"
                                                    type="color"
                                                    class="w-full h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                    :class="{ 'border-red-500': form.errors.color }"
                                                />
                                                <div class="grid grid-cols-4 gap-2">
                                                    <button
                                                        v-for="color in predefinedColors"
                                                        :key="color.value"
                                                        type="button"
                                                        @click="form.color = color.value"
                                                        class="h-8 rounded border-2 border-gray-300 hover:border-gray-400"
                                                        :class="{ 'border-medieval-bronze': form.color === color.value }"
                                                        :style="{ backgroundColor: color.value }"
                                                        :title="color.name"
                                                    ></button>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.color" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.color }}
                                            </div>
                                        </div>

                                        <!-- Ícone -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Ícone
                                            </label>
                                            <div class="space-y-3">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center" :style="{ backgroundColor: form.color + '20', color: form.color }">
                                                        <i :class="form.icon"></i>
                                                    </div>
                                                    <input
                                                        v-model="form.icon"
                                                        type="text"
                                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                        :class="{ 'border-red-500': form.errors.icon }"
                                                        placeholder="fas fa-user-shield"
                                                    />
                                                </div>
                                                <div class="grid grid-cols-5 gap-2">
                                                    <button
                                                        v-for="icon in predefinedIcons"
                                                        :key="icon"
                                                        type="button"
                                                        @click="form.icon = icon"
                                                        class="h-8 rounded border border-gray-300 hover:border-gray-400 flex items-center justify-center"
                                                        :class="{ 'border-medieval-bronze bg-medieval-bronze text-white': form.icon === icon }"
                                                        :title="icon"
                                                    >
                                                        <i :class="icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.icon" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.icon }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissões -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-key mr-2"></i>
                                        Permissões
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        <div v-for="(permissions, category) in groupedPermissions" :key="category" class="border border-gray-200 rounded-lg p-4">
                                            <h4 class="text-md font-medium text-gray-900 mb-3 capitalize">
                                                {{ category }}
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <label v-for="permission in permissions" :key="permission.id" class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50">
                                                    <input
                                                        v-model="form.permissions"
                                                        :value="permission.id"
                                                        type="checkbox"
                                                        class="rounded border-gray-300 text-medieval-bronze focus:ring-medieval-bronze"
                                                    />
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900">{{ permission.display_name }}</div>
                                                        <div class="text-xs text-gray-500">{{ permission.description }}</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="mt-8 flex justify-end space-x-4">
                                <Link
                                    :href="route('admin.roles.index')"
                                    class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                                >
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white disabled:opacity-50"
                                >
                                    <i class="fas fa-save mr-2"></i>
                                    {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
