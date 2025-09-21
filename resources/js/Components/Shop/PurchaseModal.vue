<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="card-medieval max-w-md w-full">
      <!-- Header -->
      <div class="bg-medieval-dark text-medieval-gold p-4 rounded-t-lg mb-4">
        <div class="flex items-center justify-between">
          <h2 class="text-xl text-medieval font-bold title-medieval">Confirmar Compra</h2>
          <button
            @click="$emit('close')"
            class="text-medieval-gold hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Conteúdo -->
      <div class="p-4">
        <!-- Item Info -->
        <div class="flex items-center space-x-4 mb-6">
          <div class="w-16 h-16 flex items-center justify-center">
            <img
              v-if="item.image_path"
              :src="item.image_path"
              :alt="item.name"
              class="w-full h-full object-cover rounded-lg"
            />
            <span v-else class="text-2xl">{{ getItemIcon() }}</span>
          </div>
          <div class="flex-1">
            <h3 class="text-xl text-medieval font-bold text-gray-800">{{ item.name }}</h3>
            <p class="text-sm text-gray-600 text-medieval capitalize">{{ getCategoryLabel() }}</p>
            <span :class="getRarityClass()" class="text-xs px-2 py-1 rounded-full font-bold text-medieval">
              {{ getRarityLabel() }}
            </span>
          </div>
        </div>

        <!-- Informação de Compra Única -->
        <div v-if="item.is_one_time_purchase" class="mb-4">
          <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
              <span class="text-sm text-purple-700 text-medieval font-medium">
                Este item só pode ser comprado uma vez por personagem
              </span>
            </div>
          </div>
        </div>

        <!-- Quantidade -->
        <div class="mb-4">
          <label class="block text-sm text-medieval font-medium text-gray-700 mb-2">
            Quantidade
          </label>
          <div class="flex items-center space-x-3">
            <button
              @click="decreaseQuantity"
              :disabled="quantity <= 1 || item.is_one_time_purchase"
              class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
              </svg>
            </button>
            <input
              v-model.number="quantity"
              type="number"
              min="1"
              :max="maxQuantity"
              :disabled="item.is_one_time_purchase"
              class="w-16 text-center text-medieval border border-gray-300 rounded-lg py-1 focus:ring-2 focus:ring-amber-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
            />
            <button
              @click="increaseQuantity"
              :disabled="quantity >= maxQuantity || item.is_one_time_purchase"
              class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </button>
          </div>
          <p class="text-xs text-gray-500 mt-1 text-medieval">
            <span v-if="item.is_one_time_purchase">Quantidade fixa: 1 unidade</span>
            <span v-else>Máximo: {{ maxQuantity }} unidades</span>
          </p>
        </div>

        <!-- Preços -->
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
          <h4 class="text-sm text-medieval font-medium text-gray-700 mb-2">Resumo da Compra</h4>
          
          <div class="space-y-2">
            <div v-if="totalGoldCost > 0" class="flex justify-between items-center">
              <span class="text-sm text-gray-600 text-medieval">Gold:</span>
              <div class="flex items-center">
                <span class="text-lg font-bold text-yellow-600 text-medieval">🪙 {{ totalGoldCost.toLocaleString() }}</span>
                <span v-if="item.discount_percentage > 0" class="ml-2 text-sm text-gray-500 line-through text-medieval">
                  {{ (item.gold_price * quantity).toLocaleString() }}
                </span>
              </div>
            </div>
            
            <div v-if="totalCoinCost > 0" class="flex justify-between items-center">
              <span class="text-sm text-gray-600 text-medieval">Coin:</span>
              <div class="flex items-center">
                <span class="text-lg font-bold text-blue-600 text-medieval">💎 {{ totalCoinCost.toLocaleString() }}</span>
                <span v-if="item.discount_percentage > 0" class="ml-2 text-sm text-gray-500 line-through text-medieval">
                  {{ (item.coin_price * quantity).toLocaleString() }}
                </span>
              </div>
            </div>
            
            <div v-if="item.discount_percentage > 0" class="pt-2 border-t border-gray-200">
              <div class="flex justify-between items-center">
                <span class="text-sm text-green-600 font-medium text-medieval">Desconto:</span>
                <span class="text-sm text-green-600 font-bold text-medieval">-{{ item.discount_percentage }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Saldo Atual -->
        <div class="bg-blue-50 rounded-lg p-3 mb-4">
          <h4 class="text-sm text-medieval font-medium text-gray-700 mb-2">Seu Saldo</h4>
          <div class="space-y-1">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 text-medieval">Gold:</span>
              <span class="text-sm font-bold text-medieval" :class="character.gold >= totalGoldCost ? 'text-green-600' : 'text-red-600'">
                🪙 {{ character.gold.toLocaleString() }}
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 text-medieval">Coin:</span>
              <span class="text-sm font-bold text-medieval" :class="user.coin >= totalCoinCost ? 'text-green-600' : 'text-red-600'">
                💎 {{ user.coin.toLocaleString() }}
              </span>
            </div>
          </div>
        </div>

        <!-- Validações -->
        <div v-if="!canAfford" class="bg-red-50 border border-red-200 rounded-lg p-2 mb-3">
          <div class="flex items-center">
            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm text-red-700 text-medieval">Moedas insuficientes para esta compra</span>
          </div>
        </div>

        <div v-if="!meetsLevelRequirement" class="bg-red-50 border border-red-200 rounded-lg p-2 mb-3">
          <div class="flex items-center">
            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm text-red-700 text-medieval">Nível insuficiente (Requerido: {{ item.min_level }}+)</span>
          </div>
        </div>

        <!-- Botões -->
        <div class="flex space-x-2">
          <button
            @click="$emit('close')"
            class="flex-1 btn-medieval py-1.5 px-3 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark text-sm"
          >
            Cancelar
          </button>
          <button
            @click="confirmPurchase"
            :disabled="!canPurchase"
            :class="[
              'flex-1 btn-medieval py-1.5 px-3 transition-colors text-sm',
              canPurchase
                ? 'bg-medieval-gold text-medieval-dark hover:bg-yellow-500'
                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
            ]"
          >
            Confirmar Compra
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  character: {
    type: Object,
    required: true
  },
  user: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close', 'purchase'])

// Estados reativos
const quantity = ref(1)

// Computed properties
const maxQuantity = computed(() => {
  if (props.item.stock_quantity === null) return 10
  return Math.min(props.item.stock_quantity, 10)
})

const totalGoldCost = computed(() => {
  return props.item.discounted_gold_price * quantity.value
})

const totalCoinCost = computed(() => {
  return props.item.discounted_coin_price * quantity.value
})

const canAfford = computed(() => {
  const goldOk = props.character.gold >= totalGoldCost.value
  const coinOk = props.user.coin >= totalCoinCost.value
  return goldOk && coinOk
})

const meetsLevelRequirement = computed(() => {
  return props.character.level >= props.item.min_level
})

const canPurchase = computed(() => {
  return canAfford.value && meetsLevelRequirement.value && quantity.value > 0
})

// Métodos
const increaseQuantity = () => {
  if (quantity.value < maxQuantity.value) {
    quantity.value++
  }
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const confirmPurchase = () => {
  if (!canPurchase.value) return

  emit('purchase', {
    shop_item_id: props.item.id,
    quantity: quantity.value
  })
}

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

// Watch para resetar quantidade quando o item muda
watch(() => props.item.id, () => {
  quantity.value = props.item.is_one_time_purchase ? 1 : 1
})

// Watch para definir quantidade como 1 se for compra única
watch(() => props.item.is_one_time_purchase, (isOneTime) => {
  if (isOneTime) {
    quantity.value = 1
  }
}, { immediate: true })
</script>
