<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-950 via-gray-900 to-black py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-10 bg-gray-900/80 backdrop-blur-xl p-10 rounded-2xl border border-gray-800/50 shadow-2xl shadow-black/50">

            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-white tracking-tight">
                    Вхід в <span class="text-emerald-400">WTG Spain</span>
                </h2>
                <p class="mt-3 text-gray-400">
                    Введіть свої дані
                </p>
            </div>

            <form @submit.prevent="handleLogin" class="mt-8 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        autocomplete="email"
                        class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="Введіть email"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Пароль</label>

                    <div class="relative mt-1">
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            class="block w-full px-4 py-3 pr-12 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="••••••••"
                        />

                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
                        >
                            <svg
                                v-if="!showPassword"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                          -1.274 4.057-5.064 7-9.542 7
                          -4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>

                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19
                          c-4.478 0-8.268-2.943-9.542-7
                          a9.956 9.956 0 012.293-3.95M6.223 6.223
                          A9.956 9.956 0 0112 5
                          c4.478 0 8.268 2.943 9.542 7
                          a9.956 9.956 0 01-4.293 5.274M15 12
                          a3 3 0 11-6 0
                          3 3 0 016 0zm6 6L3 3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ authStore.loading ? 'Вхід...' : 'Увійти' }}
                </button>
            </form>

            <div v-if="authStore.error" class="mt-6 p-4 bg-red-900/50 border border-red-800/50 rounded-xl text-red-300 text-center">
                {{ authStore.error }}
            </div>

            <p class="mt-8 text-center text-sm text-gray-400">
                Немає аккаунта?
                <router-link to="/register" class="text-emerald-400 hover:text-emerald-300 font-medium">
                    Зареєструватися
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const form = ref({
    email: '',
    password: '',
})

const showPassword = ref(false)

const handleLogin = async () => {
    const success = await authStore.login(form.value)

    if (success) {
        await router.push('/')
    }
}
</script>
