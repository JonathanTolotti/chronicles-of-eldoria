<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Esqueci Minha Senha" />

        <div class="text-center mb-8">
            <h1 class="text-4xl title-medieval mb-4">Esqueci Minha Senha</h1>
            <p class="text-lg text-medieval">Digite seu email para receber um link de redefinição</p>
        </div>

        <div
            v-if="status"
            class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg"
        >
            {{ status }}
        </div>

        <div class="card-medieval">
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="email" value="Email" class="subtitle-medieval text-lg" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full px-4 py-3 border-2 border-medieval-bronze rounded-lg focus:border-medieval-gold focus:ring-2 focus:ring-medieval-gold transition-colors"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Digite seu email"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="text-center">
                    <PrimaryButton
                        class="btn-medieval text-lg px-8 py-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Enviar Link de Redefinição
                    </PrimaryButton>
                </div>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-medieval text-lg">
                Lembrou da senha? 
                <Link :href="route('login')" class="text-medieval-gold hover:text-medieval-bronze font-semibold transition-colors">
                    Faça login aqui
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
