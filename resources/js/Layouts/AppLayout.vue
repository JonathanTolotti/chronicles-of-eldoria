<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold font-medieval-decorative">Chronicles of Eldoria</h1>
            <div v-if="user" class="text-sm">
              <span class="text-medieval-bronze">Bem-vindo,</span>
              <span class="font-semibold">{{ user.name }}</span>
            </div>
          </div>
          
          <nav v-if="user" class="flex space-x-4">
            <Link href="/dashboard" class="btn-medieval text-sm">Dashboard</Link>
            <Link href="/character" class="btn-medieval text-sm">Personagem</Link>
            <Link href="/logout" method="post" class="btn-medieval text-sm bg-red-600 hover:bg-red-700 border-red-700">
              Sair
            </Link>
          </nav>
          
          <nav v-else class="flex space-x-4">
            <Link href="/login" class="btn-medieval text-sm">Entrar</Link>
            <Link href="/register" class="btn-medieval text-sm">Registrar</Link>
          </nav>
        </div>
      </div>
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
