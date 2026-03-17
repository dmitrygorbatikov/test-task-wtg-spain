<template>
    <div class="h-[calc(100vh-80px)] flex bg-gray-950 text-gray-100">

        <aside
            class="
        fixed md:static inset-0 z-30
        w-full md:w-80 bg-gray-950 border-r border-gray-800
        flex flex-col transform transition-transform duration-300 ease-in-out
        md:translate-x-0
      "
            :class="{
        'translate-x-0': showChatList,
        '-translate-x-full': !showChatList && isMobile
      }"
        >

            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-xl font-bold">Чати</h2>
                <button
                    v-if="isMobile && selectedChat"
                    class="md:hidden text-gray-400 hover:text-white text-2xl leading-none"
                    @click="closeChat"
                >
                    ×
                </button>
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


        <main class="flex-1 flex flex-col min-w-0">

            <header class="
        h-14 md:h-16 border-b border-gray-800
        flex items-center px-4 md:px-6 gap-4
        bg-gray-950/90 backdrop-blur-sm sticky top-0 z-10
      ">
                <button
                    v-if="isMobile && selectedChat"
                    class="text-2xl text-gray-300 hover:text-white md:hidden"
                    @click="closeChat"
                >
                    ←
                </button>

                <div v-if="selectedChat" class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="
            w-9 h-9 md:w-10 md:h-10 rounded-full bg-gray-700
            flex items-center justify-center text-sm font-bold text-gray-300
          ">
                        {{ getInitials(getUser(selectedChat)) }}
                    </div>
                    <div class="font-medium truncate">
                        {{ getUser(selectedChat).firstName }} {{ getUser(selectedChat).lastName }}
                    </div>
                </div>

                <div v-else class="text-gray-400 font-medium md:hidden">
                    Чати
                </div>
            </header>

            <div
                v-if="!selectedChat && !isMobile"
                class="flex flex-1 flex-col items-center justify-center text-center text-gray-400"
            >

                <div class="w-24 h-24 rounded-full bg-gray-900 flex items-center justify-center mb-6">
                    💬
                </div>

                <h2 class="text-2xl font-semibold mb-2">
                    Оберіть чат
                </h2>

            </div>


            <template v-if="selectedChat">
                <div
                    ref="containerRef"
                    class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 pb-24 md:pb-6"
                    @scroll="onScroll"
                >
                    <div
                        v-for="msg in messages"
                        :key="msg.id"
                        class="flex"
                        :class="msg.senderId === myId ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            class="
                max-w-[80%] md:max-w-md px-4 py-2.5 rounded-2xl relative group break-words
              "
                            :class="msg.senderId === myId
                ? 'bg-emerald-600 text-white rounded-br-none'
                : 'bg-gray-800 text-gray-100 rounded-bl-none'"
                        >
                            {{ msg.content }}

                            <div
                                v-if="msg.senderId === myId"
                                class="absolute bottom-1 right-2.5 flex items-center text-xs opacity-80"
                            >
                                <svg
                                    v-if="messagesStore.loadingSendMessage"
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
                                </svg>

                                <svg
                                    v-else
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

                <footer class="p-3 md:p-4 border-t border-gray-800 flex gap-3 bg-gray-950 sticky bottom-0 z-10">
                    <input
                        v-model="messageInput"
                        @keyup.enter="sendMessage"
                        placeholder="Повідомлення..."
                        class="flex-1 bg-gray-900 border border-gray-800 rounded-full px-5 py-3 focus:outline-none focus:border-emerald-500 placeholder-gray-500"
                    />
                    <button
                        @click="sendMessage"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 rounded-full font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!messageInput.trim()"
                    >
                        Відправити
                    </button>
                </footer>
            </template>
        </main>
    </div>
</template>


<script setup>
import {ref, onMounted, onUnmounted, nextTick, watch, computed} from "vue"
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

const isMobile = computed(() => window.innerWidth < 768)
const showChatList = ref(true)

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

function getInitials(user) {
    return (user?.firstName?.[0] || '') + (user?.lastName?.[0] || '')
}

function closeChat() {
    selectedChat.value = null
    messages.value = []
    showChatList.value = true
    // if (echoChannel) window.Echo.leave(echoChannel)
    // echoChannel = null
    router.replace({ query: {} })
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

    showChatList.value = false

    console.log(showChatList.value)

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
    if (chatId) {
        await chatsStore.loadChatItem(chatId)
        if (chatsStore.chat) {
            await openChat(chatsStore.chat)
        }
    }

    Echo.join('online')
        .here((users) => {
            console.log('current users: ', users)
            chatsStore.updateUserActivityList(users);
        })
        .joining((user) => {
            console.log('joining user: ', user)

            chatsStore.userJoining(user);
        })
        .leaving((user) => {
            console.log('leaving user: ', user)

            chatsStore.userLeaving(user);
        })
        .error((error) => console.error('Error presence:', error));

    userChannel = Echo.private(`user.${myId}`)

    userChannel.listen('.new.message', async (e) => {
        const { chat_id, message } = e

        console.log(e)
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
