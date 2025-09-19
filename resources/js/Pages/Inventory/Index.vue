<template>
  <div class="min-h-screen bg-gradient-to-br from-medieval-dark via-medieval-stone to-medieval-dark">
    <!-- Header -->
    <div class="bg-medieval-dark shadow-lg border-b border-medieval-gold">
      <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold text-medieval-gold text-medieval mb-2">Inventário</h1>
            <p class="text-medieval-gold text-medieval">Gerencie todos os seus itens e equipamentos</p>
          </div>
          <div class="flex items-center space-x-4">
            <Link :href="route('dashboard')" 
                  class="bg-medieval-gold hover:bg-yellow-500 text-medieval-dark px-6 py-3 rounded-lg font-semibold text-medieval transition-colors">
              ← Voltar ao Dashboard
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-4">
      <!-- Filtros e Abas -->
      <div class="bg-white rounded-lg shadow-lg mb-6">
        <!-- Abas -->
        <div class="border-b border-gray-200">
          <nav class="flex space-x-8 px-6">
            <button v-for="tab in tabs" :key="tab.key"
                    @click="activeTab = tab.key"
                    :class="[
                      'py-4 px-1 border-b-2 font-medium text-sm text-medieval transition-colors',
                      activeTab === tab.key 
                        ? 'border-medieval-gold text-medieval-gold' 
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    ]">
              {{ tab.label }}
              <span v-if="tab.count !== undefined" class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">
                {{ tab.count }}
              </span>
            </button>
          </nav>
        </div>

        <!-- Filtros -->
        <div class="p-6 border-b border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div>
              <label class="block text-sm font-medium text-gray-700 text-medieval mb-2">Buscar</label>
              <input v-model="filters.search" 
                     type="text" 
                     placeholder="Nome do item..."
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-medieval-gold focus:border-transparent text-medieval">
            </div>

            <!-- Filtros específicos por aba -->
            <!-- Tipo (apenas para equipamentos) -->
            <div v-if="activeTab === 'equipment'">
              <label class="block text-sm font-medium text-gray-700 text-medieval mb-2">Tipo</label>
              <select v-model="filters.type" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-medieval-gold focus:border-transparent text-medieval">
                <option value="">Todos os tipos</option>
                <option value="helmet">Elmo</option>
                <option value="armor">Armadura</option>
                <option value="weapon">Arma</option>
                <option value="shield">Escudo</option>
                <option value="pants">Calça</option>
                <option value="boots">Bota</option>
              </select>
            </div>

            <!-- Tier (apenas para equipamentos) -->
            <div v-if="activeTab === 'equipment'">
              <label class="block text-sm font-medium text-gray-700 text-medieval mb-2">Tier</label>
              <select v-model="filters.tier" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-medieval-gold focus:border-transparent text-medieval">
                <option value="">Todos os tiers</option>
                <option value="0">Tier 0</option>
                <option value="1">Tier 1</option>
                <option value="2">Tier 2</option>
                <option value="3">Tier 3</option>
                <option value="4">Tier 4</option>
                <option value="5">Tier 5</option>
                <option value="6">Tier 6</option>
                <option value="7">Tier 7</option>
                <option value="8">Tier 8</option>
                <option value="9">Tier 9</option>
                <option value="10">Tier 10</option>
                <option value="11">Tier 11</option>
                <option value="12">Tier 12</option>
              </select>
            </div>

            <!-- Raridade (apenas para materiais) -->
            <div v-if="activeTab === 'materials'">
              <label class="block text-sm font-medium text-gray-700 text-medieval mb-2">Raridade</label>
              <select v-model="filters.rarity" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-medieval-gold focus:border-transparent text-medieval">
                <option value="">Todas as raridades</option>
                <option value="common">Comum</option>
                <option value="rare">Raro</option>
                <option value="epic">Épico</option>
                <option value="legendary">Lendário</option>
              </select>
            </div>

            <!-- Ordenação -->
            <div>
              <label class="block text-sm font-medium text-gray-700 text-medieval mb-2">Ordenar por</label>
              <select v-model="filters.sort" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-medieval-gold focus:border-transparent text-medieval">
                <option value="name">Nome</option>
                <option v-if="activeTab === 'potions' || activeTab === 'materials'" value="quantity">Quantidade</option>
                <option v-if="activeTab === 'equipment'" value="tier">Tier</option>
                <option v-if="activeTab === 'equipment' || activeTab === 'materials'" value="rarity">Raridade</option>
                <option v-if="activeTab === 'equipment'" value="type">Tipo</option>
              </select>
            </div>
          </div>

          <!-- Botões de ação -->
          <div class="mt-4 flex justify-between">
            <button @click="clearFilters" 
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 text-medieval">
              Limpar filtros
            </button>
            <div class="flex space-x-2">
              <button @click="toggleViewMode" 
                      :class="[
                        'px-4 py-2 rounded-md text-sm font-medium transition-colors text-medieval',
                        viewMode === 'grid' 
                          ? 'bg-medieval-gold text-medieval-dark' 
                          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                      ]">
                {{ viewMode === 'grid' ? 'Grade' : 'Lista' }}
              </button>
            </div>
          </div>
        </div>
        
        <!-- Inventário Rápido - Drag and Drop -->
        <div class="p-1 border-t border-gray-200">
          <div class="flex justify-between items-center mb-0.5">
            <h3 class="text-xs font-semibold text-medieval-dark text-medieval">Inventário Rápido</h3>
            <span class="text-xs text-gray-600 text-medieval">{{ quickInventoryCount }}/8</span>
          </div>
          
          <!-- Área de Drop -->
          <div class="grid grid-cols-8 gap-1 p-2 border border-dashed border-gray-300 rounded"
               @drop="handleDrop"
               @dragover.prevent
               @dragenter.prevent>
            
            <!-- Slots do Inventário Rápido -->
            <div v-for="(slot, index) in quickInventorySlots" :key="`slot-${index}`"
                 class="bg-gray-50 rounded-lg border border-medieval-bronze p-2 hover:bg-amber-100 transition-colors flex flex-col items-center justify-center min-h-[60px]"
                 :class="{ 'border-medieval-gold bg-amber-50': slot.item }">
              
              <!-- Item no slot -->
              <div v-if="slot.item" 
                   class="w-full h-full flex flex-col items-center justify-center cursor-move"
                   draggable="true"
                   @dragstart="handleDragStart($event, slot.item, index)">
                <img :src="slot.item.image" :alt="slot.item.name" class="w-7 h-7 object-contain mb-1">
                <span class="text-xs text-medieval-dark text-medieval text-center">{{ slot.item.quantity }}x</span>
              </div>
              
              <!-- Slot vazio -->
              <div v-else class="text-gray-400 text-medieval text-xs">
                +
              </div>
            </div>
          </div>
          
          <!-- Área de Remoção -->
          <div class="mt-2 p-2 border border-dashed border-red-300 rounded bg-red-50 text-center"
               @drop="handleRemoveFromQuickInventory"
               @dragover.prevent
               @dragenter.prevent>
            <div class="text-xs text-red-600 text-medieval">
              Arraste aqui para remover do inventário rápido
            </div>
          </div>
        </div>
      </div>

      <!-- Conteúdo das Abas -->
      <div class="bg-white rounded-lg shadow-lg">
        <!-- Poções -->
        <div v-if="activeTab === 'potions'" class="p-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-medieval-dark text-medieval">Poções</h2>
            <div class="text-sm text-gray-600 text-medieval">
              {{ filteredPotions.length }} de {{ potions.length }} itens
            </div>
          </div>

          <!-- Grid de Poções -->
          <div v-if="viewMode === 'grid'" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
            <div v-for="item in paginatedPotions" :key="`potion-${item.id}`"
                 :class="[
                   'bg-gray-50 rounded-lg border border-medieval-bronze p-2 transition-colors relative group',
                   isItemInQuickInventory(item) 
                     ? 'opacity-50 cursor-not-allowed' 
                     : 'hover:bg-amber-100 cursor-move'
                 ]"
                 :draggable="!isItemInQuickInventory(item)"
                 @dragstart="handleDragStart($event, item, null)">
              <div class="text-center">
                <img :src="item.image" :alt="item.name" class="w-8 h-8 mx-auto mb-1 object-contain">
                <h3 class="font-semibold text-medieval-dark text-medieval text-xs mb-0.5">{{ item.name }}</h3>
                <p class="text-xs text-gray-600 text-medieval">{{ item.quantity }}x</p>
              </div>
              
              
              <!-- Tooltip -->
              <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs text-medieval rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                <div class="text-center">
                  <div class="font-semibold mb-1">{{ item.name }}</div>
                  <div class="text-gray-300">{{ item.description }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Lista de Poções -->
          <div v-else class="space-y-2">
            <div v-for="item in paginatedPotions" :key="`potion-list-${item.id}`"
                 class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-amber-100 transition-colors">
              <div class="flex items-center space-x-4">
                <img :src="item.image" :alt="item.name" class="w-8 h-8 object-contain">
                <div>
                  <h3 class="font-semibold text-medieval-dark text-medieval">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600 text-medieval">{{ item.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 text-medieval">{{ item.quantity }}x</span>
                
              </div>
            </div>
          </div>

          <!-- Paginação -->
          <div v-if="totalPotionsPages > 1" class="mt-6 flex justify-center">
            <nav class="flex space-x-2">
              <button v-for="page in totalPotionsPages" :key="`potion-page-${page}`"
                      @click="currentPotionsPage = page"
                      :class="[
                        'px-3 py-2 text-sm font-medium rounded-md text-medieval transition-colors',
                        currentPotionsPage === page 
                          ? 'bg-medieval-gold text-medieval-dark' 
                          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                      ]">
                {{ page }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Equipamentos -->
        <div v-if="activeTab === 'equipment'" class="p-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-medieval-dark text-medieval">Equipamentos</h2>
            <div class="text-sm text-gray-600 text-medieval">
              {{ filteredEquipment.length }} de {{ equipment.length }} itens
            </div>
          </div>

          <!-- Grid de Equipamentos -->
          <div v-if="viewMode === 'grid'" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
            <div v-for="item in paginatedEquipment" :key="`equipment-${item.id}`"
                 :class="[
                   'bg-gray-50 rounded-lg border border-medieval-bronze p-2 transition-colors relative group',
                   hasSpecialTier(item.current_tier || 0) ? getTierEffect(item.current_tier) : '',
                   isItemInQuickInventory(item) 
                     ? 'opacity-50 cursor-not-allowed' 
                     : 'hover:bg-amber-100 cursor-move'
                 ]"
                 :draggable="!isItemInQuickInventory(item)"
                 @dragstart="handleDragStart($event, item, null)">
              <div class="text-center">
                <img :src="item.equipment.image" :alt="item.equipment.name" class="w-8 h-8 mx-auto mb-1 object-contain">
                <h3 class="font-semibold text-medieval-dark text-medieval text-xs mb-0.5">{{ item.equipment.name }}</h3>
                <p class="text-xs text-gray-600 text-medieval mb-1">{{ item.equipment.type }}</p>
                <div v-if="item.is_equipped" class="text-xs bg-green-500 text-white px-1 py-0.5 rounded text-medieval font-bold">
                  ✓ EQUIPADO
                </div>
              </div>
              
              
              <!-- Tooltip -->
              <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs text-medieval rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                <div class="text-center">
                  <div class="font-semibold mb-1">{{ item.equipment.name }} (T{{ item.current_tier || 0 }})</div>
                  <div class="text-gray-300">{{ item.equipment.description }}</div>
                  <div v-if="item.is_equipped" class="mt-1 text-green-300 font-bold">✓ EQUIPADO</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Lista de Equipamentos -->
          <div v-else class="space-y-2">
            <div v-for="item in paginatedEquipment" :key="`equipment-list-${item.id}`"
                 class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-amber-100 transition-colors">
              <div class="flex items-center space-x-4">
                <img :src="item.equipment.image" :alt="item.equipment.name" class="w-8 h-8 object-contain">
                <div>
                  <h3 class="font-semibold text-medieval-dark text-medieval">{{ item.equipment.name }}</h3>
                  <p class="text-sm text-gray-600 text-medieval">{{ item.equipment.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 text-medieval">T{{ item.current_tier || 0 }}</span>
                <span v-if="item.is_equipped" class="bg-green-500 text-white px-2 py-1 rounded text-xs text-medieval">
                  Equipado
                </span>
                
                
                <button @click="equipItem(item)" 
                        class="bg-medieval-gold hover:bg-yellow-500 text-medieval-dark px-3 py-1 rounded text-sm text-medieval">
                  {{ item.is_equipped ? 'Desequipar' : 'Equipar' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Paginação -->
          <div v-if="totalEquipmentPages > 1" class="mt-6 flex justify-center">
            <nav class="flex space-x-2">
              <button v-for="page in totalEquipmentPages" :key="`equipment-page-${page}`"
                      @click="currentEquipmentPage = page"
                      :class="[
                        'px-3 py-2 text-sm font-medium rounded-md text-medieval transition-colors',
                        currentEquipmentPage === page 
                          ? 'bg-medieval-gold text-medieval-dark' 
                          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                      ]">
                {{ page }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Materiais -->
        <div v-if="activeTab === 'materials'" class="p-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-medieval-dark text-medieval">Materiais</h2>
            <div class="text-sm text-gray-600 text-medieval">
              {{ filteredMaterials.length }} de {{ materials.length }} itens
            </div>
          </div>

          <!-- Grid de Materiais -->
          <div v-if="viewMode === 'grid'" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
            <div v-for="item in paginatedMaterials" :key="`material-${item.id}`"
                 :class="[
                   'bg-gray-50 rounded-lg border border-medieval-bronze p-2 transition-colors relative group',
                   isItemInQuickInventory(item) 
                     ? 'opacity-50 cursor-not-allowed' 
                     : 'hover:bg-amber-100 cursor-move'
                 ]"
                 :draggable="!isItemInQuickInventory(item)"
                 @dragstart="handleDragStart($event, item, null)">
              <div class="text-center">
                <img :src="item.image" :alt="item.name" class="w-8 h-8 mx-auto mb-1 object-contain">
                <h3 class="font-semibold text-medieval-dark text-medieval text-xs mb-0.5">{{ item.name }}</h3>
                <p class="text-xs text-gray-600 text-medieval">{{ item.quantity }}x</p>
              </div>
              
              
              <!-- Tooltip -->
              <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs text-medieval rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                <div class="text-center">
                  <div class="font-semibold mb-1">{{ item.name }}</div>
                  <div class="text-gray-300">{{ item.description }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Lista de Materiais -->
          <div v-else class="space-y-2">
            <div v-for="item in paginatedMaterials" :key="`material-list-${item.id}`"
                 class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-amber-100 transition-colors">
              <div class="flex items-center space-x-4">
                <img :src="item.image" :alt="item.name" class="w-8 h-8 object-contain">
                <div>
                  <h3 class="font-semibold text-medieval-dark text-medieval">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600 text-medieval">{{ item.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 text-medieval">{{ item.quantity }}x</span>
                
              </div>
            </div>
          </div>

          <!-- Paginação -->
          <div v-if="totalMaterialsPages > 1" class="mt-6 flex justify-center">
            <nav class="flex space-x-2">
              <button v-for="page in totalMaterialsPages" :key="`material-page-${page}`"
                      @click="currentMaterialsPage = page"
                      :class="[
                        'px-3 py-2 text-sm font-medium rounded-md text-medieval transition-colors',
                        currentMaterialsPage === page 
                          ? 'bg-medieval-gold text-medieval-dark' 
                          : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                      ]">
                {{ page }}
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  potions: Array,
  equipment: Array,
  materials: Array,
  character: Object,
});

// Estado reativo
const activeTab = ref('potions');
const viewMode = ref('grid');
const currentPotionsPage = ref(1);
const currentEquipmentPage = ref(1);
const currentMaterialsPage = ref(1);
const itemsPerPage = 24;


const filters = ref({
  search: '',
  type: '',
  rarity: '',
  tier: '',
  sort: 'name'
});

// Abas
const tabs = computed(() => [
  { key: 'potions', label: 'Poções', count: potions.value?.length || 0 },
  { key: 'equipment', label: 'Equipamentos', count: equipment.value?.length || 0 },
  { key: 'materials', label: 'Materiais', count: materials.value?.length || 0 },
]);

// Dados reativos
const potions = ref(props.potions || []);
const equipment = ref(props.equipment || []);
const materials = ref(props.materials || []);

// Drag and Drop
const quickInventorySlots = ref(Array(8).fill(null).map(() => ({ item: null })));
const draggedItem = ref(null);
const draggedSlotIndex = ref(null);

// Filtros computados
const filteredPotions = computed(() => {
  let filtered = potions.value;
  
  if (filters.value.search) {
    filtered = filtered.filter(item => 
      (item.name || '').toLowerCase().includes(filters.value.search.toLowerCase())
    );
  }
  
  if (filters.value.rarity) {
    filtered = filtered.filter(item => (item.rarity || '') === filters.value.rarity);
  }
  
  // Ordenação
  filtered.sort((a, b) => {
    switch (filters.value.sort) {
      case 'quantity':
        return (b.quantity || 0) - (a.quantity || 0);
      case 'rarity':
        return (b.rarity || '').localeCompare(a.rarity || '');
      case 'type':
        return (a.type || '').localeCompare(b.type || '');
      default:
        return (a.name || '').localeCompare(b.name || '');
    }
  });
  
  return filtered;
});

const filteredEquipment = computed(() => {
  let filtered = equipment.value;
  
  if (filters.value.search) {
    filtered = filtered.filter(item => 
      (item.equipment?.name || '').toLowerCase().includes(filters.value.search.toLowerCase())
    );
  }
  
  if (filters.value.type) {
    filtered = filtered.filter(item => (item.equipment?.type || '') === filters.value.type);
  }
  
  if (filters.value.tier) {
    filtered = filtered.filter(item => (item.current_tier || 0).toString() === filters.value.tier);
  }
  
  // Ordenação
  filtered.sort((a, b) => {
    switch (filters.value.sort) {
      case 'rarity':
        return (b.equipment?.rarity || '').localeCompare(a.equipment?.rarity || '');
      case 'type':
        return (a.equipment?.type || '').localeCompare(b.equipment?.type || '');
      case 'tier':
        return (b.current_tier || 0) - (a.current_tier || 0);
      default:
        return (a.equipment?.name || '').localeCompare(b.equipment?.name || '');
    }
  });
  
  return filtered;
});

const filteredMaterials = computed(() => {
  let filtered = materials.value;
  
  if (filters.value.search) {
    filtered = filtered.filter(item => 
      (item.name || '').toLowerCase().includes(filters.value.search.toLowerCase())
    );
  }
  
  if (filters.value.rarity) {
    filtered = filtered.filter(item => (item.rarity || '') === filters.value.rarity);
  }
  
  // Ordenação
  filtered.sort((a, b) => {
    switch (filters.value.sort) {
      case 'quantity':
        return (b.quantity || 0) - (a.quantity || 0);
      case 'rarity':
        return (b.rarity || '').localeCompare(a.rarity || '');
      case 'type':
        return (a.type || '').localeCompare(b.type || '');
      default:
        return (a.name || '').localeCompare(b.name || '');
    }
  });
  
  return filtered;
});

// Paginação
const paginatedPotions = computed(() => {
  const start = (currentPotionsPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredPotions.value.slice(start, end);
});

const paginatedEquipment = computed(() => {
  const start = (currentEquipmentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredEquipment.value.slice(start, end);
});

const paginatedMaterials = computed(() => {
  const start = (currentMaterialsPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredMaterials.value.slice(start, end);
});

// Total de páginas
const totalPotionsPages = computed(() => Math.ceil(filteredPotions.value.length / itemsPerPage));
const totalEquipmentPages = computed(() => Math.ceil(filteredEquipment.value.length / itemsPerPage));
const totalMaterialsPages = computed(() => Math.ceil(filteredMaterials.value.length / itemsPerPage));

// Contar itens no inventário rápido
const quickInventoryCount = computed(() => {
  return quickInventorySlots.value.filter(slot => slot.item).length;
});

// Verificar se pode adicionar mais itens ao inventário rápido
const canAddToQuickInventory = computed(() => {
  return quickInventoryCount.value < 8;
});

// Verificar se um item já está no inventário rápido
const isItemInQuickInventory = (item) => {
  return quickInventorySlots.value.some(slot => 
    slot.item && slot.item.id === item.id
  );
};

// Funções
const clearFilters = () => {
  filters.value = {
    search: '',
    type: '',
    rarity: '',
    tier: '',
    sort: 'name'
  };
};

const toggleViewMode = () => {
  viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const equipItem = async (item) => {
  try {
    if (item.is_equipped) {
      await axios.post('/equipment/unequip', { slot: item.equipment.type });
    } else {
      await axios.post('/equipment/equip', { 
        equipment_id: item.equipment.id, 
        slot: item.equipment.type 
      });
    }
    
    // Recarregar a página para atualizar os dados
    router.reload();
  } catch (error) {
    console.error('Erro ao equipar/desequipar:', error);
    alert('Erro ao equipar/desequipar item. Tente novamente.');
  }
};

// Efeitos visuais para tiers especiais
const hasSpecialTier = (tier) => {
  return [1, 5, 10, 12].includes(tier);
};


// Métodos de Drag and Drop
const handleDragStart = (event, item, slotIndex) => {
  draggedItem.value = item;
  draggedSlotIndex.value = slotIndex;
  event.dataTransfer.effectAllowed = 'move';
  
  // Criar elemento customizado para o drag
  const dragElement = document.createElement('div');
  dragElement.innerHTML = `
    <div style="background: white; border: 2px solid #d97706; border-radius: 8px; padding: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
      <img src="${item.image}" style="width: 24px; height: 24px; object-fit: contain;">
      <div style="font-size: 10px; color: #92400e; text-align: center; margin-top: 2px;">${item.quantity}x</div>
    </div>
  `;
  dragElement.style.position = 'absolute';
  dragElement.style.top = '-1000px';
  document.body.appendChild(dragElement);
  
  event.dataTransfer.setDragImage(dragElement, 40, 40);
  
  // Remover elemento após um tempo
  setTimeout(() => {
    if (document.body.contains(dragElement)) {
      document.body.removeChild(dragElement);
    }
  }, 100);
};

const handleDrop = async (event) => {
  event.preventDefault();
  
  if (!draggedItem.value) return;
  
  // Verificar se o item já está no inventário rápido (apenas se não está sendo movido de dentro do próprio inventário rápido)
  if (draggedSlotIndex.value === null) {
    const alreadyInQuickInventory = quickInventorySlots.value.some(slot => 
      slot.item && slot.item.id === draggedItem.value.id
    );
    
    if (alreadyInQuickInventory) {
      // Item já está no inventário rápido, não permitir duplicata
      return;
    }
  }
  
  // Encontrar o slot vazio mais próximo
  const emptySlotIndex = quickInventorySlots.value.findIndex(slot => !slot.item);
  
  if (emptySlotIndex === -1) {
    // Inventário rápido cheio
    return;
  }
  
  // Se está arrastando de um slot do inventário rápido, remover do slot original
  if (draggedSlotIndex.value !== null) {
    quickInventorySlots.value[draggedSlotIndex.value].item = null;
  }
  
  // Adicionar ao novo slot
  quickInventorySlots.value[emptySlotIndex].item = draggedItem.value;
  
  // Atualizar no backend
  try {
    const endpoint = activeTab.value === 'equipment' 
      ? '/equipment/toggle-quick-inventory' 
      : '/items/toggle-quick-inventory';
    
    await axios.post(endpoint, {
      item_id: draggedItem.value.id,
      show_in_quick_inventory: true
    });
    
    // Atualizar o item localmente
    draggedItem.value.show_in_quick_inventory = true;
    
  } catch (error) {
    console.error('Erro ao adicionar ao inventário rápido:', error);
    // Reverter a mudança local
    quickInventorySlots.value[emptySlotIndex].item = null;
    if (draggedSlotIndex.value !== null) {
      quickInventorySlots.value[draggedSlotIndex.value].item = draggedItem.value;
    }
  }
  
  // Limpar dados do drag
  draggedItem.value = null;
  draggedSlotIndex.value = null;
};

const handleRemoveFromQuickInventory = async (event) => {
  event.preventDefault();
  
  if (!draggedItem.value) return;
  
  // Se está arrastando de um slot do inventário rápido, remover do slot
  if (draggedSlotIndex.value !== null) {
    quickInventorySlots.value[draggedSlotIndex.value].item = null;
    
    // Atualizar no backend
    try {
      const endpoint = activeTab.value === 'equipment' 
        ? '/equipment/toggle-quick-inventory' 
        : '/items/toggle-quick-inventory';
      
      await axios.post(endpoint, {
        item_id: draggedItem.value.id,
        show_in_quick_inventory: false
      });
      
      // Atualizar o item localmente
      draggedItem.value.show_in_quick_inventory = false;
      
    } catch (error) {
      console.error('Erro ao remover do inventário rápido:', error);
      // Reverter a mudança local
      quickInventorySlots.value[draggedSlotIndex.value].item = draggedItem.value;
    }
  }
  
  // Limpar dados do drag
  draggedItem.value = null;
  draggedSlotIndex.value = null;
};

const getTierEffect = (tier) => {
  switch (tier) {
    case 1:
      return 'border-2 border-green-400 shadow-lg shadow-green-200 tier-glow-green';
    case 5:
      return 'border-2 border-blue-400 shadow-lg shadow-blue-200 tier-glow-blue';
    case 10:
      return 'border-2 border-purple-400 shadow-lg shadow-purple-200 tier-glow-purple';
    case 12:
      return 'border-2 border-yellow-400 shadow-lg shadow-yellow-200 tier-glow-gold';
    default:
      return '';
  }
};

// Inicializar slots do inventário rápido
const initializeQuickInventory = () => {
  // Limpar slots
  quickInventorySlots.value = Array(8).fill(null).map(() => ({ item: null }));
  
  // Adicionar itens que já estão no inventário rápido da aba ativa
  let currentItems = [];
  switch (activeTab.value) {
    case 'potions':
      currentItems = potions.value;
      break;
    case 'equipment':
      currentItems = equipment.value;
      break;
    case 'materials':
      currentItems = materials.value;
      break;
  }
  
  const quickItems = currentItems.filter(item => item.show_in_quick_inventory);
  
  quickItems.forEach((item, index) => {
    if (index < 8) {
      quickInventorySlots.value[index].item = item;
    }
  });
};

// Inicializar quando os dados mudarem
watch([potions, equipment, materials], () => {
  initializeQuickInventory();
}, { immediate: true });

// Atualizar inventário rápido quando mudar de aba
watch(activeTab, () => {
  initializeQuickInventory();
});

// Resetar página quando mudar filtros
watch(filters, () => {
  currentPotionsPage.value = 1;
  currentEquipmentPage.value = 1;
  currentMaterialsPage.value = 1;
}, { deep: true });


</script>

<style scoped>
/* Efeitos de brilho suave para tiers especiais */
.tier-glow-green {
  animation: tier-glow-green 3s ease-in-out infinite;
}

.tier-glow-blue {
  animation: tier-glow-blue 3s ease-in-out infinite;
}

.tier-glow-purple {
  animation: tier-glow-purple 3s ease-in-out infinite;
}

.tier-glow-gold {
  animation: tier-glow-gold 3s ease-in-out infinite;
}

@keyframes tier-glow-green {
  0%, 100% {
    box-shadow: 0 0 5px rgba(34, 197, 94, 0.3), 0 0 10px rgba(34, 197, 94, 0.2), 0 0 15px rgba(34, 197, 94, 0.1);
  }
  50% {
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.6), 0 0 20px rgba(34, 197, 94, 0.4), 0 0 30px rgba(34, 197, 94, 0.2);
  }
}

@keyframes tier-glow-blue {
  0%, 100% {
    box-shadow: 0 0 5px rgba(59, 130, 246, 0.3), 0 0 10px rgba(59, 130, 246, 0.2), 0 0 15px rgba(59, 130, 246, 0.1);
  }
  50% {
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.6), 0 0 20px rgba(59, 130, 246, 0.4), 0 0 30px rgba(59, 130, 246, 0.2);
  }
}

@keyframes tier-glow-purple {
  0%, 100% {
    box-shadow: 0 0 5px rgba(147, 51, 234, 0.3), 0 0 10px rgba(147, 51, 234, 0.2), 0 0 15px rgba(147, 51, 234, 0.1);
  }
  50% {
    box-shadow: 0 0 10px rgba(147, 51, 234, 0.6), 0 0 20px rgba(147, 51, 234, 0.4), 0 0 30px rgba(147, 51, 234, 0.2);
  }
}

@keyframes tier-glow-gold {
  0%, 100% {
    box-shadow: 0 0 5px rgba(251, 191, 36, 0.3), 0 0 10px rgba(251, 191, 36, 0.2), 0 0 15px rgba(251, 191, 36, 0.1);
  }
  50% {
    box-shadow: 0 0 10px rgba(251, 191, 36, 0.6), 0 0 20px rgba(251, 191, 36, 0.4), 0 0 30px rgba(251, 191, 36, 0.2);
  }
}
</style>
