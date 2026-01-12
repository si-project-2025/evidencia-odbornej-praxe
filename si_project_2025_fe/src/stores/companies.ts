import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company } from '@/types/internship'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env.ts'
import type { CompanyForm } from '@/types/form.ts'
export const useCompaniesStore = defineStore('companies', {
  state: () => ({
    companies: [] as Company[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchCompanies(registration = false) {
      try {
        const response = await axios.get(`${API_URL}/api/internships/companies`, {
          headers: authHeaders(),
          params: { registration },
        })

        this.companies = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy:', error)
      }
    },

    async createCompany(data: CompanyForm) {
      try {
        const response = await axios.post(`${API_URL}/api/companies`, data, {
          headers: authHeaders(),
        })

        await this.fetchCompanies()
        return response.data.company_id
      } catch (e) {
        if (axios.isAxiosError(e) && e.response) {
          if (e.response.data?.error) {
            throw new Error(e.response.data.error)
          }
          if (e.response.status === 422 && e.response.data.errors) {
            const errors = e.response.data.errors
            if (errors.name?.[0]) throw new Error(errors.name[0])
            if (errors.ico?.[0]) throw new Error(errors.ico[0])
          }
        }
        throw new Error('Nepodarilo sa vytvoriť firmu.')
      }
    },
  },
})
