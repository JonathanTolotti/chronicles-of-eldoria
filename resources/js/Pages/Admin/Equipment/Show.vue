<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    equipment: Object,
});

const getTypeLabel = (type) => {
    const labels = {
        weapon: 'Arma',
        armor: 'Armadura',
        helmet: 'Capacete',
        shield: 'Escudo',
        boots: 'Botas',
        pants: 'Calças',
    };
    return labels[type] || type;
};

const getTypeIcon = (type) => {
    const icons = {
        weapon: 'fas fa-sword',
        armor: 'fas fa-shield-alt',
        helmet: 'fas fa-hard-hat',
        shield: 'fas fa-shield',
        boots: 'fas fa-shoe-prints',
        pants: 'fas fa-user-tie',
    };
    return icons[type] || 'fas fa-shield-alt';
};

</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-medieval-bronze mb-2">
                                <i :class="getTypeIcon(equipment?.type)" class="mr-3"></i>
                                {{ equipment?.name }}
                            </h1>
                            <p class="text-gray-600">{{ getTypeLabel(equipment?.type) }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link
                                :href="route('admin.equipment.index')"
                                class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <Link
                                :href="route('admin.equipment.edit', { equipment: equipment?.uuid })"
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
                        <!-- Imagem e Informações Básicas -->
                        <div class="card-medieval">
                            <div class="p-8">
                                <div class="flex items-start space-x-6">
                                    <!-- Imagem -->
                                    <div class="flex-shrink-0">
                                        <div v-if="equipment?.image" class="h-32 w-32 rounded-full overflow-hidden">
                                            <img :src="equipment.image" :alt="equipment.name" class="h-full w-full object-cover" />
                                        </div>
                                        <div v-else class="h-32 w-32 rounded-full bg-medieval-bronze flex items-center justify-center">
                                            <i :class="getTypeIcon(equipment?.type)" class="text-white text-4xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <h2 class="text-2xl font-bold text-gray-900">{{ equipment?.name }}</h2>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-medieval-bronze text-white">
                                                {{ getTypeLabel(equipment?.type) }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <i :class="getTypeIcon(equipment?.type)" class="w-5 text-medieval-bronze mr-3"></i>
                                                <span class="text-gray-700">{{ getTypeLabel(equipment?.type) }}</span>
                                            </div>
                                            
                                            <div v-if="equipment?.description" class="flex items-start">
                                                <i class="fas fa-align-left w-5 text-medieval-bronze mr-3 mt-1"></i>
                                                <p class="text-gray-700">{{ equipment.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Estatísticas do Equipamento
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-red-600">{{ equipment?.strength_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">Força</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ equipment?.dexterity_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">Destreza</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ equipment?.constitution_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">Constituição</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-purple-600">{{ equipment?.intelligence_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">Inteligência</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-yellow-600">{{ equipment?.luck_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">Sorte</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-red-500">{{ equipment?.hp_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">HP</div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-500">{{ equipment?.mp_bonus || 0 }}</div>
                                        <div class="text-sm text-gray-600">MP</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Estatísticas Resumidas -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Resumo
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tipo:</span>
                                        <span class="font-medium">{{ getTypeLabel(equipment?.type) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Criado em:</span>
                                        <span class="font-medium">{{ new Date(equipment?.created_at).toLocaleDateString('pt-BR') }}</span>
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

<style scoped>
.admin-layout *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
