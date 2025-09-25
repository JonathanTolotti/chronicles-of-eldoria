<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    permission: Object,
});

const getCategoryColor = (category) => {
    const colors = {
        users: 'bg-blue-100 text-blue-800',
        characters: 'bg-green-100 text-green-800',
        battles: 'bg-red-100 text-red-800',
        system: 'bg-purple-100 text-purple-800',
        roles: 'bg-orange-100 text-orange-800',
        permissions: 'bg-yellow-100 text-yellow-800',
    };
    return colors[category] || 'bg-gray-100 text-gray-800';
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
                                    <i class="fas fa-key mr-3"></i>
                                    {{ permission?.display_name }}
                                </h1>
                                <p class="text-gray-600">{{ permission?.description || 'Sem descrição' }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('admin.permissions.edit', permission?.id)"
                                class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Informações Principais -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Detalhes da Permissão -->
                        <div class="card-medieval">
                            <div class="p-8">
                                <h2 class="text-xl font-semibold text-medieval-bronze mb-6">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Informações da Permissão
                                </h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                        <div class="text-lg font-semibold text-gray-900">{{ permission?.name }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                                        <div class="text-lg font-semibold text-gray-900">{{ permission?.display_name }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                                        <span :class="getCategoryColor(permission?.category)" class="px-3 py-1 text-sm rounded-full">
                                            {{ permission?.category }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <span :class="getStatusColor(permission?.is_active)" class="px-3 py-1 text-sm rounded-full">
                                            {{ permission?.is_active ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                                        <div class="text-gray-900">{{ permission?.description || 'Sem descrição' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles que usam esta permissão -->
                        <div class="card-medieval">
                            <div class="p-8">
                                <h2 class="text-xl font-semibold text-medieval-bronze mb-6">
                                    <i class="fas fa-user-shield mr-2"></i>
                                    Roles que usam esta permissão ({{ permission?.roles?.length || 0 }})
                                </h2>
                                
                                <div v-if="permission?.roles && permission.roles.length > 0" class="space-y-4">
                                    <div v-for="role in permission.roles" :key="role.id" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center" :style="{ backgroundColor: role.color + '20', color: role.color }">
                                                    <i :class="role.icon || 'fas fa-user-shield'"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ role.display_name }}</div>
                                                <div class="text-sm text-gray-500">{{ role.name }}</div>
                                                <div class="text-xs text-gray-400">Nível {{ role.level }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span :class="role.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs rounded-full">
                                                {{ role.is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            <Link
                                                :href="route('admin.roles.show', role.id)"
                                                class="text-medieval-bronze hover:text-medieval-gold"
                                                title="Ver role"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                                
                                <div v-else class="text-center py-8">
                                    <i class="fas fa-user-shield text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Nenhum role usa esta permissão</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Estatísticas -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Estatísticas
                                </h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Roles</span>
                                        <span class="text-lg font-semibold text-gray-900">{{ permission?.roles?.length || 0 }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Categoria</span>
                                        <span class="text-lg font-semibold text-gray-900">{{ permission?.category }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Status</span>
                                        <span :class="getStatusColor(permission?.is_active)" class="px-2 py-1 text-xs rounded-full">
                                            {{ permission?.is_active ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Técnicas -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-cog mr-2"></i>
                                    Informações Técnicas
                                </h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nome Técnico</label>
                                        <div class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ permission?.name }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Categoria</label>
                                        <div class="text-sm text-gray-900">{{ permission?.category }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Criado em</label>
                                        <div class="text-sm text-gray-900">{{ new Date(permission?.created_at).toLocaleDateString('pt-BR') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Atualizado em</label>
                                        <div class="text-sm text-gray-900">{{ new Date(permission?.updated_at).toLocaleDateString('pt-BR') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
