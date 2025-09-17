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
            <h2 class="text-lg font-medieval">Arena de Batalha</h2>
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

    <!-- Battle Modal -->
    <div v-if="showBattleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-1 sm:p-4">
      <div class="bg-white rounded-lg shadow-2xl max-w-6xl w-full h-[98vh] sm:h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-medieval-dark text-medieval-gold p-2 rounded-t-lg">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-bold title-medieval">Combate</h3>
            <button 
              @click="closeBattleModal"
              class="text-medieval-gold hover:text-white text-lg p-1"
            >
              ×
            </button>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-1 sm:p-4 flex-1 flex flex-col overflow-hidden">
          <!-- Battle Arena -->
          <div class="mb-2">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-1 sm:gap-2">
              <!-- Personagem -->
              <div class="bg-gray-50 rounded-lg p-2 sm:p-3">
                <h4 class="subtitle-medieval text-medieval-gold mb-1 sm:mb-2 text-center text-xs sm:text-sm">{{ characterData?.name }}</h4>
                
                <div class="flex items-center space-x-1 sm:space-x-2">
                  <!-- Imagem do Personagem -->
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-medieval-bronze rounded-lg flex items-center justify-center text-lg sm:text-xl">
                      ⚔️
                    </div>
                  </div>
                  
                  <!-- Stats Compactos -->
                  <div class="flex-1 space-y-1">
                    <!-- HP Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">HP:</span>
                        <span class="text-red-600 font-semibold text-xs">{{ characterData?.current_hp }}/{{ characterData?.max_hp }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 shadow-inner">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             :style="{ width: characterData?.max_hp ? `${((characterData?.current_hp || 0) / characterData.max_hp) * 100}%` : '0%' }"></div>
                      </div>
                    </div>
                    
                    <!-- Stamina Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">Stamina:</span>
                        <span class="text-blue-600 font-semibold text-xs">{{ characterData?.current_stamina }}/{{ characterData?.max_stamina }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 shadow-inner">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             :style="{ width: characterData?.max_stamina ? `${((characterData?.current_stamina || 0) / characterData.max_stamina) * 100}%` : '0%' }"></div>
                      </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-2 text-xs text-medieval">
                      <div class="flex justify-between">
                        <span>Nível:</span>
                        <span class="font-semibold text-medieval-gold">{{ characterData?.level }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Poder:</span>
                        <span class="font-semibold text-green-600">{{ characterData?.power }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Monstro -->
              <div class="bg-red-50 rounded-lg p-2 sm:p-3">
                <h4 class="subtitle-medieval text-medieval-gold mb-1 sm:mb-2 text-center text-xs sm:text-sm">{{ selectedMonster?.name }}</h4>
                
                <div class="flex items-center space-x-1 sm:space-x-2">
                  <!-- Imagem do Monstro -->
                  <div class="flex-shrink-0">
                    <img 
                      v-if="selectedMonster?.image_path" 
                      :src="selectedMonster.image_path" 
                      :alt="selectedMonster.name"
                      class="w-10 h-10 sm:w-14 sm:h-14 object-contain"
                      @error="handleImageError"
                    />
                    <div v-else class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-300 rounded-lg flex items-center justify-center text-lg sm:text-xl">
                      {{ selectedMonster?.image }}
                    </div>
                  </div>
                  
                  <!-- Stats Compactos -->
                  <div class="flex-1 space-y-1">
                    <!-- HP Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">HP:</span>
                        <span class="text-red-600 font-semibold text-xs">{{ selectedMonster?.current_hp }}/{{ selectedMonster?.max_hp }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 shadow-inner">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             :style="{ width: selectedMonster?.max_hp ? `${((selectedMonster?.current_hp || 0) / selectedMonster.max_hp) * 100}%` : '0%' }"></div>
                      </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-2 text-xs text-medieval">
                      <div class="flex justify-between">
                        <span>Nível:</span>
                        <span class="font-semibold text-medieval-gold">{{ selectedMonster?.level }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Ataque:</span>
                        <span class="font-semibold text-orange-600">{{ selectedMonster?.attack_power }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Defesa:</span>
                        <span class="font-semibold text-blue-600">{{ selectedMonster?.defense }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Velocidade:</span>
                        <span class="font-semibold text-green-600">{{ selectedMonster?.speed }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Battle Log -->
          <div class="mb-1 sm:mb-2 flex-1 flex flex-col min-h-0 max-h-32 sm:max-h-48">
            <h3 class="text-xs sm:text-sm subtitle-medieval mb-1 sm:mb-2 text-medieval-gold">Log de Combate</h3>
            <div class="bg-gray-900 text-green-400 p-2 sm:p-4 rounded-lg flex-1 overflow-y-auto font-mono text-xs sm:text-sm battle-log min-h-0">
              <div v-for="(message, index) in battleLog" :key="index" class="mb-1 break-words">
                {{ message }}
              </div>
              <div v-if="battleLog.length === 0" class="text-gray-500">
                A batalha começou! Escolha sua ação...
              </div>
            </div>
          </div>

          <!-- Battle Actions -->
          <div v-if="!battleOver" class="border-t border-gray-200 pt-1 sm:pt-2">
            <div class="text-center">
              <h3 class="text-xs subtitle-medieval mb-1 text-medieval-gold">Escolha sua Ação</h3>
              
              <!-- Aviso de Vida Baixa -->
              <div v-if="(characterData?.current_hp || 0) <= 0" class="bg-red-50 border border-red-200 rounded-lg p-1 sm:p-2 mb-2 sm:mb-3">
                <p class="text-xs text-red-800">
                  💀 Você está morto! Precisa ser revivido para batalhar.
                </p>
              </div>
              
              <!-- Checkbox de Batalha Automática -->
              <div v-if="(characterData?.current_hp || 0) > 0" class="flex items-center justify-center mb-2 sm:mb-3">
                <label class="flex items-center space-x-1 sm:space-x-2 cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="autoBattle"
                    @change="toggleAutoBattle"
                    class="w-3 h-3 sm:w-4 sm:h-4 text-medieval-gold bg-gray-100 border-gray-300 rounded focus:ring-medieval-gold focus:ring-2"
                  >
                  <span class="text-xs text-medieval font-medium">Batalha Automática</span>
                </label>
              </div>
              
              <div class="flex flex-row justify-center gap-1 sm:gap-2">
                <button 
                  @click="attack"
                  :disabled="isAttacking || (characterData?.current_hp || 0) <= 0 || autoBattle"
                  class="btn-medieval px-2 sm:px-3 py-1 text-xs font-semibold disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-transform"
                >
                  {{ isAttacking ? 'Atacando...' : autoBattle ? '🤖 Automático' : 'Atacar' }}
                </button>
                <button 
                  @click="flee"
                  :disabled="isAttacking"
                  class="btn-medieval px-2 sm:px-3 py-1 text-xs font-semibold bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-transform"
                >
                  Fugir
                </button>
              </div>
            </div>
          </div>
          
          <!-- Espaço após ações -->
          <div class="mb-1 sm:mb-3"></div>

          <!-- Battle Result -->
          <div v-if="battleOver" class="border-t border-gray-200 pt-3 sm:pt-4">
            <div class="text-center">
              <p class="text-base sm:text-lg text-medieval mb-3 sm:mb-4 font-semibold">{{ battleResult?.message }}</p>
              
              <div v-if="battleResult?.winner === 'character'" class="bg-green-50 border border-green-200 p-2 sm:p-4 rounded-lg mb-3 sm:mb-4">
                <h4 class="font-bold text-green-800 mb-2 text-sm sm:text-base">Recompensas Obtidas:</h4>
                <div class="flex justify-center space-x-4 sm:space-x-6 text-xs sm:text-sm">
                  <span class="text-yellow-600 font-semibold">🪙 +{{ battleResult?.gold_reward || 0 }} Gold</span>
                  <span class="text-blue-600 font-semibold">⭐ +{{ battleResult?.exp_reward || 0 }} EXP</span>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row justify-center gap-2">
                <button 
                  @click="startNewBattle"
                  class="btn-medieval px-4 sm:px-6 py-2 text-sm sm:text-base font-semibold transform hover:scale-105 transition-transform"
                >
                  Batalhar Novamente
                </button>
                <button 
                  @click="closeBattleModal"
                  class="btn-medieval px-4 sm:px-6 py-2 text-sm sm:text-base font-semibold bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark transform hover:scale-105 transition-transform"
                >
                  Fechar
                </button>
              </div>
            </div>
          </div>

          <!-- Poções e Habilidades -->
          <div v-if="!battleOver" class="border-t border-medieval-bronze pt-3">
            <div class="flex flex-col sm:flex-row gap-3">
              <!-- Poções -->
              <div class="flex-1">
                <h3 class="text-sm subtitle-medieval mb-2 text-medieval-gold text-center">Poções</h3>
                <div class="flex justify-center gap-1 sm:gap-2">
                  <div 
                    v-for="slot in 4" 
                    :key="slot"
                    class="relative group"
                  >
                    <button 
                      :data-slot="slot"
                      @click="useHotkeyPotion(slot)"
                      :disabled="isAttacking || !hotkeyItems[slot - 1]"
                      class="bg-white rounded-lg border-2 border-medieval-bronze w-12 h-12 sm:w-16 sm:h-16 flex flex-col items-center justify-center hover:bg-amber-100 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                      :class="{ 
                        'border-medieval-gold bg-medieval-bronze shadow-md': hotkeyItems[slot - 1],
                        'border-medieval-bronze bg-white': !hotkeyItems[slot - 1]
                      }"
                    >
                      <div v-if="hotkeyItems[slot - 1]" class="flex flex-col items-center justify-center h-full">
                        <img :src="hotkeyItems[slot - 1].item.image_path" :alt="hotkeyItems[slot - 1].item.name" class="w-4 h-4 sm:w-6 sm:h-6 mb-1" />
                        <span class="text-xs text-medieval font-semibold">{{ hotkeyItems[slot - 1].quantity }}x</span>
                        <div class="text-xs text-medieval-gold font-semibold">[F{{ slot }}]</div>
                      </div>
                      <div v-else class="flex flex-col items-center justify-center h-full">
                        <span class="text-medieval-stone text-xs font-semibold">F{{ slot }}</span>
                        <span class="text-medieval-stone text-xs font-semibold">Vazio</span>
                      </div>
                    </button>
                    
                    <!-- Tooltip para poções -->
                    <div 
                      v-if="hotkeyItems[slot - 1]" 
                      class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap"
                    >
                      <div class="font-semibold text-medieval-gold mb-1">{{ hotkeyItems[slot - 1].item.name }}</div>
                      <div v-for="effect in hotkeyItems[slot - 1].item.effects" :key="effect.id" class="text-green-400">
                        {{ getEffectDescription(effect) }}
                      </div>
                      <div class="text-gray-400 mt-1">Quantidade: {{ hotkeyItems[slot - 1].quantity }}</div>
                      <!-- Seta do tooltip -->
                      <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Habilidades (Bloqueado) -->
              <div class="flex-1">
                <h3 class="text-sm subtitle-medieval mb-2 text-medieval-gold text-center">Habilidades</h3>
                <div class="flex justify-center">
                  <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-2 sm:p-3 text-center opacity-75 h-12 sm:h-16 flex items-center justify-center w-full max-w-xs">
                    <div class="text-gray-500">
                      <div class="text-sm sm:text-lg mb-1">🚧</div>
                      <div class="font-medium text-xs sm:text-sm">Em Breve</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Toast Notification -->
      <div v-if="showToast" class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="card-medieval p-3 max-w-sm"
             :class="{
               'border-green-500 bg-green-50': toastType === 'success',
               'border-red-500 bg-red-50': toastType === 'error',
               'border-blue-500 bg-blue-50': toastType === 'info'
             }">
          <div class="flex items-center space-x-3">
            <div class="text-xl">
              <span v-if="toastType === 'success'">🧪</span>
              <span v-else-if="toastType === 'error'">❌</span>
              <span v-else>ℹ️</span>
            </div>
            <div class="flex-1">
              <p class="text-sm font-semibold text-medieval-dark">{{ toastMessage }}</p>
            </div>
            <button @click="showToast = false" class="text-medieval-stone hover:text-medieval-dark">
              ×
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="max-w-6xl mx-auto">
        
        <!-- Status do Personagem -->
        <div class="card-medieval mb-6">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Seu Personagem</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
              <div class="text-2xl mb-2">⚔️</div>
              <h4 class="font-semibold text-medieval">{{ character?.name }}</h4>
              <p class="text-sm text-medieval-stone">Nível {{ character?.level }}</p>
            </div>
            <div class="text-center">
              <div class="text-2xl mb-2">❤️</div>
              <h4 class="font-semibold text-red-600">{{ character?.current_hp }}/{{ character?.max_hp }}</h4>
              <p class="text-sm text-medieval-stone">Pontos de Vida</p>
            </div>
            <div class="text-center">
              <div class="text-2xl mb-2">⚡</div>
              <h4 class="font-semibold text-blue-600">{{ character?.current_stamina }}/{{ character?.max_stamina }}</h4>
              <p class="text-sm text-medieval-stone">Pontos de Stamina</p>
            </div>
          </div>
        </div>

        <!-- Lista de Monstros -->
        <div class="card-medieval">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Escolha seu Oponente</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="monster in monsters" 
              :key="monster.id"
              class="bg-white rounded-lg border-2 border-gray-200 p-4 hover:border-medieval-gold transition-colors"
            >
              <!-- Imagem do Monstro -->
              <div class="mb-2 flex justify-center">
                <img 
                  v-if="monster.image_path" 
                  :src="monster.image_path" 
                  :alt="monster.name"
                  class="w-16 h-16 object-contain"
                  @error="handleImageError"
                />
                <div v-else class="text-4xl">{{ monster.image }}</div>
              </div>
              
              <h4 class="font-semibold text-lg text-medieval text-center mb-2">{{ monster.name }}</h4>
              <p class="text-sm text-medieval-stone text-center mb-3">{{ monster.description }}</p>
              
              <!-- Stats do Monstro -->
              <div class="space-y-1 text-xs text-medieval mb-4">
                <div class="flex justify-between">
                  <span>Nível:</span>
                  <span class="font-semibold text-medieval-gold">{{ monster.level }}</span>
                </div>
                <div class="flex justify-between">
                  <span>HP:</span>
                  <span class="font-semibold text-red-600">{{ monster.max_hp }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Ataque:</span>
                  <span class="font-semibold text-orange-600">{{ monster.attack_power }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Defesa:</span>
                  <span class="font-semibold text-blue-600">{{ monster.defense }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Velocidade:</span>
                  <span class="font-semibold text-green-600">{{ monster.speed }}</span>
                </div>
              </div>
              
              <!-- Recompensas e Custo -->
              <div class="bg-gray-50 p-2 rounded mb-3">
                <div class="text-xs text-center space-y-1">
                  <div class="flex justify-between">
                    <span class="text-yellow-600">🪙 Gold:</span>
                    <span class="font-semibold">{{ formatNumber(monster.gold_reward) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-blue-600">⭐ EXP:</span>
                    <span class="font-semibold">{{ formatNumber(monster.exp_reward) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-purple-600">⚡ Stamina:</span>
                    <span class="font-semibold">{{ monster.stamina_cost || 10 }}</span>
                  </div>
                </div>
              </div>
              
              <!-- Botão de Batalha -->
              <button 
                @click="startBattle(monster)"
                :disabled="monster.level > (character?.level || 0) + 2 || (character?.current_stamina || 0) < (monster.stamina_cost || 10) || (character?.current_hp || 0) <= 0"
                class="w-full btn-medieval text-sm py-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ 
                  monster.level > (character?.level || 0) + 2 ? 'Muito Forte' : 
                  (character?.current_hp || 0) <= 0 ? 'Morto' :
                  (character?.current_stamina || 0) < (monster.stamina_cost || 10) ? 'Sem Stamina' : 
                  'Batalhar' 
                }}
              </button>
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
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
  character: Object,
  monsters: Array,
});

const page = usePage();

const showBattleModal = ref(false);
const selectedMonster = ref(null);
const battleInstanceId = ref(null);
const battleLog = ref([]);
const battleOver = ref(false);
const battleResult = ref(null);
const isAttacking = ref(false);
const autoBattle = ref(false);
const autoBattleInterval = ref(null);

// Hotkeys de poções
const hotkeyItems = ref([null, null, null, null]);

// Toast/Notificação visual
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success'); // success, error, info

// Dados do personagem reativos para atualização em tempo real
const characterData = ref({ ...props.character });

// Debug inicial
console.log('Props character inicial:', props.character);
console.log('CharacterData inicial:', characterData.value);


const formatNumber = (num) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M';
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K';
  }
  return num.toString();
};

// Função para converter efeitos em descrições legíveis
const getEffectDescription = (effect) => {
  switch (effect.effect_type) {
    case 'heal_hp':
      return `+${effect.effect_value} HP`;
    case 'restore_stamina':
      return `+${effect.effect_value} Stamina`;
    case 'restore_mp':
      return `+${effect.effect_value} MP`;
    case 'buff_strength':
      return `+${effect.effect_value} Força`;
    case 'buff_dexterity':
      return `+${effect.effect_value} Destreza`;
    case 'buff_constitution':
      return `+${effect.effect_value} Constituição`;
    case 'buff_intelligence':
      return `+${effect.effect_value} Inteligência`;
    case 'buff_luck':
      return `+${effect.effect_value} Sorte`;
    default:
      return `${effect.effect_type}: +${effect.effect_value}`;
  }
};

// Função para mostrar toast
const showToastNotification = (message, type = 'success') => {
  toastMessage.value = message;
  toastType.value = type;
  showToast.value = true;
  
  // Auto-hide após 3 segundos
  setTimeout(() => {
    showToast.value = false;
  }, 3000);
};

// Carregar hotkeys de poções
const loadHotkeyItems = async () => {
  try {
    const response = await fetch('/api/items/hotkeys', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    
    if (response.ok) {
      const data = await response.json();
      console.log('Hotkeys recebidos na batalha:', data.hotkeys);
      
      if (data.success && data.hotkeys) {
        // A API retorna um objeto com chaves 1-4, converter para array 0-3
        const hotkeysArray = [null, null, null, null];
        for (let slot = 1; slot <= 4; slot++) {
          if (data.hotkeys[slot]) {
            hotkeysArray[slot - 1] = data.hotkeys[slot];
          }
        }
        hotkeyItems.value = hotkeysArray;
        console.log('Hotkeys processados na batalha:', hotkeyItems.value);
      } else {
        hotkeyItems.value = [null, null, null, null];
      }
    }
  } catch (error) {
    console.error('Erro ao carregar hotkeys:', error);
    hotkeyItems.value = [null, null, null, null];
  }
};

// Usar poção do hotkey
const useHotkeyPotion = async (slot) => {
  if (!hotkeyItems.value[slot - 1] || isAttacking.value) return;
  
  const item = hotkeyItems.value[slot - 1];
  
  try {
    const response = await fetch('/api/items/use-hotkey', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        slot: slot
      })
    });

    if (response.ok) {
      const data = await response.json();
      
      if (data.success) {
        // Atualizar dados do personagem em tempo real
        if (data.character) {
          // Atualizar characterData local
          characterData.value = { ...characterData.value, ...data.character };
          
          // Atualizar props.character
          Object.keys(data.character).forEach(key => {
            props.character[key] = data.character[key];
          });
        }
        
        // Forçar reatividade
        await nextTick();
        
        // Atualizar hotkeys
        await loadHotkeyItems();
        
        // Adicionar mensagem ao log
        addToLog(`🧪 ${data.message}`);
        
        // Mostrar toast de sucesso
        showToastNotification(data.message, 'success');
        
        // Feedback visual - destacar o botão usado
        const button = document.querySelector(`[data-slot="${slot}"]`);
        if (button) {
          button.classList.add('animate-pulse', 'bg-green-100', 'border-green-400');
          setTimeout(() => {
            button.classList.remove('animate-pulse', 'bg-green-100', 'border-green-400');
          }, 1000);
        }
      } else {
        addToLog(`❌ ${data.message}`);
        showToastNotification(data.message, 'error');
      }
    }
  } catch (error) {
    addToLog('❌ Erro ao usar poção');
    showToastNotification('Erro ao usar poção', 'error');
  }
};

const startBattle = async (monster) => {
  try {
    const csrfToken = page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const response = await fetch(route('battle.start'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        monster_id: monster.id
      })
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      console.error('Erro ao iniciar batalha:', errorData);
      
      if (errorData.error) {
        addToLog(`❌ ${errorData.error}`);
        return;
      }
      
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    // Update character data
    Object.assign(props.character, data.character);
    Object.assign(characterData.value, data.character);
    
    // Set up battle
    selectedMonster.value = data.monster;
    battleInstanceId.value = data.battle_instance_id;
    showBattleModal.value = true;
    battleLog.value = [];
    battleOver.value = false;
    battleResult.value = null;
    isAttacking.value = false;
    
    console.log('Battle setup:', {
      battle_instance_id: data.battle_instance_id,
      monster_hp: data.monster.current_hp,
      is_continuing: data.is_continuing_battle
    });
    
    // Verificar se é uma batalha nova ou continuação
    if (data.is_continuing_battle) {
      addToLog(`Continuando batalha contra ${monster.name}! (HP: ${data.monster.current_hp}/${data.monster.max_hp})`);
    } else {
      addToLog(`Batalha iniciada contra ${monster.name}!`);
    }
    
  } catch (error) {
    console.error('Erro ao iniciar batalha:', error);
    addToLog('❌ Erro ao iniciar batalha!');
  }
};

const closeBattleModal = () => {
  // Parar batalha automática
  stopAutoBattle();
  
  showBattleModal.value = false;
  selectedMonster.value = null;
  battleInstanceId.value = null;
  battleLog.value = [];
  battleOver.value = false;
  battleResult.value = null;
  isAttacking.value = false;
  autoBattle.value = false;
};

const startNewBattle = async () => {
  if (selectedMonster.value) {
    // Start a new battle with the same monster
    await startBattle(selectedMonster.value);
  }
};

const addToLog = (message) => {
  battleLog.value.push(`[${new Date().toLocaleTimeString()}] ${message}`);
  
  // Limitar a 20 mensagens para não sobrecarregar o log
  if (battleLog.value.length > 20) {
    battleLog.value = battleLog.value.slice(-20);
  }
  
  // Auto-scroll para a mensagem mais recente
  setTimeout(() => {
    const logContainer = document.querySelector('.battle-log');
    if (logContainer) {
      logContainer.scrollTop = logContainer.scrollHeight;
    }
  }, 100);
};

const toggleAutoBattle = () => {
  if (autoBattle.value) {
    startAutoBattle();
  } else {
    stopAutoBattle();
  }
};

const startAutoBattle = () => {
  if (autoBattleInterval.value) {
    clearInterval(autoBattleInterval.value);
  }
  
  addToLog('🤖 Batalha automática ativada!');
  
  autoBattleInterval.value = setInterval(() => {
    if (!battleOver.value && !isAttacking.value && (characterData.value?.current_hp || 0) > 0) {
      attack();
    } else if (battleOver.value) {
      stopAutoBattle();
    }
  }, 3000); // 3 segundos
};

const stopAutoBattle = () => {
  if (autoBattleInterval.value) {
    clearInterval(autoBattleInterval.value);
    autoBattleInterval.value = null;
  }
  
  if (autoBattle.value) {
    addToLog('⏹️ Batalha automática desativada!');
    autoBattle.value = false;
  }
};

const attack = async () => {
  if (isAttacking.value || !selectedMonster.value) return;
  
  isAttacking.value = true;
  addToLog('Você atacou!');
  
  try {
    const csrfToken = page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const response = await fetch(route('battle.attack'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        battle_instance_id: battleInstanceId.value
      })
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      console.error('Erro na resposta:', errorData);
      
      if (errorData.error && errorData.error.includes('Stamina insuficiente')) {
        addToLog(`❌ ${errorData.error}`);
        return;
      }
      
      throw new Error(`HTTP error! status: ${response.status} - ${errorData.error || 'Erro desconhecido'}`);
    }
    
    const data = await response.json();
    console.log('Dados recebidos:', data);
    
    // Update character and monster data
    Object.assign(props.character, data.character);
    Object.assign(characterData.value, data.character);
    Object.assign(selectedMonster.value, data.monster);
    
    // Add attack results to log
    if (data.attack_result) {
      addToLog(data.attack_result.message);
    }
    
    if (data.monster_attack_result) {
      addToLog(data.monster_attack_result.message);
    }
    
    // Check if battle is over
    if (data.battle_over) {
      battleOver.value = true;
      battleResult.value = data.result;
      autoBattle.value = false; // Desmarcar checkbox quando batalha termina
      
      if (data.result.winner === 'character') {
        addToLog(`[VITÓRIA] Você derrotou o monstro! Ganhou ${data.result.gold_reward} gold e ${data.result.exp_reward} EXP!`);
      } else if (data.result.winner === 'monster') {
        addToLog('[DERROTA] Você foi derrotado pelo monstro!');
      } else if (data.result.winner === 'fled') {
        addToLog('[FUGA] Você fugiu da batalha!');
      }
    }
    
  } catch (error) {
    console.error('Erro ao atacar:', error);
    addToLog('❌ Erro ao executar ataque!');
  } finally {
    isAttacking.value = false;
  }
};

const flee = async () => {
  if (isAttacking.value) return;
  
  isAttacking.value = true;
  
  try {
    const csrfToken = page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const response = await fetch(route('battle.flee'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        battle_instance_id: battleInstanceId.value
      })
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    battleOver.value = true;
    battleResult.value = data.result;
    addToLog(data.result.message);
    
  } catch (error) {
    console.error('Erro ao fugir:', error);
    addToLog('❌ Erro ao tentar fugir!');
  } finally {
    isAttacking.value = false;
  }
};

const handleImageError = (event) => {
  // Se a imagem falhar ao carregar, esconder a img e mostrar o emoji
  event.target.style.display = 'none';
  const fallback = event.target.nextElementSibling;
  if (fallback) {
    fallback.style.display = 'flex';
  }
};

// Listener para teclas F1-F4
const handleKeyPress = (event) => {
  // Só funciona se a batalha estiver ativa e não estiver atacando
  if (battleOver.value || isAttacking.value) return;
  
  const key = event.key;
  let slot = null;
  
  switch (key) {
    case 'F1':
      slot = 1;
      break;
    case 'F2':
      slot = 2;
      break;
    case 'F3':
      slot = 3;
      break;
    case 'F4':
      slot = 4;
      break;
    default:
      return;
  }
  
  // Prevenir comportamento padrão das teclas F
  event.preventDefault();
  
  // Usar poção se existir no slot
  if (hotkeyItems.value[slot - 1]) {
    useHotkeyPotion(slot);
  }
};

onMounted(() => {
  // Carregar hotkeys de poções
  loadHotkeyItems();
  
  // Adicionar listener para teclas F1-F4
  document.addEventListener('keydown', handleKeyPress);
});

onUnmounted(() => {
  // Limpar intervalo de batalha automática
  stopAutoBattle();
  
  // Remover listener de teclas
  document.removeEventListener('keydown', handleKeyPress);
});
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>