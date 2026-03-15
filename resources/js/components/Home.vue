<template>
    <div class="h-[calc(100vh-80px)] flex bg-gray-950 text-gray-100">

        <aside class="w-80 border-r border-gray-800 flex flex-col">

            <div class="p-4 border-b border-gray-800">
                <h2 class="text-xl font-bold">Чати</h2>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div
                    v-for="chat in chatsStore.chats"
                    :key="chat.id"
                    @click="openChat(chat)"
                    class="flex items-center gap-4 px-4 py-3 cursor-pointer hover:bg-gray-900/70 transition-colors duration-150"
                    :class="selectedChat?.id === chat.id ? 'bg-gray-900/50' : ''"
                >

                <div class="relative shrink-0">

                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center text-base font-semibold text-gray-300 shadow-sm">
                        {{ getUser(chat).firstName?.[0] || '' }}{{ getUser(chat).lastName?.[0] || '' }}
                    </div>

                    <span
                        v-if="chatsStore.isUserOnline(chat, myId)"
                        class="absolute bottom-0 right-0 block w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-gray-950 shadow-md"
                        title="Online"
                    ></span>

                </div>

                <div class="flex-1 min-w-0 flex flex-col">

                    <div class="flex items-baseline justify-between gap-2">
      <span class="font-medium text-gray-100 truncate">
        {{ getUser(chat).firstName }} {{ getUser(chat).lastName }}
      </span>

                        <span class="text-xs text-gray-500 shrink-0">
        {{ formatLastMessageTime(chat.lastMessageAt) }}
      </span>
                    </div>

                    <div class="flex items-center justify-between gap-2 mt-0.5">

                        <span class="text-sm text-gray-400 truncate flex-1">
        {{ chat.lastMessageContent?.content || 'Нет сообщений' }}
      </span>

                        <span
                            v-if="chat.unreadCount > 0"
                            class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold text-white bg-emerald-500 rounded-full shadow-sm flex-shrink-0"
                        >
        {{ chat.unreadCount > 99 ? '99+' : chat.unreadCount }}
      </span>

                    </div>

                </div>

            </div>


                <div
                    v-if="chatsStore.hasMore"
                    ref="loadMoreTrigger"
                    class="h-16 flex items-center justify-center text-sm text-gray-500 py-4"
                >
                    <span v-if="chatsStore.loadingMore">Loading...</span>
                </div>

                <div
                    v-if="chatsStore.loading && chatsStore.chats.length === 0"
                    class="py-8 text-center text-gray-500"
                >
                    Chats loading...
                </div>

                <div
                    v-if="!chatsStore.loading && chatsStore.chats.length === 0"
                    class="py-8 text-center text-gray-500"
                >
                    У вас поки немає чатів
                </div>
            </div>

        </aside>


        <main class="flex-1 flex flex-col">

            <div
                v-if="!selectedChat"
                class="flex flex-1 flex-col items-center justify-center text-center text-gray-400"
            >

                <div class="w-24 h-24 rounded-full bg-gray-900 flex items-center justify-center mb-6">
                    💬
                </div>

                <h2 class="text-2xl font-semibold mb-2">
                    Выберите чат
                </h2>

            </div>


            <template v-else>

                <header class="h-16 border-b border-gray-800 flex items-center px-6">

                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold">
                            {{ getUser(selectedChat).firstName[0] }}{{ getUser(selectedChat).lastName[0] }}
                        </div>

                        <div>
                            <div class="font-semibold">
                                {{ getUser(selectedChat).firstName }} {{ getUser(selectedChat).lastName }}
                            </div>
                        </div>

                    </div>

                </header>


                <div
                    ref="containerRef"
                    class="messages-container flex-1 overflow-y-auto p-6 space-y-4"
                    @scroll="onScroll"
                >

                    <div
                        v-for="msg in messages"
                        :key="msg.id"
                        class="flex"
                        :class="msg.senderId === myId ? 'justify-end' : 'justify-start'"
                    >

                        <div
                            class="max-w-md px-4 py-2 rounded-xl relative group"
                            :class="msg.senderId === myId
        ? 'bg-emerald-600 text-white'
        : 'bg-gray-800 text-gray-100'"
                        >
                            {{ msg.content }}

                            <div
                                v-if="msg.senderId === myId"
                                class="absolute bottom-1 right-2 flex items-center text-xs"
                            >
                                <svg
                                    v-if="messagesStore.loadingSendMessage && messagesStore.messages[messagesStore.messages.length-1].content === msg.content"
                                    class="w-4 h-4 text-gray-300 animate-pulse"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>

                                <svg
                                    v-else-if="!msg.isRead"
                                    class="w-4 h-4 text-gray-300"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" transform="translate(6 0)"/>
                                </svg>

                                <svg
                                    v-else-if="msg.isRead"
                                    class="w-4 h-4 text-blue-400"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" transform="translate(6 0)"/>
                                </svg>
                            </div>
                        </div>

                    </div>

                </div>


                <footer class="p-4 border-t border-gray-800 flex gap-3">

                    <input
                        v-model="messageInput"
                        @keyup.enter="sendMessage"
                        placeholder="Повідомлення..."
                        class="flex-1 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 focus:outline-none focus:border-emerald-500"
                    />

                    <button
                        @click="sendMessage"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-semibold"
                    >
                        Відправити
                    </button>

                </footer>

            </template>

        </main>

    </div>
</template>


<script setup>
import {ref, onMounted, onUnmounted, nextTick, watch} from "vue"
import { useChatsStore } from "../stores/chats.js"
import { useMessagesStore } from "../stores/messages.js"
import { useAuthStore } from "../stores/auth.js"
import { useRoute, useRouter } from "vue-router"
import '../utils/date.js'
import {formatLastMessageTime} from "../utils/date.js";
const route = useRoute()
const router = useRouter()

const chatsStore = useChatsStore()
const messagesStore = useMessagesStore()
const authStore = useAuthStore()

const selectedChat = ref(null)
const messages = ref([])
const messageInput = ref("")
const myId = authStore.user.id

let userChannel = null
let echoChannel = null
let isLoadingOld = false
let hasMore = true
let page = 1
const perPage = messagesStore.perPage

const containerRef = ref(null)

/* HELPERS */
function getUser(chat) {
    return chat.first.id === myId ? chat.second : chat.first
}

function scrollToBottom() {
    nextTick(() => {
        if (containerRef.value) {
            containerRef.value.scrollTop = containerRef.value.scrollHeight
        }
    })
}

function subscribeToChat(chatId) {
    if (echoChannel) window.Echo.leave(echoChannel)

    echoChannel = `chat.${chatId}`
    const channel = window.Echo.private(echoChannel)

    channel.listen(".message.sent", async (message) => {
        if (message.senderId !== myId) {
            await messagesStore.readMessages(chatId)
            messages.value.push(message)
            scrollToBottom()
        }

        const chat_id = message.chatId

        let chatIndex = chatsStore.chats.findIndex(c => c.id === chat_id)

        if (chatIndex !== -1) {
            const chat = chatsStore.chats[chatIndex]

            chat.lastMessageContent = { content: message.content }
            chat.lastMessageAt = message.createdAt

            chatsStore.sortChats()
        }
        else {
            try {
                await chatsStore.loadChatItem(chat_id)

                if (chatsStore.chat) {
                    const newChat = {
                        ...chatsStore.chat,
                        unreadCount: 1,
                        lastMessageContent: { content: message.content },
                        lastMessageAt: message.createdAt
                    }

                    chatsStore.chats.unshift(newChat)
                    chatsStore.sortChats()
                }
            } catch (err) {
                console.warn(`Error chat loading ${chat_id} for message`, err)
            }
        }
    })

    channel.listen(".message.read", () => {
        messagesStore.messages.forEach(message => {
            message.isRead = true
        })
    })
}

async function readMessagesEcho(chatId) {
    const res = await messagesStore.readMessages(chatId)

    if(res === true) {
        const index = chatsStore.chats.findIndex(chat => chat.id === chatId)

        chatsStore.chats[index].unreadCount = 0
    }
}

async function openChat(chat) {
    messagesStore.resetSendMessage()
    messagesStore.reset()

    selectedChat.value = chat
    page = 1
    hasMore = true

    await loadMessages(chat.id, page, true)
    subscribeToChat(chat.id)

    if(chat.unreadCount > 0) {
        await readMessagesEcho(chat.id)
    }

    scrollToBottom()

    await router.replace({query: {chatId: chat.id}})
}

async function loadMessages(chatId, pageNumber = 1, scrollToEnd = false) {
    if (isLoadingOld || !hasMore) return
    isLoadingOld = true

    const container = containerRef.value
    const previousHeight = container?.scrollHeight || 0

    await messagesStore.loadMessages(chatId, pageNumber)

    if (messagesStore.messages.length < perPage) hasMore = false

    messages.value = messagesStore.messages

    if (pageNumber === 1) {
        if (scrollToEnd) scrollToBottom()
    } else {
        await nextTick(() => {
            if (container) {
                container.scrollTop = container.scrollHeight - previousHeight
            }
        })
    }

    isLoadingOld = false
}

function onScroll() {
    if (!containerRef.value) return
    if (containerRef.value.scrollTop <= 50 && hasMore && !isLoadingOld) {
        page++
        loadMessages(selectedChat.value.id, page)
    }
}

async function sendMessage() {
    if (!messageInput.value.trim()) return

    await messagesStore.sendMessage(selectedChat.value.id, messageInput.value)
    messages.value.push(messagesStore.sendMessageData)
    messagesStore.resetSendMessage()
    messageInput.value = ""
    scrollToBottom()
}

const loadMoreTrigger = ref(null)
let observer = null

onMounted(async () => {
    await chatsStore.loadChats(true)

    const chatId = route.query.chatId
    if (!chatId) return

    await chatsStore.loadChatItem(chatId)
    if (chatsStore.chat) {
        await openChat(chatsStore.chat)
    }

    Echo.join('online')
        .here((users) => {
            chatsStore.updateUserActivityList(users);
        })
        .joining((user) => {
            chatsStore.userJoining(user);
        })
        .leaving((user) => {
            chatsStore.userLeaving(user);
        })
        .error((error) => console.error('Error presence:', error));

    userChannel = Echo.private(`user.${myId}`)

    userChannel.listen('.new.message', async (e) => {
        const { chat_id, message } = e

        if (selectedChat.value?.id === chat_id) {

            console.log(true)
            return
        }

        let chatIndex = chatsStore.chats.findIndex(c => c.id === chat_id)

        if (chatIndex !== -1) {
            const chat = chatsStore.chats[chatIndex]

            chat.unreadCount = (chat.unreadCount || 0) + 1
            chat.lastMessageContent = { content: message.content }
            chat.lastMessageAt = message.created_at

            chatsStore.sortChats()
        }
        else {
            try {
                await chatsStore.loadChatItem(chat_id)

                if (chatsStore.chat) {
                    const newChat = {
                        ...chatsStore.chat,
                        unreadCount: 1,
                        lastMessageContent: { content: message.content },
                        lastMessageAt: message.created_at
                    }

                    chatsStore.chats.unshift(newChat)
                    chatsStore.sortChats()
                }
            } catch (err) {
                console.warn(`Error chat loading ${chat_id} for message`, err)
            }
        }
    })

    observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && chatsStore.hasMore && !chatsStore.loadingMore) {
                chatsStore.loadMoreChats()
            }
        },
        {
            root: null,
            rootMargin: '0px 0px 300px 0px',
            threshold: 0.01
        }
    )

    if (loadMoreTrigger.value) {
        observer.observe(loadMoreTrigger.value)
    } else {
        console.warn('loadMoreTrigger не найден в DOM')
    }

    watch(
        [() => chatsStore.chats.length, () => chatsStore.hasMore, () => chatsStore.loadingMore],
        async () => {
            await nextTick()

            if (loadMoreTrigger.value && chatsStore.hasMore) {
                observer.observe(loadMoreTrigger.value)
            } else if (loadMoreTrigger.value) {
                observer.unobserve(loadMoreTrigger.value)
            }
        },
        { immediate: true }
    )
})

onUnmounted(() => {
    if (observer) {
        observer.disconnect()
    }
    if (echoChannel) window.Echo.leave(echoChannel)
    if (userChannel) {
        Echo.leave(`user.${myId}`)
    }
})
</script>
