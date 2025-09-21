<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100 flex flex-col">
    <!-- Header -->
    <header class="bg-medieval-dark text-medieval-gold shadow-lg">
      <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
          <div class="flex-1"></div>
          <h1 class="text-2xl font-bold font-medieval-decorative text-center flex-1">Chronicles of Eldoria</h1>
          <div class="flex-1 flex justify-end">
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
    <main class="flex-1">
      <div class="container mx-auto px-4 py-8">
      <div class="text-center mb-8">
        <h1 class="text-4xl title-medieval mb-4">Selecionar Personagem</h1>
        <p class="text-lg text-medieval">Escolha com qual personagem deseja jogar</p>
      </div>

      <div v-if="characters.length === 0" class="text-center">
        <div class="card-medieval max-w-md mx-auto">
          <h2 class="text-2xl subtitle-medieval mb-4">Nenhum Personagem Encontrado</h2>
          <p class="text-medieval mb-6">Você ainda não criou nenhum personagem.</p>
          <Link href="/characters/create" class="btn-medieval text-lg px-8 py-3">
            Criar Primeiro Personagem
          </Link>
        </div>
      </div>

      <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="character in characters" 
          :key="character.id"
          @click="selectCharacter(character.id)"
          :class="[
            'card-medieval cursor-pointer transition-all hover:shadow-xl',
            selectedCharacterId === character.id 
              ? 'ring-4 ring-medieval-gold bg-amber-100' 
              : 'hover:bg-amber-50'
          ]"
        >
          <div class="text-center">
            <!-- Avatar com Moldura -->
            <div class="flex justify-center mb-4">
              <div class="relative w-44 h-44">
                <!-- Moldura do Avatar -->
                <img
                  v-if="character?.active_frame?.image_path"
                  :src="character.active_frame.image_path"
                  :alt="`Moldura ${character.active_frame.name}`"
                  class="w-44 h-44 rounded-full object-cover pointer-events-none z-20"
                />
                <!-- Avatar do personagem -->
                <img
                  :src="getAvatarUrl(character)"
                  :alt="`Avatar de ${character.name}`"
                  :class="[
                    'absolute top-10 left-10 w-24 h-24 rounded-full object-cover z-10',
                    !character?.active_frame?.image_path ? 'border-2 border-medieval-gold' : ''
                  ]"
                />
              </div>
            </div>
            
            <h3 class="text-xl subtitle-medieval mb-2">{{ character.name }}</h3>
            <p class="text-medieval mb-4">{{ getClassDisplayName(character.class) }}</p>
            
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span>Nível:</span>
                <span class="font-semibold">{{ character.level }}</span>
              </div>
              <div class="flex justify-between">
                <span>Poder:</span>
                <span class="font-semibold text-medieval-gold">{{ character.power }}</span>
              </div>
              <div class="flex justify-between">
                <span>Ouro:</span>
                <span class="font-semibold text-medieval-gold">{{ character.gold }}</span>
              </div>
            </div>

            <div v-if="selectedCharacterId === character.id" class="mt-4">
              <div class="text-medieval-gold font-semibold">✓ Selecionado</div>
            </div>
          </div>
        </div>

        <!-- Botão para criar novo personagem -->
        <div 
          @click="$inertia.visit('/characters/create')"
          class="card-medieval cursor-pointer transition-all hover:shadow-xl hover:bg-amber-50 border-dashed border-2 border-medieval-bronze"
        >
          <div class="text-center py-8">
            <div class="text-4xl mb-4">➕</div>
            <h3 class="text-xl subtitle-medieval mb-2">Criar Novo Personagem</h3>
            <p class="text-medieval">Adicione mais personagens à sua conta</p>
          </div>
        </div>
      </div>

      <div v-if="characters.length > 0" class="text-center mt-8">
        <PrimaryButton 
          @click="confirmSelection"
          :disabled="!selectedCharacterId"
          class="btn-medieval text-lg px-8 py-3"
        >
          Continuar com {{ getSelectedCharacterName() }}
        </PrimaryButton>
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
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  characters: Array,
  user: Object
});

const selectedCharacterId = ref(props.user?.active_character_id || null);

const form = useForm({
  character_id: null
});

const selectCharacter = (characterId) => {
  selectedCharacterId.value = characterId;
  form.character_id = characterId;
};

// Função para obter URL do avatar
const getAvatarUrl = (character) => {
  if (!character) return '/images/avatars/default.png';
  
  if (character.avatar_url) {
    return character.avatar_url;
  }
  
  if (character.avatar) {
    const avatarPath = `images/avatars/${character.avatar}.png`;
    return `/${avatarPath}`;
  }
  
  return '/images/avatars/default.png';
};

const confirmSelection = () => {
  if (selectedCharacterId.value) {
    form.post(route('characters.select'), {
      onSuccess: () => {
        // Redirecionamento será feito no controller
      }
    });
  }
};

const getClassDisplayName = (classId) => {
  const classNames = {
    'warrior': 'Guerreiro',
    'mage': 'Mago',
    'archer': 'Arqueiro',
    'rogue': 'Ladino'
  };
  return classNames[classId] || classId;
};

const getSelectedCharacterName = () => {
  const character = props.characters.find(c => c.id === selectedCharacterId.value);
  return character ? character.name : '';
};
</script>
