<script setup>
const props = defineProps({
    user: {
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
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-center space-x-4">
            <img
                :src="user.avatar_url"
                :alt="`Avatar de ${user.name}`"
                class="h-20 w-20 rounded-full object-cover"
            />
            <div>
                <h2 class="text-2xl font-bold text-medieval">{{ user.name }}</h2>
                <div class="flex items-center space-x-2 mt-1">
                    <!-- Badge VIP -->
                    <span
                        v-if="user.is_vip"
                        class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800"
                    >
                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        VIP
                    </span>
                    
                    <!-- Badge Staff -->
                    <span
                        v-if="user.is_staff"
                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800"
                    >
                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        STAFF
                    </span>
                </div>
            </div>
        </div>

        <!-- Biografia -->
        <div v-if="user.biography" class="mt-6">
            <h3 class="text-lg font-medium text-medieval mb-2">Biografia</h3>
            <div 
                class="prose prose-sm max-w-none"
                v-html="user.biography"
            ></div>
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
                <h4 class="text-sm font-medium text-medieval">Estatísticas</h4>
                <dl class="mt-2 space-y-1">
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Personagens:</dt>
                        <dd class="text-sm text-medieval">{{ user.characters_count || 0 }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Nível mais alto:</dt>
                        <dd class="text-sm text-medieval">{{ user.highest_level_character || 0 }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-medieval">Perfil:</dt>
                        <dd class="text-sm text-medieval">
                            <span :class="user.profile_public ? 'text-green-600' : 'text-red-600'">
                                {{ user.profile_public ? 'Público' : 'Privado' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>
