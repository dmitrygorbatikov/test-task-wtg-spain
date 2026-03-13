<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-950 via-gray-900 to-black text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-10 w-full">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                    Особистий кабінет <span class="text-emerald-400">{{ authStore.userName }}</span>
                </h1>
                <p class="mt-4 text-xl text-gray-400">
                    Керуйте своїм профілем
                </p>
            </div>

            <div class="bg-gray-900/80 backdrop-blur-xl rounded-2xl border border-gray-800/50 shadow-2xl shadow-black/50 overflow-hidden w-full">
                <div class="relative px-6 sm:px-8 pt-20 pb-10 bg-gradient-to-b from-emerald-950/30 to-transparent">
                    <div class="flex justify-center -mt-16 mb-6">
                        <div class="w-28 h-28 md:w-36 md:h-36 rounded-full border-4 border-emerald-500 overflow-hidden shadow-2xl shadow-emerald-900/60 bg-gray-800 flex items-center justify-center transition-transform hover:scale-105">
                            <svg class="w-20 h-20 md:w-24 md:h-24 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="text-center">
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">
                            {{ authStore.user?.firstName }} {{ authStore.user?.lastName }}
                        </h2>
                        <p class="text-lg md:text-xl text-gray-400">
                            {{ authStore.user?.email }}
                        </p>
                        <p class="mt-3 text-emerald-400 font-medium text-lg">
                            Статус: {{ authStore.user?.status?.title || 'Активний' }}
                        </p>
                    </div>
                </div>

                <div class="p-6 sm:p-8 md:p-12">
                    <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/50 mb-8">
                        <h3 class="text-xl font-semibold mb-6 text-emerald-400 text-center">
                            Дані облікового запису
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-300 text-base">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Ім'я</span>
                                <span class="font-medium">{{ authStore.user?.firstName || '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Прізвище</span>
                                <span class="font-medium">{{ authStore.user?.lastName || '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Email</span>
                                <span class="font-medium break-all">{{ authStore.user?.email || '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">ID / Slug</span>
                                <span class="font-medium">{{ authStore.user?.slug || '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <button
                        @click="handleLogout"
                        class="w-full py-4 px-6 bg-red-600/80 hover:bg-red-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-900/30 flex items-center justify-center gap-3"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Вийти з облікового запису
                    </button>
                </div>
            </div>

            <div class="text-center text-gray-500 text-sm mt-6">
                <p>Останній вхід: {{ new Date().toLocaleString('uk-UA') }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
    authStore.logout()
    await router.push('/login')
}
</script>
