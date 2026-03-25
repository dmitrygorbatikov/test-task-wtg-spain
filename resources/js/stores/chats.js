import { defineStore } from "pinia"
import axios from "axios"

export const useChatsStore = defineStore("chats", {
    state: () => ({
        onlineUsers: new Map(),
        chats: [],
        page: 1,
        perPage: 15,
        total: 0,
        hasMore: true,
        loading: false,
        loadingMore: false,
        error: null,
        chat: null,
        loadingChatItem: false,
        errorChatItem: null
    }),
    actions: {
        updateUserActivityList(activeUsers) {
            const newOnline = new Map()

            activeUsers.forEach(id => {
                newOnline.set(id, true)
            })

            this.onlineUsers = newOnline
        },
        userJoining(userId) {
            const newOnline = new Map()

            this.onlineUsers.forEach(user => {
                newOnline.set(user.id, true)
            })
            newOnline.set(userId, userId);
            this.onlineUsers = newOnline
        },
        userLeaving(userId) {
            const newOnline = new Map()

            this.onlineUsers.forEach(id => {
                newOnline.set(id, true)
            })
            newOnline.delete(userId);
            this.onlineUsers = newOnline
        },
        isUserOnline(chat, authUserId) {
            const interlocutorId = chat.first.id === authUserId ? chat.second.id : chat.first.id
            return this.onlineUsers.has(interlocutorId)
        },
        async loadChats(reset = false) {
            if (reset) {
                this.page = 1
                this.hasMore = true
                this.chats = []
            }

            this.loading = true
            this.error = null

            try {
                const res = await axios.get("/api/chats", {
                    params: {
                        pagination: [this.page, this.perPage],
                    }
                })

                const newChats = res.data || []

                const existingIds = new Set(this.chats.map(c => c.id))
                const uniqueNew = newChats.filter(chat => !existingIds.has(chat.id))

                this.chats.push(...uniqueNew)

                const contentRange = res.headers["content-range"] || ""
                const totalMatch = contentRange.match(/\/(\d+)$/)
                this.total = totalMatch ? parseInt(totalMatch[1]) : this.chats.length

                this.hasMore = this.chats.length < this.total

                this.page++
            } catch (err) {
                this.error = err.message
            } finally {
                this.loading = false
                this.loadingMore = false
            }
        },

        async loadMoreChats() {
            this.loadingMore = true
            await this.loadChats()
        },

        sortChats() {
            this.chats.sort((a, b) => {

                const dateA = new Date(a.lastMessageAt ?? 0)
                const dateB = new Date(b.lastMessageAt ?? 0)

                return dateB - dateA
            })
        },

        async loadChatItem(chatId) {
            this.loading = true
            this.error = null

            try {
                const res = await axios.get(`/api/chats/${chatId}`)

                this.chat = res.data

            } catch (err) {
                this.errorChatItem = err.message
            } finally {
                this.loadingChatItem = false
            }
        },

        reset() {
            this.chats = []
            this.page = 1
            this.total = 0
        },
    }
})
