<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
        <!-- Header Medieval -->
        <header class="bg-medieval-dark text-medieval-gold shadow-lg">
            <div class="container mx-auto px-4 py-4 text-center">
                <Link href="/" class="inline-block">
                    <h1 class="text-3xl font-bold font-medieval-decorative hover:text-medieval-bronze transition-colors">
                        Chronicles of Eldoria
                    </h1>
                </Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-12">
            <div class="max-w-md mx-auto">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-medieval-dark text-medieval-gold py-6 mt-12">
            <div class="container mx-auto px-4 text-center">
                <p class="text-sm">&copy; 2025 Chronicles of Eldoria. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
</template>
