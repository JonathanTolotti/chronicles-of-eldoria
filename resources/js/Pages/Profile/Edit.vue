<!-- MEDIEVAL PROFILE PAGE - COMPLETE -->
<template>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
        <!-- Header -->
        <header class="bg-medieval-dark text-medieval-gold shadow-lg">
            <div class="container mx-auto px-4 py-4">
                <!-- Desktop Header -->
                <div class="hidden md:flex justify-between items-center">
                    <div class="flex-1">
                        <Link :href="route('dashboard')" class="text-2xl font-bold text-medieval hover:text-medieval-gold transition-colors">
                            Chronicles of Eldoria
                        </Link>
                    </div>
                    <div class="flex-1 text-center">
                        <h2 class="text-lg font-bold text-medieval">Configurações do Perfil</h2>
                    </div>
                    <div class="flex-1 flex justify-end items-center space-x-4">
                        <!-- Botões -->
                        <Link 
                            :href="route('dashboard')" 
                            class="btn-medieval text-sm text-medieval px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
                        >
                            Voltar ao Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Mobile Header -->
                <div class="md:hidden">
                    <div class="flex justify-between items-center mb-3">
                        <Link :href="route('dashboard')" class="text-lg font-bold text-medieval hover:text-medieval-gold transition-colors">
                            Chronicles of Eldoria
                        </Link>
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 text-medieval-gold hover:bg-medieval-bronze rounded-lg transition-colors duration-200"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    

                    <!-- Menu Mobile -->
                    <div v-show="mobileMenuOpen" class="bg-medieval-bronze rounded-lg p-4 space-y-3">
                        <h3 class="text-center text-medieval-gold font-semibold mb-3">Menu</h3>
                        
                        <Link 
                            :href="route('dashboard')" 
                            class="block w-full btn-medieval text-center py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
                            @click="mobileMenuOpen = false"
                        >
                            🏠 Voltar ao Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4">
            <div class="max-w-4xl mx-auto space-y-6">
                
                    <!-- Informações do Perfil -->
                    <div class="card-medieval">
                        <h3 class="text-xl font-bold text-medieval mb-4 text-medieval-gold">Informações do Perfil</h3>
                        <ProfileInfo :user="user" :character="character" />
                    </div>

                    <!-- Seleção de Avatar -->
                    <div class="card-medieval">
                        <h3 class="text-xl font-bold text-medieval mb-4 text-medieval-gold">Avatar</h3>
                        <AvatarSelector 
                            :current-avatar="character.avatar"
                            :available-avatars="availableAvatars"
                            :character="character"
                        />
                    </div>

                    <!-- Editor de Biografia -->
                    <div class="card-medieval">
                        <h3 class="text-xl font-bold text-medieval mb-4 text-medieval-gold">Biografia</h3>
                        <BiographyEditor 
                            :current-biography="character.biography"
                            :is-public="character.profile_public"
                        />
                    </div>


                <!-- Troca de Senha -->
                <div class="card-medieval">
                    <h3 class="text-xl font-bold text-medieval mb-4 text-medieval-gold">Trocar Senha</h3>
                    <div class="bg-amber-50 p-4 rounded-lg">
                        <p class="text-sm text-medieval mb-4">
                            Altere sua senha para manter sua conta segura.
                        </p>
                        <form @submit.prevent="updatePassword" class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-medieval mb-2">Senha Atual</label>
                                <input
                                    id="current_password"
                                    v-model="passwordForm.current_password"
                                    type="password"
                                    class="w-full px-3 py-2 border border-medieval-bronze rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-medieval-gold text-medieval-dark"
                                    required
                                />
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-medieval mb-2">Nova Senha</label>
                                <input
                                    id="password"
                                    v-model="passwordForm.password"
                                    type="password"
                                    class="w-full px-3 py-2 border border-medieval-bronze rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-medieval-gold text-medieval-dark"
                                    required
                                />
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-medieval mb-2">Confirmar Nova Senha</label>
                                <input
                                    id="password_confirmation"
                                    v-model="passwordForm.password_confirmation"
                                    type="password"
                                    class="w-full px-3 py-2 border border-medieval-bronze rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-medieval-gold text-medieval-dark"
                                    required
                                />
                            </div>
                            <button
                                type="submit"
                                class="btn-medieval px-4 py-2 bg-medieval-gold text-medieval-dark hover:bg-yellow-500 transition-colors"
                            >
                                Atualizar Senha
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-medieval-dark text-medieval-stone text-center py-4">
            <p>&copy; 2025 Chronicles of Eldoria. Todos os direitos reservados.</p>
        </footer>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import AvatarSelector from './Partials/AvatarSelector.vue';
import BiographyEditor from './Partials/BiographyEditor.vue';
import ProfileInfo from './Partials/ProfileInfo.vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
        required: true,
    },
    character: {
        type: Object,
        required: true,
    },
    availableAvatars: {
        type: Object,
        required: true,
    },
});

const mobileMenuOpen = ref(false);

// Formulário de senha
const passwordForm = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Função para atualizar senha
const updatePassword = () => {
    router.post(route('profile.update-password'), passwordForm, {
        onSuccess: () => {
            // Limpar formulário
            passwordForm.current_password = '';
            passwordForm.password = '';
            passwordForm.password_confirmation = '';
        },
        onError: (errors) => {
            console.error('Erro ao atualizar senha:', errors);
        }
    });
};
</script>