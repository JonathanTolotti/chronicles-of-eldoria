<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    equipment: Object,
    types: Array,
});

const form = useForm({
    name: props.equipment?.name || '',
    description: props.equipment?.description || '',
    type: props.equipment?.type || '',
    image: null,
    strength_bonus: props.equipment?.strength_bonus || 0,
    dexterity_bonus: props.equipment?.dexterity_bonus || 0,
    constitution_bonus: props.equipment?.constitution_bonus || 0,
    intelligence_bonus: props.equipment?.intelligence_bonus || 0,
    luck_bonus: props.equipment?.luck_bonus || 0,
    hp_bonus: props.equipment?.hp_bonus || 0,
    mp_bonus: props.equipment?.mp_bonus || 0,
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

const submit = () => {
    form.post(route('admin.equipment.update', { equipment: props.equipment?.uuid }));
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
                                <i class="fas fa-edit mr-3"></i>
                                Editar Equipamento
                            </h1>
                            <p class="text-gray-600">Modifique as informações do equipamento</p>
                        </div>
                        <Link
                            :href="route('admin.equipment.show', { equipment: equipment?.uuid })"
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
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome do Equipamento *
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Descrição
                                    </label>
                                    <textarea
                                        v-model="form.description"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.description }"
                                    ></textarea>
                                    <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.description }}
                                    </div>
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo do Equipamento *
                                    </label>
                                    <select
                                        v-model="form.type"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.type }"
                                    >
                                        <option value="">Selecione o tipo</option>
                                        <option v-for="type in types" :key="type" :value="type">
                                            {{ getTypeLabel(type) }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.type }}
                                    </div>
                                </div>

                                <!-- Imagem -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Imagem do Equipamento
                                    </label>
                                    
                                    <!-- Preview da imagem atual -->
                                    <div v-if="equipment?.image" class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Imagem atual:</p>
                                        <div class="h-20 w-20 rounded-full overflow-hidden">
                                            <img :src="equipment.image" :alt="equipment.name" class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="form.image = $event.target.files[0]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                        :class="{ 'border-red-500': form.errors.image }"
                                    />
                                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a imagem atual</p>
                                    <div v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.image }}
                                    </div>
                                </div>

                                <!-- Estatísticas -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-semibold text-medieval-bronze mb-4">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Estatísticas do Equipamento
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <!-- Força -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-fist-raised mr-1"></i>
                                                Força
                                            </label>
                                            <input
                                                v-model.number="form.strength_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.strength_bonus }"
                                            />
                                            <div v-if="form.errors.strength_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.strength_bonus }}
                                            </div>
                                        </div>

                                        <!-- Destreza -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-running mr-1"></i>
                                                Destreza
                                            </label>
                                            <input
                                                v-model.number="form.dexterity_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.dexterity_bonus }"
                                            />
                                            <div v-if="form.errors.dexterity_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.dexterity_bonus }}
                                            </div>
                                        </div>

                                        <!-- Constituição -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Constituição
                                            </label>
                                            <input
                                                v-model.number="form.constitution_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.constitution_bonus }"
                                            />
                                            <div v-if="form.errors.constitution_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.constitution_bonus }}
                                            </div>
                                        </div>

                                        <!-- Inteligência -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-brain mr-1"></i>
                                                Inteligência
                                            </label>
                                            <input
                                                v-model.number="form.intelligence_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.intelligence_bonus }"
                                            />
                                            <div v-if="form.errors.intelligence_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.intelligence_bonus }}
                                            </div>
                                        </div>

                                        <!-- Sorte -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-dice mr-1"></i>
                                                Sorte
                                            </label>
                                            <input
                                                v-model.number="form.luck_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.luck_bonus }"
                                            />
                                            <div v-if="form.errors.luck_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.luck_bonus }}
                                            </div>
                                        </div>

                                        <!-- HP -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-heart mr-1"></i>
                                                HP
                                            </label>
                                            <input
                                                v-model.number="form.hp_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.hp_bonus }"
                                            />
                                            <div v-if="form.errors.hp_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.hp_bonus }}
                                            </div>
                                        </div>

                                        <!-- MP -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-magic mr-1"></i>
                                                MP
                                            </label>
                                            <input
                                                v-model.number="form.mp_bonus"
                                                type="number"
                                                min="0"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-medieval-bronze focus:border-transparent"
                                                :class="{ 'border-red-500': form.errors.mp_bonus }"
                                            />
                                            <div v-if="form.errors.mp_bonus" class="mt-1 text-sm text-red-600">
                                                {{ form.errors.mp_bonus }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="flex justify-end space-x-4 mt-8">
                                <Link
                                    :href="route('admin.equipment.show', { equipment: equipment?.uuid })"
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

<style scoped>
.admin-layout *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
