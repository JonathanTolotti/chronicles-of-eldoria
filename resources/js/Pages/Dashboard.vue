<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
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
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full">
        
        <!-- Coluna Esquerda - Herói -->
        <div class="lg:col-span-3">
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
                <p class="text-sm text-medieval flex items-center">
                  <span class="animate-bounce mr-2">✨</span>
                  Você tem <span class="font-bold text-yellow-600">{{ character?.stat_points || 0 }}</span> pontos para distribuir
                </p>
                <Link 
                  :href="route('character.stats.index')" 
                  class="btn-medieval text-xs px-3 py-1 mt-2 inline-block hover:scale-105 transition-transform duration-200"
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
        <div class="lg:col-span-6">
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
        <div class="lg:col-span-3">
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
              <div class="grid grid-cols-4 gap-1">
                <!-- Slots do inventário -->
                <div v-for="i in 20" :key="i" 
                     class="bg-gray-100 rounded border-2 border-dashed border-gray-300 aspect-square flex items-center justify-center">
                  <span v-if="i <= 3" class="text-xs text-gray-600">
                    {{ i === 1 ? 'Po x3' : i === 2 ? 'Ru x1' : 'Flr x5' }}
                  </span>
                  <span v-else class="text-gray-400 text-xs">+</span>
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
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  user: Object,
  character: Object,
});

const currentTime = ref(new Date());
let timeInterval = null;

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