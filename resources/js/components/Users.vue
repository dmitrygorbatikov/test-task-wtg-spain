<template>
    <div class="h-[calc(100vh-80px)] flex flex-col bg-gray-950 text-gray-100 p-6">
        <h2 class="text-2xl font-bold mb-6">Пошук</h2>

        <input
            v-model="search"
            type="text"
            placeholder="Введіть ім'я..."
            class="mb-6 px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg focus:outline-none focus:border-emerald-500"
        />

        <div class="flex-1 overflow-y-auto space-y-2">

            <div v-if="usersStore.loading" class="text-gray-400 text-center py-4">
                Загрузка...
            </div>

            <div
                v-for="user in usersStore.users"
                :key="user.slug"
                class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-900 transition"
            >
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold">
                        {{ user.firstName[0] }}{{ user.lastName[0] }}
                    </div>

                    <div>
                        <div class="font-semibold">
                            {{ user.firstName }} {{ user.lastName }}
                        </div>

                        <div class="text-sm text-gray-400">
                            {{ user.email || user.slug }}
                        </div>

                        <div
                            v-if="user.chatId"
                            class="text-xs text-emerald-400 mt-1"
                        >
                            Чат вже існує
                        </div>
                    </div>
                </div>

                <button
                    v-if="user.chatId"
                    @click="openChat(user.chatId)"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold"
                >
                    Відкрити чат
                </button>

                <button
                    v-else
                    @click="selectUser(user)"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-sm font-semibold"
                >
                    Написати
                </button>
            </div>

            <div
                v-if="!usersStore.loading && usersStore.users.length === 0"
                class="text-gray-500 text-center mt-10"
            >
                Користувачі не знайдені
            </div>
        </div>

        <div class="mt-4 flex justify-center items-center gap-3">

            <button
                @click="prevPage"
                :disabled="usersStore.page === 1"
                class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg disabled:opacity-50"
            >
                Назад
            </button>

            <span>{{ usersStore.page }} / {{ totalPages }}</span>

            <button
                @click="nextPage"
                :disabled="usersStore.page >= totalPages"
                class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg disabled:opacity-50"
            >
                Далі
            </button>
        </div>

        <div
            v-if="activeUser"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        >
            <div class="bg-gray-900 rounded-2xl w-full max-w-lg p-6 relative">

                <button
                    @click="activeUser = null"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-200"
                >
                    ✕
                </button>

                <h3 class="text-xl font-bold mb-4">
                    Повідомлення для {{ activeUser.firstName }} {{ activeUser.lastName }}
                </h3>

                <textarea
                    v-model="messageInput"
                    rows="4"
                    placeholder="Повідомлення..."
                    class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 focus:outline-none focus:border-emerald-500"
                ></textarea>

                <div class="mt-4 flex justify-end gap-3">

                    <button
                        @click="sendMessage"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-semibold"
                    >
                        Відправити
                    </button>

                    <button
                        @click="activeUser = null"
                        class="px-6 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg font-semibold"
                    >
                        Назад
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, watch, computed, onMounted } from "vue"
import { useRouter } from "vue-router"
import { useUsersStore } from "../stores/users"
import { debounce } from "lodash-es"
import axios from "axios"

const router = useRouter()
const usersStore = useUsersStore()

const search = ref("")
const activeUser = ref(null)
const messageInput = ref("")

const totalPages = computed(() =>
    Math.ceil(usersStore.total / usersStore.perPage)
)

const debouncedLoadUsers = debounce((val) => {
    usersStore.reset()
    usersStore.loadUsers(val)
}, 1500)

watch(search, (val) => {
    debouncedLoadUsers(val)
})

onMounted(() => {
    usersStore.reset()
    usersStore.loadUsers()
})

function selectUser(user) {

    console.log(user)
    activeUser.value = user
    messageInput.value = ""
}

function openChat(chatId) {
    router.push(`/?chatId=${chatId}`)
}

async function sendMessage() {

    if (!messageInput.value.trim()) return

    try {

        const res = await axios.post("/api/chats", {
            secondId: activeUser.value.id,
            message: messageInput.value
        })

        const chatId = res.data.chat.id

        await router.push(`/?chatId=${chatId}`)

    } catch (e) {
        console.error(e)
    }

    activeUser.value = null
    messageInput.value = ""
}

function nextPage() {
    if (usersStore.page >= totalPages.value) return
    usersStore.page++
    usersStore.loadUsers(search.value)
}

function prevPage() {
    if (usersStore.page <= 1) return
    usersStore.page--
    usersStore.loadUsers(search.value)
}

</script>
