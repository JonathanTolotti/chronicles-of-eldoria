<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h1 class="text-2xl text-medieval font-bold text-medieval">Chronicles of Eldoria</h1>
          </div>
          <div class="flex-1 text-center">
            <h2 class="text-lg text-medieval font-medieval">Taverna do Mercador</h2>
          </div>
          <div class="flex-1 flex justify-end space-x-4">
            <!-- Recursos do Jogador -->
            <div class="flex items-center space-x-4 mr-4">
              <!-- Gold -->
              <div class="flex items-center space-x-1">
                <span class="text-sm text-medieval font-semibold text-yellow-400">{{ character.gold.toLocaleString() }}</span>
              </div>
              
              <!-- Coin -->
              <div class="flex items-center space-x-1">
                <span class="text-sm text-medieval font-semibold text-blue-400">{{ user.coin.toLocaleString() }}</span>
              </div>
            </div>
            
            <Link 
              :href="route('dashboard')" 
              class="btn-medieval text-sm text-medieval px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Voltar ao Dashboard
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <!-- Filtros e Busca -->
      <div class="card-medieval mb-6">
        <h3 class="text-xl text-medieval subtitle-medieval mb-4 text-medieval-gold">Filtros</h3>
        <div class="flex flex-wrap gap-4 items-center">
          <!-- Busca -->
          <div class="flex-1 min-w-64">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                    placeholder="Buscar itens..."
                class="w-full pl-10 pr-4 py-2 border-2 border-medieval-gold rounded-lg bg-white text-medieval-dark text-medieval focus:ring-2 focus:ring-medieval-gold focus:border-transparent"
                @input="debouncedSearch"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-medieval-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Filtro de Raridade -->
          <select
            v-model="selectedRarity"
            @change="applyFilters"
            class="px-4 py-2 border-2 border-medieval-gold rounded-lg bg-white text-medieval-dark text-medieval focus:ring-2 focus:ring-medieval-gold focus:border-transparent"
          >
            <option value="all">Todas as Raridades</option>
            <option v-for="(label, value) in rarities" :key="value" :value="value">
              {{ label }}
            </option>
          </select>

          <!-- Ordenação -->
          <select
            v-model="selectedSort"
            @change="applyFilters"
            class="px-4 py-2 border-2 border-medieval-gold rounded-lg bg-white text-medieval-dark text-medieval focus:ring-2 focus:ring-medieval-gold focus:border-transparent"
          >
            <option value="featured">Em Destaque</option>
            <option value="price_low">Menor Preço</option>
            <option value="price_high">Maior Preço</option>
            <option value="newest">Mais Recentes</option>
          </select>
        </div>
      </div>


      <!-- Itens em Destaque -->
      <div v-if="featuredItems.length > 0" class="card-medieval mb-6">
        <h3 class="text-xl text-medieval subtitle-medieval mb-4 text-medieval-gold">Itens em Destaque</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
          <ShopItemCard
            v-for="item in featuredItems"
            :key="item.id"
            :item="item"
            @purchase="handlePurchase"
          />
        </div>
      </div>

      <!-- Lista de Itens -->
      <div class="card-medieval">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl text-medieval subtitle-medieval text-medieval-gold">Todos os Itens</h3>
          <div class="text-sm text-medieval-dark text-medieval">
            Mostrando {{ shopItems.data.length }} de {{ shopItems.total }} itens
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-medieval-gold"></div>
        </div>

        <!-- Grid de Itens -->
        <div v-else-if="shopItems.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <ShopItemCard
            v-for="item in shopItems.data"
            :key="item.id"
            :item="item"
            @purchase="handlePurchase"
          />
        </div>

            <!-- Nenhum item encontrado -->
            <div v-else class="text-center py-12">
              <div class="text-6xl mb-4">🛒</div>
              <h3 class="text-xl font-semibold text-medieval-dark text-medieval mb-2">Nenhum item encontrado</h3>
              <p class="text-medieval-dark text-medieval">Tente ajustar os filtros ou buscar por outros termos.</p>
            </div>

        <!-- Paginação -->
        <div v-if="shopItems.last_page > 1" class="mt-8 flex justify-center">
          <nav class="flex space-x-2">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors',
                page === shopItems.current_page
                  ? 'bg-medieval-gold text-medieval-dark'
                  : 'bg-white text-medieval-dark text-medieval hover:bg-medieval-bronze border-2 border-medieval-gold'
              ]"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>
    </main>

        <!-- Modal de Compra -->
        <PurchaseModal
          v-if="showPurchaseModal"
          :item="selectedItem"
          :character="character"
          :user="user"
          @close="showPurchaseModal = false"
          @purchase="confirmPurchase"
        />

        <!-- Alerta Medieval -->
        <MedievalAlert
          :show="showAlert"
          :type="alertType"
          :title="alertTitle"
          :message="alertMessage"
          :button-text="alertButtonText"
          @close="handleAlertClose"
        />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import ShopItemCard from '@/Components/Shop/ShopItemCard.vue'
import PurchaseModal from '@/Components/Shop/PurchaseModal.vue'
import MedievalAlert from '@/Components/MedievalAlert.vue'

const props = defineProps({
  shopItems: Object,
  featuredItems: Array,
  character: Object,
  user: Object,
  filters: Object,
  categories: Object,
  rarities: Object,
})

const page = usePage()

// Estados reativos
const loading = ref(false)
const searchQuery = ref(props.filters.search || '')
const selectedCategory = ref(props.filters.category || 'all')
const selectedRarity = ref(props.filters.rarity || 'all')
const selectedSort = ref(props.filters.sort || 'featured')
const showPurchaseModal = ref(false)
const selectedItem = ref(null)

// Estados do alerta
const showAlert = ref(false)
const alertType = ref('success')
const alertTitle = ref('')
const alertMessage = ref('')
const alertButtonText = ref('Entendi')

// Função debounce simples
const debounce = (func, delay) => {
  let timeoutId
  return (...args) => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => func.apply(null, args), delay)
  }
}

// Debounced search
const debouncedSearch = debounce(() => {
  applyFilters()
}, 500)

// Computed para páginas visíveis
const visiblePages = computed(() => {
  const current = props.shopItems.current_page
  const last = props.shopItems.last_page
  const delta = 2
  const range = []
  const rangeWithDots = []

  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    rangeWithDots.push(1, '...')
  } else {
    rangeWithDots.push(1)
  }

  rangeWithDots.push(...range)

  if (current + delta < last - 1) {
    rangeWithDots.push('...', last)
  } else {
    rangeWithDots.push(last)
  }

  return rangeWithDots
})

// Métodos
const applyFilters = () => {
  loading.value = true
  
  router.get(route('shop.index'), {
    category: selectedCategory.value,
    rarity: selectedRarity.value,
    sort: selectedSort.value,
    search: searchQuery.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
    }
  })
}

const goToPage = (page) => {
  if (page === '...' || page === props.shopItems.current_page) return
  
  loading.value = true
  
  router.get(route('shop.index'), {
    page,
    category: selectedCategory.value,
    rarity: selectedRarity.value,
    sort: selectedSort.value,
    search: searchQuery.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
    }
  })
}

const handlePurchase = (item) => {
  selectedItem.value = item
  showPurchaseModal.value = true
}

// Função para mostrar alertas
const showMedievalAlert = (type, title, message, buttonText = 'Entendi') => {
  alertType.value = type
  alertTitle.value = title
  alertMessage.value = message
  alertButtonText.value = buttonText
  showAlert.value = true
}

// Função para lidar com o fechamento do alerta
const handleAlertClose = () => {
  showAlert.value = false
  
  // Se foi um alerta de sucesso, recarregar a página para atualizar estoque
  if (alertType.value === 'success') {
    setTimeout(() => {
      window.location.reload()
    }, 300) // Pequeno delay para animação
  }
}

// Função para obter token CSRF atualizado
const getCsrfToken = () => {
  return page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Função para renovar token CSRF se necessário
const refreshCsrfToken = async () => {
  try {
    // Simplesmente atualizar o token da meta tag
    const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (newToken) {
      page.props.csrf_token = newToken;
    }
  } catch (error) {
    console.warn('Erro ao renovar CSRF token:', error);
  }
}

const confirmPurchase = async (purchaseData) => {
  try {
    const csrfToken = getCsrfToken();
    
    const response = await fetch(route('shop.purchase'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify(purchaseData),
    })

    // Se receber erro 419, tentar renovar token e tentar novamente
    if (response.status === 419) {
      await refreshCsrfToken();
      
      const newCsrfToken = getCsrfToken();
      const retryResponse = await fetch(route('shop.purchase'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': newCsrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: JSON.stringify(purchaseData),
      });
      
      const retryResult = await retryResponse.json();
      
      if (retryResult.success) {
        // Atualizar dados locais
        props.character.gold = retryResult.character.gold
        props.user.coin = retryResult.user.coin
        
        // Guardar nome do item antes de limpar
        const itemName = selectedItem.value?.name || 'item'
        
        // Fechar modal
        showPurchaseModal.value = false
        selectedItem.value = null
        
        // Mostrar sucesso com alerta medieval
        showMedievalAlert(
          'success',
          'Compra Realizada!',
          `Você comprou ${purchaseData.quantity}x ${itemName} com sucesso!`,
          'OK'
        )
      } else {
        showMedievalAlert(
          'error',
          'Erro na Compra',
          retryResult.error || 'Não foi possível realizar a compra. Tente novamente.',
          'Entendi'
        )
      }
      return;
    }

    const result = await response.json()

    if (result.success) {
      // Atualizar dados locais
      props.character.gold = result.character.gold
      props.user.coin = result.user.coin
      
      // Guardar nome do item antes de limpar
      const itemName = selectedItem.value?.name || 'item'
      
      // Fechar modal
      showPurchaseModal.value = false
      selectedItem.value = null
      
      // Mostrar sucesso com alerta medieval
      showMedievalAlert(
        'success',
        'Compra Realizada!',
        `Você comprou ${purchaseData.quantity}x ${itemName} com sucesso!`,
        'OK'
      )
    } else {
      showMedievalAlert(
        'error',
        'Erro na Compra',
        result.error || 'Não foi possível realizar a compra. Tente novamente.',
        'Entendi'
      )
    }
  } catch (error) {
    console.error('Erro na compra:', error)
    showMedievalAlert(
      'error',
      'Erro na Compra',
      'Ocorreu um erro inesperado. Tente novamente mais tarde.',
      'Entendi'
    )
  }
}

// Watch para mudanças nos filtros
watch([selectedCategory, selectedRarity, selectedSort], () => {
  applyFilters()
})
</script>
