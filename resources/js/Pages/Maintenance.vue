<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
      <!-- Main Card -->
      <div class="card-medieval text-center p-8 sm:p-12">
        <!-- Icon -->
        <div class="text-6xl sm:text-8xl mb-6 animate-pulse">
          ⚔️
        </div>
        
        <!-- Title -->
        <h1 class="text-3xl sm:text-4xl font-medieval-decorative font-bold text-medieval-gold mb-4">
          Chronicles of Eldoria
        </h1>
        
        <!-- Subtitle -->
        <h2 class="text-xl sm:text-2xl font-medieval text-medieval-bronze mb-6">
          Em Manutenção
        </h2>
        
        <!-- Message -->
        <div class="bg-medieval-dark/10 rounded-lg p-6 mb-8">
          <p class="text-lg text-medieval font-medium mb-4">
            Nossos artesãos estão trabalhando arduamente para melhorar sua experiência!
          </p>
          <p class="text-medieval-stone">
            Estamos realizando atualizações importantes no reino. 
            Retorne em breve para continuar sua jornada épica.
          </p>
        </div>
        
        
        <!-- Estimated Time -->
        <div class="text-medieval-stone mb-8">
          <p class="text-sm">
            <span class="font-semibold">Tempo estimado:</span> {{ estimatedTime }}
          </p>
        </div>
        
        <!-- Refresh Button -->
        <button 
          @click="refreshPage"
          class="btn-medieval-primary px-8 py-3 text-lg font-semibold"
        >
          🔄 Tentar Novamente
        </button>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-medieval-bronze/30">
          <p class="text-sm text-medieval-stone">
            Obrigado pela sua paciência, aventureiro!
          </p>
        </div>
      </div>
      
      <!-- Decorative Elements -->
      <div class="absolute top-4 left-4 text-2xl opacity-20 animate-bounce" style="animation-delay: 0s">🏰</div>
      <div class="absolute top-8 right-8 text-xl opacity-20 animate-bounce" style="animation-delay: 1s">⚔️</div>
      <div class="absolute bottom-8 left-8 text-xl opacity-20 animate-bounce" style="animation-delay: 2s">🛡️</div>
      <div class="absolute bottom-4 right-4 text-2xl opacity-20 animate-bounce" style="animation-delay: 3s">🏰</div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Props (opcional - pode ser passado via Inertia)
const props = defineProps({
  message: {
    type: String,
    default: 'Nossos artesãos estão trabalhando arduamente para melhorar sua experiência!'
  },
  estimatedTime: {
    type: String,
    default: '15-30 minutos'
  }
});

// Reactive data
const estimatedTime = ref(props.estimatedTime);

const refreshPage = () => {
  window.location.reload();
};
</script>

<style scoped>
/* Custom animations */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Ensure decorative elements are positioned correctly */
.absolute {
  position: absolute;
}
</style>
