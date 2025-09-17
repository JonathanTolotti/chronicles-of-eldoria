<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <!-- Desktop Header -->
        <div class="hidden md:flex justify-between items-center">
          <div class="flex-1">
            <h1 class="text-2xl font-bold font-medieval-decorative">Chronicles of Eldoria</h1>
          </div>
          <div class="flex-1 text-center">
            <h2 class="text-lg font-medieval">Dashboard</h2>
          </div>
          <div class="flex-1 flex justify-end items-center space-x-4">
            <!-- Recursos do Jogador -->
            <div class="flex items-center space-x-4 mr-4">
              <!-- Gold -->
              <div class="flex items-center space-x-1 relative group">
                <span class="text-lg">🪙</span>
                <span class="text-sm font-semibold text-yellow-400">{{ formatNumber(character?.gold || 0) }}</span>
                <!-- Tooltip Gold -->
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                  <div class="text-center">
                    <div class="font-semibold mb-1">Gold</div>
                    <div>• Moeda do jogo</div>
                    <div>• Ganha derrotando monstros</div>
                    <div>• Usa para comprar itens</div>
                    <div>• Individual por personagem</div>
                  </div>
                  <!-- Seta do tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-medieval-dark"></div>
                </div>
              </div>
              
              <!-- Coin -->
              <div class="flex items-center space-x-1 relative group">
                <span class="text-lg">💎</span>
                <span class="text-sm font-semibold text-blue-400">{{ formatNumber(user?.coin || 0) }}</span>
                <!-- Tooltip Coin -->
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                  <div class="text-center">
                    <div class="font-semibold mb-1">Coin</div>
                    <div>• Moeda premium</div>
                    <div>• Comprada com dinheiro real</div>
                    <div>• Usa para itens especiais</div>
                    <div>• Compartilhada entre personagens</div>
                  </div>
                  <!-- Seta do tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-medieval-dark"></div>
                </div>
              </div>
            </div>
            
            <!-- Botões -->
            <Link 
              :href="route('characters.select')" 
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Trocar Personagem
            </Link>
            <Link 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Sair
            </Link>
          </div>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden">
          <div class="flex justify-between items-center mb-3">
            <h1 class="text-lg font-bold font-medieval-decorative">Chronicles of Eldoria</h1>
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="p-2 text-medieval-gold hover:bg-medieval-bronze rounded-lg transition-colors duration-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
          
          <!-- Recursos Mobile -->
          <div class="flex justify-center items-center space-x-6 mb-3">
            <!-- Gold -->
            <div class="flex items-center space-x-1">
              <span class="text-lg">🪙</span>
              <span class="text-sm font-semibold text-yellow-400">{{ formatNumber(character?.gold || 0) }}</span>
            </div>
            
            <!-- Coin -->
            <div class="flex items-center space-x-1">
              <span class="text-lg">💎</span>
              <span class="text-sm font-semibold text-blue-400">{{ formatNumber(user?.coin || 0) }}</span>
            </div>
          </div>

          <!-- Menu Mobile -->
          <div v-show="mobileMenuOpen" class="bg-medieval-bronze rounded-lg p-4 space-y-3">
            <h3 class="text-center text-medieval-gold font-semibold mb-3">Menu</h3>
            
            <Link 
              :href="route('characters.select')" 
              class="block w-full btn-medieval text-center py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
              @click="mobileMenuOpen = false"
            >
              🔄 Trocar Personagem
            </Link>
            
            <Link 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="block w-full btn-medieval text-center py-2 bg-transparent border-red-400 text-red-400 hover:bg-red-400 hover:text-white"
              @click="mobileMenuOpen = false"
            >
              🚪 Sair
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Active Events Banner -->
    <div v-if="hasActiveEvents" class="relative overflow-hidden">
      <!-- Background with animation -->
      <div class="absolute inset-0 bg-gradient-to-r from-medieval-gold via-yellow-400 to-medieval-gold animate-pulse"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
      
      <!-- Content -->
      <div class="relative bg-gradient-to-r from-medieval-gold to-yellow-400 text-medieval-dark py-3 px-4 shadow-lg">
        <div class="container mx-auto">
          <div class="flex items-center justify-center space-x-6">
            <!-- Left decoration -->
            <div class="text-2xl animate-bounce">🎉</div>
            
            <!-- Main content -->
            <div class="text-center">
              <div class="font-medieval-decorative font-bold text-lg mb-1 tracking-wide">
                ⚔️ EVENTOS ATIVOS ⚔️
              </div>
              <div class="font-medieval text-sm space-x-4">
                <span 
                  v-for="(event, index) in activeEvents" 
                  :key="event.type" 
                  class="inline-flex items-center px-3 py-1 bg-medieval-dark/20 rounded-full border border-medieval-dark/30 shadow-sm"
                >
                  <span class="font-semibold">{{ event.description }}</span>
                </span>
              </div>
            </div>
            
            <!-- Right decoration -->
            <div class="text-2xl animate-bounce" style="animation-delay: 0.5s">🎉</div>
          </div>
        </div>
        
        <!-- Decorative border -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-medieval-dark/50 to-transparent"></div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 h-full">
        
        <!-- Coluna Esquerda - Herói -->
        <div class="md:col-span-1 lg:col-span-3">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Herói</h3>
            
            <!-- Avatar -->
            <div class="text-center mb-4">
              <div class="w-20 h-20 bg-medieval-bronze rounded-lg mx-auto mb-2 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <span class="text-4xl animate-pulse">⚔️</span>
              </div>
              <h4 class="text-lg font-semibold text-medieval-gold">{{ character?.name || 'Nome do Personagem' }}</h4>
              <p class="text-medieval-brown">{{ getClassName(character?.class) || 'Guerreiro' }}</p>
              <p class="text-medieval">Nível {{ character?.level || 1 }}</p>
              <p class="text-green-600 font-semibold">CP: {{ character?.power || 0 }}</p>
            </div>

            <!-- Barras de Progresso -->
            <div class="space-y-3 mb-6">
              <!-- HP -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">HP:</span>
                  <span class="text-red-600 font-semibold">{{ character?.current_hp || 0 }}/{{ character?.max_hp || 0 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3 shadow-inner">
                  <div class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                       :style="{ width: character?.max_hp ? `${((character?.current_hp || 0) / character.max_hp) * 100}%` : '0%' }"></div>
                </div>
              </div>

              <!-- Stamina -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">Stamina:</span>
                  <span class="text-blue-600 font-semibold">{{ character?.current_stamina || 0 }}/{{ character?.max_stamina || 0 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3 shadow-inner">
                  <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                       :style="{ width: character?.max_stamina ? `${((character?.current_stamina || 0) / character.max_stamina) * 100}%` : '0%' }"></div>
                </div>
              </div>

              <!-- EXP -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">EXP:</span>
                  <span class="text-green-600 font-semibold">{{ character?.experience || 0 }}/{{ character?.experience_to_next_level || 100 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3 shadow-inner">
                  <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                       :style="{ width: character?.experience_to_next_level ? `${((character?.experience || 0) / character.experience_to_next_level) * 100}%` : '0%' }"></div>
                </div>
              </div>
            </div>

            <!-- Stats -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Stats</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-medieval">Força:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.strength || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Destreza:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.dexterity || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Constituição:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.constitution || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Inteligência:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.intelligence || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Sorte:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.luck || 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Treinamento -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Treinamento</h4>
              <div class="bg-amber-50 p-3 rounded-lg hover:bg-amber-100 transition-colors duration-200">
                <p v-if="!character?.training_stat" class="text-sm text-medieval">Nenhum treinamento ativo</p>
                <div v-else class="text-sm text-medieval">
                  <p class="font-semibold flex items-center">
                    <span class="animate-spin mr-2">⚔️</span>
                    Treinando: {{ getStatName(character.training_stat) }}
                  </p>
                  <p class="text-xs">Tempo restante: {{ getTimeRemaining() }}</p>
                  <p class="text-xs text-green-600 font-semibold">+{{ character.training_points || 0 }} pontos</p>
                </div>
                <Link 
                  :href="route('training.index')" 
                  class="btn-medieval text-xs px-3 py-1 mt-2 inline-block hover:scale-105 transition-transform duration-200"
                >
                  {{ character?.training_stat ? 'Ver Treinamento' : 'Iniciar Treino' }}
                </Link>
              </div>
            </div>

            <!-- Pontos de Atributo -->
            <div v-if="character?.stat_points > 0" class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Pontos Livres</h4>
              <div class="bg-gradient-to-r from-amber-50 to-yellow-50 p-3 rounded-lg border-2 border-yellow-300 hover:border-yellow-400 transition-all duration-200">
                <p class="text-sm text-medieval flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-0">
                  <span class="animate-bounce mr-2">✨</span>
                  <span>Você tem <span class="font-bold text-yellow-600">{{ character?.stat_points || 0 }}</span> pontos para distribuir</span>
                </p>
                <Link 
                  :href="route('character.stats.index')" 
                  class="btn-medieval text-xs px-3 py-1 mt-2 inline-block hover:scale-105 transition-transform duration-200 w-full sm:w-auto text-center"
                >
                  Distribuir Pontos
                </Link>
              </div>
            </div>

            <!-- VIP -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">VIP</h4>
              <div class="bg-amber-50 p-3 rounded-lg">
                <p class="text-sm text-medieval">Status VIP: Inativo</p>
                <button class="btn-medieval text-xs px-3 py-1 mt-2">Gerenciar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Centro - Mundo de Eldoria -->
        <div class="md:col-span-1 lg:col-span-6">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Mundo de Eldoria</h3>
            
            <!-- Área Principal do Jogo -->
            <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-lg h-96 flex items-center justify-center mb-4 shadow-2xl hover:shadow-3xl transition-all duration-300">
              <div class="text-center text-gray-300">
                <div class="text-6xl mb-4 animate-pulse">🏰</div>
                <p class="text-lg font-semibold">Área de Jogo Principal</p>
                <p class="text-sm opacity-75">(Mapa, Batalha, Chat)</p>
                <div class="mt-4 flex justify-center space-x-2">
                  <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                  <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping" style="animation-delay: 0.2s"></div>
                  <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping" style="animation-delay: 0.4s"></div>
                </div>
              </div>
            </div>

            <!-- Menu de Funcionalidades -->
            <div class="grid grid-cols-2 gap-4">
              <!-- Explorar - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">🗺️</div>
                <h4 class="subtitle-medieval text-gray-500">Explorar</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Chat - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">💬</div>
                <h4 class="subtitle-medieval text-gray-500">Chat</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Missões - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">📜</div>
                <h4 class="subtitle-medieval text-gray-500">Missões</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Batalha - Ativo -->
              <Link 
                :href="route('battle.index')" 
                class="card-medieval text-center p-4 relative hover:shadow-lg transition-all duration-200 hover:scale-105"
              >
                <div class="text-4xl mb-2 text-red-600 hover:scale-110 transition-transform duration-200">⚔️</div>
                <h4 class="subtitle-medieval text-medieval-gold">Batalha</h4>
                <p class="text-sm text-medieval">Lute contra monstros</p>
              </Link>

              <!-- Inventário - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">🎒</div>
                <h4 class="subtitle-medieval text-gray-500">Inventário</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Loja - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">🏪</div>
                <h4 class="subtitle-medieval text-gray-500">Loja</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Guilda - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">🏰</div>
                <h4 class="subtitle-medieval text-gray-500">Guilda</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Configurações - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative hover:opacity-60 transition-opacity duration-200">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg animate-pulse">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500 hover:scale-110 transition-transform duration-200">⚙️</div>
                <h4 class="subtitle-medieval text-gray-500">Configurações</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Direita - Itens -->
        <div class="md:col-span-2 lg:col-span-3">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Itens</h3>
            
            <!-- Equipamento -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Equipamento</h4>
              <div class="flex flex-col items-center space-y-2">
                <!-- Elmo -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">⛑️</div>
                  <div class="text-xs text-gray-600">T5</div>
                </div>
                
                <!-- Armadura com armas ao lado -->
                <div class="flex items-center space-x-2">
                  <!-- Espada Esquerda -->
                  <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                    <div class="text-2xl mb-1">⚔️</div>
                    <div class="text-xs text-gray-600">T8</div>
                  </div>
                  
                  <!-- Armadura (centro) -->
                  <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                    <div class="text-2xl mb-1">🛡️</div>
                    <div class="text-xs text-gray-600">T7</div>
                  </div>
                  
                  <!-- Escudo Direita -->
                  <div class="bg-gray-100 rounded-lg p-2 text-center w-16 border-2 border-dashed border-gray-300">
                    <div class="text-gray-400 text-lg">+</div>
                    <div class="text-xs text-gray-400">Vazio</div>
                  </div>
                </div>
                
                <!-- Calça -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">👖</div>
                  <div class="text-xs text-gray-600">T7</div>
                </div>
                
                <!-- Bota -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">👢</div>
                  <div class="text-xs text-gray-600">T6</div>
                </div>
              </div>
            </div>

            <!-- Inventário -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Inventário</h4>
              
              <!-- Abas do inventário -->
              <div class="flex">
                <button 
                  @click="activeInventoryTab = 'potions'"
                  class="px-3 py-1 text-xs rounded-tl-lg transition-colors border-2 border-medieval-bronze border-b-0 border-r-0"
                  :class="activeInventoryTab === 'potions' ? 'bg-medieval-gold text-medieval-dark font-semibold border-medieval-gold' : 'bg-medieval-dark text-medieval-gold hover:bg-medieval-bronze'"
                >
                  Poções
                </button>
                <button 
                  disabled
                  class="px-3 py-1 text-xs transition-colors border-2 border-medieval-bronze border-b-0 border-r-0 opacity-50 cursor-not-allowed bg-medieval-dark text-medieval-stone"
                >
                  Equipamentos 🔒
                </button>
                <button 
                  disabled
                  class="px-3 py-1 text-xs rounded-tr-lg transition-colors border-2 border-medieval-bronze border-b-0 opacity-50 cursor-not-allowed bg-medieval-dark text-medieval-stone"
                >
                  Materiais 🔒
                </button>
              </div>
              
              <!-- Conteúdo das abas -->
              <div class="bg-amber-50 rounded-b-lg border-2 border-medieval-bronze border-t-0 p-2">
                <!-- Aba Poções -->
                <div v-if="activeInventoryTab === 'potions'" class="grid grid-cols-4 gap-1">
                  <div v-for="(item, index) in inventory?.potions?.slice(0, 8)" :key="item.id" 
                       class="bg-white rounded border-2 border-medieval-bronze aspect-square flex flex-col items-center justify-center p-1 hover:bg-amber-100 transition-colors cursor-pointer"
                       @click="setHotkeyFromInventory(item)">
                    <img :src="item.item.image_path" :alt="item.item.name" class="w-6 h-6 mb-1" />
                    <span class="text-xs text-medieval-dark">{{ item.quantity }}x</span>
                    
                    <!-- Label do Hotkey -->
                    <div v-if="item.slot_position" class="text-xs text-medieval-gold font-bold">
                      [F{{ item.slot_position }}]
                    </div>
                  </div>
                  
                  <!-- Slots vazios -->
                  <div v-for="i in Math.max(0, 8 - (inventory?.potions?.length || 0))" :key="`empty-${i}`" 
                       class="bg-white rounded border-2 border-dashed border-medieval-bronze aspect-square flex items-center justify-center">
                    <span class="text-gray-400 text-xs">+</span>
                  </div>
                </div>
                
                <!-- Aba Equipamentos -->
                <div v-else-if="activeInventoryTab === 'equipment'" class="grid grid-cols-4 gap-1">
                  <div v-for="(item, index) in inventory?.equipment?.slice(0, 8)" :key="item.id" 
                       class="bg-medieval-dark rounded border-2 border-medieval-bronze aspect-square flex flex-col items-center justify-center p-1 hover:bg-medieval-bronze transition-colors cursor-pointer">
                    <img :src="item.item.image_path" :alt="item.item.name" class="w-6 h-6 mb-1" />
                    <span class="text-xs text-medieval-gold">{{ item.quantity }}x</span>
                  </div>
                  
                  <!-- Slots vazios -->
                  <div v-for="i in Math.max(0, 8 - (inventory?.equipment?.length || 0))" :key="`empty-${i}`" 
                       class="bg-medieval-dark rounded border-2 border-dashed border-medieval-bronze aspect-square flex items-center justify-center">
                    <span class="text-medieval-stone text-xs">+</span>
                  </div>
                </div>
                
                <!-- Aba Materiais -->
                <div v-else-if="activeInventoryTab === 'materials'" class="grid grid-cols-4 gap-1">
                  <div v-for="(item, index) in inventory?.materials?.slice(0, 8)" :key="item.id" 
                       class="bg-medieval-dark rounded border-2 border-medieval-bronze aspect-square flex flex-col items-center justify-center p-1 hover:bg-medieval-bronze transition-colors cursor-pointer">
                    <img :src="item.item.image_path" :alt="item.item.name" class="w-6 h-6 mb-1" />
                    <span class="text-xs text-medieval-gold">{{ item.quantity }}x</span>
                  </div>
                  
                  <!-- Slots vazios -->
                  <div v-for="i in Math.max(0, 8 - (inventory?.materials?.length || 0))" :key="`empty-${i}`" 
                       class="bg-medieval-dark rounded border-2 border-dashed border-medieval-bronze aspect-square flex items-center justify-center">
                    <span class="text-medieval-stone text-xs">+</span>
                  </div>
                </div>
              </div>
              
              <!-- Estatísticas do inventário -->
              <div v-if="stats" class="mt-3 text-xs text-medieval-stone">
                <div class="flex justify-between">
                  <span v-if="activeInventoryTab === 'potions'" class="text-medieval-gold">Poções: {{ stats.potions_count || 0 }}</span>
                  <span v-else-if="activeInventoryTab === 'equipment'" class="text-medieval-gold">Equipamentos: {{ stats.equipment_count || 0 }}</span>
                  <span v-else-if="activeInventoryTab === 'materials'" class="text-medieval-gold">Materiais: {{ stats.materials_count || 0 }}</span>
                </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-medieval-dark text-medieval-stone text-center py-4">
      <p>&copy; 2025 Chronicles of Eldoria. Todos os direitos reservados.</p>
    </footer>

    <!-- Modal de Seleção de Hotkey -->
    <div v-if="showHotkeyModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
      <div class="card-medieval max-w-sm w-full shadow-2xl">
        <!-- Header do Modal -->
        <div class="bg-medieval-dark text-medieval-gold p-4 rounded-t-lg border-b-2 border-medieval-bronze">
          <div class="flex items-center justify-center space-x-2">
            <span class="text-xl">⚔️</span>
            <h3 class="text-lg font-bold title-medieval">Escolher Hotkey</h3>
            <span class="text-xl">⚔️</span>
          </div>
        </div>
        
        <!-- Conteúdo do Modal -->
        <div class="p-4 bg-amber-50">
          <div class="text-center mb-4">
            <p class="text-sm text-medieval mb-1">Selecione em qual slot deseja colocar:</p>
            <div class="flex items-center justify-center space-x-2">
              <img :src="selectedItem?.item?.image_path" :alt="selectedItem?.item?.name" class="w-5 h-5" />
              <span class="text-sm text-medieval-dark font-semibold">{{ selectedItem?.item?.name }}</span>
            </div>
          </div>
          
          <!-- Slots de Hotkey -->
          <div class="grid grid-cols-4 gap-2 mb-4">
            <button 
              v-for="slot in 4" 
              :key="slot"
              @click="assignToHotkey(slot)"
              class="group relative p-2 border-2 border-medieval-bronze rounded-lg hover:border-medieval-gold hover:bg-amber-100 transition-all duration-200 bg-white shadow-sm"
              :class="{ 
                'border-medieval-gold bg-medieval-bronze shadow-md scale-105': selectedHotkey === slot,
                'hover:scale-105': selectedHotkey !== slot
              }"
            >
              <div class="text-center">
                <div class="text-sm font-bold text-medieval-dark mb-1">F{{ slot }}</div>
                
                <!-- Mostrar poção atual no slot -->
                <div v-if="currentHotkeys[slot - 1]" class="space-y-1">
                  <img :src="currentHotkeys[slot - 1].item.image_path" :alt="currentHotkeys[slot - 1].item.name" class="w-4 h-4 mx-auto" />
                  <div class="text-xs text-medieval-stone">
                    {{ currentHotkeys[slot - 1].quantity }}x
                  </div>
                </div>
                <div v-else class="text-xs text-gray-400 mt-2">
                  Vazio
                </div>
              </div>
              
              <!-- Indicador de seleção -->
              <div v-if="selectedHotkey === slot" class="absolute -top-1 -right-1 w-3 h-3 bg-medieval-gold rounded-full flex items-center justify-center">
                <span class="text-xs text-medieval-dark">✓</span>
              </div>
            </button>
          </div>
          
          <!-- Botões de Ação -->
          <div class="flex justify-center space-x-3">
            <button 
              @click="cancelHotkeySelection"
              class="btn-medieval px-4 py-2 bg-transparent border-2 border-medieval-bronze text-medieval-bronze hover:bg-medieval-bronze hover:text-medieval-dark transition-all duration-200"
            >
              Cancelar
            </button>
            <button 
              @click="confirmHotkeySelection"
              class="btn-medieval px-4 py-2 bg-medieval-gold text-medieval-dark hover:bg-yellow-500 transition-all duration-200 shadow-md"
              :disabled="!selectedHotkey"
              :class="{ 'opacity-50 cursor-not-allowed': !selectedHotkey }"
            >
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  user: Object,
  character: Object,
  inventory: Object,
  equipped: Object,
  stats: Object,
  activeEvents: Array,
  hasActiveEvents: Boolean,
});

const currentTime = ref(new Date());
const mobileMenuOpen = ref(false);
let timeInterval = null;

// Modal de seleção de hotkey
const showHotkeyModal = ref(false);
const selectedHotkey = ref(null);
const selectedItem = ref(null);

// Abas do inventário
const activeInventoryTab = ref('potions');

// Hotkeys atuais
const currentHotkeys = ref([null, null, null, null]);

// Carregar hotkeys atuais
const loadCurrentHotkeys = async () => {
  try {
    const response = await axios.get('/api/items/hotkeys');
    console.log('Resposta completa da API:', response.data);
    
    if (response.data.success) {
      console.log('Hotkeys recebidos:', response.data.hotkeys);
      // A API retorna um objeto com chaves 1-4, converter para array 0-3
      const hotkeysArray = [null, null, null, null];
      if (response.data.hotkeys) {
        // response.data.hotkeys é um objeto {1: item, 2: item, 3: item, 4: item}
        for (let slot = 1; slot <= 4; slot++) {
          if (response.data.hotkeys[slot]) {
            console.log(`Colocando item no slot ${slot - 1}:`, response.data.hotkeys[slot]);
            hotkeysArray[slot - 1] = response.data.hotkeys[slot];
          }
        }
      }
      currentHotkeys.value = hotkeysArray;
      console.log('Hotkeys processados:', currentHotkeys.value);
    } else {
      console.log('API retornou success: false');
    }
  } catch (error) {
    console.error('Erro ao carregar hotkeys:', error);
  }
};

// Função para definir hotkey a partir do inventário
const setHotkeyFromInventory = async (item) => {
  await loadCurrentHotkeys();
  selectedItem.value = item;
  showHotkeyModal.value = true;
};

// Função para selecionar hotkey no modal
const assignToHotkey = (slot) => {
  selectedHotkey.value = slot;
};

// Função para cancelar seleção
const cancelHotkeySelection = () => {
  showHotkeyModal.value = false;
  selectedHotkey.value = null;
  selectedItem.value = null;
};

// Função para confirmar seleção
const confirmHotkeySelection = async () => {
  if (!selectedHotkey.value || !selectedItem.value) return;
  
  try {
    const response = await axios.post('/api/items/set-hotkey', {
      character_item_id: selectedItem.value.id,
      slot: selectedHotkey.value
    });
    
    if (response.data.success) {
      await loadCurrentHotkeys();
      router.reload();
    }
  } catch (error) {
    console.error('Erro ao definir hotkey:', error);
  }
  
  cancelHotkeySelection();
};

const getClassName = (className) => {
  switch (className) {
    case 'warrior': return 'Guerreiro';
    case 'mage': return 'Mago';
    case 'archer': return 'Arqueiro';
    case 'rogue': return 'Ladino';
    default: return 'Guerreiro';
  }
};

const getStatName = (stat) => {
  switch (stat) {
    case 'strength': return 'Força';
    case 'dexterity': return 'Destreza';
    case 'constitution': return 'Constituição';
    case 'intelligence': return 'Inteligência';
    case 'luck': return 'Sorte';
    default: return stat;
  }
};

const formatNumber = (number) => {
  if (number >= 1000000) {
    return (number / 1000000).toFixed(1) + 'M';
  } else if (number >= 1000) {
    return (number / 1000).toFixed(1) + 'K';
  }
  return number.toString();
};

const getTimeRemaining = () => {
  if (!props.character?.training_ends_at) return '0 minutos';
  
  const now = currentTime.value;
  const endTime = new Date(props.character.training_ends_at);
  const diff = endTime - now;
  
  if (diff <= 0) return 'Pronto!';
  
  const minutes = Math.floor(diff / 60000);
  const seconds = Math.floor((diff % 60000) / 1000);
  
  return `${minutes}m ${seconds}s`;
};

// Update time every second for real-time display
onMounted(() => {
  timeInterval = setInterval(() => {
    currentTime.value = new Date();
    
    // Check if training is complete
    if (props.character?.training_stat) {
      const endTime = new Date(props.character.training_ends_at);
      if (currentTime.value >= endTime) {
        // Training is complete, refresh the page
        router.reload();
      }
    }
  }, 1000);
});

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval);
  }
});
</script>