import { defineStore } from 'pinia'
import type { User } from '@/types/user.ts'
import axios from 'axios'
import router from '@/router'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | User,
    token: localStorage.getItem('token') as string | null,
  }),

  actions: {
    setUser(data: { user: User; access_token: string }) {
      this.user = data.user
      this.token = data.access_token

      localStorage.setItem('token', data.access_token)
      localStorage.setItem('user', JSON.stringify(data.user))

      axios.defaults.headers.common['Authorization'] = `Bearer ${data.access_token}`
    },

    loadUser() {
      const user = localStorage.getItem('user')

      if (!this.token || !user) return
      this.user = JSON.parse(user)

      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    },

    async logout() {
      try {
        await axios.post(
          'http://localhost:8000/api/logout',
          {},
          {
            headers: { Authorization: `Bearer ${this.token}` },
          },
        )
      } catch (error) {
        console.error('Logout API error:', error)
      } finally {
        this.clearSession()
        await router.push('/login')
      }
    },

    clearSession() {
      localStorage.removeItem('token')
      localStorage.removeItem('user')

      this.user = null
      this.token = null

      delete axios.defaults.headers.common.Authorization
    },
  },
})
