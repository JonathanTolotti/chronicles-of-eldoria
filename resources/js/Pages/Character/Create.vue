<template>
    <AppLayout :user="user">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl title-medieval mb-4">Criar Personagem</h1>
                <p class="text-lg text-medieval">Escolha sua classe e comece sua jornada épica</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Seleção de Classe -->
                <div class="card-medieval">
                    <h2 class="text-2xl subtitle-medieval mb-6">Escolha sua Classe</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div 
                            v-for="classInfo in classes" 
                            :key="classInfo.id"
                            @click="selectedClass = classInfo.id"
                            :class="[
                                'p-4 border-2 rounded-lg cursor-pointer transition-all',
                                selectedClass === classInfo.id 
                                    ? 'border-medieval-gold bg-amber-100' 
                                    : 'border-medieval-bronze hover:border-medieval-gold'
                            ]"
                        >
                            <h3 class="text-lg font-semibold text-medieval-dark mb-2">{{ classInfo.name }}</h3>
                            <p class="text-sm text-medieval mb-3">{{ classInfo.description }}</p>
                            <div class="text-xs text-medieval">
                                <div>Força: {{ classInfo.stats.strength }}</div>
                                <div>Destreza: {{ classInfo.stats.dexterity }}</div>
                                <div>Constituição: {{ classInfo.stats.constitution }}</div>
                                <div>Inteligência: {{ classInfo.stats.intelligence }}</div>
                                <div>Sorte: {{ classInfo.stats.luck }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulário de Criação -->
                <div class="card-medieval">
                    <h2 class="text-2xl subtitle-medieval mb-6">Detalhes do Personagem</h2>
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Nome do Personagem" class="subtitle-medieval" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-2 block w-full px-4 py-3 border-2 border-medieval-bronze rounded-lg focus:border-medieval-gold focus:ring-2 focus:ring-medieval-gold"
                                placeholder="Digite o nome do seu personagem"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div v-if="selectedClass">
                            <h3 class="text-lg font-semibold text-medieval-dark mb-3">
                                {{ classes.find(c => c.id === selectedClass)?.name }} - Estatísticas Iniciais
                            </h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="flex justify-between">
                                    <span>Força:</span>
                                    <span class="font-semibold">{{ classes.find(c => c.id === selectedClass)?.stats.strength }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Destreza:</span>
                                    <span class="font-semibold">{{ classes.find(c => c.id === selectedClass)?.stats.dexterity }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Constituição:</span>
                                    <span class="font-semibold">{{ classes.find(c => c.id === selectedClass)?.stats.constitution }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Inteligência:</span>
                                    <span class="font-semibold">{{ classes.find(c => c.id === selectedClass)?.stats.intelligence }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sorte:</span>
                                    <span class="font-semibold">{{ classes.find(c => c.id === selectedClass)?.stats.luck }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <Link href="/dashboard" class="btn-medieval bg-transparent border-medieval-bronze text-medieval-brown hover:bg-medieval-bronze hover:text-white">
                                Cancelar
                            </Link>
                            <PrimaryButton 
                                type="submit"
                                class="btn-medieval"
                                :disabled="!selectedClass || form.processing"
                            >
                                Criar Personagem
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const selectedClass = ref(null);

const classes = [
    {
        id: 'warrior',
        name: 'Guerreiro',
        description: 'Especialista em combate corpo a corpo',
        stats: { strength: 15, dexterity: 10, constitution: 14, intelligence: 8, luck: 10 }
    },
    {
        id: 'mage',
        name: 'Mago',
        description: 'Mestre das artes arcanas',
        stats: { strength: 8, dexterity: 10, constitution: 10, intelligence: 16, luck: 12 }
    },
    {
        id: 'archer',
        name: 'Arqueiro',
        description: 'Especialista em combate à distância',
        stats: { strength: 10, dexterity: 16, constitution: 12, intelligence: 10, luck: 14 }
    },
    {
        id: 'rogue',
        name: 'Ladino',
        description: 'Mestre da furtividade e agilidade',
        stats: { strength: 10, dexterity: 15, constitution: 10, intelligence: 12, luck: 16 }
    }
];

const form = useForm({
    name: '',
    class: '',
});

const submit = () => {
    if (!selectedClass.value) return;
    
    form.class = selectedClass.value;
    form.post(route('characters.store'));
};
</script>
