<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Entrar" />

        <div class="text-center mb-8">
            <h1 class="text-4xl title-medieval mb-4">Entrar</h1>
            <p class="text-lg text-medieval">Acesse sua conta e continue sua jornada épica</p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
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

                <div>
                    <InputLabel for="password" value="Senha" class="subtitle-medieval text-lg" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-2 block w-full px-4 py-3 border-2 border-medieval-bronze rounded-lg focus:border-medieval-gold focus:ring-2 focus:ring-medieval-gold transition-colors"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Digite sua senha"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" class="mr-2" />
                    <span class="text-medieval">Lembrar de mim</span>
                </div>

                <div class="space-y-4">
                    <div class="text-center">
                        <PrimaryButton
                            class="btn-medieval text-lg px-8 py-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Entrar
                        </PrimaryButton>
                    </div>

                    <div class="text-center">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-medieval hover:text-medieval-gold transition-colors text-sm"
                        >
                            Esqueceu sua senha?
                        </Link>
                    </div>
                </div>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-medieval text-lg">
                Não tem uma conta? 
                <Link :href="route('register')" class="text-medieval-gold hover:text-medieval-bronze font-semibold transition-colors">
                    Registre-se aqui
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
