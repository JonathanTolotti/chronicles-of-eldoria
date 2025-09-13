<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
          <div class="flex-1"></div>
          <h1 class="text-2xl font-bold font-medieval-decorative text-center flex-1">Chronicles of Eldoria</h1>
          <div class="flex-1 flex justify-end">
            <Link 
              v-if="user" 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Sair
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
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
          
          <div v-else-if="user && (!user.characters || user.characters.length === 0)" class="space-x-4">
            <Link href="/characters/create" class="btn-medieval text-lg px-8 py-3">
              Criar Primeiro Personagem
            </Link>
          </div>
          
          <div v-else-if="user && user.characters && user.characters.length > 0" class="space-x-4">
            <Link href="/characters/select" class="btn-medieval text-lg px-8 py-3">
              Selecionar Personagem
            </Link>
            <Link href="/characters/create" class="btn-medieval text-lg px-8 py-3 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark">
              Criar Novo Personagem
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
    </main>

    <!-- Footer -->
    <footer class="bg-medieval-dark text-medieval-stone text-center py-4 mt-8">
      <p>&copy; 2025 Chronicles of Eldoria. Todos os direitos reservados.</p>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  user: Object,
  stats: Object
})
</script>