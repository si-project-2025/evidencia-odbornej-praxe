import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company, Garant, Internship } from '@/types/internship'
import type { InternshipForm } from '@/types/form.ts'
import { useUserStore } from '@/stores/user.ts'

const API_URL = import.meta.env.VITE_API_URL

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
    internshipDetail: null as Internship | null,
    companies: [] as Company[],
    garants: [] as Garant[],
    loading: false,
    error: null as string | null,
    userStore: useUserStore(),
  }),

  actions: {
    getAuthHeaders() {
      return { Authorization: `Bearer ${this.userStore.token}` }
    },

    async fetchInternships() {
      this.loading = true
      this.error = null

      try {
        const response = await axios.get(`${API_URL}/api/user/internships`, {
          headers: this.getAuthHeaders(),
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
          headers: this.getAuthHeaders(),
        })

        this.internshipDetail = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať detail praxe:', error)
        this.error = 'Nepodarilo sa načítať detail praxe.'
      } finally {
        this.loading = false
      }
    },

    async fetchCompaniesAndGarants() {
      try {
        const [companiesRes, garantsRes] = await Promise.all([
          axios.get(`${API_URL}/api/internships/companies`),
          axios.get(`${API_URL}/api/internships/garants`),
        ])

        this.companies = companiesRes.data
        this.garants = garantsRes.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy alebo garantov:', error)
      }
    },

    async createInternship(data: InternshipForm) {
      try {
        const response = await axios.post(`${API_URL}/api/internships`, data, {
          headers: this.getAuthHeaders(),
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
          headers: this.getAuthHeaders(),
        })

        this.internships = this.internships.filter((i) => i.internships_id !== id)
      } catch (error) {
        console.error('Nepodarilo sa zmazať prax:', error)
        throw error
      }
    },

    async sendVerificationEmail(id: number) {
      try {
        await axios.post(
          `${API_URL}/api/internships/${id}/send-verification`,
          {},
          {
            headers: this.getAuthHeaders(),
          },
        )
      } catch (error) {
        console.error('Nepodarilo sa odoslať overovací email:', error)
        throw error
      }
    },
  },
})
