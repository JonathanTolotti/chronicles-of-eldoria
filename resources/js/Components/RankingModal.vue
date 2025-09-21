<template>
  <Modal :show="show" @close="$emit('close')" max-width="2xl">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-yellow-600 bg-gradient-to-r from-gray-800 to-gray-700">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl text-medieval font-bold text-yellow-400 flex items-center">
            <span class="text-3xl mr-3">🏆</span>
            Ranking dos Heróis
          </h2>
          <button 
            @click="$emit('close')"
            class="text-yellow-400 hover:text-white transition-colors duration-200 bg-gray-700 hover:bg-gray-600 rounded-full p-2"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="px-6 py-4 border-b border-gray-600">
        <div class="flex space-x-1 bg-gray-700 p-1 rounded-lg">
          <button
            @click="activeTab = 'level'"
            :class="[
              'flex-1 py-2 px-4 rounded-md text-medieval font-semibold transition-all duration-200',
              activeTab === 'level' 
                ? 'bg-yellow-500 text-gray-900 shadow-lg' 
                : 'text-gray-300 hover:text-white hover:bg-gray-600'
            ]"
          >
            <span class="mr-2">📈</span>
            Ranking por Nível
          </button>
          <button
            @click="activeTab = 'power'"
            :class="[
              'flex-1 py-2 px-4 rounded-md text-medieval font-semibold transition-all duration-200',
              activeTab === 'power' 
                ? 'bg-yellow-500 text-gray-900 shadow-lg' 
                : 'text-gray-300 hover:text-white hover:bg-gray-600'
            ]"
          >
            <span class="mr-2">⚔️</span>
            Ranking por CP
          </button>
        </div>
      </div>

      <!-- Content -->
      <div class="px-6 py-4 max-h-96 overflow-y-auto bg-gray-800/50">
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-400"></div>
          <p class="mt-2 text-medieval text-gray-300">Carregando ranking...</p>
        </div>

        <div v-else-if="currentRanking.length === 0" class="text-center py-8">
          <span class="text-4xl mb-4 block">📊</span>
          <p class="text-medieval text-gray-300">Nenhum herói encontrado no ranking.</p>
        </div>

        <div v-else class="space-y-2">
          <!-- Header da tabela -->
          <div class="grid grid-cols-12 gap-4 py-3 px-4 bg-gray-700 rounded-lg text-medieval font-semibold text-white border border-gray-600">
            <div class="col-span-1 text-center text-yellow-400">#</div>
            <div class="col-span-5 text-yellow-400">Nome do Herói</div>
            <div class="col-span-2 text-center text-yellow-400">Classe</div>
            <div class="col-span-2 text-center text-yellow-400">Nível</div>
            <div class="col-span-2 text-center text-yellow-400">CP</div>
          </div>

          <!-- Lista de jogadores -->
          <div 
            v-for="(player, index) in currentRanking" 
            :key="player.position"
            class="grid grid-cols-12 gap-4 py-3 px-4 rounded-lg transition-all duration-200 hover:bg-gray-700/50 border border-gray-600/50"
            :class="{
              'bg-gradient-to-r from-yellow-500/30 to-yellow-600/30 border-yellow-500/50 shadow-lg': player.position <= 3,
              'bg-gray-800/80': player.position > 3
            }"
          >
            <!-- Posição -->
            <div class="col-span-1 text-center flex items-center justify-center">
              <span 
                class="font-bold text-lg"
                :class="{
                  'text-yellow-300': player.position === 1,
                  'text-gray-200': player.position === 2,
                  'text-orange-300': player.position === 3,
                  'text-white': player.position > 3
                }"
              >
                {{ getPositionIcon(player.position) }}
              </span>
            </div>

            <!-- Nome -->
            <div class="col-span-5 flex items-center">
              <Link 
                :href="route('profile.show', player.name)"
                class="text-medieval font-semibold text-white truncate hover:text-yellow-400 transition-colors duration-200 cursor-pointer"
              >
                {{ player.name }}
              </Link>
            </div>

            <!-- Classe -->
            <div class="col-span-2 text-center flex items-center justify-center">
              <span class="text-medieval text-sm text-gray-300">{{ getClassName(player.class) }}</span>
            </div>

            <!-- Nível -->
            <div class="col-span-2 text-center flex items-center justify-center">
              <span class="text-medieval font-semibold text-green-400">{{ player.level }}</span>
            </div>

            <!-- CP -->
            <div class="col-span-2 text-center flex items-center justify-center">
              <span class="text-medieval font-semibold text-blue-400">{{ formatNumber(player.power) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-600 bg-gray-800/50">
        <div class="flex justify-between items-center text-medieval text-sm text-gray-300">
          <div>
            <span class="text-medieval font-semibold text-yellow-400">Total:</span> {{ currentRanking.length }} heróis
          </div>
          <div>
            <span class="text-medieval font-semibold text-yellow-400">Atualizado:</span> {{ formatDate(new Date()) }}
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Modal from './Modal.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const activeTab = ref('level')
const levelRanking = ref([])
const powerRanking = ref([])
const loading = ref(false)

const currentRanking = computed(() => {
  return activeTab.value === 'power' ? powerRanking.value : levelRanking.value
})

const getPositionIcon = (position) => {
  if (position === 1) return '🥇'
  if (position === 2) return '🥈'
  if (position === 3) return '🥉'
  return position
}

const getClassName = (classType) => {
  const classes = {
    'warrior': 'Guerreiro',
    'mage': 'Mago',
    'archer': 'Arqueiro',
    'rogue': 'Ladino',
    'paladin': 'Paladino',
    'priest': 'Sacerdote'
  }
  return classes[classType] || classType
}

const formatNumber = (number) => {
  return new Intl.NumberFormat('pt-BR').format(number)
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

const fetchRanking = async (type) => {
  loading.value = true
  try {
    const response = await fetch(`/api/ranking?type=${type}`)
    const data = await response.json()
    
    if (type === 'level') {
      levelRanking.value = data.ranking
    } else {
      powerRanking.value = data.ranking
    }
  } catch (error) {
    console.error('Erro ao carregar ranking:', error)
  } finally {
    loading.value = false
  }
}

// Carregar rankings quando o modal abrir
watch(() => props.show, (newValue) => {
  if (newValue) {
    fetchRanking('level')
    fetchRanking('power')
  }
})

// Carregar ranking inicial
onMounted(() => {
  if (props.show) {
    fetchRanking('level')
    fetchRanking('power')
  }
})
</script>
