import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company } from '@/types/internship'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env.ts'
import type { CreateCompanyPayload } from '@/types/company'

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

    async createCompany(data: CreateCompanyPayload) {
      try {
        const response = await axios.post(`${API_URL}/api/internships/companies`, data, { headers: authHeaders() })

        await this.fetchCompanies()

        return response.data.company_id
      } catch (e) {
        console.error('Chyba pri vytváraní firmy:', e)
        throw e
      }
    },
  },
})
