import { defineStore } from 'pinia'
import axios from 'axios'
import { API_URL } from '@/stores/helpers/env'
import { authHeaders } from '@/stores/helpers/auth.ts'
import type { Internship } from '@/types/internship'
import type { InternshipForm } from '@/types/form.ts'
import { useUserStore } from '@/stores/user.ts'

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
    internshipDetail: null as Internship | null,
    loading: false,
    error: null as string | null,
    userStore: useUserStore(),
  }),

  actions: {
    async fetchInternships() {
      this.loading = true
      this.error = null

      try {
        const response = await axios.get(`${API_URL}/api/user/internships`, {
          headers: authHeaders(),
        })

        this.internships = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať praxe:', error)
        this.error = 'Nepodarilo sa načítať praxe'
      } finally {
        this.loading = false
      }
    },

    async fetchInternshipDetail(id: number) {
      this.loading = true
      this.error = null
      this.internshipDetail = null

      try {
        const response = await axios.get(`${API_URL}/api/internships/${id}`, {
          headers: authHeaders(),
        })

        this.internshipDetail = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať detail praxe:', error)
        this.error = 'Nepodarilo sa načítať detail praxe.'
      } finally {
        this.loading = false
      }
    },

    async createInternship(data: InternshipForm) {
      try {
        const response = await axios.post(`${API_URL}/api/internships`, data, {
          headers: authHeaders(),
        })

        this.internships.push(response.data)
        return response.data
      } catch (error) {
        if (axios.isAxiosError(error) && error.response) {
          console.error('Chyba pri vytváraní praxe:', error.response.data)
        } else {
          console.error('Neznáma chyba pri vytváraní praxe:', error)
        }
        throw error
      }
    },

    async deleteInternship(id: number) {
      try {
        await axios.delete(`${API_URL}/api/internships/${id}`, {
          headers: authHeaders(),
        })

        this.internships = this.internships.filter((i) => i.internships_id !== id)
      } catch (error) {
        console.error('Nepodarilo sa zmazať prax:', error)
        throw error
      }
    },

    async updateInternship(id: number, data: InternshipForm) {
      try {
        const response = await axios.put(`${API_URL}/api/internships/${id}`, data, {
          headers: authHeaders(),
        })

        this.internshipDetail = response.data
        this.internships = this.internships.map((internship) =>
          internship.internships_id === id ? response.data : internship,
        )

        return response.data
      } catch (error: unknown) {
        if (axios.isAxiosError(error)) {
          const message =
            error.response?.data?.message ||
            (error.response?.data?.errors ? Object.values(error.response.data.errors).flat()[0] : null) ||
            'Nepodarilo sa upraviť prax.'

          throw new Error(message)
        }

        if (error instanceof Error) {
          throw error
        }

        throw new Error('Nepodarilo sa upraviť prax.')
      }
    },

    async sendVerificationEmail(id: number) {
      try {
        await axios.post(
          `${API_URL}/api/internships/${id}/send-verification`,
          {},
          {
            headers: authHeaders(),
          },
        )
      } catch (error) {
        console.error('Nepodarilo sa odoslať overovací email:', error)
        throw error
      }
    },

    async fetchVerificationDetails(email: string, token: string) {
      this.loading = true
      this.error = null
      this.internshipDetail = null

      try {
        const response = await axios.get(`${API_URL}/api/public/internships/get-verification-details`, {
          params: { email, token },
        })

        if (response.data.is_expired) {
          this.error = 'Odkaz na potvrdenie praxe expiroval'
          return
        }

        this.internshipDetail = response.data.internship
      } catch (error) {
        console.error('Nepodarilo sa načítať verifikačné detaily', error)
        this.error = 'Platnosť relácie vypršala.'
      } finally {
        this.loading = false
      }
    },

    async handleInternshipAction(email: string, token: string, action: 'confirm' | 'reject') {
      try {
        this.loading = true
        const response = await axios.post(`${API_URL}/api/public/internships/action`, { email, token, action })
        if (this.internshipDetail) {
          this.internshipDetail.status = response.data.internship.status
        }
        return response.data.message
      } catch (error) {
        console.error(`Nepodarilo sa ${action === 'confirm' ? 'potvrdiť' : 'zamietnuť'} prax:`, error)
        throw error
      } finally {
        this.loading = false
      }
    },
  },
})
