<template>
    <GuestLayout>
        <Head title="Email Verificado" />

        <div class="text-center mb-8">
            <h1 class="text-4xl title-medieval mb-4">🎉 Email Verificado!</h1>
            <p class="text-lg text-medieval">Sua jornada épica em Eldoria pode começar!</p>
        </div>

        <div class="card-medieval text-center">
            <div class="mb-6">
                <div class="text-6xl mb-4">⚔️</div>
                <h2 class="text-2xl subtitle-medieval mb-4">Bem-vindo a Eldoria!</h2>
                <p class="text-medieval mb-6">
                    Sua conta foi ativada com sucesso! Agora você pode criar seu personagem e começar sua aventura épica.
                </p>
            </div>

            <div class="space-y-4">
                <Link href="/characters/create" class="btn-medieval text-lg px-8 py-3">
                    🏰 Criar Primeiro Personagem
                </Link>
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
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>
