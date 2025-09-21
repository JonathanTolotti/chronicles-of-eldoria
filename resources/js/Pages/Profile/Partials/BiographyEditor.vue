<script setup>
import { router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const props = defineProps({
    currentBiography: {
        type: String,
        default: ''
    },
    isPublic: {
        type: Boolean,
        default: true
    }
});

const form = reactive({
    biography: props.currentBiography,
    profile_public: props.isPublic
});

const isProcessing = ref(false);
const showSuccess = ref(false);

const updateBiography = () => {
    isProcessing.value = true;
    showSuccess.value = false;
    
    router.post(route('profile.update-profile'), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            isProcessing.value = false;
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 3000);
        },
        onError: (errors) => {
            isProcessing.value = false;
            console.error('Erro ao salvar biografia:', errors);
        }
    });
};

const allowedTags = [
    'p', 'br', 'strong', 'em', 'u', 'ol', 'ul', 'li', 
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
];
</script>

<template>
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-medium text-medieval">Biografia</h3>
            <p class="mt-1 text-sm text-medieval">
                Escreva uma breve biografia sobre você. Você pode usar HTML básico para formatação.
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Tags permitidas: {{ allowedTags.join(', ') }}
            </p>
        </div>

        <form @submit.prevent="updateBiography" class="space-y-4">
            <div>
                <label for="biography" class="block text-sm font-medium text-medieval mb-2">Biografia</label>
                <textarea
                    id="biography"
                    v-model="form.biography"
                    rows="6"
                    class="w-full px-3 py-2 border border-medieval-bronze rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-medieval-gold text-medieval-dark"
                    placeholder="Conte um pouco sobre você..."
                ></textarea>
            </div>

            <div class="flex items-center">
                <input
                    id="profile_public"
                    v-model="form.profile_public"
                    type="checkbox"
                    class="h-4 w-4 rounded border-medieval-bronze text-medieval-gold focus:ring-medieval-gold"
                />
                <label for="profile_public" class="ml-2 text-sm text-medieval">
                    Tornar perfil público
                </label>
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    :disabled="isProcessing"
                    class="btn-medieval px-4 py-2 bg-medieval-gold text-medieval-dark hover:bg-yellow-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ isProcessing ? 'Salvando...' : 'Salvar Biografia' }}
                </button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="showSuccess"
                        class="text-sm text-green-600"
                    >
                        Biografia atualizada!
                    </p>
                </Transition>
            </div>
        </form>

        <!-- Preview da biografia -->
        <div v-if="form.biography" class="mt-6">
            <h4 class="text-sm font-medium text-medieval">Preview:</h4>
            <div 
                class="mt-2 rounded-lg border border-medieval-bronze bg-amber-50 p-4 text-sm"
                v-html="form.biography"
            ></div>
        </div>
    </div>
</template>
