<template>
    <GuestLayout>
        <Head title="Verificação de Email" />

        <div class="text-center mb-8">
            <h1 class="text-4xl title-medieval mb-4">⚔️ Verificação de Email</h1>
            <p class="text-lg text-medieval">Sua jornada épica está quase começando!</p>
        </div>

        <div class="card-medieval text-center">
            <div class="mb-6">
                <div class="text-6xl mb-4">🔮</div>
                <h2 class="text-2xl subtitle-medieval mb-4">Email de Verificação Enviado!</h2>
                <p class="text-medieval mb-6">
                    Enviamos um link de verificação para <strong>{{ user?.email }}</strong>.
                    Clique no link para ativar sua conta e começar sua aventura em Eldoria!
                </p>
                
                <div v-if="$page.props.flash?.message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ $page.props.flash.message }}
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm text-medieval">
                    Não recebeu o email? Verifique sua caixa de spam ou
                </p>
                
                <form @submit.prevent="resend" class="space-y-4">
                    <PrimaryButton 
                        type="submit"
                        class="btn-medieval"
                        :disabled="form.processing"
                    >
                        🔄 Reenviar Email de Verificação
                    </PrimaryButton>
                </form>

                <div class="pt-4 border-t border-medieval-bronze">
                    <Link 
                        href="/logout" 
                        method="post" 
                        class="text-medieval-brown hover:text-medieval-gold transition-colors"
                    >
                        ← Fazer logout e tentar novamente
                    </Link>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <div class="card-medieval">
                <h3 class="text-lg subtitle-medieval mb-4">O que te aguarda em Eldoria:</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-medieval">
                    <div>⚔️ Crie seu personagem épico</div>
                    <div>🏰 Explore terras misteriosas</div>
                    <div>👑 Torne-se uma lenda viva</div>
                    <div>🏆 Conquiste glória eterna</div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({});

const resend = () => {
    form.post(route('verification.resend'));
};
</script>