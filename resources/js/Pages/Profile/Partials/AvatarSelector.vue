<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    currentAvatar: {
        type: String,
        default: 'default'
    },
    availableAvatars: {
        type: Object,
        required: true
    }
});

// Lista completa de avatares disponíveis (sem o padrão)
const allAvatars = [
    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
    '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
    '21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
    '31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
    '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'
];

const selectAvatar = (avatarKey) => {
    router.post(route('profile.update-profile'), {
        avatar: avatarKey
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            // Avatar atualizado com sucesso
        },
        onError: (errors) => {
            console.error('Erro ao atualizar avatar:', errors);
        }
    });
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-amber-50 p-4 rounded-lg">
            <p class="text-sm text-medieval mb-4">
                Escolha seu avatar para personalizar seu perfil no jogo.
            </p>
            
            <!-- Grid de Avatares -->
            <div class="grid grid-cols-5 gap-4">
                <div
                    v-for="avatarKey in allAvatars"
                    :key="avatarKey"
                    @click="selectAvatar(avatarKey)"
                    :class="[
                        'relative cursor-pointer rounded-lg border-2 p-3 transition-all hover:scale-105 flex items-center justify-center',
                        currentAvatar === avatarKey 
                            ? 'border-medieval-gold bg-medieval-gold/20 shadow-lg' 
                            : 'border-medieval-bronze hover:border-medieval-gold hover:shadow-md'
                    ]"
                >
                    <img
                        :src="`/images/avatars/${avatarKey}.png`"
                        :alt="`Avatar ${avatarKey}`"
                        class="h-16 w-16 rounded-lg object-cover"
                    />
                    
                    <!-- Indicador de seleção -->
                    <div
                        v-if="currentAvatar === avatarKey"
                        class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-medieval-gold flex items-center justify-center shadow-md"
                    >
                        <svg class="h-3 w-3 text-medieval-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Avatar atual -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-medieval text-medieval-gold">Avatar Atual:</h4>
                <div class="mt-2 flex items-center space-x-3">
                    <img
                        :src="`/images/avatars/${currentAvatar || 'default'}.png`"
                        :alt="`Avatar ${currentAvatar}`"
                        class="h-12 w-12 rounded-lg object-cover border-2 border-medieval-gold"
                    />
                    <span class="text-sm text-medieval">{{ `Avatar ${currentAvatar}` }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
