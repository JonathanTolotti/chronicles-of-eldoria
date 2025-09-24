<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    items: Array,
    equipment: Array,
});

const form = useForm({
    item_id: '',
    equipment_id: '',
    gold_price: 0,
    coin_price: 0,
    is_available: true,
    stock_quantity: null,
    daily_limit: null,
    min_level: 1,
    max_level: null,
    is_featured: false,
    is_limited_time: false,
    available_until: null,
    discount_percentage: 0,
});

const submit = () => {
    form.post(route('admin.shop.store'));
};

const getItemName = (item) => {
    return item.name;
};

const getEquipmentName = (equipment) => {
    return equipment.name;
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
                                <i class="fas fa-plus mr-3"></i>
                                Adicionar Item à Loja
                            </h1>
                            <p class="text-gray-600">Configure um novo item para venda na loja</p>
                        </div>
                        <Link
                            :href="route('admin.shop.index')"
                            class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </Link>
                    </div>
                </div>

                <!-- Formulário -->
                <div class="card-medieval">
                    <div class="p-8">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Seleção do Item -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-box mr-2"></i>
                                        Seleção do Item
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Selecione <strong>apenas um</strong> item OU equipamento para vender na loja.
                                    </p>
                                    
                                    <!-- Item -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Item
                                        </label>
                                        <select
                                            v-model="form.item_id"
                                            @change="form.equipment_id = ''"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.item_id }"
                                        >
                                            <option value="">Selecione um item</option>
                                            <option v-for="item in items" :key="item.id" :value="item.id">
                                                {{ getItemName(item) }} ({{ getTypeLabel(item.type) }} - {{ getRarityLabel(item.rarity) }})
                                            </option>
                                        </select>
                                        <div v-if="form.errors.item_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.item_id }}
                                        </div>
                                    </div>

                                    <!-- Equipamento -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Equipamento
                                        </label>
                                        <select
                                            v-model="form.equipment_id"
                                            @change="form.item_id = ''"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.equipment_id }"
                                        >
                                            <option value="">Selecione um equipamento</option>
                                            <option v-for="equipment in equipment" :key="equipment.id" :value="equipment.id">
                                                {{ getEquipmentName(equipment) }} ({{ getTypeLabel(equipment.type) }})
                                            </option>
                                        </select>
                                        <div v-if="form.errors.equipment_id" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.equipment_id }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Preços -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-coins mr-2"></i>
                                        Preços
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Preço em Ouro -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-coins mr-1"></i>
                                                Preço em Ouro
                                            </label>
                                            <input
                                                v-model.number="form.gold_price"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.gold_price }"
                                            />
                                            <div v-if="form.errors.gold_price" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.gold_price }}
                                            </div>
                                        </div>

                                        <!-- Preço em Coins -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-coins mr-1"></i>
                                                Preço em Coins
                                            </label>
                                            <input
                                                v-model.number="form.coin_price"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.coin_price }"
                                            />
                                            <div v-if="form.errors.coin_price" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.coin_price }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configurações -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-cogs mr-2"></i>
                                        Configurações
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Estoque -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-boxes mr-1"></i>
                                                Estoque (deixe vazio para ilimitado)
                                            </label>
                                            <input
                                                v-model.number="form.stock_quantity"
                                                type="number"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.stock_quantity }"
                                            />
                                            <div v-if="form.errors.stock_quantity" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.stock_quantity }}
                                            </div>
                                        </div>

                                        <!-- Limite Diário -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-calendar-day mr-1"></i>
                                                Limite Diário por Personagem
                                            </label>
                                            <input
                                                v-model.number="form.daily_limit"
                                                type="number"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.daily_limit }"
                                            />
                                            <div v-if="form.errors.daily_limit" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.daily_limit }}
                                            </div>
                                        </div>

                                        <!-- Nível Mínimo -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-level-up-alt mr-1"></i>
                                                Nível Mínimo
                                            </label>
                                            <input
                                                v-model.number="form.min_level"
                                                type="number"
                                                min="1"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.min_level }"
                                            />
                                            <div v-if="form.errors.min_level" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.min_level }}
                                            </div>
                                        </div>

                                        <!-- Nível Máximo -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-level-up-alt mr-1"></i>
                                                Nível Máximo (opcional)
                                            </label>
                                            <input
                                                v-model.number="form.max_level"
                                                type="number"
                                                min="1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.max_level }"
                                            />
                                            <div v-if="form.errors.max_level" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.max_level }}
                                            </div>
                                        </div>

                                        <!-- Desconto -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-percentage mr-1"></i>
                                                Desconto (%)
                                            </label>
                                            <input
                                                v-model.number="form.discount_percentage"
                                                type="number"
                                                min="0"
                                                max="100"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.discount_percentage }"
                                            />
                                            <div v-if="form.errors.discount_percentage" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.discount_percentage }}
                                            </div>
                                        </div>

                                        <!-- Disponível Até -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-clock mr-1"></i>
                                                Disponível Até (opcional)
                                            </label>
                                            <input
                                                v-model="form.available_until"
                                                type="datetime-local"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.available_until }"
                                            />
                                            <div v-if="form.errors.available_until" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.available_until }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opções -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-toggle-on mr-2"></i>
                                        Opções
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input
                                                v-model="form.is_available"
                                                type="checkbox"
                                                id="is_available"
                                                class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                            />
                                            <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                                Disponível para compra
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                v-model="form.is_featured"
                                                type="checkbox"
                                                id="is_featured"
                                                class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                            />
                                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                                Item em destaque
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                v-model="form.is_limited_time"
                                                type="checkbox"
                                                id="is_limited_time"
                                                class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                            />
                                            <label for="is_limited_time" class="ml-2 block text-sm text-gray-900">
                                                Oferta por tempo limitado
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="flex justify-end space-x-4 mt-8">
                                <Link
                                    :href="route('admin.shop.index')"
                                    class="btn-medieval bg-transparent text-medieval-bronze hover:bg-medieval-bronze hover:text-white"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white disabled:opacity-50"
                                >
                                    <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
                                    <i v-else class="fas fa-save mr-2"></i>
                                    {{ form.processing ? 'Criando...' : 'Criar Item da Loja' }}
                                </button>
                            </div>
                        </form>
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
