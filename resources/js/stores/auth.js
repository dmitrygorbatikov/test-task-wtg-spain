import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {

    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token'),
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        userName: (state) => state.user?.firstName || 'Гость'
    },

    actions: {

        async init() {
            if (!this.token) return

            try {
                const { data } = await axios.get('/api/users/me')
                this.user = data
            } catch (e) {
                await this.logout()
            }
        },

        async login(credentials) {
            this.loading = true
            this.error = null

            try {
                const { data } = await axios.post('/api/code/login', credentials)

                this.token = data.token
                this.user = data.user

                localStorage.setItem('auth_token', data.token)

                return true

            } catch (e) {
                this.error = e.response?.data?.errors?.password?.[0] || e.response?.data?.message || 'Ошибка входа'
                return false
            } finally {
                this.loading = false
            }
        },

        async registrationInitialize(payload) {
            this.loading = true
            this.error = null

            try {
                const { data } = await axios.post('/api/code/initialize', payload)

                localStorage.setItem('reg_code_identifier', data.codeIdentifier)
                localStorage.setItem('register_email', payload.email)

                return true
            } catch (e) {
                console.log(e.response?.data)
                console.log(e.response?.data?.errors)
                console.log(e.response?.data?.errors?.password)
                console.log(e.response?.data?.errors?.password?.[0])
                this.error = e.response?.data?.errors?.password?.[0] || e.response?.data?.message || 'Ошибка отправки кода'
                return false
            } finally {
                this.loading = false
            }
        },

        async registrationResend(email) {
            this.loading = true
            this.error = null

            try {
                const { data } = await axios.post('/api/code/resend-verification', {
                    email,
                    codePurpose: { id: 'user_finish_email_registration_with_confirmation_code' }
                })

                localStorage.setItem('reg_code_identifier', data.codeIdentifier)

                return true
            } catch (e) {
                this.error = e.response?.data?.message || 'Ошибка повторной отправки'
                return false
            } finally {
                this.loading = false
            }
        },

        async registrationFinish(code) {
            this.loading = true
            this.error = null

            const identifier = localStorage.getItem('reg_code_identifier')

            if (!identifier) {
                this.error = 'Identifier not found'
                this.loading = false
                return false
            }

            try {
                const { data } = await axios.post('/api/code/finish', {
                    code,
                    codeIdentifier: identifier,
                    codePurpose: { id: 'user_finish_email_registration_with_confirmation_code' }
                })

                this.token = data.token
                this.user = data.user

                localStorage.setItem('auth_token', data.token)
                localStorage.removeItem('reg_code_identifier')

                return true

            } catch (e) {
                this.error = e.response?.data?.message || 'Неверный код'
                return false
            } finally {
                this.loading = false
            }
        },

        async logout() {
            this.loading = true
            this.error = null

            try {
                await axios.post('/api/logout')

                return true
            } catch (e) {
                this.error = e.response?.data?.message
                return false
            } finally {
                this.loading = false
                this.user = null
                this.token = null

                localStorage.removeItem('auth_token')
            }
        }

    }

})
