<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-950 via-gray-900 to-black py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-10 bg-gray-900/80 backdrop-blur-xl p-10 rounded-2xl border border-gray-800/50 shadow-2xl shadow-black/50">

            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-white tracking-tight">

                    Реєстрація в <p class="text-emerald-400">WTG Spain</p>
                    <p v-if="success">завершена!</p>
                </h2>
                <p v-if="!success" class="mt-3 text-gray-400">
                    Створи аккаунт за 30 секунд
                </p>
            </div>

            <form v-if="!codeSent" @submit.prevent="sendCode" class="mt-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Ім'я</label>
                        <input
                            v-model="form.firstName"
                            type="text"
                            required
                            class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Введіть ім'я"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Прізвище</label>
                        <input
                            v-model="form.lastName"
                            type="text"
                            required
                            class="mt-1 block w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Введіть прізвище"
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

                <div class="text-sm text-gray-400">
                    Натиснувши «Зареєструватися», ви погоджуєтеся з
                    <a href="#" class="text-emerald-400 hover:underline">політикою конфіденційності</a>
                </div>

                <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ authStore.loading ? 'Відправка коду...' : 'Зареєструватися' }}
                </button>
            </form>

            <form v-if="!success && codeSent" @submit.prevent="finishRegistration" class="mt-8 space-y-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white">Підтвердження email</h3>
                    <p class="mt-3 text-gray-400">
                        Ми відправили код на <strong class="text-emerald-400">{{ registerEmail }}</strong>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Код з повідомлення</label>
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
                        :disabled="authStore.loading || !code.trim()"
                        class="w-full py-4 px-6 rounded-xl text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-900/30 disabled:opacity-50"
                    >
                        {{ authStore.loading ? 'Перевірка...' : 'Підтвердити' }}
                    </button>

                    <button
                        type="button"
                        @click="resendCode"
                        :disabled="authStore.loading || resendCooldown"
                        class="text-emerald-400 hover:text-emerald-300 text-sm transition-colors"
                    >
                        {{ 'Відправити код ще раз' }}
                    </button>
                </div>
            </form>

            <div v-if="authStore.error" class="mt-6 p-4 bg-red-900/50 border border-red-800/50 rounded-xl text-red-300 text-center">
                {{ authStore.error }}
            </div>

            <div v-if="success" class="mt-6 p-6 bg-emerald-900/30 border border-emerald-800/50 rounded-2xl text-center">
                <h3 class="text-2xl font-bold text-emerald-400 mb-3">Реєстрація завершена!</h3>
                <p class="text-gray-300 mb-6">Ласкаво просимо в WTG Spain</p>
                <button
                    @click="goToDashboard"
                    class="inline-flex items-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-emerald-900/30"
                >
                    Перейти до особистого кабінету
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import {useRouter} from 'vue-router'
import {useAuthStore} from "../stores/auth.js";

const router = useRouter()

const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
})

const registerEmail = ref('')
const authStore = useAuthStore()
const showPassword = ref(false)

const code = ref('')
const codeIdentifier = ref(null)

const success = ref(false)
const codeSent = ref(false)
const resendCooldown = ref(0)

const sendCode = async () => {
    const initializeResult = await authStore.registrationInitialize(form.value)

    if(initializeResult) {
        codeSent.value = true
    }
}

const resendCode = async () => {
    const resendResult = await authStore.registrationResend(form.value.email)

    if (resendResult) {
        codeSent.value = true
    }
}

const finishRegistration = async () => {
    const finishResult = await authStore.registrationFinish(code.value.trim())

    if (finishResult) {
        success.value = true

        setTimeout(() => {
            router.push('/profile')
        }, 2000000)
    }
}

const goToDashboard = () => {
    router.push('/')
}

onMounted(() => {
    registerEmail.value = localStorage.getItem('register_email') || 'email'
    const savedId = localStorage.getItem('reg_code_identifier')
    if (savedId) {
        codeIdentifier.value = savedId
        codeSent.value = true
    }
})
</script>
