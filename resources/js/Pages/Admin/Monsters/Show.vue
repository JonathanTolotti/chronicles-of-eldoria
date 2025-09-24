<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    monster: Object,
});

</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Link
                                :href="route('admin.monsters.index')"
                                class="text-medieval-bronze hover:text-medieval-gold mr-4"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </Link>
                        </div>
                        
                        <div class="flex space-x-3">
                            <Link
                                :href="route('admin.monsters.edit', monster.uuid)"
                                class="btn-medieval bg-transparent"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Informações Principais -->
                    <div class="card-medieval">
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-medieval-bronze mb-6">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informações Básicas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Nome</label>
                                    <p class="text-xl font-semibold text-gray-900">{{ monster?.name || '-' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Nível</label>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium bg-blue-100 text-blue-800">
                                        Nível {{ monster?.level || 0 }}
                                    </span>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Descrição</label>
                                    <p class="text-gray-900 text-lg leading-relaxed">{{ monster?.description || 'Sem descrição' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Imagem</label>
                                    <div class="h-20 w-20 rounded-full overflow-hidden">
                                        <img v-if="monster?.image_path" :src="monster.image_path" :alt="monster.name" class="w-full h-full object-cover" />
                                        <div v-else class="w-full h-full bg-medieval-bronze flex items-center justify-center text-white text-4xl">🐉</div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                                    <span 
                                        :class="monster?.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium"
                                    >
                                        <i :class="monster?.is_active ? 'fas fa-check-circle mr-2' : 'fas fa-times-circle mr-2'"></i>
                                        {{ monster?.is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="card-medieval">
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-medieval-bronze mb-6">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Estatísticas
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                                <div class="text-center p-4 bg-red-50 rounded-lg">
                                    <div class="text-3xl font-bold text-red-600 mb-2">{{ monster?.current_hp || 0 }}</div>
                                    <div class="text-sm text-gray-600 mb-1">HP Atual</div>
                                    <div class="text-xs text-gray-500">/ {{ monster?.max_hp || 0 }}</div>
                                </div>
                                
                                <div class="text-center p-4 bg-orange-50 rounded-lg">
                                    <div class="text-3xl font-bold text-orange-600 mb-2">{{ monster?.attack_power || 0 }}</div>
                                    <div class="text-sm text-gray-600">Ataque</div>
                                </div>
                                
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ monster?.defense || 0 }}</div>
                                    <div class="text-sm text-gray-600">Defesa</div>
                                </div>
                                
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-3xl font-bold text-green-600 mb-2">{{ monster?.speed || 0 }}</div>
                                    <div class="text-sm text-gray-600">Velocidade</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recompensas -->
                    <div class="card-medieval">
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-medieval-bronze mb-6">
                                <i class="fas fa-gift mr-2"></i>
                                Recompensas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="text-center p-6 bg-yellow-50 rounded-lg">
                                    <div class="flex items-center justify-center mb-3">
                                        <i class="fas fa-coins text-yellow-500 text-3xl mr-3"></i>
                                        <div class="text-3xl font-bold text-yellow-600">{{ monster?.gold_reward || 0 }}</div>
                                    </div>
                                    <div class="text-lg text-gray-600">Ouro</div>
                                </div>
                                
                                <div class="text-center p-6 bg-purple-50 rounded-lg">
                                    <div class="flex items-center justify-center mb-3">
                                        <i class="fas fa-star text-purple-500 text-3xl mr-3"></i>
                                        <div class="text-3xl font-bold text-purple-600">{{ monster?.exp_reward || 0 }}</div>
                                    </div>
                                    <div class="text-lg text-gray-600">EXP</div>
                                </div>
                                
                                <div class="text-center p-6 bg-red-50 rounded-lg">
                                    <div class="flex items-center justify-center mb-3">
                                        <i class="fas fa-bolt text-red-500 text-3xl mr-3"></i>
                                        <div class="text-3xl font-bold text-red-600">{{ monster?.stamina_cost || 0 }}</div>
                                    </div>
                                    <div class="text-lg text-gray-600">Stamina</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="card-medieval">
                    <div class="p-8">
                        <h3 class="text-xl font-semibold text-medieval-bronze mb-6">
                            <i class="fas fa-cog mr-2"></i>
                            Informações do Sistema
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ID</label>
                                <p class="text-lg text-gray-900">{{ monster?.id || '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Criado em</label>
                                <p class="text-lg text-gray-900">{{ monster?.created_at ? new Date(monster.created_at).toLocaleString('pt-BR') : '-' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Atualizado em</label>
                                <p class="text-lg text-gray-900">{{ monster?.updated_at ? new Date(monster.updated_at).toLocaleString('pt-BR') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview do Monstro -->
                <div class="card-medieval">
                    <div class="p-8">
                        <h3 class="text-xl font-semibold text-medieval-bronze mb-6">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </h3>
                        
                        <div class="text-center">
                            <div class="h-32 w-32 rounded-full overflow-hidden mx-auto mb-6">
                                <img v-if="monster?.image_path" :src="monster.image_path" :alt="monster.name" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-medieval-bronze flex items-center justify-center text-white text-5xl">🐉</div>
                            </div>
                            <h4 class="text-2xl font-semibold text-gray-900 mb-2">{{ monster?.name || 'Monstro' }}</h4>
                            <p class="text-lg text-gray-600 mb-4">Nível {{ monster?.level || 0 }}</p>
                            <div class="flex justify-center space-x-3">
                                <span class="text-sm bg-red-100 text-red-800 px-3 py-2 rounded-lg">HP: {{ monster?.current_hp || 0 }}/{{ monster?.max_hp || 0 }}</span>
                                <span class="text-sm bg-orange-100 text-orange-800 px-3 py-2 rounded-lg">ATK: {{ monster?.attack_power || 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Importar Font Awesome para os ícones */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Aplicar fonte Cinzel em todo o componente, exceto ícones */
.monsters-show *:not(.fas):not(.fa):not([class*="fa-"]) {
    font-family: 'Cinzel', serif !important;
}

.card-medieval {
    @apply bg-white rounded-lg shadow-lg border border-medieval-bronze;
}
</style>
