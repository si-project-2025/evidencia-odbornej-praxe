import { defineStore } from 'pinia'
import axios from 'axios'
import type { Internship } from '@/types/internship'

export const useInternshipStore = defineStore('internships', {
  state: () => ({
    internships: [] as Internship[],
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
      } finally {
        this.loading = false
      }
    },
  },
})
