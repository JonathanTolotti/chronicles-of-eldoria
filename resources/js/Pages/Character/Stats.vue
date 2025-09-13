<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h1 class="text-2xl font-bold title-medieval">Chronicles of Eldoria</h1>
          </div>
          <div class="flex-1 text-center">
            <h2 class="text-lg font-medieval">Distribuição de Atributos</h2>
          </div>
          <div class="flex-1 flex justify-end space-x-4">
            <Link 
              :href="route('dashboard')" 
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Voltar ao Dashboard
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="max-w-4xl mx-auto">
        
        <!-- Status do Personagem -->
        <div class="card-medieval mb-6">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Status do Personagem</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info Básica -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Informações</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-medieval">Nome:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Classe:</span>
                  <span class="text-medieval-gold font-semibold">{{ getClassName(character?.class) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Nível:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.level }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Pontos Livres:</span>
                  <span class="font-semibold text-green-600 text-lg">{{ character?.stat_points || 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Stats Atuais -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Atributos Atuais</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-medieval">Força:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.strength || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Destreza:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.dexterity || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Constituição:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.constitution || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Inteligência:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.intelligence || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Sorte:</span>
                  <span class="text-medieval-gold font-semibold">{{ character?.luck || 0 }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Distribuição de Pontos -->
        <div v-if="character?.stat_points > 0" class="card-medieval mb-6">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Distribuir Pontos</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Força -->
            <div class="bg-amber-50 p-4 rounded-lg">
              <div class="text-center mb-3">
                <div class="text-3xl mb-2 relative group">💪
                  <!-- Tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                    <div class="text-center">
                      <div class="font-semibold mb-1">Força</div>
                      <div>• Aumenta o dano físico</div>
                      <div>• Melhora a capacidade de carregar itens</div>
                      <div>• +2 Power por ponto</div>
                    </div>
                    <!-- Seta do tooltip -->
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                  </div>
                </div>
                <h4 class="subtitle-medieval text-medieval-gold">Força</h4>
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="decreaseStat('strength')"
                    :disabled="pendingDistribution.strength <= 0"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    −
                  </button>
                  <span class="text-lg font-bold min-w-[60px] text-center text-medieval-gold">
                    {{ (character?.strength || 0) + pendingDistribution.strength }}
                    <span v-if="pendingDistribution.strength > 0" class="text-green-600 text-sm block">
                      (+{{ pendingDistribution.strength }})
                    </span>
                  </span>
                  <button 
                    @click="increaseStat('strength')"
                    :disabled="!canAddPoints"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    +
                  </button>
                </div>
                <p class="text-xs text-medieval mt-1">Atual: {{ character?.strength || 0 }}</p>
              </div>
            </div>

            <!-- Destreza -->
            <div class="bg-amber-50 p-4 rounded-lg">
              <div class="text-center mb-3">
                <div class="text-3xl mb-2 relative group">🏹
                  <!-- Tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                    <div class="text-center">
                      <div class="font-semibold mb-1">Destreza</div>
                      <div>• Aumenta a velocidade de ataque</div>
                      <div>• Melhora a precisão</div>
                      <div>• +1.5 Power por ponto</div>
                    </div>
                    <!-- Seta do tooltip -->
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                  </div>
                </div>
                <h4 class="subtitle-medieval text-medieval-gold">Destreza</h4>
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="decreaseStat('dexterity')"
                    :disabled="pendingDistribution.dexterity <= 0"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    −
                  </button>
                  <span class="text-lg font-bold min-w-[60px] text-center text-medieval-gold">
                    {{ (character?.dexterity || 0) + pendingDistribution.dexterity }}
                    <span v-if="pendingDistribution.dexterity > 0" class="text-green-600 text-sm block">
                      (+{{ pendingDistribution.dexterity }})
                    </span>
                  </span>
                  <button 
                    @click="increaseStat('dexterity')"
                    :disabled="!canAddPoints"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    +
                  </button>
                </div>
                <p class="text-xs text-medieval mt-1">Atual: {{ character?.dexterity || 0 }}</p>
              </div>
            </div>

            <!-- Constituição -->
            <div class="bg-amber-50 p-4 rounded-lg">
              <div class="text-center mb-3">
                <div class="text-3xl mb-2 relative group">🛡️
                  <!-- Tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                    <div class="text-center">
                      <div class="font-semibold mb-1">Constituição</div>
                      <div>• Aumenta HP máximo</div>
                      <div>• Melhora resistência física</div>
                      <div>• +1.8 Power por ponto</div>
                    </div>
                    <!-- Seta do tooltip -->
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                  </div>
                </div>
                <h4 class="subtitle-medieval text-medieval-gold">Constituição</h4>
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="decreaseStat('constitution')"
                    :disabled="pendingDistribution.constitution <= 0"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    −
                  </button>
                  <span class="text-lg font-bold min-w-[60px] text-center text-medieval-gold">
                    {{ (character?.constitution || 0) + pendingDistribution.constitution }}
                    <span v-if="pendingDistribution.constitution > 0" class="text-green-600 text-sm block">
                      (+{{ pendingDistribution.constitution }})
                    </span>
                  </span>
                  <button 
                    @click="increaseStat('constitution')"
                    :disabled="!canAddPoints"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    +
                  </button>
                </div>
                <p class="text-xs text-medieval mt-1">Atual: {{ character?.constitution || 0 }}</p>
              </div>
            </div>

            <!-- Inteligência -->
            <div class="bg-amber-50 p-4 rounded-lg">
              <div class="text-center mb-3">
                <div class="text-3xl mb-2 relative group">🧙
                  <!-- Tooltip -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                    <div class="text-center">
                      <div class="font-semibold mb-1">Inteligência</div>
                      <div>• Aumenta MP máximo</div>
                      <div>• Melhora dano mágico</div>
                      <div>• +2.2 Power por ponto</div>
                    </div>
                    <!-- Seta do tooltip -->
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                  </div>
                </div>
                <h4 class="subtitle-medieval text-medieval-gold">Inteligência</h4>
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="decreaseStat('intelligence')"
                    :disabled="pendingDistribution.intelligence <= 0"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    −
                  </button>
                  <span class="text-lg font-bold min-w-[60px] text-center text-medieval-gold">
                    {{ (character?.intelligence || 0) + pendingDistribution.intelligence }}
                    <span v-if="pendingDistribution.intelligence > 0" class="text-green-600 text-sm block">
                      (+{{ pendingDistribution.intelligence }})
                    </span>
                  </span>
                  <button 
                    @click="increaseStat('intelligence')"
                    :disabled="!canAddPoints"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    +
                  </button>
                </div>
                <p class="text-xs text-medieval mt-1">Atual: {{ character?.intelligence || 0 }}</p>
              </div>
            </div>

            <!-- Sorte -->
            <div class="bg-amber-50 p-4 rounded-lg relative group">
              <div class="text-center mb-3">
                <div class="text-3xl mb-2">🍀</div>
                <h4 class="subtitle-medieval text-medieval-gold">Sorte</h4>
                
                <!-- Tooltip -->
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                  <div class="text-center">
                    <div class="font-semibold mb-1">Sorte</div>
                    <div>• Aumenta chance de crítico</div>
                    <div>• Melhora drops de itens</div>
                    <div>• +1 Power por ponto</div>
                  </div>
                  <!-- Seta do tooltip -->
                  <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                </div>
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="decreaseStat('luck')"
                    :disabled="pendingDistribution.luck <= 0"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    −
                  </button>
                  <span class="text-lg font-bold min-w-[60px] text-center text-medieval-gold">
                    {{ (character?.luck || 0) + pendingDistribution.luck }}
                    <span v-if="pendingDistribution.luck > 0" class="text-green-600 text-sm block">
                      (+{{ pendingDistribution.luck }})
                    </span>
                  </span>
                  <button 
                    @click="increaseStat('luck')"
                    :disabled="!canAddPoints"
                    class="w-7 h-7 rounded border-2 border-medieval-gold bg-medieval-dark text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark disabled:border-gray-400 disabled:text-gray-400 disabled:cursor-not-allowed flex items-center justify-center text-sm font-bold transition-all duration-200"
                  >
                    +
                  </button>
                </div>
                <p class="text-xs text-medieval mt-1">Atual: {{ character?.luck || 0 }}</p>
              </div>
            </div>
          </div>

          <!-- Botões de Ação -->
          <div class="mt-6 flex justify-center space-x-4">
            <button 
              @click="resetDistribution"
              :disabled="totalPendingPoints === 0"
              class="btn-medieval px-6 py-3 text-sm font-semibold border-2 border-gray-500 bg-transparent text-gray-600 hover:bg-gray-500 hover:text-white disabled:border-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-all duration-200"
            >
              Resetar
            </button>
            <button 
              @click="saveDistribution"
              :disabled="totalPendingPoints === 0"
              class="btn-medieval px-8 py-3 text-sm font-semibold border-2 border-medieval-gold bg-medieval-gold text-medieval-dark hover:bg-transparent hover:text-medieval-gold disabled:border-gray-300 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-300 disabled:hover:text-gray-400 transition-all duration-200"
            >
              Confirmar Distribuição
            </button>
          </div>

          <!-- Resumo -->
          <div class="mt-4 text-center">
            <p class="text-sm text-medieval">
              Pontos disponíveis: <span class="font-bold text-green-600">{{ availablePoints }}</span> | 
              Pontos pendentes: <span class="font-bold" :class="totalPendingPoints > 0 ? 'text-blue-600' : 'text-gray-500'">{{ totalPendingPoints }}</span>
            </p>
          </div>
        </div>

        <!-- Sem Pontos -->
        <div v-else class="card-medieval text-center">
          <div class="text-6xl mb-4">📈</div>
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Sem Pontos para Distribuir</h3>
          <p class="text-medieval mb-4">
            Você não tem pontos de atributo disponíveis no momento.
          </p>
          <p class="text-sm text-gray-600">
            Ganhe experiência subindo de nível para receber mais pontos!
          </p>
        </div>

        <!-- Stats Calculados -->
        <div class="card-medieval">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Stats Calculados</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
              <div class="text-2xl mb-2">❤️</div>
              <h4 class="subtitle-medieval text-red-600">HP</h4>
              <p class="text-lg text-medieval-gold font-semibold">{{ character?.current_hp || 0 }}/{{ character?.max_hp || 0 }}</p>
            </div>
            <div class="text-center">
              <div class="text-2xl mb-2">💙</div>
              <h4 class="subtitle-medieval text-blue-600">MP</h4>
              <p class="text-lg text-medieval-gold font-semibold">{{ character?.current_mp || 0 }}/{{ character?.max_mp || 0 }}</p>
            </div>
            <div class="text-center">
              <div class="text-2xl mb-2">⚡</div>
              <h4 class="subtitle-medieval text-green-600">Power</h4>
              <p class="text-lg text-medieval-gold font-semibold">{{ character?.power || 0 }}</p>
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
import { ref, computed } from 'vue';

const props = defineProps({
  character: Object,
});

const pendingDistribution = ref({
  strength: 0,
  dexterity: 0,
  constitution: 0,
  intelligence: 0,
  luck: 0
});

const getClassName = (className) => {
  switch (className) {
    case 'warrior': return 'Guerreiro';
    case 'mage': return 'Mago';
    case 'archer': return 'Arqueiro';
    case 'rogue': return 'Ladino';
    default: return 'Guerreiro';
  }
};

// Computed properties
const availablePoints = computed(() => {
  return (props.character?.stat_points || 0) - totalPendingPoints.value;
});

const totalPendingPoints = computed(() => {
  return Object.values(pendingDistribution.value).reduce((sum, points) => sum + points, 0);
});

const canAddPoints = computed(() => {
  return availablePoints.value > 0;
});

// Functions
const increaseStat = (stat) => {
  if (canAddPoints.value) {
    pendingDistribution.value[stat]++;
  }
};

const decreaseStat = (stat) => {
  if (pendingDistribution.value[stat] > 0) {
    pendingDistribution.value[stat]--;
  }
};

const resetDistribution = () => {
  pendingDistribution.value = {
    strength: 0,
    dexterity: 0,
    constitution: 0,
    intelligence: 0,
    luck: 0
  };
};

const saveDistribution = () => {
  if (totalPendingPoints.value === 0) return;

  // Create array of distributions to send
  const distributions = [];
  
  Object.entries(pendingDistribution.value).forEach(([stat, points]) => {
    if (points > 0) {
      distributions.push({ stat, points });
    }
  });

  // Send all distributions at once
  router.post(route('character.stats.distribute'), {
    distributions: distributions
  }, {
    onSuccess: () => {
      resetDistribution();
    }
  });
};
</script>
