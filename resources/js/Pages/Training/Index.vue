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
            <h2 class="text-lg font-medieval">Treinamento</h2>
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
                  <span class="font-semibold text-medieval-gold">{{ character?.name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Classe:</span>
                  <span class="font-semibold text-medieval-gold">{{ getClassName(character?.class) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Nível:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.level }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Pontos Livres:</span>
                  <span class="font-semibold text-green-600">{{ character?.stat_points || 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Stats Atuais -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Atributos</h4>
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
          </div>
        </div>

        <!-- Status do Treinamento -->
        <div v-if="character?.training_stat" class="card-medieval mb-6">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Treinamento Ativo</h3>
          
          <div class="bg-amber-50 p-4 rounded-lg">
            <div class="mb-4">
              <p class="text-medieval font-semibold">
                Treinando: {{ getStatName(character.training_stat) }}
              </p>
              <p class="text-sm text-medieval">
                Tempo restante: {{ getTimeRemaining() }}
              </p>
              <p class="text-xs text-gray-600 mt-1">
                Pontos a receber: {{ character.training_points || 0 }}
              </p>
            </div>
            
            <div class="w-full bg-gray-300 rounded-full h-3">
              <div class="bg-green-500 h-3 rounded-full transition-all duration-300" 
                   :style="{ width: getTrainingProgress() + '%' }"></div>
            </div>
            
            <p class="text-xs text-gray-600 mt-2">
              O treinamento será concluído automaticamente quando o tempo acabar.
            </p>
          </div>
        </div>

        <!-- Iniciar Treinamento -->
        <div v-else class="card-medieval">
          <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Iniciar Treinamento</h3>
          
          <form @submit.prevent="startTraining" class="space-y-4">
            <div>
              <label class="block text-medieval font-semibold mb-2">Atributo para Treinar</label>
              <select 
                v-model="form.stat" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-transparent"
                required
              >
                <option value="">Selecione um atributo</option>
                <option value="strength">Força (+2 pontos por hora)</option>
                <option value="dexterity">Destreza (+2 pontos por hora)</option>
                <option value="constitution">Constituição (+2 pontos por hora)</option>
                <option value="intelligence">Inteligência (+2 pontos por hora)</option>
                <option value="luck">Sorte (+2 pontos por hora)</option>
              </select>
            </div>

            <div>
              <label class="block text-medieval font-semibold mb-2">Duração (minutos)</label>
              <select 
                v-model="form.duration" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-medieval-gold focus:border-transparent"
                required
              >
                <option value="">Selecione a duração</option>
                <option value="5">5 minutos (+1 ponto)</option>
                <option value="15">15 minutos (+3 pontos)</option>
                <option value="30">30 minutos (+6 pontos)</option>
                <option value="60">1 hora (+12 pontos)</option>
              </select>
            </div>

            <button 
              type="submit" 
              class="btn-medieval w-full py-3 text-lg"
              :disabled="!form.stat || !form.duration"
            >
              Iniciar Treinamento
            </button>
          </form>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  character: Object,
});

const form = ref({
  stat: '',
  duration: ''
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

const getTrainingProgress = () => {
  if (!props.character?.training_ends_at || !props.character?.training_started_at) return 0;
  
  const now = currentTime.value;
  const startTime = new Date(props.character.training_started_at);
  const endTime = new Date(props.character.training_ends_at);
  
  const total = endTime - startTime;
  const elapsed = now - startTime;
  
  if (total <= 0) return 100;
  
  // Ensure progress starts at 0 and goes to 100
  const progress = Math.min(100, Math.max(0, (elapsed / total) * 100));
  
  return progress;
};

const startTraining = () => {
  router.post(route('training.start'), form.value, {
    onSuccess: () => {
      form.value = { stat: '', duration: '' };
    }
  });
};


// Update time every second for real-time display
onMounted(() => {
  timeInterval = setInterval(() => {
    currentTime.value = new Date();
    
    // Check if training is complete
    if (props.character?.training_stat) {
      const endTime = new Date(props.character.training_ends_at);
      if (currentTime.value >= endTime) {
        // Training is complete, refresh the page to get updated data
        router.reload();
      }
    }
  }, 1000);
  
  // Also check every 30 seconds if training was processed by backend
  const backendCheckInterval = setInterval(() => {
    if (props.character?.training_stat) {
      const endTime = new Date(props.character.training_ends_at);
      if (currentTime.value >= endTime) {
        // Training should be complete, check with backend
        router.reload();
      }
    }
  }, 30000); // Check every 30 seconds
  
  // Store interval for cleanup
  window.backendCheckInterval = backendCheckInterval;
});

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval);
  }
  if (window.backendCheckInterval) {
    clearInterval(window.backendCheckInterval);
  }
});
</script>
