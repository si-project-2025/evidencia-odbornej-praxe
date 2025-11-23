import { defineStore } from 'pinia'
import axios from 'axios'
import type { Garant, Student } from '@/types/internship'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env.ts'

export const useLookupStore = defineStore('lookup', {
  state: () => ({
    garants: [] as Garant[],
    students: [] as Student[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchGarants() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_URL}/api/internships/garants`, {
          headers: authHeaders(),
        })
        this.garants = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať garantov:', error)
        this.error = 'Nepodarilo sa načítať garantov'
      } finally {
        this.loading = false
      }
    },

    async fetchStudents() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_URL}/api/internships/students`, {
          headers: authHeaders(),
        })
        this.students = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať študentov:', error)
        this.error = 'Nepodarilo sa načítať študentov'
      } finally {
        this.loading = false
      }
    },
  },
})
