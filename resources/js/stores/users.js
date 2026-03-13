import { defineStore } from "pinia"
import axios from "axios"

export const useUsersStore = defineStore("users", {
    state: () => ({
        users: [],
        page: 1,
        perPage: 10,
        total: 0,
        loading: false,
        error: null
    }),
    actions: {
        async loadUsers(search = "") {
            this.loading = true
            this.error = null

            try {
                const res = await axios.get("/api/users", {
                    params: {
                        pagination: [this.page, this.perPage],
                        filter: { search }
                    }
                })

                this.users = res.data

                const contentRange = res.headers["content-range"] || ""
                const totalMatch = contentRange.match(/\/(\d+)$/)
                this.total = totalMatch ? parseInt(totalMatch[1]) : this.users.length

            } catch (err) {
                this.error = err.message
            } finally {
                this.loading = false
            }
        },

        reset() {
            this.users = []
            this.page = 1
            this.total = 0
        },
    }
})
