<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    if (!date) return 'Nunca';
    return new Date(date).toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="`Perfil de ${profile.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Perfil de {{ profile.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <!-- Cabeçalho do Perfil -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <div class="flex items-center space-x-6">
                        <img
                            :src="profile.avatar_url"
                            :alt="`Avatar de ${profile.name}`"
                            class="h-24 w-24 rounded-full object-cover"
                        />
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ profile.name }}</h1>
                            <div class="flex items-center space-x-3 mt-2">
                                <!-- Badge VIP -->
                                <span
                                    v-if="profile.is_vip"
                                    class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800"
                                >
                                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Membro VIP
                                </span>
                                
                                <!-- Badge Staff -->
                                <span
                                    v-if="profile.is_staff"
                                    class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800"
                                >
                                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Equipe do Jogo
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biografia -->
                <div v-if="profile.biography" class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Biografia</h3>
                    <div 
                        class="prose prose-gray max-w-none"
                        v-html="profile.biography"
                    ></div>
                </div>

                <!-- Estatísticas -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Estatísticas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ profile.characters_count }}</div>
                            <div class="text-sm text-gray-600">Personagens</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ profile.highest_level_character || 0 }}</div>
                            <div class="text-sm text-gray-600">Nível Mais Alto</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">Membro desde</div>
                            <div class="text-lg text-gray-900">{{ formatDate(profile.created_at) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Informações</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Última atividade</dt>
                            <dd class="text-sm text-gray-900">{{ formatDate(profile.last_seen_at) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Status do perfil</dt>
                            <dd class="text-sm">
                                <span class="text-green-600 font-medium">Público</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
