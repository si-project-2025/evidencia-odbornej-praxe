import { defineStore } from 'pinia'
import axios from 'axios'
import type { Internship } from '@/types/internship'

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
    internshipDetail: null as Internship | null,
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
  },
})
