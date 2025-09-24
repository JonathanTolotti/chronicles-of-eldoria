<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    types: Array,
    rarities: Array,
    effect_types: Object,
});

const form = useForm({
    name: '',
    description: '',
    type: 'potion',
    rarity: 'common',
    image_path: null,
    effects: [],
});

const addEffect = () => {
    form.effects.push({
        effect_type: '',
        effect_value: '',
        effect_duration: '',
    });
};

const removeEffect = (index) => {
    form.effects.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.items.store'));
};

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
                                Criar Novo Item
                            </h1>
                            <p class="text-gray-600">Adicione um novo item ao jogo</p>
                        </div>
                        <Link
                            :href="route('admin.items.index')"
                            class="btn-medieval bg-gray-600 hover:bg-gray-700 text-white"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </Link>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Informações Básicas -->
                    <div class="card-medieval">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informações Básicas
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome do Item *
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

                                <!-- Tipo -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo *
                                    </label>
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.type }"
                                    >
                                        <option v-for="type in types" :key="type" :value="type">
                                            {{ getTypeLabel(type) }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.type }}
                                    </p>
                                </div>

                                <!-- Raridade -->
                                <div>
                                    <label for="rarity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Raridade *
                                    </label>
                                    <select
                                        id="rarity"
                                        v-model="form.rarity"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.rarity }"
                                    >
                                        <option v-for="rarity in rarities" :key="rarity" :value="rarity">
                                            {{ getRarityLabel(rarity) }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.rarity" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.rarity }}
                                    </p>
                                </div>

                                <!-- Upload da Imagem -->
                                <div>
                                    <label for="image_path" class="block text-sm font-medium text-gray-700 mb-2">
                                        Imagem do Item
                                    </label>
                                    <input
                                        id="image_path"
                                        @change="form.image_path = $event.target.files[0]"
                                        type="file"
                                        accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.image_path }"
                                    />
                                    <p v-if="form.errors.image_path" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.image_path }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Formatos aceitos: JPEG, PNG, JPG, GIF, SVG (máx. 2MB)
                                    </p>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.description }"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Efeitos -->
                    <div class="card-medieval">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-medieval-bronze">
                                    <i class="fas fa-magic mr-2"></i>
                                    Efeitos do Item
                                </h3>
                                <button
                                    type="button"
                                    @click="addEffect"
                                    class="btn-medieval bg-medieval-gold hover:bg-medieval-bronze text-white"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Adicionar Efeito
                                </button>
                            </div>

                            <div v-if="form.effects.length > 0" class="space-y-4">
                                <div
                                    v-for="(effect, index) in form.effects"
                                    :key="index"
                                    class="bg-gray-50 p-4 rounded-lg"
                                >
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-medium text-gray-900">Efeito {{ index + 1 }}</h4>
                                        <button
                                            type="button"
                                            @click="removeEffect(index)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Tipo do Efeito -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Tipo do Efeito *
                                            </label>
                                            <select
                                                v-model="effect.effect_type"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            >
                                                <option value="">Selecione um tipo</option>
                                                <option v-for="(label, value) in effect_types" :key="value" :value="value">
                                                    {{ label }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Valor do Efeito -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Valor *
                                            </label>
                                            <input
                                                v-model.number="effect.effect_value"
                                                type="number"
                                                min="1"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            />
                                        </div>

                                        <!-- Duração -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Duração (turnos)
                                            </label>
                                            <input
                                                v-model.number="effect.effect_duration"
                                                type="number"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-8 text-gray-500">
                                <i class="fas fa-magic text-4xl mb-4"></i>
                                <p>Nenhum efeito adicionado ainda.</p>
                                <p class="text-sm">Clique em "Adicionar Efeito" para começar.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-end space-x-4">
                        <Link
                            :href="route('admin.items.index')"
                            class="btn-medieval bg-gray-600 hover:bg-gray-700 text-white"
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
                            {{ form.processing ? 'Salvando...' : 'Salvar Item' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.admin-layout *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
