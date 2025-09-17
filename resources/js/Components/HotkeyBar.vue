<template>
  <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50">
    <div class="bg-gray-800 border border-gray-600 rounded-lg p-2 shadow-lg">
      <div class="flex space-x-2">
        <div 
          v-for="slot in 4" 
          :key="slot"
          class="hotkey-slot"
          :class="{ 
            'has-item': hotkeys[slot],
            'cooldown': cooldowns[slot] > 0
          }"
          @click="useHotkeyItem(slot)"
        >
          <div class="hotkey-number">F{{ slot }}</div>
          <div v-if="hotkeys[slot]" class="hotkey-item">
            <img 
              :src="hotkeys[slot].item.image_path" 
              :alt="hotkeys[slot].item.name"
              class="w-8 h-8 mx-auto mb-1"
            />
            <div class="text-xs text-center">{{ hotkeys[slot].quantity }}x</div>
          </div>
          <div v-else class="text-gray-500 text-xs">Vazio</div>
          
          <!-- Cooldown overlay -->
          <div v-if="cooldowns[slot] > 0" class="cooldown-overlay">
            <div class="cooldown-text">{{ cooldowns[slot] }}s</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const hotkeys = ref({})
const cooldowns = ref({})

// Inicializar cooldowns
for (let i = 1; i <= 4; i++) {
  cooldowns.value[i] = 0
}

const loadHotkeys = async () => {
  try {
    const response = await axios.get('/api/items/hotkeys')
    if (response.data.success) {
      hotkeys.value = response.data.hotkeys
    }
  } catch (error) {
    console.error('Erro ao carregar hotkeys:', error)
  }
}

const useHotkeyItem = async (slot) => {
  if (cooldowns.value[slot] > 0) {
    return // Item em cooldown
  }

  try {
    const response = await axios.post('/api/items/use-hotkey', {
      slot: slot
    })

    if (response.data.success) {
      // Mostrar efeitos aplicados
      if (response.data.effects?.length > 0) {
        showEffectNotification(response.data.effects)
      }
      
      // Definir cooldown (2 segundos)
      cooldowns.value[slot] = 2
      startCooldown(slot)
      
      // Recarregar hotkeys
      await loadHotkeys()
      
      // Emitir evento para atualizar stats do personagem
      window.dispatchEvent(new CustomEvent('character-stats-updated'))
    } else {
      showErrorNotification(response.data.message)
    }
  } catch (error) {
    console.error('Erro ao usar item:', error)
    showErrorNotification('Erro ao usar item')
  }
}

const startCooldown = (slot) => {
  const interval = setInterval(() => {
    cooldowns.value[slot]--
    if (cooldowns.value[slot] <= 0) {
      clearInterval(interval)
    }
  }, 1000)
}

const showEffectNotification = (effects) => {
  // Criar notificação visual
  const notification = document.createElement('div')
  notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50'
  notification.textContent = `Efeitos: ${effects.join(', ')}`
  
  document.body.appendChild(notification)
  
  setTimeout(() => {
    notification.remove()
  }, 3000)
}

const showErrorNotification = (message) => {
  const notification = document.createElement('div')
  notification.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50'
  notification.textContent = message
  
  document.body.appendChild(notification)
  
  setTimeout(() => {
    notification.remove()
  }, 3000)
}

// Sistema de hotkeys do teclado
const handleKeyPress = (event) => {
  if (event.key >= 'F1' && event.key <= 'F4') {
    event.preventDefault()
    const slot = parseInt(event.key.replace('F', ''))
    useHotkeyItem(slot)
  }
}

onMounted(() => {
  loadHotkeys()
  window.addEventListener('keydown', handleKeyPress)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyPress)
})
</script>

<style scoped>
.hotkey-slot {
  @apply bg-gray-700 border border-gray-600 rounded-lg p-2 text-center cursor-pointer transition-all duration-200 relative;
  width: 60px;
  height: 60px;
}

.hotkey-slot:hover {
  @apply bg-gray-600 border-gray-500 transform scale-105;
}

.hotkey-slot.has-item {
  @apply border-blue-500 bg-blue-900/20;
}

.hotkey-slot.cooldown {
  @apply opacity-50 cursor-not-allowed;
}

.hotkey-number {
  @apply text-xs text-gray-400 absolute top-1 left-1;
}

.hotkey-item {
  @apply text-center pt-2;
}

.cooldown-overlay {
  @apply absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center;
}

.cooldown-text {
  @apply text-white font-bold text-sm;
}
</style>
