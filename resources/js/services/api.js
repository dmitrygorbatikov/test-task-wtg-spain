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

        if (error.response?.status === 401) {

            const auth = useAuthStore()
            auth.logout()

            router.push('/login')
        }

        return Promise.reject(error)
    }
)
