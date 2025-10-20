import { defineStore } from 'pinia'
import axios from 'axios'
import { useRouter } from 'vue-router'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | { id: number; name: string; email: string },
    token: null as null | string
  }),
  actions: {
    setUser(data: any) {
      this.user = data.user
      this.token = data.access_token
      localStorage.setItem('token', data.access_token)
      localStorage.setItem('user', JSON.stringify(data.user))
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.access_token}`
    },
    loadUser() {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      if (token && user) {
        this.token = token
        this.user = JSON.parse(user)
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      }
    },
    async logout() {
      const router = useRouter()

      try {
        await axios.post('http://localhost:8000/api/logout', {}, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
      } catch (error) {
        console.error('Logout API error:', error)
      } finally {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        this.user = null
        this.token = null
        delete axios.defaults.headers.common['Authorization']

        router.push('/login')
      }
    }
  }
})
