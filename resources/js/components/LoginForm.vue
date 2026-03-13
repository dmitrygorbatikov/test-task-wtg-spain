<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-950 via-gray-900 to-black py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-10 bg-gray-900/80 backdrop-blur-xl p-10 rounded-2xl border border-gray-800/50 shadow-2xl shadow-black/50">

            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-white tracking-tight">
                    Вход в <span class="text-emerald-400">PharmaPro</span>
                </h2>
                <p class="mt-3 text-gray-400">
                    Введите свои данные
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
                        placeholder="hy6ikvto@gmail.com"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Пароль</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="••••••••"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ authStore.loading ? 'Вход...' : 'Войти' }}
                </button>
            </form>

            <div v-if="authStore.error" class="mt-6 p-4 bg-red-900/50 border border-red-800/50 rounded-xl text-red-300 text-center">
                {{ authStore.error }}
            </div>

            <p class="mt-8 text-center text-sm text-gray-400">
                Нет аккаунта?
                <router-link to="/register" class="text-emerald-400 hover:text-emerald-300 font-medium">
                    Зарегистрироваться
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

const handleLogin = async () => {
    const success = await authStore.login(form.value)

    if (success) {
        await router.push('/')
    }
}
</script>
