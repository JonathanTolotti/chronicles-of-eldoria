<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    shopItem: Object,
});

const getItemName = (shopItem) => {
    return shopItem.item?.name || shopItem.equipment?.name || 'Item não encontrado';
};

const getItemType = (shopItem) => {
    if (shopItem.item) {
        return shopItem.item.type;
    }
    if (shopItem.equipment) {
        return shopItem.equipment.type;
    }
    return 'unknown';
};

const getItemImage = (shopItem) => {
    return shopItem.item?.image_path || shopItem.equipment?.image || null;
};

const getItemRarity = (shopItem) => {
    return shopItem.item?.rarity || 'common';
};

const getRarityColor = (rarity) => {
    const colors = {
        common: 'text-gray-600',
        uncommon: 'text-green-600',
        rare: 'text-blue-600',
        epic: 'text-purple-600',
        legendary: 'text-yellow-600',
    };
    return colors[rarity] || 'text-gray-600';
};

const formatPrice = (goldPrice, coinPrice) => {
    const parts = [];
    if (goldPrice > 0) parts.push(`${goldPrice.toLocaleString()} ouro`);
    if (coinPrice > 0) parts.push(`${coinPrice.toLocaleString()} coins`);
    return parts.join(' + ') || 'Gratuito';
};

const getTypeLabel = (type) => {
    const labels = {
        weapon: 'Arma',
        armor: 'Armadura',
        helmet: 'Capacete',
        shield: 'Escudo',
        boots: 'Botas',
        pants: 'Calças',
        potion: 'Poção',
        material: 'Material',
        accessory: 'Acessório',
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
                                <i class="fas fa-store mr-3"></i>
                                {{ getItemName(shopItem) }}
                            </h1>
                            <p class="text-gray-600">{{ getTypeLabel(getItemType(shopItem)) }} • {{ getRarityLabel(getItemRarity(shopItem)) }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <Link
                                :href="route('admin.shop.index')"
                                class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                            <Link
                                :href="route('admin.shop.edit', { shopItem: shopItem?.uuid })"
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
                                        <div v-if="getItemImage(shopItem)" class="h-32 w-32 rounded-full overflow-hidden">
                                            <img :src="getItemImage(shopItem)" :alt="getItemName(shopItem)" class="h-full w-full object-cover" />
                                        </div>
                                        <div v-else class="h-32 w-32 rounded-full bg-medieval-bronze flex items-center justify-center">
                                            <i class="fas fa-store text-white text-4xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <h2 class="text-2xl font-bold text-gray-900">{{ getItemName(shopItem) }}</h2>
                                            <span :class="getRarityColor(getItemRarity(shopItem))" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100">
                                                {{ getRarityLabel(getItemRarity(shopItem)) }}
                                            </span>
                                            <span v-if="shopItem?.is_featured" class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>
                                                Destaque
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <i class="fas fa-tag w-5 text-medieval-bronze mr-3"></i>
                                                <span class="text-gray-700">{{ getTypeLabel(getItemType(shopItem)) }}</span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <i class="fas fa-coins w-5 text-medieval-bronze mr-3"></i>
                                                <span class="text-gray-700">{{ formatPrice(shopItem?.gold_price, shopItem?.coin_price) }}</span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <i class="fas fa-level-up-alt w-5 text-medieval-bronze mr-3"></i>
                                                <span class="text-gray-700">Nível {{ shopItem?.min_level }}{{ shopItem?.max_level ? '-' + shopItem.max_level : '+' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações da Loja -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Configurações da Loja
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Disponível:</span>
                                            <span :class="shopItem?.is_available ? 'text-green-600' : 'text-red-600'" class="font-medium">
                                                <i :class="shopItem?.is_available ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1"></i>
                                                {{ shopItem?.is_available ? 'Sim' : 'Não' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Em Destaque:</span>
                                            <span :class="shopItem?.is_featured ? 'text-yellow-600' : 'text-gray-600'" class="font-medium">
                                                <i class="fas fa-star mr-1"></i>
                                                {{ shopItem?.is_featured ? 'Sim' : 'Não' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Tempo Limitado:</span>
                                            <span :class="shopItem?.is_limited_time ? 'text-red-600' : 'text-gray-600'" class="font-medium">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ shopItem?.is_limited_time ? 'Sim' : 'Não' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Estoque:</span>
                                            <span class="font-medium">
                                                {{ shopItem?.stock_quantity !== null ? shopItem.stock_quantity.toLocaleString() : 'Ilimitado' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Limite Diário:</span>
                                            <span class="font-medium">
                                                {{ shopItem?.daily_limit || 'Sem limite' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Desconto:</span>
                                            <span class="font-medium">
                                                {{ shopItem?.discount_percentage || 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="shopItem?.available_until" class="mt-4 p-3 bg-red-50 rounded-lg">
                                    <div class="flex items-center text-red-800">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="font-medium">Disponível até:</span>
                                        <span class="ml-2">{{ new Date(shopItem.available_until).toLocaleString('pt-BR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Histórico de Compras -->
                        <div v-if="shopItem?.purchases && shopItem.purchases.length > 0" class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-history mr-2"></i>
                                    Histórico de Compras
                                </h3>
                                <div class="space-y-3">
                                    <div
                                        v-for="purchase in shopItem.purchases.slice(0, 10)"
                                        :key="purchase.id"
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                    >
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-medieval-bronze rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ purchase.character?.name }}</p>
                                                <p class="text-sm text-gray-500">{{ purchase.character?.user?.name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900">{{ purchase.quantity }}x</p>
                                            <p class="text-sm text-gray-500">{{ formatPrice(purchase.gold_paid, purchase.coin_paid) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="shopItem.purchases.length > 10" class="mt-3 text-center">
                                    <span class="text-sm text-gray-500">E mais {{ shopItem.purchases.length - 10 }} compras...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Resumo -->
                        <div class="card-medieval">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Resumo
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tipo:</span>
                                        <span class="font-medium">{{ getTypeLabel(getItemType(shopItem)) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Raridade:</span>
                                        <span :class="getRarityColor(getItemRarity(shopItem))" class="font-medium">{{ getRarityLabel(getItemRarity(shopItem)) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Preço:</span>
                                        <span class="font-medium">{{ formatPrice(shopItem?.gold_price, shopItem?.coin_price) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nível:</span>
                                        <span class="font-medium">{{ shopItem?.min_level }}{{ shopItem?.max_level ? '-' + shopItem.max_level : '+' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Compras:</span>
                                        <span class="font-medium">{{ shopItem?.purchases?.length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Criado em:</span>
                                        <span class="font-medium">{{ new Date(shopItem?.created_at).toLocaleDateString('pt-BR') }}</span>
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
