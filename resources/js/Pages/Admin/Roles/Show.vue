<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    role: Object,
});

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
                                    <i :class="role?.icon || 'fas fa-user-shield'" class="mr-3" :style="{ color: role?.color }"></i>
                                    {{ role?.display_name }}
                                </h1>
                                <p class="text-gray-600">{{ role?.description || 'Sem descrição' }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('admin.roles.edit', role?.id)"
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
                        <!-- Detalhes do Role -->
                        <div class="card-medieval">
                            <div class="p-8">
                                <h2 class="text-xl font-semibold text-medieval-bronze mb-6">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Informações do Role
                                </h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                        <div class="text-lg font-semibold text-gray-900">{{ role?.name }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                                        <div class="text-lg font-semibold text-gray-900">{{ role?.display_name }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nível</label>
                                        <span :class="getLevelColor(role?.level)" class="px-3 py-1 text-sm rounded-full">
                                            Nível {{ role?.level }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <span :class="getStatusColor(role?.is_active)" class="px-3 py-1 text-sm rounded-full">
                                            {{ role?.is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                                        <div class="text-gray-900">{{ role?.description || 'Sem descrição' }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300" :style="{ backgroundColor: role?.color }"></div>
                                            <span class="text-sm text-gray-600">{{ role?.color }}</span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ícone</label>
                                        <div class="flex items-center space-x-2">
                                            <i :class="role?.icon || 'fas fa-user-shield'" class="text-xl" :style="{ color: role?.color }"></i>
                                            <span class="text-sm text-gray-600">{{ role?.icon || 'fas fa-user-shield' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissões -->
                        <div class="card-medieval">
                            <div class="p-8">
                                <h2 class="text-xl font-semibold text-medieval-bronze mb-6">
                                    <i class="fas fa-key mr-2"></i>
                                    Permissões ({{ role?.permissions?.length || 0 }})
                                </h2>
                                
                                <div v-if="role?.permissions && role.permissions.length > 0" class="space-y-4">
                                    <div v-for="permission in role.permissions" :key="permission.id" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-key text-gray-400"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ permission.display_name }}</div>
                                                <div class="text-sm text-gray-500">{{ permission.name }}</div>
                                                <div class="text-xs text-gray-400">{{ permission.description }}</div>
                                            </div>
                                        </div>
                                        <span :class="getCategoryColor(permission.category)" class="px-2 py-1 text-xs rounded-full">
                                            {{ permission.category }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div v-else class="text-center py-8">
                                    <i class="fas fa-key text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Nenhuma permissão atribuída</p>
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
                                        <span class="text-sm text-gray-600">Usuários</span>
                                        <span class="text-lg font-semibold text-gray-900">{{ role?.users?.length || 0 }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Permissões</span>
                                        <span class="text-lg font-semibold text-gray-900">{{ role?.permissions?.length || 0 }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Nível</span>
                                        <span class="text-lg font-semibold text-gray-900">{{ role?.level }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usuários -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-users mr-2"></i>
                                    Usuários ({{ role?.users?.length || 0 }})
                                </h3>
                                
                                <div v-if="role?.users && role.users.length > 0" class="space-y-3">
                                    <div v-for="user in role.users.slice(0, 5)" :key="user.id" class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ user.email }}</div>
                                        </div>
                                    </div>
                                    
                                    <div v-if="role.users.length > 5" class="text-center">
                                        <span class="text-xs text-gray-500">E mais {{ role.users.length - 5 }} usuários...</span>
                                    </div>
                                </div>
                                
                                <div v-else class="text-center py-4">
                                    <i class="fas fa-users text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Nenhum usuário</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
