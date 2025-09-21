<template>
  <div :class="[
    'relative bg-white rounded-lg shadow-lg border-2 border-medieval-bronze hover:border-medieval-gold transition-all duration-300 overflow-hidden group h-full flex flex-col',
    item.rarity === 'legendary' ? 'border-purple-400 shadow-lg shadow-purple-200 legendary-glow' : ''
  ]">
    <!-- Efeito piscante intenso para lendários -->
    <div v-if="item.rarity === 'legendary'" class="absolute inset-0 rounded-lg border-2 border-purple-400 shadow-lg shadow-purple-200 animate-pulse pointer-events-none"></div>
    <div v-if="item.rarity === 'legendary'" class="absolute inset-0 rounded-lg bg-gradient-to-r from-purple-400/10 via-purple-300/20 to-purple-400/10 animate-pulse pointer-events-none"></div>
    <!-- Imagem do Item -->
    <div class="relative h-32 bg-gradient-to-br from-amber-50 to-yellow-100 flex items-center justify-center">
      <img
        v-if="item.image_path"
        :src="item.image_path"
        :alt="item.name"
        class="w-16 h-16 object-cover"
      />
      <div v-else class="text-4xl text-medieval-dark">
        {{ getItemIcon() }}
      </div>
      
          <!-- Badges -->
          <div class="absolute top-2 left-2 flex flex-col space-y-1">
            <span
              v-if="item.is_limited_time"
              class="bg-orange-600 text-white text-xs px-2 py-1 rounded-full font-bold text-medieval"
            >
              ⏰ LIMITADO
            </span>
          </div>

      <!-- Raridade -->
      <div class="absolute top-2 right-2">
        <span :class="getRarityClass()" class="text-xs px-2 py-1 rounded-full font-bold text-medieval">
          {{ getRarityLabel() }}
        </span>
      </div>
    </div>

    <!-- Conteúdo do Card -->
    <div class="p-4 flex-1 flex flex-col">
      <!-- Nome e Categoria -->
      <div class="mb-3">
        <h3 class="text-lg font-bold text-medieval-dark text-medieval mb-1">{{ item.name }}</h3>
        <p class="text-sm text-medieval-dark text-medieval capitalize">{{ getCategoryLabel() }}</p>
      </div>

      <!-- Descrição -->
      <p v-if="item.description" class="text-sm text-medieval-dark text-medieval mb-3 line-clamp-2 flex-1">
        {{ item.description }}
      </p>

      <!-- Requisitos de Nível -->
      <div v-if="item.min_level > 1" class="mb-3">
        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-medieval">
          Nível {{ item.min_level }}+
        </span>
      </div>

      <!-- Preços -->
      <div class="mb-4">
        <div class="flex items-center justify-between">
          <div class="flex flex-col space-y-1">
            <div v-if="item.discounted_gold_price > 0" class="flex items-center">
              <span class="text-lg font-bold text-yellow-600">🪙 {{ item.discounted_gold_price.toLocaleString() }}</span>
              <span v-if="item.discount_percentage > 0" class="ml-2 text-sm text-gray-500 line-through">
                {{ item.gold_price.toLocaleString() }}
              </span>
            </div>
            <div v-if="item.discounted_coin_price > 0" class="flex items-center">
              <span class="text-lg font-bold text-blue-600">💎 {{ item.discounted_coin_price.toLocaleString() }}</span>
              <span v-if="item.discount_percentage > 0" class="ml-2 text-sm text-gray-500 line-through">
                {{ item.coin_price.toLocaleString() }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Estoque -->
      <div v-if="item.stock_quantity !== null" class="mb-3">
        <div class="text-xs text-medieval-dark text-medieval">
          <span v-if="item.stock_quantity > 0">
            Estoque: {{ item.stock_quantity }} unidades
          </span>
          <span v-else class="text-red-600 font-bold text-medieval">
            Esgotado
          </span>
        </div>
      </div>

      <!-- Botão de Compra -->
      <button
        @click="$emit('purchase', item)"
        :disabled="!item.can_buy || item.stock_quantity === 0 || item.already_purchased"
        :class="[
          'w-full py-2 px-4 rounded-lg font-bold transition-all duration-200 mt-auto',
          item.can_buy && item.stock_quantity !== 0 && !item.already_purchased
            ? 'bg-medieval-gold text-medieval-dark hover:bg-yellow-500 transform hover:scale-105'
            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
        ]"
      >
        <span v-if="item.already_purchased" class="text-medieval">Já Comprado</span>
        <span v-else-if="!item.can_buy" class="text-medieval">Requisitos não atendidos</span>
        <span v-else-if="item.stock_quantity === 0" class="text-medieval">Esgotado</span>
        <span v-else class="text-medieval">Comprar</span>
      </button>

      <!-- Informações adicionais -->
      <div v-if="item.daily_limit" class="mt-2 text-xs text-medieval-dark text-medieval text-center">
        Limite diário: {{ item.daily_limit }} por personagem
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true
  }
})

defineEmits(['purchase'])

const getItemIcon = () => {
  const category = props.item?.category || 'unknown'
  const icons = {
    weapon: '⚔️',
    armor: '🛡️',
    accessory: '💍',
    potion: '🧪',
    material: '📦',
    special: '✨'
  }
  return icons[category] || '📦'
}

const getCategoryLabel = () => {
  const category = props.item?.category || 'unknown'
  const labels = {
    weapon: 'Arma',
    armor: 'Armadura',
    accessory: 'Acessório',
    potion: 'Poção',
    material: 'Material',
    special: 'Especial'
  }
  return labels[category] || category
}

const getRarityClass = () => {
  const rarity = props.item?.rarity || 'common'
  const classes = {
    common: 'bg-gray-100 text-gray-800',
    uncommon: 'bg-green-100 text-green-800',
    rare: 'bg-blue-100 text-blue-800',
    epic: 'bg-purple-100 text-purple-800',
    legendary: 'bg-purple-200 text-purple-900'
  }
  return classes[rarity] || classes.common
}

const getRarityLabel = () => {
  const rarity = props.item?.rarity || 'common'
  const labels = {
    common: 'Comum',
    uncommon: 'Incomum',
    rare: 'Raro',
    epic: 'Épico',
    legendary: 'Lendário'
  }
  return labels[rarity] || 'Comum'
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Efeito de brilho para itens lendários */
@keyframes legendary-glow {
  0%, 100% {
    box-shadow: 0 0 5px rgba(147, 51, 234, 0.3), 0 0 10px rgba(147, 51, 234, 0.2), 0 0 15px rgba(147, 51, 234, 0.1);
  }
  50% {
    box-shadow: 0 0 10px rgba(147, 51, 234, 0.6), 0 0 20px rgba(147, 51, 234, 0.4), 0 0 30px rgba(147, 51, 234, 0.2);
  }
}

.legendary-glow {
  animation: legendary-glow 2s ease-in-out infinite;
}
</style>
