<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-medieval-dark text-medieval-gold py-6 mt-12">
      <div class="container mx-auto px-4 text-center">
        <p class="text-sm">&copy; 2025 Chronicles of Eldoria. Todos os direitos reservados.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  user: Object
})
</script>
