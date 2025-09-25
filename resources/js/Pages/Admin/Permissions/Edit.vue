<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    permission: Object,
    categories: Array,
});

const form = useForm({
    name: props.permission?.name || '',
    display_name: props.permission?.display_name || '',
    description: props.permission?.description || '',
    category: props.permission?.category || '',
    is_active: props.permission?.is_active || true,
});

const submit = () => {
    form.put(route('admin.permissions.update', props.permission?.id));
};

const predefinedCategories = [
    'users',
    'characters',
    'battles',
    'system',
    'roles',
    'permissions',
];

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
                                :href="route('admin.permissions.index')"
                                class="text-medieval-bronze hover:text-medieval-gold mr-4"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <div>
                                <h1 class="text-3xl font-bold text-medieval-bronze mb-2">
                                    <i class="fas fa-edit mr-3"></i>
                                    Editar Permissão
                                </h1>
                                <p class="text-gray-600">Edite as informações da permissão</p>
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
                                                placeholder="users.create, battles.manage, etc."
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
                                                placeholder="Criar Usuários, Gerenciar Batalhas, etc."
                                            />
                                            <div v-if="form.errors.display_name" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.display_name }}
                                            </div>
                                        </div>

                                        <!-- Categoria -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Categoria *
                                            </label>
                                            <select
                                                v-model="form.category"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.category }"
                                            >
                                                <option value="">Selecione uma categoria</option>
                                                <option v-for="category in predefinedCategories" :key="category" :value="category">
                                                    {{ category }}
                                                </option>
                                            </select>
                                            <div v-if="form.errors.category" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.category }}
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
                                                <span class="ml-2 text-sm text-gray-700">Ativa</span>
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
                                            placeholder="Descreva o que esta permissão permite fazer..."
                                        ></textarea>
                                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.description }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="mt-8 flex justify-end space-x-4">
                                <Link
                                    :href="route('admin.permissions.index')"
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
