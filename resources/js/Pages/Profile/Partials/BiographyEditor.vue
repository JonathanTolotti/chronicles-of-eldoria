<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

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

const form = useForm({
    biography: props.currentBiography,
    profile_public: props.isPublic
});

const updateBiography = () => {
    form.post(route('profile.update-profile'), {
        preserveScroll: true
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
                <InputLabel for="biography" value="Biografia" />
                <textarea
                    id="biography"
                    v-model="form.biography"
                    rows="6"
                    class="mt-1 block w-full rounded-lg border border-medieval-bronze shadow-sm focus:border-medieval-gold focus:ring-medieval-gold text-medieval-dark"
                    placeholder="Conte um pouco sobre você..."
                ></textarea>
                <InputError class="mt-2" :message="form.errors.biography" />
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
                <PrimaryButton :disabled="form.processing">
                    {{ form.processing ? 'Salvando...' : 'Salvar Biografia' }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
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
