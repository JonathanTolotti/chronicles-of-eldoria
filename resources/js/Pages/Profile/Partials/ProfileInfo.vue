<script setup>
const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    character: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    if (!date) return 'Nunca';
    return new Date(date).toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getClassName = (classType) => {
    const classNames = {
        'warrior': 'Guerreiro',
        'mage': 'Mago',
        'archer': 'Arqueiro',
        'rogue': 'Ladino',
        'paladin': 'Paladino',
        'priest': 'Sacerdote'
    };
    return classNames[classType] || classType;
};

const formatNumber = (number) => {
    if (!number) return '0';
    return new Intl.NumberFormat('pt-BR').format(number);
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="relative w-56 h-56">
                    <!-- Moldura do Avatar -->
                    <img
                        v-if="character?.active_frame?.image_path"
                        :src="character.active_frame.image_path"
                        :alt="`Moldura ${character.active_frame.name}`"
                        class="w-56 h-56 rounded-full object-cover pointer-events-none z-20"
                    />
                    <!-- Avatar do personagem -->
                    <img
                        :src="character.avatar_url"
                        :alt="`Avatar de ${character.name}`"
                        :class="[
                            'absolute top-12 left-12 w-32 h-32 rounded-full object-cover z-10',
                            !character?.active_frame?.image_path ? 'border-4 border-medieval-gold shadow-lg' : ''
                        ]"
                    />
                </div>
            </div>
            
            <!-- Informações Básicas -->
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-2 md:space-y-0 md:space-x-4">
                    <h1 class="text-4xl font-bold text-medieval">{{ character.name }}</h1>
                </div>
                
                <div class="mt-4 space-y-2">
                    <p class="text-xl text-medieval-brown">{{ getClassName(character.class) }}</p>
                    <p class="text-lg text-medieval">Nível {{ character.level }}</p>
                    <div class="flex items-center justify-center md:justify-start space-x-4 text-sm text-medieval">
                        <span>CP: <span class="font-bold text-medieval-gold">{{ formatNumber(character.power) }}</span></span>
                    </div>
                </div>
            </div>
            
            <!-- Badges - Lado Direito e Bem Maiores -->
            <div class="flex items-center space-x-4">
                <!-- Badge VIP -->
                <div v-if="user?.is_vip" class="relative group">
                    <img
                        src="/images/badges/vip.png"
                        alt="VIP"
                        class="h-32 w-32"
                    />
                    <!-- Tooltip VIP -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                        <div class="text-center">
                            <div class="font-semibold mb-1">VIP</div>
                            <div>Membro VIP</div>
                        </div>
                        <!-- Seta do tooltip -->
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                    </div>
                </div>
                
                <!-- Badge Staff -->
                <div v-if="user?.is_staff" class="relative group">
                    <img
                        src="/images/badges/staff.png"
                        alt="Staff"
                        class="h-32 w-32"
                    />
                    <!-- Tooltip Staff -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                        <div class="text-center">
                            <div class="font-semibold mb-1">Staff</div>
                            <div>Membro da equipe</div>
                        </div>
                        <!-- Seta do tooltip -->
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-medieval-dark"></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Atributos do Personagem -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-medieval mb-4">Atributos</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-amber-50 rounded-lg p-3 text-center">
                    <div class="text-sm text-medieval">Força</div>
                    <div class="text-lg font-bold text-medieval-gold">{{ character.strength || 0 }}</div>
                </div>
                <div class="bg-amber-50 rounded-lg p-3 text-center">
                    <div class="text-sm text-medieval">Destreza</div>
                    <div class="text-lg font-bold text-medieval-gold">{{ character.dexterity || 0 }}</div>
                </div>
                <div class="bg-amber-50 rounded-lg p-3 text-center">
                    <div class="text-sm text-medieval">Constituição</div>
                    <div class="text-lg font-bold text-medieval-gold">{{ character.constitution || 0 }}</div>
                </div>
                <div class="bg-amber-50 rounded-lg p-3 text-center">
                    <div class="text-sm text-medieval">Inteligência</div>
                    <div class="text-lg font-bold text-medieval-gold">{{ character.intelligence || 0 }}</div>
                </div>
                <div class="bg-amber-50 rounded-lg p-3 text-center">
                    <div class="text-sm text-medieval">Sorte</div>
                    <div class="text-lg font-bold text-medieval-gold">{{ character.luck || 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Informações da conta -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-amber-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-medieval">Informações da Conta</h4>
                <dl class="mt-2 space-y-1">
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Email:</dt>
                        <dd class="text-sm text-medieval">{{ user.email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Membro desde:</dt>
                        <dd class="text-sm text-medieval">{{ formatDate(user.created_at) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Última atividade:</dt>
                        <dd class="text-sm text-medieval">{{ formatDate(user.last_seen_at) }}</dd>
                    </div>
                </dl>
            </div>

                <div class="bg-amber-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-medieval">Estatísticas do Personagem</h4>
                    <dl class="mt-2 space-y-1">
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Nível:</dt>
                            <dd class="text-sm text-medieval">{{ character.level }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Experiência:</dt>
                            <dd class="text-sm text-medieval">{{ formatNumber(character.experience || 0) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Poder:</dt>
                            <dd class="text-sm text-medieval">{{ formatNumber(character.power || 0) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Gold:</dt>
                            <dd class="text-sm text-medieval">{{ formatNumber(character.gold || 0) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Pontos Livres:</dt>
                            <dd class="text-sm text-medieval">{{ character.stat_points || 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">HP:</dt>
                            <dd class="text-sm text-medieval">{{ character.current_hp || 0 }}/{{ character.max_hp || 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Stamina:</dt>
                            <dd class="text-sm text-medieval">{{ character.current_stamina || 0 }}/{{ character.max_stamina || 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-medieval">Perfil:</dt>
                            <dd class="text-sm text-medieval">
                                <span :class="character.profile_public ? 'text-green-600' : 'text-red-600'">
                                    {{ character.profile_public ? 'Público' : 'Privado' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
        </div>
    </div>
</template>
