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
            <h2 class="text-lg font-medieval">Dashboard</h2>
          </div>
          <div class="flex-1 flex justify-end space-x-4">
            <Link 
              :href="route('characters.select')" 
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Trocar Personagem
            </Link>
            <Link 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="btn-medieval text-sm px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
            >
              Sair
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full">
        
        <!-- Coluna Esquerda - Herói -->
        <div class="lg:col-span-3">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Herói</h3>
            
            <!-- Avatar -->
            <div class="text-center mb-4">
              <div class="w-20 h-20 bg-medieval-bronze rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-4xl">⚔️</span>
              </div>
              <h4 class="text-lg font-semibold text-medieval-gold">{{ character?.name || 'Nome do Personagem' }}</h4>
              <p class="text-medieval-brown">{{ getClassName(character?.class) || 'Guerreiro' }}</p>
              <p class="text-medieval">Nível {{ character?.level || 1 }}</p>
              <p class="text-green-600 font-semibold">CP: {{ character?.power || 150 }}</p>
            </div>

            <!-- Barras de Progresso -->
            <div class="space-y-3 mb-6">
              <!-- HP -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">HP:</span>
                  <span class="text-red-600 font-semibold">{{ character?.current_hp || 100 }}/{{ character?.max_hp || 100 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3">
                  <div class="bg-red-500 h-3 rounded-full transition-all duration-300" 
                       :style="{ width: `${((character?.current_hp || 100) / (character?.max_hp || 100)) * 100}%` }"></div>
                </div>
              </div>

              <!-- MP -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">MP:</span>
                  <span class="text-blue-600 font-semibold">{{ character?.current_mp || 50 }}/{{ character?.max_mp || 50 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3">
                  <div class="bg-blue-500 h-3 rounded-full transition-all duration-300" 
                       :style="{ width: `${((character?.current_mp || 50) / (character?.max_mp || 50)) * 100}%` }"></div>
                </div>
              </div>

              <!-- EXP -->
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-medieval">EXP:</span>
                  <span class="text-yellow-600 font-semibold">{{ character?.current_exp || 0 }}/{{ character?.exp_to_next_level || 100 }}</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3">
                  <div class="bg-yellow-500 h-3 rounded-full transition-all duration-300" 
                       :style="{ width: `${((character?.current_exp || 0) / (character?.exp_to_next_level || 100)) * 100}%` }"></div>
                </div>
              </div>
            </div>

            <!-- Stats -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Stats</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-medieval">Força:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.strength || 15 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Destreza:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.dexterity || 10 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Constituição:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.constitution || 14 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Inteligência:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.intelligence || 8 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-medieval">Sorte:</span>
                  <span class="font-semibold text-medieval-gold">{{ character?.luck || 10 }}</span>
                </div>
              </div>
            </div>

            <!-- Treinamento -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Treinamento</h4>
              <div class="bg-amber-50 p-3 rounded-lg">
                <p class="text-sm text-medieval">Nenhum treinamento ativo</p>
                <button class="btn-medieval text-xs px-3 py-1 mt-2">Iniciar Treino</button>
              </div>
            </div>

            <!-- VIP -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">VIP</h4>
              <div class="bg-amber-50 p-3 rounded-lg">
                <p class="text-sm text-medieval">Status VIP: Inativo</p>
                <button class="btn-medieval text-xs px-3 py-1 mt-2">Gerenciar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Centro - Mundo de Eldoria -->
        <div class="lg:col-span-6">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Mundo de Eldoria</h3>
            
            <!-- Área Principal do Jogo -->
            <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-lg h-96 flex items-center justify-center mb-4">
              <div class="text-center text-gray-300">
                <div class="text-6xl mb-4">🏰</div>
                <p class="text-lg">Área de Jogo Principal</p>
                <p class="text-sm">(Mapa, Batalha, Chat)</p>
              </div>
            </div>

            <!-- Menu de Funcionalidades -->
            <div class="grid grid-cols-2 gap-4">
              <!-- Explorar - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">🗺️</div>
                <h4 class="subtitle-medieval text-gray-500">Explorar</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Chat - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">💬</div>
                <h4 class="subtitle-medieval text-gray-500">Chat</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Missões - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">📜</div>
                <h4 class="subtitle-medieval text-gray-500">Missões</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Batalha - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">⚔️</div>
                <h4 class="subtitle-medieval text-gray-500">Batalha</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Inventário - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">🎒</div>
                <h4 class="subtitle-medieval text-gray-500">Inventário</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Loja - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">🏪</div>
                <h4 class="subtitle-medieval text-gray-500">Loja</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Guilda - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">🏰</div>
                <h4 class="subtitle-medieval text-gray-500">Guilda</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>

              <!-- Configurações - Bloqueado -->
              <div class="card-medieval text-center opacity-50 cursor-not-allowed p-4 relative">
                <div class="absolute top-2 right-2">
                  <span class="text-gray-500 text-lg">🔒</span>
                </div>
                <div class="text-4xl mb-2 text-gray-500">⚙️</div>
                <h4 class="subtitle-medieval text-gray-500">Configurações</h4>
                <p class="text-sm text-gray-500">Em breve</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Direita - Itens -->
        <div class="lg:col-span-3">
          <div class="card-medieval h-full">
            <h3 class="text-xl subtitle-medieval mb-4 text-medieval-gold">Itens</h3>
            
            <!-- Equipamento -->
            <div class="mb-6">
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Equipamento</h4>
              <div class="flex flex-col items-center space-y-2">
                <!-- Elmo -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">⛑️</div>
                  <div class="text-xs text-gray-600">T5</div>
                </div>
                
                <!-- Armadura com armas ao lado -->
                <div class="flex items-center space-x-2">
                  <!-- Espada Esquerda -->
                  <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                    <div class="text-2xl mb-1">⚔️</div>
                    <div class="text-xs text-gray-600">T8</div>
                  </div>
                  
                  <!-- Armadura (centro) -->
                  <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                    <div class="text-2xl mb-1">🛡️</div>
                    <div class="text-xs text-gray-600">T7</div>
                  </div>
                  
                  <!-- Escudo Direita -->
                  <div class="bg-gray-100 rounded-lg p-2 text-center w-16 border-2 border-dashed border-gray-300">
                    <div class="text-gray-400 text-lg">+</div>
                    <div class="text-xs text-gray-400">Vazio</div>
                  </div>
                </div>
                
                <!-- Calça -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">👖</div>
                  <div class="text-xs text-gray-600">T7</div>
                </div>
                
                <!-- Bota -->
                <div class="bg-gray-200 rounded-lg p-2 text-center w-16">
                  <div class="text-2xl mb-1">👢</div>
                  <div class="text-xs text-gray-600">T6</div>
                </div>
              </div>
            </div>

            <!-- Inventário -->
            <div>
              <h4 class="subtitle-medieval mb-3 text-medieval-gold">Inventário</h4>
              <div class="grid grid-cols-4 gap-1">
                <!-- Slots do inventário -->
                <div v-for="i in 20" :key="i" 
                     class="bg-gray-100 rounded border-2 border-dashed border-gray-300 aspect-square flex items-center justify-center">
                  <span v-if="i <= 3" class="text-xs text-gray-600">
                    {{ i === 1 ? 'Po x3' : i === 2 ? 'Ru x1' : 'Flr x5' }}
                  </span>
                  <span v-else class="text-gray-400 text-xs">+</span>
                </div>
                    </div>
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
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  character: Object,
});

const getClassName = (className) => {
  switch (className) {
    case 'warrior': return 'Guerreiro';
    case 'mage': return 'Mago';
    case 'archer': return 'Arqueiro';
    case 'rogue': return 'Ladino';
    default: return 'Guerreiro';
  }
};
</script>