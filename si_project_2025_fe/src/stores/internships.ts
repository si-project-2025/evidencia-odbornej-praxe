import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company, Garant, Internship, Student } from '@/types/internship'
import type { InternshipForm } from '@/types/form.ts'

const API_URL = import.meta.env.VITE_API_URL

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
    internshipDetail: null as Internship | null,
    companies: [] as Company[],
    garants: [] as Garant[],
    students: [] as Student[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchInternships() {
      this.loading = true
      this.error = null
      try {
        const token = localStorage.getItem('token')
        const response = await axios.get(`${API_URL}/api/user/internships`, {
          headers: { Authorization: `Bearer ${token}` },
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
        const token = localStorage.getItem('token')
        const response = await axios.get(`${API_URL}/api/internships/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        this.internshipDetail = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať detail praxe:', error)
        this.error = 'Nepodarilo sa načítať detail praxe.'
      } finally {
        this.loading = false
      }
    },

    async fetchCompanies() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/companies`)
        this.companies = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy:', error)
      }
    },

    async fetchGarants() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/garants`)
        this.garants = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať garantov:', error)
      }
    },

    async fetchStudents() {
      try {
        const token = localStorage.getItem('token')
        const response = await axios.get(`${API_URL}/api/internships/students`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        this.students = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať študentov:', error)
      }
    },

    async createInternship(data: InternshipForm) {
      try {
        const token = localStorage.getItem('token')

        const response = await axios.post(`${API_URL}/api/internships`, data, {
          headers: { Authorization: `Bearer ${token}` },
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
        const token = localStorage.getItem('token')

        await axios.delete(`${API_URL}/api/internships/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        })

        this.internships = this.internships.filter((i) => i.internships_id !== id)
      } catch (error) {
        console.error('Nepodarilo sa zmazať prax:', error)
        throw error
      }
    },

    async sendVerificationEmail(id: number) {
      try {
        const token = localStorage.getItem('token')

        await axios.post(
          `${API_URL}/api/internships/${id}/send-verification`,
          {},
          {
            headers: { Authorization: `Bearer ${token}` },
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
        const response = await axios.get(`${API_URL}/api/internships/get-verification-details`, {
          params: { email, token },
        })
        if (response.data.is_expired) {
          this.error = 'Odkaz na potvrdenie praxe expiroval'
          return
        }
        this.internshipDetail = response.data.internship
      } catch (error) {
        console.error('Nepodarilo sa načítať verifikačné detaily:', error)
        this.error = 'Nepodarilo sa načítať verifikačné detaily.'
      } finally {
        this.loading = false
      }
    },

    async confirmInternship(email: string, token: string) {
      try {
        this.loading = true
        const response = await axios.post(`${API_URL}/api/internships/verify`, {
          email,
          token,
        })
        this.internshipDetail = response.data.internship
        return response.data.message
      } catch (error) {
        console.error('Nepodarilo sa potvrdiť prax:', error)
        throw error
      } finally {
        this.loading = false
      }
    },
  },
})
