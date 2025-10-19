import { defineStore } from 'pinia'
import type { User } from '@/types/user.ts'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | User,
    token: null as null | string,
  }),

  actions: {
    setUser(data: { user: User; access_token: string }) {
      this.user = data.user
      this.token = data.access_token
      localStorage.setItem('token', data.access_token)
      localStorage.setItem('user', JSON.stringify(data.user))
    },

    loadUser() {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      if (token && user) {
        this.token = token
        this.user = JSON.parse(user)
      }
    },

    logout() {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      this.user = null
      this.token = null
    },
  },
})
