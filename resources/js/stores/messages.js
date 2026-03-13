import { defineStore } from "pinia"
import axios from "axios"

export const useMessagesStore = defineStore("messages", {
    state: () => ({
        messages: [],
        page: 1,
        perPage: 15,
        total: 0,
        loading: false,
        error: null,
        sendMessageData: null,
        loadingSendMessage: false,
        errorSendMessage: null
    }),
    actions: {
        async loadMessages(chatId, pageNumber) {
            this.loading = true
            this.error = null

            try {
                const res = await axios.get(`/api/messages?chatId=${chatId}`, {
                    params: {
                        pagination: [pageNumber, this.perPage],
                    }
                })

                const loadedMessages = this.messages

                this.messages = [...res.data, ...loadedMessages]

                const contentRange = res.headers["content-range"] || ""
                const totalMatch = contentRange.match(/\/(\d+)$/)
                this.total = totalMatch ? parseInt(totalMatch[1]) : this.messages.length

            } catch (err) {
                this.error = err.message
            } finally {
                this.loading = false
            }
        },

        async sendMessage(chatId, content) {
            this.loadingSendMessage = true
            this.errorSendMessage = null

            try {
                const res = await axios.post(`/api/messages`, {
                    chatId,
                    content,
                })

                this.sendMessageData = res.data
            } catch (err) {
                this.errorSendMessage = err.message
            } finally {
                this.loadingSendMessage = false
            }
        },
        async readMessages(chatId) {
            try {
                await axios.post(`/api/messages/read`, {
                    chatId
                })
                return true
            } catch (e) {
                return false
            }
        },
        resetSendMessage() {
            this.sendMessageData = null
        },
        reset() {
            this.messages = []
            this.page = 1
            this.total = 0
        },
    }
})
