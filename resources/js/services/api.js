import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import router from '../router'

axios.interceptors.request.use(config => {

    const token = localStorage.getItem('auth_token')

    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config
})

axios.interceptors.response.use(
    r => r,
    error => {
        const auth = useAuthStore()
        const originalRequest = error.config

        if (error.response?.status === 401 &&
            !originalRequest._retry &&
            !originalRequest.url?.includes('/login') &&
            !originalRequest.url?.includes('/register')
        ) {
            originalRequest._retry = true
            auth.logout()
            router.push('/login')
        }

        return Promise.reject(error)
    }
)
