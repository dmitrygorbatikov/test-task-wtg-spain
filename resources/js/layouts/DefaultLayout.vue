<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-950 via-gray-900 to-black text-white">
        <header class="bg-gray-950/80 backdrop-blur-lg border-b border-gray-800/50 sticky top-0 z-50 shadow-lg shadow-black/30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20">
                    <div class="flex items-center gap-10">
                        <router-link to="/" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-emerald-900/40 transition-transform group-hover:scale-110">
                                T
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight">
                WTG<span class="text-emerald-400">Spain</span>
              </span>
                        </router-link>
                        <nav class="hidden md:flex items-center gap-8">
                            <router-link
                                v-for="(label, path) in navItems"
                                :key="path"
                                :to="path"
                                class="text-gray-300 hover:text-emerald-400 font-medium transition-all duration-300 hover:scale-105"
                                active-class="text-emerald-400 font-semibold underline underline-offset-4"
                            >
                                {{ label }}
                            </router-link>
                        </nav>
                    </div>

                    <div class="flex items-center gap-6 md:gap-8">
                        <div v-if="isAuthenticated" class="flex items-center gap-6">
                            <router-link
                                to="/profile"
                                class="text-gray-300 hover:text-emerald-400 font-medium transition-colors flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ userName }}
                            </router-link>
                            <button
                                @click="logout"
                                class="text-gray-400 hover:text-red-400 font-medium transition-colors"
                            >
                                Вихід
                            </button>
                        </div>

                        <div v-else class="flex items-center gap-5">
                            <router-link
                                to="/login"
                                class="text-gray-300 hover:text-emerald-400 font-medium transition-colors"
                            >
                                Увійти
                            </router-link>
                            <router-link
                                to="/register"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-md hover:shadow-lg hover:shadow-emerald-500/30"
                            >
                                Реєстрація
                            </router-link>
                        </div>


                        <button class="md:hidden text-white focus:outline-none" @click="mobileMenuOpen = !mobileMenuOpen">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="mobileMenuOpen"
                class="md:hidden bg-gray-950 border-b border-gray-800 py-6 px-4 animate-fade-in"
            >
                <div class="flex flex-col gap-6">
                    <router-link
                        v-for="(label, path) in navItems"
                        :key="path"
                        :to="path"
                        class="text-gray-300 hover:text-emerald-400 font-medium text-lg transition-colors"
                        @click="mobileMenuOpen = false"
                    >
                        {{ label }}
                    </router-link>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <router-view />
        </main>

        <footer class="bg-gray-950 border-t border-gray-800/70 mt-auto w-full">
            <div class="w-full px-6 sm:px-10 lg:px-16 xl:px-20 py-16 md:py-20">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 md:gap-16 mb-16">
                        <div>
                            <h3 class="text-white font-bold text-xl mb-5">WTG Spain</h3>
                            <p class="text-gray-400 text-base leading-relaxed">
                                Можливість працювати над цікавим проектом, де буде змога не тільки виконувати таски, а й реалізувати свої ідеї для покращення продукту
                            </p>
                        </div>

                        <div>
                            <h4 class="text-white font-semibold text-lg mb-6">Навігація</h4>
                            <ul class="space-y-4 text-gray-400 text-base">
                                <li><router-link to="/" class="hover:text-emerald-400 transition-colors">Чати</router-link></li>
                                <li><router-link to="/users" class="hover:text-emerald-400 transition-colors">Користувачі</router-link></li>
                                <li><a href="/policy" class="hover:text-emerald-400 transition-colors">Політика конфіденційності</a></li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-white font-semibold text-lg mb-6">Зв'язок з нами</h4>
                            <div class="space-y-4 text-gray-400 text-base">
                                <p>Telegram: <a href="https://t.me/dmitry4312" target="_blank" class="text-emerald-400 hover:underline">@dmitry4312</a></p>
                                <p>Пошта: <a href="mailto:hy6ikvto@gmail.com" class="text-emerald-400 hover:underline">hy6ikvto@gmail.com</a></p>
                                <a href="https://djinni.co/q/54b7a6d864/" target="_blank" class="hover:text-emerald-400 transition-colors">Профіль на Ginni</a>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-10 text-center text-sm text-gray-500">
                        <p>© {{ new Date().getFullYear() }} The WESTERN TRADE GROUP</p>
                        <p class="mt-2">has been a leading corporation in the European market for investing in Spanish real estate for over 25 years</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from "vue-router"

const router = useRouter()
const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)
const userName = computed(() => authStore.userName)

const navItems = {
    '/': 'Чати',
    '/users': 'Користувачі',
}

const mobileMenuOpen = ref(false)

const logout = async () => {
    await authStore.logout()
    mobileMenuOpen.value = false
    await router.push('/login')
}
</script>
