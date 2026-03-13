import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import DefaultLayout from "../layouts/DefaultLayout.vue"
import Home from "../components/Home.vue"
import LoginForm from "../components/LoginForm.vue"
import RegisterForm from '../components/RegisterForm.vue'
import UserProfile from "../components/UserProfile.vue"
import Users from "../components/Users.vue";

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        children: [
            { path: '', component: Home, name: 'home', meta: { requiresAuth: true } },

            { path: 'profile', component: UserProfile, name: 'profile', meta: { requiresAuth: true } },

            { path: 'users', component: Users, name: 'users', meta: { requiresAuth: true } },

            { path: 'login', component: LoginForm, name: 'login', meta: { guest: true } },

            { path: 'register', component: RegisterForm, name: 'register', meta: { guest: true } },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {

    const auth = useAuthStore()

    if (auth.token && !auth.user) {
        await auth.init()
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return next({ name: 'login' })
    }

    if (to.meta.guest && auth.isAuthenticated) {
        return next({ name: 'home' })
    }

    next()
})

export default router
