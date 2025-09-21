<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="card-medieval max-w-md w-full">
      <!-- Header -->
      <div :class="headerClass" class="p-4 rounded-t-lg mb-4">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-bold title-medieval flex items-center text-white">
            <span class="mr-2">{{ icon }}</span>
            {{ title }}
          </h2>
          <button
            @click="close"
            class="text-white hover:text-gray-200 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Conteúdo -->
      <div class="p-4">
        <p class="text-medieval-dark text-medieval mb-4">{{ message }}</p>
        
        <!-- Botões -->
        <div class="flex justify-center">
          <button
            @click="close"
            :class="buttonClass"
            class="btn-medieval py-2 px-6 transition-colors"
          >
            {{ buttonText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'success', // success, error, warning, info
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    required: true
  },
  buttonText: {
    type: String,
    default: 'Entendi'
  }
})

const emit = defineEmits(['close'])

const icon = computed(() => {
  switch (props.type) {
    case 'success': return ''
    case 'error': return ''
    case 'warning': return ''
    case 'info': return ''
    default: return ''
  }
})

const headerClass = computed(() => {
  switch (props.type) {
    case 'success': return 'bg-green-700 text-white'
    case 'error': return 'bg-red-700 text-white'
    case 'warning': return 'bg-yellow-700 text-white'
    case 'info': return 'bg-blue-700 text-white'
    default: return 'bg-medieval-dark text-medieval-gold'
  }
})

const buttonClass = computed(() => {
  switch (props.type) {
    case 'success': return 'bg-green-700 text-white hover:bg-green-800'
    case 'error': return 'bg-red-700 text-white hover:bg-red-800'
    case 'warning': return 'bg-yellow-700 text-white hover:bg-yellow-800'
    case 'info': return 'bg-blue-700 text-white hover:bg-blue-800'
    default: return 'bg-medieval-gold text-medieval-dark hover:bg-yellow-500'
  }
})

const close = () => {
  emit('close')
}
</script>
