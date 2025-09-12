<template>
  <AppLayout :user="user">
    <div class="max-w-4xl mx-auto">
      <!-- Hero Section -->
      <div class="text-center mb-12">
        <h1 class="text-6xl title-medieval mb-6">
          Chronicles of Eldoria
        </h1>
        <p class="text-xl subtitle-medieval mb-8">
          Um MMORPG épico onde heróis nascem e lendas são forjadas
        </p>
        
        <div v-if="!user" class="space-x-4">
          <Link href="/register" class="btn-medieval text-lg px-8 py-3">
            Começar Jornada
          </Link>
          <Link href="/login" class="btn-medieval text-lg px-8 py-3 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark">
            Entrar
          </Link>
        </div>
        
        <div v-else class="space-x-4">
          <Link href="/dashboard" class="btn-medieval text-lg px-8 py-3">
            Continuar Aventura
          </Link>
          <Link href="/character" class="btn-medieval text-lg px-8 py-3 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark">
            Meu Personagem
          </Link>
        </div>
      </div>

      <!-- Features Section -->
      <div class="grid md:grid-cols-3 gap-8 mb-12">
        <div class="card-medieval text-center">
          <div class="text-4xl mb-4">⚔️</div>
          <h3 class="text-xl subtitle-medieval mb-2">Combate Épico</h3>
          <p class="text-medieval">
            Enfrente monstros temíveis e outros jogadores em batalhas estratégicas em tempo real.
          </p>
        </div>
        
        <div class="card-medieval text-center">
          <div class="text-4xl mb-4">🏰</div>
          <h3 class="text-xl subtitle-medieval mb-2">Progressão de Personagem</h3>
          <p class="text-medieval">
            Desenvolva seus atributos, colete equipamentos lendários e torne-se o herói mais poderoso.
          </p>
        </div>
        
        <div class="card-medieval text-center">
          <div class="text-4xl mb-4">🗺️</div>
          <h3 class="text-xl subtitle-medieval mb-2">Exploração</h3>
          <p class="text-medieval">
            Explore o vasto reino de Eldoria, descubra tesouros e complete missões épicas.
          </p>
        </div>
      </div>

      <!-- Game Stats -->
      <div class="card-medieval text-center">
        <h2 class="text-2xl title-medieval mb-6">Estatísticas do Jogo</h2>
        <div class="grid md:grid-cols-4 gap-6">
          <div>
            <div class="text-3xl font-bold text-medieval-gold">{{ stats.totalPlayers || 0 }}</div>
            <div class="text-medieval-brown">Jogadores Ativos</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-medieval-gold">{{ stats.totalCharacters || 0 }}</div>
            <div class="text-medieval-brown">Personagens Criados</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-medieval-gold">{{ stats.totalBattles || 0 }}</div>
            <div class="text-medieval-brown">Batalhas Realizadas</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-medieval-gold">{{ stats.highestLevel || 0 }}</div>
            <div class="text-medieval-brown">Maior Nível</div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  user: Object,
  stats: Object
})
</script>
