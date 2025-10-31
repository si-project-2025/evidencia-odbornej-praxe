import { defineStore } from 'pinia'
import axios from 'axios'
import type { Internship, InternshipCreateInput } from '@/types/internship'

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
    internshipDetail: null as Internship | null,
    companies: [] as { company_id: number; name: string }[],
    garants: [] as { users_id: number; name: string; surname: string }[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchStudentInternships() {
      this.loading = true
      this.error = null

      try {
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/student/internships', {
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
        const response = await axios.get(`http://127.0.0.1:8000/api/internships/${id}`, {
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
    async fetchCompaniesAndGarants() {
      try {
        const [companiesRes, garantsRes] = await Promise.all([
          axios.get('http://localhost:8000/api/internships/companies'),
          axios.get('http://localhost:8000/api/internships/garants'),
        ])
        this.companies = companiesRes.data
        this.garants = garantsRes.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy alebo garantov:', error)
      }
    },
    async createInternship(data: InternshipCreateInput) {
      try {
        const token = localStorage.getItem('token')
        const response = await axios.post('http://localhost:8000/api/internships', data, {
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
        await axios.delete(`http://localhost:8000/api/internships/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        this.internships = this.internships.filter((i) => i.internships_id !== id)
      } catch (error) {
        console.error('Nepodarilo sa zmazať prax:', error)
        throw error
      }
    },
  },
})
