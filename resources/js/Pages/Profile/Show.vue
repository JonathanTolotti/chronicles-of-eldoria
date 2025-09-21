<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    if (!date) return 'Nunca';
    return new Date(date).toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatNumber = (number) => {
    if (!number) return '0';
    return new Intl.NumberFormat('pt-BR').format(number);
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

const getEquipmentTooltipTitle = (slot) => {
    const equipped = props.profile.equipment?.equipped?.[slot];
    if (!equipped) {
        return 'Slot Vazio';
    }
    return `${equipped.equipment.name} (T${equipped.current_tier})`;
};

const getEquipmentTooltipLines = (slot) => {
    const equipped = props.profile.equipment?.equipped?.[slot];
    if (!equipped) {
        return ['Nenhum equipamento'];
    }
    
    const lines = [];
    if (equipped.equipment.description) {
        lines.push(equipped.equipment.description);
    }
    lines.push(`Tier: ${equipped.current_tier}`);
    
    return lines;
};
</script>

<template>
    <Head :title="`Perfil de ${profile.name}`" />

    <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
        <!-- Header -->
        <header class="bg-medieval-dark text-medieval-gold shadow-lg">
            <div class="container mx-auto px-4 py-4">
                <!-- Desktop Header -->
                <div class="hidden md:flex justify-between items-center">
                    <div class="flex-1">
                        <Link 
                            :href="route('dashboard')" 
                            class="text-2xl text-medieval font-bold text-medieval hover:text-medieval-gold transition-colors"
                        >
                            Chronicles of Eldoria
                        </Link>
                    </div>
                    <div class="flex-1 text-center">
                        <h2 class="text-lg text-medieval font-medieval">Perfil Público</h2>
                    </div>
                    <div class="flex-1 flex justify-end items-center space-x-4">
                        <Link 
                            :href="route('dashboard')" 
                            class="btn-medieval text-sm text-medieval px-4 py-2 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
                        >
                            Voltar ao Dashboard
                        </Link>
                    </div>
                </div>
                
                <!-- Mobile Header -->
                <div class="md:hidden flex justify-between items-center">
                    <Link 
                        :href="route('dashboard')" 
                        class="text-xl text-medieval font-bold text-medieval hover:text-medieval-gold transition-colors"
                    >
                        Chronicles of Eldoria
                    </Link>
                    <Link 
                        :href="route('dashboard')" 
                        class="btn-medieval text-sm text-medieval px-3 py-1 bg-transparent border-medieval-gold text-medieval-gold hover:bg-medieval-gold hover:text-medieval-dark"
                    >
                        Voltar
                    </Link>
                </div>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Cabeçalho do Perfil -->
            <div class="card-medieval mb-8">
                <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <img
                            :src="profile.avatar_url"
                            :alt="`Avatar de ${profile.name}`"
                            class="h-32 w-32 rounded-full object-cover border-4 border-medieval-gold shadow-lg"
                        />
                    </div>
                    
                    <!-- Informações Básicas -->
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row items-center md:items-start space-y-2 md:space-y-0 md:space-x-4">
                            <h1 class="text-4xl font-bold text-medieval">{{ profile.name }}</h1>
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            <p class="text-xl text-medieval-brown">{{ getClassName(profile.class) }}</p>
                            <p class="text-lg text-medieval">Nível {{ profile.level }}</p>
                            <div class="flex items-center justify-center md:justify-start space-x-4 text-sm text-medieval">
                                <span>CP: <span class="font-bold text-medieval-gold">{{ formatNumber(profile.power) }}</span></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Badges - Lado Direito e Bem Maiores -->
                    <div class="flex items-center space-x-4">
                        <!-- Badge VIP -->
                        <div v-if="profile.is_vip" class="relative group">
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
                        <div v-if="profile.is_staff" class="relative group">
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
            </div>

            <!-- Grid Principal: Biografia, Conquistas e Equipamentos -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Biografia -->
                <div class="card-medieval">
                    <h3 class="text-xl font-bold text-medieval mb-6 text-medieval-gold">Biografia</h3>
                    
                    <div v-if="profile.biography" class="prose prose-medieval max-w-none">
                        <div v-html="profile.biography"></div>
                    </div>
                    
                    <div v-else class="text-center py-8">
                        <span class="text-4xl mb-4 block">📜</span>
                        <p class="text-medieval text-gray-600">Nenhuma biografia disponível</p>
                    </div>
                </div>

                <!-- Conquistas - Campo Temporário -->
                <div class="card-medieval">
                    <h3 class="text-xl font-bold text-medieval mb-6 text-medieval-gold">Conquistas</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-medieval">
                            <span class="text-2xl text-medieval-gold">🏆</span>
                            <span>Primeiro Nível</span>
                        </div>
                        <div class="flex items-center space-x-3 text-medieval">
                            <span class="text-2xl text-medieval-gold">⚔️</span>
                            <span>Primeira Batalha</span>
                        </div>
                        <div class="flex items-center space-x-3 text-medieval">
                            <span class="text-2xl text-medieval-gold">💎</span>
                            <span>Colecionador</span>
                        </div>
                    </div>
                </div>

                <!-- Equipamentos - Layout igual ao Dashboard -->
                <div class="card-medieval">
                    <h3 class="text-xl font-bold text-medieval mb-6 text-medieval-gold">Equipamentos</h3>
                    
                    <div v-if="profile.equipment && profile.equipment.equipped && Object.keys(profile.equipment.equipped).length > 0" class="flex flex-col items-center space-y-2">
                        <!-- Elmo -->
                        <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                             :class="profile.equipment?.equipped?.helmet ? getTierEffect(profile.equipment.equipped.helmet.current_tier) : ''">
                            <div v-if="profile.equipment?.equipped?.helmet" class="text-2xl text-medieval mb-1 relative">
                                <img :src="profile.equipment.equipped.helmet.equipment.image" 
                                     :alt="profile.equipment.equipped.helmet.equipment.name"
                                     class="w-8 h-8 mx-auto object-contain">
                            </div>
                            <div v-else class="text-gray-400 text-medieval text-lg">⛑️</div>
                            <div class="text-xs text-medieval text-gray-600">
                                {{ profile.equipment?.equipped?.helmet ? '' : 'Vazio' }}
                            </div>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                <div class="text-center">
                                    <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('helmet') }}</div>
                                    <div v-for="line in getEquipmentTooltipLines('helmet')" :key="line">• {{ line }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Armadura com armas ao lado -->
                        <div class="flex items-center space-x-2">
                            <!-- Espada Esquerda -->
                            <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                                 :class="profile.equipment?.equipped?.weapon ? getTierEffect(profile.equipment.equipped.weapon.current_tier) : ''">
                                <div v-if="profile.equipment?.equipped?.weapon" class="text-2xl text-medieval mb-1 relative">
                                    <img :src="profile.equipment.equipped.weapon.equipment.image" 
                                         :alt="profile.equipment.equipped.weapon.equipment.name"
                                         class="w-8 h-8 mx-auto object-contain">
                                </div>
                                <div v-else class="text-gray-400 text-medieval text-lg">⚔️</div>
                                <div class="text-xs text-medieval text-gray-600">
                                    {{ profile.equipment?.equipped?.weapon ? '' : 'Vazio' }}
                                </div>
                                
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('weapon') }}</div>
                                        <div v-for="line in getEquipmentTooltipLines('weapon')" :key="line">• {{ line }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Armadura (centro) -->
                            <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                                 :class="profile.equipment?.equipped?.armor ? getTierEffect(profile.equipment.equipped.armor.current_tier) : ''">
                                <div v-if="profile.equipment?.equipped?.armor" class="text-2xl text-medieval mb-1 relative">
                                    <img :src="profile.equipment.equipped.armor.equipment.image" 
                                         :alt="profile.equipment.equipped.armor.equipment.name"
                                         class="w-8 h-8 mx-auto object-contain">
                                </div>
                                <div v-else class="text-gray-400 text-medieval text-lg">🛡️</div>
                                <div class="text-xs text-medieval text-gray-600">
                                    {{ profile.equipment?.equipped?.armor ? '' : 'Vazio' }}
                                </div>
                                
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('armor') }}</div>
                                        <div v-for="line in getEquipmentTooltipLines('armor')" :key="line">• {{ line }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Escudo Direita -->
                            <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                                 :class="profile.equipment?.equipped?.shield ? getTierEffect(profile.equipment.equipped.shield.current_tier) : ''">
                                <div v-if="profile.equipment?.equipped?.shield" class="text-2xl text-medieval mb-1 relative">
                                    <img :src="profile.equipment.equipped.shield.equipment.image" 
                                         :alt="profile.equipment.equipped.shield.equipment.name"
                                         class="w-8 h-8 mx-auto object-contain">
                                </div>
                                <div v-else class="text-gray-400 text-medieval text-lg">🛡️</div>
                                <div class="text-xs text-medieval text-gray-600">
                                    {{ profile.equipment?.equipped?.shield ? '' : 'Vazio' }}
                                </div>
                                
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('shield') }}</div>
                                        <div v-for="line in getEquipmentTooltipLines('shield')" :key="line">• {{ line }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calça -->
                        <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                             :class="profile.equipment?.equipped?.pants ? getTierEffect(profile.equipment.equipped.pants.current_tier) : ''">
                            <div v-if="profile.equipment?.equipped?.pants" class="text-2xl text-medieval mb-1 relative">
                                <img :src="profile.equipment.equipped.pants.equipment.image" 
                                     :alt="profile.equipment.equipped.pants.equipment.name"
                                     class="w-8 h-8 mx-auto object-contain">
                            </div>
                            <div v-else class="text-gray-400 text-medieval text-lg">👖</div>
                            <div class="text-xs text-medieval text-gray-600">
                                {{ profile.equipment?.equipped?.pants ? '' : 'Vazio' }}
                            </div>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                <div class="text-center">
                                    <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('pants') }}</div>
                                    <div v-for="line in getEquipmentTooltipLines('pants')" :key="line">• {{ line }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bota -->
                        <div class="bg-gray-200 rounded-lg p-2 text-center w-16 relative group" 
                             :class="profile.equipment?.equipped?.boots ? getTierEffect(profile.equipment.equipped.boots.current_tier) : ''">
                            <div v-if="profile.equipment?.equipped?.boots" class="text-2xl text-medieval mb-1 relative">
                                <img :src="profile.equipment.equipped.boots.equipment.image" 
                                     :alt="profile.equipment.equipped.boots.equipment.name"
                                     class="w-8 h-8 mx-auto object-contain">
                            </div>
                            <div v-else class="text-gray-400 text-medieval text-lg">👢</div>
                            <div class="text-xs text-medieval text-gray-600">
                                {{ profile.equipment?.equipped?.boots ? '' : 'Vazio' }}
                            </div>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-medieval-dark text-medieval-gold text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10 whitespace-nowrap">
                                <div class="text-center">
                                    <div class="font-semibold mb-1">{{ getEquipmentTooltipTitle('boots') }}</div>
                                    <div v-for="line in getEquipmentTooltipLines('boots')" :key="line">• {{ line }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fallback quando não há equipamentos -->
                    <div v-else class="text-center py-8">
                        <span class="text-4xl mb-4 block">⚔️</span>
                        <p class="text-medieval text-gray-600">Nenhum equipamento encontrado</p>
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

<style scoped>
.prose-medieval {
    @apply text-medieval;
}

.prose-medieval h1,
.prose-medieval h2,
.prose-medieval h3,
.prose-medieval h4,
.prose-medieval h5,
.prose-medieval h6 {
    @apply text-medieval-gold font-bold;
}

.prose-medieval p {
    @apply text-medieval mb-4;
}

.prose-medieval strong {
    @apply text-medieval-gold font-bold;
}

.prose-medieval em {
    @apply text-medieval-brown italic;
}

.prose-medieval ul,
.prose-medieval ol {
    @apply text-medieval;
}

.prose-medieval li {
    @apply text-medieval mb-2;
}

/* Efeitos de tier iguais ao dashboard */
.tier-glow-green {
    animation: glow-green 2s ease-in-out infinite alternate;
}

.tier-glow-blue {
    animation: glow-blue 2s ease-in-out infinite alternate;
}

.tier-glow-purple {
    animation: glow-purple 2s ease-in-out infinite alternate;
}

.tier-glow-gold {
    animation: glow-gold 2s ease-in-out infinite alternate;
}

@keyframes glow-green {
    from { box-shadow: 0 0 5px rgba(34, 197, 94, 0.5); }
    to { box-shadow: 0 0 20px rgba(34, 197, 94, 0.8); }
}

@keyframes glow-blue {
    from { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
    to { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); }
}

@keyframes glow-purple {
    from { box-shadow: 0 0 5px rgba(147, 51, 234, 0.5); }
    to { box-shadow: 0 0 20px rgba(147, 51, 234, 0.8); }
}

@keyframes glow-gold {
    from { box-shadow: 0 0 5px rgba(251, 191, 36, 0.5); }
    to { box-shadow: 0 0 20px rgba(251, 191, 36, 0.8); }
}
</style>