<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    monster: Object,
});

const form = useForm({
    name: props.monster?.name || '',
    description: props.monster?.description || '',
    level: props.monster?.level || 1,
    max_hp: props.monster?.max_hp || 100,
    attack_power: props.monster?.attack_power || 10,
    defense: props.monster?.defense || 5,
    speed: props.monster?.speed || 8,
    gold_reward: props.monster?.gold_reward || 50,
    exp_reward: props.monster?.exp_reward || 25,
    stamina_cost: props.monster?.stamina_cost || 10,
    image_path: null,
    is_active: props.monster?.is_active || false,
});

const submit = () => {
    if (props.monster?.uuid) {
        form.post(route('admin.monsters.update', props.monster.uuid));
    }
};
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4">
                        <Link
                            :href="route('admin.monsters.show', monster.uuid)"
                            class="text-medieval-bronze hover:text-medieval-gold transition-colors duration-200"
                        >
                            <i class="fas fa-arrow-left text-xl"></i>
                        </Link>
                        <div>
                            <h1 class="text-3xl font-bold text-medieval-bronze">Editar Monstro</h1>
                            <p class="text-gray-600">Edite as informações do monstro</p>
                        </div>
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
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome do Monstro *
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

                                <!-- Descrição -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descrição
                                    </label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.description }"
                                    ></textarea>
                                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <!-- Nível -->
                                <div>
                                    <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nível *
                                    </label>
                                    <input
                                        id="level"
                                        v-model.number="form.level"
                                        type="number"
                                        min="1"
                                        max="100"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.level }"
                                    />
                                    <p v-if="form.errors.level" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.level }}
                                    </p>
                                </div>

                                <!-- Upload da Imagem -->
                                <div>
                                    <label for="image_path" class="block text-sm font-medium text-gray-700 mb-2">
                                        Imagem do Monstro
                                    </label>
                                    <div v-if="monster?.image_path" class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Imagem atual:</p>
                                        <img :src="monster.image_path" :alt="monster.name" class="h-16 w-16 rounded-full object-cover" />
                                    </div>
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
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="card-medieval">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Estatísticas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- HP Máximo -->
                                <div>
                                    <label for="max_hp" class="block text-sm font-medium text-gray-700 mb-2">
                                        HP Máximo *
                                    </label>
                                    <input
                                        id="max_hp"
                                        v-model.number="form.max_hp"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.max_hp }"
                                    />
                                    <p v-if="form.errors.max_hp" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.max_hp }}
                                    </p>
                                </div>

                                <!-- Poder de Ataque -->
                                <div>
                                    <label for="attack_power" class="block text-sm font-medium text-gray-700 mb-2">
                                        Poder de Ataque *
                                    </label>
                                    <input
                                        id="attack_power"
                                        v-model.number="form.attack_power"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.attack_power }"
                                    />
                                    <p v-if="form.errors.attack_power" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.attack_power }}
                                    </p>
                                </div>

                                <!-- Defesa -->
                                <div>
                                    <label for="defense" class="block text-sm font-medium text-gray-700 mb-2">
                                        Defesa *
                                    </label>
                                    <input
                                        id="defense"
                                        v-model.number="form.defense"
                                        type="number"
                                        min="0"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.defense }"
                                    />
                                    <p v-if="form.errors.defense" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.defense }}
                                    </p>
                                </div>

                                <!-- Velocidade -->
                                <div>
                                    <label for="speed" class="block text-sm font-medium text-gray-700 mb-2">
                                        Velocidade *
                                    </label>
                                    <input
                                        id="speed"
                                        v-model.number="form.speed"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.speed }"
                                    />
                                    <p v-if="form.errors.speed" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.speed }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recompensas -->
                    <div class="card-medieval">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                <i class="fas fa-gift mr-2"></i>
                                Recompensas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Recompensa de Ouro -->
                                <div>
                                    <label for="gold_reward" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                        Recompensa de Ouro *
                                    </label>
                                    <input
                                        id="gold_reward"
                                        v-model.number="form.gold_reward"
                                        type="number"
                                        min="0"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.gold_reward }"
                                    />
                                    <p v-if="form.errors.gold_reward" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.gold_reward }}
                                    </p>
                                </div>

                                <!-- Recompensa de EXP -->
                                <div>
                                    <label for="exp_reward" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-star text-purple-500 mr-1"></i>
                                        Recompensa de EXP *
                                    </label>
                                    <input
                                        id="exp_reward"
                                        v-model.number="form.exp_reward"
                                        type="number"
                                        min="0"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.exp_reward }"
                                    />
                                    <p v-if="form.errors.exp_reward" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.exp_reward }}
                                    </p>
                                </div>

                                <!-- Custo de Stamina -->
                                <div>
                                    <label for="stamina_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-bolt text-red-500 mr-1"></i>
                                        Custo de Stamina *
                                    </label>
                                    <input
                                        id="stamina_cost"
                                        v-model.number="form.stamina_cost"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.stamina_cost }"
                                    />
                                    <p v-if="form.errors.stamina_cost" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.stamina_cost }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card-medieval">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                <i class="fas fa-toggle-on mr-2"></i>
                                Status
                            </h3>
                            
                            <div class="flex items-center">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="h-4 w-4 text-medieval-bronze focus:ring-medieval-bronze border-gray-300 rounded"
                                />
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Monstro ativo (disponível para batalhas)
                                </label>
                            </div>
                            <p v-if="form.errors.is_active" class="mt-1 text-sm text-red-600">
                                {{ form.errors.is_active }}
                            </p>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-end space-x-4">
                        <Link
                            :href="route('admin.monsters.show', monster.uuid)"
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
                            {{ form.processing ? 'Salvando...' : 'Salvar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Importar Font Awesome para os ícones */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Aplicar fonte Cinzel em todo o componente, exceto ícones */
.monsters-edit *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}

.card-medieval {
    @apply bg-white rounded-lg shadow-lg border border-medieval-bronze;
}
</style>
