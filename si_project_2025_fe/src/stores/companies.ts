import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company } from '@/types/internship'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env.ts'

export const useCompaniesStore = defineStore('companies', {
  state: () => ({
    companies: [] as Company[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchCompanies() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/companies`, {
          headers: authHeaders(),
        })

        this.companies = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy:', error)
      }
    },
  },
})
