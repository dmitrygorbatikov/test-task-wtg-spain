<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-950 via-gray-900 to-black py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-10 bg-gray-900/80 backdrop-blur-xl p-10 rounded-2xl border border-gray-800/50 shadow-2xl shadow-black/50">

            <!-- Заголовок -->
            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-white tracking-tight">
                    Регистрация в <span class="text-emerald-400">PharmaPro</span>
                </h2>
                <p class="mt-3 text-gray-400">
                    Создай аккаунт за 30 секунд
                </p>
            </div>

            <!-- Этап 1: Данные пользователя -->
            <form v-if="!codeSent" @submit.prevent="sendCode" class="mt-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Имя</label>
                        <input
                            v-model="form.firstName"
                            type="text"
                            required
                            class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Dmitry"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Фамилия</label>
                        <input
                            v-model="form.lastName"
                            type="text"
                            required
                            class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Gorbatikov"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
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
                        class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="••••••••"
                    />
                </div>

                <div class="text-sm text-gray-400">
                    Нажимая «Зарегистрироваться», вы соглашаетесь с
                    <a href="#" class="text-emerald-400 hover:underline">политикой конфиденциальности</a>
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? 'Отправка кода...' : 'Зарегистрироваться' }}
                </button>
            </form>

            <!-- Этап 2: Ввод кода -->
            <form v-else @submit.prevent="finishRegistration" class="mt-8 space-y-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white">Подтверждение email</h3>
                    <p class="mt-3 text-gray-400">
                        Мы отправили код на <strong class="text-emerald-400">{{ form.email }}</strong>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Код из письма (6 символов)</label>
                    <input
                        v-model="code"
                        type="text"
                        maxlength="6"
                        required
                        class="mt-1 block w-full px-6 py-4 bg-gray-800 border border-gray-700 rounded-xl text-white text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="3AY959"
                    />
                </div>

                <div class="flex flex-col gap-4">
                    <button
                        type="submit"
                        :disabled="loading || !code.trim()"
                        class="w-full py-4 px-6 rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50"
                    >
                        {{ loading ? 'Проверка...' : 'Подтвердить' }}
                    </button>

                    <button
                        type="button"
                        @click="resendCode"
                        :disabled="loading || resendCooldown"
                        class="text-emerald-400 hover:text-emerald-300 text-sm transition-colors"
                    >
                        {{ resendCooldown ? `Отправить повторно через ${resendCooldown} сек` : 'Отправить код повторно' }}
                    </button>
                </div>
            </form>

            <!-- Ошибка -->
            <div v-if="error" class="mt-6 p-4 bg-red-900/50 border border-red-800/50 rounded-xl text-red-300 text-center">
                {{ error }}
            </div>

            <!-- Успех -->
            <div v-if="success" class="mt-6 p-6 bg-emerald-900/30 border border-emerald-800/50 rounded-2xl text-center">
                <h3 class="text-2xl font-bold text-emerald-400 mb-3">Регистрация завершена!</h3>
                <p class="text-gray-300 mb-6">Добро пожаловать в PharmaPro</p>
                <button
                    @click="goToDashboard"
                    class="inline-flex items-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-emerald-900/30"
                >
                    Перейти в личный кабинет
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
})

const code = ref('')
const codeIdentifier = ref(null)

const loading = ref(false)
const error = ref('')
const success = ref(false)
const codeSent = ref(false)
const resendCooldown = ref(0)

// Таймер для повторной отправки (60 сек)
const startResendCooldown = () => {
    resendCooldown.value = 60
    const interval = setInterval(() => {
        resendCooldown.value--
        if (resendCooldown.value <= 0) clearInterval(interval)
    }, 1000)
}

const sendCode = async () => {
    error.value = ''
    loading.value = true

    try {
        const response = await fetch('http://localhost:8082/api/code/initialize', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                firstName: form.value.firstName,
                lastName: form.value.lastName,
                email: form.value.email,
                password: form.value.password,
            }),
        })

        if (!response.ok) {
            const err = await response.json()
            throw new Error(err.message || 'Ошибка отправки кода')
        }

        const data = await response.json()
        codeIdentifier.value = data.codeIdentifier
        localStorage.setItem('reg_code_identifier', data.codeIdentifier) // сохраняем

        codeSent.value = true
        startResendCooldown()
    } catch (err) {
        error.value = err.message
    } finally {
        loading.value = false
    }
}

const resendCode = async () => {
    if (resendCooldown.value > 0) return

    error.value = ''
    loading.value = true

    try {
        const response = await fetch('http://localhost:8082/api/code/resend-verification', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: form.value.email,
                codePurpose: { id: 'user_finish_email_registration_with_confirmation_code' },
            }),
        })

        if (!response.ok) throw new Error('Ошибка повторной отправки')

        startResendCooldown()
    } catch (err) {
        error.value = err.message
    } finally {
        loading.value = false
    }
}

const finishRegistration = async () => {
    error.value = ''
    loading.value = true

    try {
        const response = await fetch('http://localhost:8082/api/code/finish', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                code: code.value.trim(),
                codeIdentifier: codeIdentifier.value,
                codePurpose: { id: 'user_finish_email_registration_with_confirmation_code' },
            }),
        })

        if (!response.ok) {
            const err = await response.json()
            throw new Error(err.message || 'Неверный код')
        }

        const data = await response.json()

        // Сохраняем токен
        localStorage.setItem('auth_token', data.token)
        localStorage.removeItem('reg_code_identifier')

        success.value = true

        // Через 2 секунды редирект
        setTimeout(() => {
            router.push('/') // или '/profile', '/'
        }, 2000)
    } catch (err) {
        error.value = err.message
    } finally {
        loading.value = false
    }
}

const goToDashboard = () => {
    router.push('/')
}

// При загрузке страницы — если есть сохранённый codeIdentifier и email, можно восстановить этап
onMounted(() => {
    const savedId = localStorage.getItem('reg_code_identifier')
    if (savedId) {
        codeIdentifier.value = savedId
        codeSent.value = true // сразу показываем ввод кода
    }
})
</script>
