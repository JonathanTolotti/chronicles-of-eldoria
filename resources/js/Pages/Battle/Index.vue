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
    <div v-if="showBattleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
      <div class="bg-white rounded-lg shadow-2xl max-w-6xl w-full h-[95vh] sm:h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-medieval-dark text-medieval-gold p-3 sm:p-4 rounded-t-lg">
          <div class="flex justify-between items-center">
            <h3 class="text-lg sm:text-xl font-bold title-medieval">Combate</h3>
            <button 
              @click="closeBattleModal"
              class="text-medieval-gold hover:text-white text-xl sm:text-2xl p-1"
            >
              ×
            </button>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-2 sm:p-4 flex-1 flex flex-col overflow-hidden">
          <!-- Battle Arena -->
          <div class="mb-2 sm:mb-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-4">
              <!-- Personagem -->
              <div class="bg-gray-50 rounded-lg p-2 sm:p-4">
                <h4 class="subtitle-medieval text-medieval-gold mb-2 sm:mb-3 text-center text-sm sm:text-base">{{ character?.name }}</h4>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                  <!-- Imagem do Personagem -->
                  <div class="flex-shrink-0">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-medieval-bronze rounded-lg flex items-center justify-center text-lg sm:text-2xl">
                      ⚔️
                    </div>
                  </div>
                  
                  <!-- Stats Compactos -->
                  <div class="flex-1 space-y-1 sm:space-y-2">
                    <!-- HP Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">HP:</span>
                        <span class="text-red-600 font-semibold text-xs">{{ character?.current_hp }}/{{ character?.max_hp }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 sm:h-2 shadow-inner">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1.5 sm:h-2 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             :style="{ width: character?.max_hp ? `${((character?.current_hp || 0) / character.max_hp) * 100}%` : '0%' }"></div>
                      </div>
                    </div>
                    
                    <!-- Stamina Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">Stamina:</span>
                        <span class="text-blue-600 font-semibold text-xs">{{ character?.current_stamina }}/{{ character?.max_stamina }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 sm:h-2 shadow-inner">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 sm:h-2 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             :style="{ width: character?.max_stamina ? `${((character?.current_stamina || 0) / character.max_stamina) * 100}%` : '0%' }"></div>
                      </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-2 text-xs text-medieval">
                      <div class="flex justify-between">
                        <span>Nível:</span>
                        <span class="font-semibold text-medieval-gold">{{ character?.level }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Poder:</span>
                        <span class="font-semibold text-green-600">{{ character?.power }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Monstro -->
              <div class="bg-red-50 rounded-lg p-2 sm:p-4">
                <h4 class="subtitle-medieval text-medieval-gold mb-2 sm:mb-3 text-center text-sm sm:text-base">{{ selectedMonster?.name }}</h4>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                  <!-- Imagem do Monstro -->
                  <div class="flex-shrink-0">
                    <img 
                      v-if="selectedMonster?.image_path" 
                      :src="selectedMonster.image_path" 
                      :alt="selectedMonster.name"
                      class="w-12 h-12 sm:w-16 sm:h-16 object-contain"
                      @error="handleImageError"
                    />
                    <div v-else class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-300 rounded-lg flex items-center justify-center text-lg sm:text-2xl">
                      {{ selectedMonster?.image }}
                    </div>
                  </div>
                  
                  <!-- Stats Compactos -->
                  <div class="flex-1 space-y-1 sm:space-y-2">
                    <!-- HP Bar -->
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="text-medieval">HP:</span>
                        <span class="text-red-600 font-semibold text-xs">{{ selectedMonster?.current_hp }}/{{ selectedMonster?.max_hp }}</span>
                      </div>
                      <div class="w-full bg-gray-300 rounded-full h-1.5 sm:h-2 shadow-inner">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1.5 sm:h-2 rounded-full transition-all duration-500 ease-out shadow-sm" 
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
          <div class="mb-2 sm:mb-4 flex-1 flex flex-col min-h-0">
            <h3 class="text-sm sm:text-base subtitle-medieval mb-1 sm:mb-2 text-medieval-gold">Log de Combate</h3>
            <div class="bg-gray-900 text-green-400 p-2 sm:p-3 rounded-lg flex-1 overflow-y-auto font-mono text-xs battle-log min-h-0">
              <div v-for="(message, index) in battleLog" :key="index" class="mb-1 break-words">
                {{ message }}
              </div>
              <div v-if="battleLog.length === 0" class="text-gray-500">
                A batalha começou! Escolha sua ação...
              </div>
            </div>
          </div>

          <!-- Battle Actions -->
          <div v-if="!battleOver" class="border-t border-gray-200 pt-2 sm:pt-3">
            <div class="text-center">
              <h3 class="text-sm sm:text-base subtitle-medieval mb-2 sm:mb-3 text-medieval-gold">Escolha sua Ação</h3>
              
              <!-- Aviso de Vida Baixa -->
              <div v-if="(character?.current_hp || 0) <= 0" class="bg-red-50 border border-red-200 rounded-lg p-2 sm:p-3 mb-3 sm:mb-4">
                <p class="text-xs sm:text-sm text-red-800">
                  💀 Você está morto! Precisa ser revivido para batalhar.
                </p>
              </div>
              
              <!-- Checkbox de Batalha Automática -->
              <div v-if="(character?.current_hp || 0) > 0" class="flex items-center justify-center mb-3 sm:mb-4">
                <label class="flex items-center space-x-2 cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="autoBattle"
                    @change="toggleAutoBattle"
                    class="w-4 h-4 text-medieval-gold bg-gray-100 border-gray-300 rounded focus:ring-medieval-gold focus:ring-2"
                  >
                  <span class="text-xs sm:text-sm text-medieval font-medium">Batalha Automática</span>
                </label>
              </div>
              
              <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4">
                <button 
                  @click="attack"
                  :disabled="isAttacking || (character?.current_hp || 0) <= 0 || autoBattle"
                  class="btn-medieval px-4 sm:px-6 py-2 text-sm sm:text-base font-semibold disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-transform"
                >
                  {{ isAttacking ? 'Atacando...' : autoBattle ? '🤖 Automático' : 'Atacar' }}
                </button>
                <button 
                  @click="flee"
                  :disabled="isAttacking"
                  class="btn-medieval px-4 sm:px-6 py-2 text-sm sm:text-base font-semibold bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 transition-transform"
                >
                  Fugir
                </button>
              </div>
            </div>
          </div>

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
              
              <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4">
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
import { ref, onUnmounted } from 'vue';

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

const formatNumber = (num) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M';
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K';
  }
  return num.toString();
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
    if (!battleOver.value && !isAttacking.value && (props.character?.current_hp || 0) > 0) {
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

onUnmounted(() => {
  // Limpar intervalo de batalha automática
  stopAutoBattle();
});
</script>