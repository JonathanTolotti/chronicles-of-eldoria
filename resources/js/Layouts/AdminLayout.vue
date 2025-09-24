<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100 admin-layout">
            
            <nav class="relative border-b border-medieval-bronze bg-white shadow-lg">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('admin.dashboard')" class="flex items-center space-x-2">
                                    <span class="text-xl font-bold text-medieval-bronze">Painel Administrativo</span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                    class="text-medieval-bronze hover:text-medieval-gold font-medium"
                                >
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Dashboard
                                </NavLink>
                                
                                <!-- Futuras rotas administrativas -->
                                <NavLink
                                    :href="route('admin.users.index')"
                                    :active="route().current('admin.users.*')"
                                    class="text-gray-600 hover:text-medieval-bronze font-medium"
                                >
                                    <i class="fas fa-users mr-2"></i>
                                    Usuários
                                </NavLink>
                                
                                <NavLink
                                    href="#"
                                    class="text-gray-600 hover:text-medieval-bronze font-medium opacity-60"
                                >
                                    <i class="fas fa-user-ninja mr-2"></i>
                                    Personagens
                                </NavLink>
                                
                                <NavLink
                                    href="#"
                                    class="text-gray-600 hover:text-medieval-bronze font-medium opacity-60"
                                >
                                    <i class="fas fa-sword mr-2"></i>
                                    Batalhas
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Staff Badge -->
                            <div class="mr-4">
                                <span class="inline-flex items-center rounded-full bg-medieval-gold px-3 py-1 text-xs font-medium text-white shadow-lg">
                                    <i class="fas fa-crown mr-1"></i>
                                    Staff
                                </span>
                            </div>
                            
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-medieval-bronze transition duration-150 ease-in-out hover:text-medieval-gold hover:bg-amber-50 focus:outline-none shadow-md"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('dashboard')">
                                            <i class="fas fa-gamepad mr-2"></i>
                                            Voltar ao Jogo
                                        </DropdownLink>
                                        <DropdownLink :href="route('profile.edit')">
                                            <i class="fas fa-user-cog mr-2"></i>
                                            Perfil
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Sair
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-medieval-bronze transition duration-150 ease-in-out hover:bg-amber-100 hover:text-medieval-gold focus:bg-amber-100 focus:text-medieval-gold focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('admin.dashboard')"
                            :active="route().current('admin.dashboard')"
                        >
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-medieval-bronze pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-medieval-bronze">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-600">
                                {{ $page.props.auth.user.email }}
                            </div>
                            <div class="mt-1">
                                <span class="inline-flex items-center rounded-full bg-medieval-gold px-2 py-1 text-xs font-medium text-white">
                                    <i class="fas fa-crown mr-1"></i>
                                    Staff
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')">
                                <i class="fas fa-gamepad mr-2"></i>
                                Voltar ao Jogo
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')">
                                <i class="fas fa-user-cog mr-2"></i>
                                Perfil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Sair
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="relative bg-white shadow-lg border-b border-medieval-bronze"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* Importar Font Awesome para os ícones */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Aplicar fonte Cinzel em todo o layout administrativo, exceto ícones */
.admin-layout *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}
</style>
