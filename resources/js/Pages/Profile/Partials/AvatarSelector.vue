<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    currentAvatar: {
        type: String,
        default: 'default'
    },
    availableAvatars: {
        type: Object,
        required: true,
        default: () => ({
            'default': 'Avatar Padrão',
            'warrior': 'Guerreiro',
            'mage': 'Mago',
            'archer': 'Arqueiro',
            'rogue': 'Ladino',
            'knight': 'Cavaleiro',
            'priest': 'Sacerdote',
            'druid': 'Druida',
            'paladin': 'Paladino',
            'necromancer': 'Necromante',
        })
    }
});

const form = useForm({
    avatar: props.currentAvatar
});

const selectAvatar = (avatarKey) => {
    form.avatar = avatarKey;
    form.post(route('profile.update-profile'), {
        preserveScroll: true,
        onSuccess: () => {
            // Avatar atualizado com sucesso
        }
    });
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-amber-50 p-4 rounded-lg">
            <p class="text-sm text-medieval mb-4"></p>
                Escolha seu avatar para personalizar seu perfil no jogo.
            </p>
            
            <div class="grid grid-cols-5 gap-4">
                <div
                    v-for="(name, key) in (availableAvatars || {})"
                    :key="key"
                    @click="selectAvatar(key)"
                    :class="[
                        'relative cursor-pointer rounded-lg border-2 p-2 transition-all hover:scale-105',
                        currentAvatar === key 
                            ? 'border-medieval-gold bg-medieval-gold/20 shadow-lg' 
                            : 'border-medieval-bronze hover:border-medieval-gold hover:shadow-md'
                    ]"
                >
                    <img
                        :src="`/images/avatars/${key}.png`"
                        :alt="name || 'Avatar'"
                        class="h-16 w-16 rounded-lg object-cover"
                        @error="$event.target.src = '/images/avatars/default.png'"
                    />
                    <div class="mt-1 text-center text-xs text-medieval">
                        {{ name || 'Avatar' }}
                    </div>
                    
                    <!-- Indicador de seleção -->
                    <div
                        v-if="currentAvatar === key"
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
                        :alt="(availableAvatars && availableAvatars[currentAvatar]) || 'Avatar Padrão'"
                        class="h-12 w-12 rounded-lg object-cover border-2 border-medieval-gold"
                        @error="$event.target.src = '/images/avatars/default.png'"
                    />
                    <span class="text-sm text-medieval">{{ (availableAvatars && availableAvatars[currentAvatar]) || 'Avatar Padrão' }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
