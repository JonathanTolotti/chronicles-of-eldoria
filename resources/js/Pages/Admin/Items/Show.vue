<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    item: Object,
});

const getTypeLabel = (type) => {
    const labels = {
        potion: 'Poção',
        weapon: 'Arma',
        armor: 'Armadura',
        accessory: 'Acessório',
        material: 'Material',
    };
    return labels[type] || type;
};

const getRarityLabel = (rarity) => {
    const labels = {
        common: 'Comum',
        uncommon: 'Incomum',
        rare: 'Raro',
        epic: 'Épico',
        legendary: 'Lendário',
    };
    return labels[rarity] || rarity;
};

const getRarityColor = (rarity) => {
    const colors = {
        common: 'bg-gray-100 text-gray-800',
        uncommon: 'bg-green-100 text-green-800',
        rare: 'bg-blue-100 text-blue-800',
        epic: 'bg-purple-100 text-purple-800',
        legendary: 'bg-yellow-100 text-yellow-800',
    };
    return colors[rarity] || 'bg-gray-100 text-gray-800';
};

const getEffectLabel = (effectType) => {
    const labels = {
        heal_hp: 'Curar HP',
        restore_stamina: 'Restaurar Stamina',
        restore_mp: 'Restaurar MP',
        buff_attack: 'Buff de Ataque',
        buff_defense: 'Buff de Defesa',
        buff_speed: 'Buff de Velocidade',
    };
    return labels[effectType] || effectType;
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
                                <i class="fas fa-gem mr-3"></i>
                                {{ item?.name }}
                            </h1>
                            <p class="text-gray-600">{{ getTypeLabel(item?.type) }} • {{ getRarityLabel(item?.rarity) }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link
                                :href="route('admin.items.index')"
                                class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <Link
                                :href="route('admin.items.edit', item?.uuid)"
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
                                        <div v-if="item?.image_path" class="h-32 w-32 rounded-full overflow-hidden">
                                            <img :src="item.image_path" :alt="item.name" class="h-full w-full object-cover" />
                                        </div>
                                        <div v-else class="h-32 w-32 rounded-full bg-medieval-bronze flex items-center justify-center">
                                            <i class="fas fa-gem text-white text-4xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <h2 class="text-2xl font-bold text-gray-900">{{ item?.name }}</h2>
                                            <span :class="getRarityColor(item?.rarity)" class="px-3 py-1 rounded-full text-sm font-medium">
                                                {{ getRarityLabel(item?.rarity) }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <i class="fas fa-tag w-5 text-medieval-bronze mr-3"></i>
                                                <span class="text-gray-700">{{ getTypeLabel(item?.type) }}</span>
                                            </div>
                                            
                                            <div v-if="item?.description" class="flex items-start">
                                                <i class="fas fa-align-left w-5 text-medieval-bronze mr-3 mt-1"></i>
                                                <p class="text-gray-700">{{ item.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Efeitos -->
                        <div v-if="item?.effects && item.effects.length > 0" class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-magic mr-2"></i>
                                    Efeitos
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div
                                        v-for="effect in item.effects"
                                        :key="effect.id"
                                        class="bg-gray-50 p-4 rounded-lg"
                                    >
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ getEffectLabel(effect.effect_type) }}</h4>
                                            <span class="text-lg font-bold text-medieval-bronze">{{ effect.effect_value }}</span>
                                        </div>
                                        <div v-if="effect.effect_duration" class="text-sm text-gray-600">
                                            Duração: {{ effect.effect_duration }} turnos
                                        </div>
                                    </div>
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
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tipo:</span>
                                        <span class="font-medium">{{ getTypeLabel(item?.type) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Raridade:</span>
                                        <span :class="getRarityColor(item?.rarity)" class="px-2 py-1 rounded text-sm font-medium">
                                            {{ getRarityLabel(item?.rarity) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Efeitos:</span>
                                        <span class="font-medium">{{ item?.effects?.length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Criado em:</span>
                                        <span class="font-medium">{{ new Date(item?.created_at).toLocaleDateString('pt-BR') }}</span>
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
