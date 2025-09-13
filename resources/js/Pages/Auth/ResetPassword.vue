<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Redefinir Senha" />

        <div class="text-center mb-8">
            <h1 class="text-4xl title-medieval mb-4">Redefinir Senha</h1>
            <p class="text-lg text-medieval">Digite sua nova senha para continuar sua jornada</p>
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

                <div>
                    <InputLabel for="password" value="Nova Senha" class="subtitle-medieval text-lg" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-2 block w-full px-4 py-3 border-2 border-medieval-bronze rounded-lg focus:border-medieval-gold focus:ring-2 focus:ring-medieval-gold transition-colors"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Digite sua nova senha"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmar Nova Senha"
                        class="subtitle-medieval text-lg"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-2 block w-full px-4 py-3 border-2 border-medieval-bronze rounded-lg focus:border-medieval-gold focus:ring-2 focus:ring-medieval-gold transition-colors"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirme sua nova senha"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="text-center">
                    <PrimaryButton
                        class="btn-medieval text-lg px-8 py-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Redefinir Senha
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
