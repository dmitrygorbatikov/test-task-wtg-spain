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
        {{ chat.lastMessageContent?.content || ' ' }}
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
        bg-gray-950/90 backdrop-blur-sm top-0 z-10
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

                    <!-- Кнопка звонка -->
                    <button
                        class="ml-auto text-emerald-500 hover:text-emerald-400"
                        @click="startCall(getUser(selectedChat).id)"
                        title="Позвонить"
                    >
                        📞
                    </button>
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

            <template v-if="callModal.visible">
                <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                    <div class="bg-gray-900 p-6 rounded-xl flex flex-col gap-4 w-80 text-center">
                        <h3>
                            {{
                                callModal.status === 'calling' ? 'Звоним...' :
                                    callModal.status === 'ringing' ? 'Входящий звонок' :
                                        callModal.status === 'active' ? 'В разговоре' :
                                            'Звонок'
                            }}
                        </h3>
                        <p class="text-gray-300">
                            {{ getUserById(callModal.incoming ? callModal.from : callModal.to)?.firstName }}
                        </p>

                        <div class="flex gap-4 justify-center mt-4">
                            <audio ref="remoteAudio" autoplay playsinline></audio>
                            <!-- входящий -->
                            <button v-if="callModal.status === 'ringing'" @click="acceptCall">
                                Принять
                            </button>

                            <button v-if="callModal.status === 'ringing'" @click="rejectCall">
                                Отклонить
                            </button>

                            <!-- исходящий -->
                            <button v-if="callModal.status === 'calling'" @click="endCall">
                                Отменить
                            </button>

                            <!-- активный -->
                            <button v-if="callModal.status === 'active'" @click="endCall">
                                Завершить
                            </button>
                        </div>
                    </div>
                </div>
            </template>


            <template v-if="selectedChat">
                <div
                    ref="containerRef"
                    class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 md:pb-6"
                    @scroll="onScroll"
                >
                    <template v-for="(msg, index) in messagesStore.messages" :key="msg.id || msg.tempId">

                        <div v-if="!messagesStore.loading && isNewDay(index)" class="flex justify-center my-4">
                <span class="bg-gray-800 text-gray-300 text-xs px-3 py-1 rounded-full">
                    {{ formatDate(msg.createdAt, messagesStore) }}
                </span>
                        </div>

                        <div
                            class="flex"
                            :class="msg.senderId === myId ? 'justify-end' : 'justify-start'"
                        >
                            <div
                                class="max-w-[80%] md:max-w-md px-4 py-2.5 pr-16 rounded-2xl relative group break-words"
                                :class="msg.senderId === myId
                        ? 'bg-emerald-600 text-white rounded-br-none'
                        : 'bg-gray-800 text-gray-100 rounded-bl-none'"
                            >

                                <div class="whitespace-pre-wrap">
                                    {{ msg.content }}
                                </div>

                                <div
                                    class="absolute bottom-1.5 right-2 flex items-center gap-1 text-xs opacity-80"
                                >
                                    <svg
                                        v-if="msg.senderId === myId && messagesStore.loadingSendMessage && msg.id === null && index === messagesStore.messages.length-1"
                                        class="w-4 h-4 text-gray-300 animate-pulse"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>

                                    <svg
                                        v-if="msg.senderId === myId && messagesStore.errorSendMessage && msg.id === null"
                                        class="w-4 h-4 text-red-500"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.536-10.95a1 1 0 10-1.414-1.414L10 7.757 7.879 5.636A1 1 0 106.464 7.05L8.586 9.172 6.464 11.293a1 1 0 101.415 1.414L10 10.586l2.121 2.121a1 1 0 001.415-1.414L11.414 9.17l2.122-2.121z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                    <svg
                                        v-if="msg.senderId === myId && !msg.isRead && msg.id !== null && msg.createdAt !== null"
                                        class="w-4 h-4 text-gray-300"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>

                                    <svg
                                        v-if="msg.senderId === myId && msg.isRead"
                                        class="w-4 h-4 text-blue-400"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" transform="translate(6 0)"/>
                                    </svg>

                                    <span
                                        v-if="!(msg.senderId === myId && messagesStore.errorSendMessage && msg.id === null) && msg.createdAt !== null"
                                        class="text-[11px] opacity-70"
                                    >
                            {{ formatTime(msg.createdAt) }}
                        </span>

                                </div>
                            </div>
                        </div>
                    </template>

                </div>
                <footer class="p-3 md:p-4 border-t border-gray-800 flex gap-3 bg-gray-950 bottom-0 z-10"> <input v-model="messageInput" @keyup.enter="sendMessage" placeholder="Повідомлення..." class="flex-1 bg-gray-900 border border-gray-800 rounded-full px-5 py-3 focus:outline-none focus:border-emerald-500 placeholder-gray-500" /> <button @click="sendMessage" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 rounded-full font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!messageInput.trim()" > Відправити </button> </footer>

            </template>
        </main>
    </div>
</template>


<!--
  ВАШ ТЕМПЛЕЙТ ОСТАЁТСЯ БЕЗ ИЗМЕНЕНИЙ
  (я только исправил <script setup>)
-->

<script setup>
import {ref, onMounted, onUnmounted, nextTick, watch, computed} from "vue"
import { useChatsStore } from "../stores/chats.js"
import { useMessagesStore } from "../stores/messages.js"
import { useAuthStore } from "../stores/auth.js"
import { useRoute, useRouter } from "vue-router"
import '../utils/date.js'
import {formatDate, formatLastMessageTime, formatTime, isNewDay} from "../utils/date.js";
import { io } from "socket.io-client"

const socket = io(import.meta.env.VITE_WEBSOCKET_SERVER_URL, {
    transports: ["websocket"],
})

let pendingCandidates = []

/* =======================
   WEBRTC
======================= */

let pc = null

const configuration = {
    iceServers: [
        // Бесплатные STUN (для обнаружения публичного IP)
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun2.l.google.com:19302' },

        // OpenRelay (твой текущий) — оставляем как запасной
        {
            urls: 'turn:openrelay.metered.ca:80',
            username: 'openrelayproject',
            credential: 'openrelayproject',
        },
        {
            urls: 'turn:openrelay.metered.ca:443?transport=tcp',
            username: 'openrelayproject',
            credential: 'openrelayproject',
        },

        // === Добавь эти серверы (самое важное улучшение) ===
        // ExpressTURN — хороший бесплатный лимит (до 1000 GB/мес на free плане)
        {
            urls: 'turn:turn.expressturn.com:3478',
            username: 'expressturn',           // проверь актуальные credentials на сайте
            credential: 'expressturn',         // часто меняются — зайди на https://www.expressturn.com/
        },

        // Дополнительно: freeturn.net или другие публичные (если нужно)
        // { urls: 'turn:freeturn.net:3478', username: '...', credential: '...' },
    ],

    // Важные настройки для надёжности
    iceTransportPolicy: import.meta.env.VITE_ICE_TRANSPORT_POLICY, // 'all' или 'relay' для тестов
    bundlePolicy: 'max-bundle',
    rtcpMuxPolicy: 'require',
};

const localStream = ref(null)
const remoteStream = ref(null)

async function initMedia() {
    localStream.value = await navigator.mediaDevices.getUserMedia({
        audio: true,
        video: false,
    })
}

async function createPeerConnection() {
    pc = new RTCPeerConnection(configuration)

    pc.onicecandidate = (event) => {
        if (event.candidate && callModal.value.callId) {
            socket.emit('call.ice', {
                callId: callModal.value.callId,
                candidate: event.candidate,
                sender: myId,
            })
        }
    }

    pc.ontrack = (event) => {
        const stream = event.streams[0]
        remoteStream.value = stream

        if (remoteAudio.value) {
            remoteAudio.value.srcObject = stream
            remoteAudio.value.muted = false

            remoteAudio.value.onloadedmetadata = () => {
                remoteAudio.value.play().catch(err => {
                    console.warn("Автовоспроизведение заблокировано:", err)
                })
            }
        }
    }

    if (localStream.value) {
        localStream.value.getTracks().forEach(track => {
            pc.addTrack(track, localStream.value)
        })
    }

    pc.oniceconnectionstatechange = () => {
        console.log('ICE connection state:', pc.iceConnectionState);
    };

    pc.onicegatheringstatechange = () => {
        console.log('ICE gathering state:', pc.iceGatheringState);
    };

// Логируем все кандидаты
    pc.onicecandidate = (event) => {
        if (event.candidate) {
            console.log('New ICE candidate:', {
                type: event.candidate.type,           // host / srflx / relay — самое важное!
                protocol: event.candidate.protocol,
                address: event.candidate.address,
                port: event.candidate.port,
                relatedAddress: event.candidate.relatedAddress,
            });

            socket.emit('call.ice', {
                callId: callModal.value.callId,
                candidate: event.candidate,
                sender: myId,
            });
        }
    };
}

/* =======================
   CALL STATE
======================= */

const callModal = ref({
    visible: false,
    callId: null,
    from: null,
    to: null,
    incoming: false,
    status: 'idle',
    offer: null,
})

async function startCall(toUserId) {
    await initMedia()

    const callId = crypto.randomUUID()

    callModal.value = {
        visible: true,
        callId,
        from: myId,
        to: toUserId,
        incoming: false,
        status: 'calling',
        offer: null,
    }

    await createPeerConnection()

    const offer = await pc.createOffer()
    await pc.setLocalDescription(offer)

    // 1. Создаём звонок на бэкенде
    socket.emit('call.request', { from: myId, to: toUserId, callId })

    // 2. Отправляем SDP offer
    socket.emit('call.offer', {
        callId,
        offer,
        sender: myId,
    })
}

async function acceptCall() {
    if (!callModal.value.offer) {
        console.error("No offer received yet!")
        return
    }

    await initMedia()
    await createPeerConnection()

    await pc.setRemoteDescription(new RTCSessionDescription(callModal.value.offer))

    // применяем кандидаты, которые пришли до setRemoteDescription
    for (const candidate of pendingCandidates) {
        await pc.addIceCandidate(new RTCIceCandidate(candidate))
    }
    pendingCandidates = []

    const answer = await pc.createAnswer()
    await pc.setLocalDescription(answer)

    // 🔥 ИСПРАВЛЕНИЕ: добавили sender (нужно для handleAnswer на бэкенде)
    socket.emit('call.answer', {
        callId: callModal.value.callId,
        answer,
        sender: myId,
    })

    callModal.value.status = 'active'
}

function rejectCall() {
    socket.emit('call.reject', {
        callId: callModal.value.callId,
        from: myId,
        to: callModal.value.from,
    })

    callModal.value.visible = false
    cleanupCall()
}

function endCall() {
    cleanupCall()
    socket.emit('call.end', { callId: callModal.value.callId })
    callModal.value.visible = false
}

function cleanupCall() {
    if (pc) {
        pc.close()
        pc = null
    }
    if (localStream.value) {
        localStream.value.getTracks().forEach(track => track.stop())
        localStream.value = null
    }
    pendingCandidates = []
}

/* =======================
   AUDIO
======================= */

const remoteAudio = ref(null)

watch(remoteStream, (stream) => {
    if (remoteAudio.value && stream) {
        remoteAudio.value.srcObject = stream
    }
})

function getUserById(userId) {
    for (const chat of chatsStore.chats) {
        if (chat.first.id === userId) return chat.first
        if (chat.second.id === userId) return chat.second
    }
    return null
}

const route = useRoute()
const router = useRouter()

const chatsStore = useChatsStore()
const messagesStore = useMessagesStore()
const authStore = useAuthStore()

const selectedChat = ref(null)
const messageInput = ref("")
const myId = authStore.user.id

const isMobile = computed(() => window.innerWidth < 768)
const showChatList = ref(true)

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
    messagesStore.messages = []
    showChatList.value = true
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
    socket.off('message.sent')

    socket.on('message.sent', async (message) => {
        if (message.senderId !== myId) {
            await messagesStore.readMessages(chatId)
            messagesStore.messages.push(message)
            scrollToBottom()
        }
    })

    socket.on("message.read", () => {
        messagesStore.messages.forEach(message => {
            message.isRead = true
        })
    })
}

async function readMessagesEcho(chatId) {
    const res = await messagesStore.readMessages(chatId)
    if (res === true) {
        const index = chatsStore.chats.findIndex(chat => chat.id === chatId)
        if (index !== -1) chatsStore.chats[index].unreadCount = 0
    }
}

async function openChat(chat) {
    if (selectedChat?.value?.id) {
        socket.emit('leaveChat', selectedChat.value.id)
    }

    socket.emit('joinChat', chat.id)

    messagesStore.resetSendMessage()
    messagesStore.reset()

    selectedChat.value = chat
    showChatList.value = false

    page = 1
    hasMore = true

    await loadMessages(chat.id, page, true)
    subscribeToChat(chat.id)

    if (chat.unreadCount > 0) {
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

    if (pageNumber === 1) {
        if (scrollToEnd) scrollToBottom()
    } else {
        await nextTick(() => {
            if (container) container.scrollTop = container.scrollHeight - previousHeight
        })
    }

    isLoadingOld = false
}

function onScroll() {
    if (!containerRef.value) return
    if (containerRef.value.scrollTop <= 75 && hasMore && !isLoadingOld) {
        page++
        loadMessages(selectedChat.value.id, page)
    }
}

async function sendMessage() {
    const content = messageInput.value.trim()
    if (!content) return

    messagesStore.messages.push({
        chatId: selectedChat.value.id,
        content,
        createdAt: null,
        id: null,
        isRead: false,
        senderId: myId
    })

    messageInput.value = ""
    scrollToBottom()

    const chatId = selectedChat.value.id
    let chatIndex = chatsStore.chats.findIndex(c => c.id === chatId)

    if (chatIndex !== -1) {
        const chat = chatsStore.chats[chatIndex]
        chat.lastMessageContent = { content }
        chat.lastMessageAt = new Date()
        chatsStore.sortChats()
    } else {
        try {
            await chatsStore.loadChatItem(chatId)
            if (chatsStore.chat) {
                const newChat = {
                    ...chatsStore.chat,
                    unreadCount: 1,
                    lastMessageContent: { content },
                    lastMessageAt: new Date()
                }
                chatsStore.chats.unshift(newChat)
                chatsStore.sortChats()
            }
        } catch (err) {
            console.warn(`Error chat loading ${chatId} for message`, err)
        }
    }

    await messagesStore.sendMessage(selectedChat.value.id, content)

    if (messagesStore.sendMessageData) {
        const lastMsg = messagesStore.messages[messagesStore.messages.length - 1]
        if (lastMsg) {
            lastMsg.id = messagesStore.sendMessageData.id
            lastMsg.createdAt = messagesStore.sendMessageData.createdAt
        }
    }

    messagesStore.resetSendMessage()
}

const loadMoreTrigger = ref(null)
let observer = null

onMounted(async () => {
    socket.on("connect", () => {
        socket.emit("joinUser", myId)
    })

    // =======================
    // CALL LISTENERS (исправленные)
    // =======================
    socket.on('call.incoming', (data) => {
        callModal.value = {
            visible: true,
            callId: data.callId,
            from: data.from,
            to: myId,
            incoming: true,
            status: 'ringing',
            offer: null,
        }
    })

    socket.on('call.offer', (data) => {
        if (callModal.value.callId === data.callId) {
            callModal.value.offer = data.offer
        }
    })

    // 🔥 ИСПРАВЛЕНИЕ: теперь бэкенд отправляет call.answer
    socket.on('call.answer', async (data) => {
        if (!pc || callModal.value.callId !== data.callId) return

        await pc.setRemoteDescription(new RTCSessionDescription(data.answer))

        for (const candidate of pendingCandidates) {
            await pc.addIceCandidate(new RTCIceCandidate(candidate)).catch(console.error)
        }
        pendingCandidates = []

        callModal.value.status = 'active'
    })

    socket.on('call.ice', async (data) => {
        if (!pc || callModal.value.callId !== data.callId) return

        if (!pc.remoteDescription) {
            pendingCandidates.push(data.candidate)
            return
        }

        try {
            await pc.addIceCandidate(new RTCIceCandidate(data.candidate))
        } catch (e) {
            console.error(e)
        }
    })

    socket.on('call.ended', () => {
        cleanupCall()
        callModal.value.visible = false
    })

    socket.on('call.rejected', () => {
        cleanupCall()
        callModal.value.visible = false
    })

    // =======================
    // Остальные слушатели (чат + онлайн)
    // =======================
    socket.on("online:list", (userIds) => {
        chatsStore.updateUserActivityList(userIds)
    })

    socket.on("online:join", (userIds) => {
        chatsStore.userJoining(userIds)
    })

    socket.on("online:leave", (userId) => {
        chatsStore.userLeaving(userId)
    })

    socket.on("connect_error", (err) => {
        console.log("❌ CONNECT ERROR", err.message)
    })

    socket.on("disconnect", (reason) => {
        console.log("❌ DISCONNECTED", reason)
    })

    await chatsStore.loadChats(true)

    const chatId = route.query.chatId
    if (chatId) {
        await chatsStore.loadChatItem(chatId)
        if (chatsStore.chat) {
            await openChat(chatsStore.chat)
        }
    }

    socket.on('new.message', async (message) => {
        let chatIndex = chatsStore.chats.findIndex(c => c.id === message.chatId)

        if (chatIndex !== -1) {
            const chat = chatsStore.chats[chatIndex]
            if (selectedChat.value?.id !== message.chatId) {
                chat.unreadCount = (chat.unreadCount || 0) + 1
            }
            chat.lastMessageContent = { content: message.content }
            chat.lastMessageAt = message.createdAt
            chatsStore.sortChats()
        } else {
            try {
                await chatsStore.loadChatItem(message.chatId)
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
                console.warn(`Error chat loading ${message.chatId} for message`, err)
            }
        }
    })

    observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && chatsStore.hasMore && !chatsStore.loadingMore) {
                chatsStore.loadMoreChats()
            }
        },
        { rootMargin: '0px 0px 300px 0px', threshold: 0.01 }
    )

    if (loadMoreTrigger.value) observer.observe(loadMoreTrigger.value)

    watch(
        [() => chatsStore.chats.length, () => chatsStore.hasMore, () => chatsStore.loadingMore],
        async () => {
            await nextTick()
            if (loadMoreTrigger.value) {
                if (chatsStore.hasMore) observer.observe(loadMoreTrigger.value)
                else observer.unobserve(loadMoreTrigger.value)
            }
        },
        { immediate: true }
    )
})

onUnmounted(() => {
    if (observer) observer.disconnect()
    if (selectedChat?.value?.id) socket.emit('leaveChat', selectedChat.value.id)

    socket.off('new.message')
    socket.off('message.sent')
    socket.off('message.read')
    socket.off('call.incoming')
    socket.off('call.offer')
    socket.off('call.answer')
    socket.off('call.ice')
    socket.off('call.ended')
    socket.off('call.rejected')

    cleanupCall()
})
</script>
