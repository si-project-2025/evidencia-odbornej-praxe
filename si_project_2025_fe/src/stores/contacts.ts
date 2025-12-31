import { defineStore } from 'pinia'
import axios from 'axios'
import type { ContactPerson } from '@/types/internship'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env.ts'
import type { ContactForm } from '@/types/form.ts'

export const useContactstore = defineStore('contacts', {
  state: () => ({
    contacts: [] as ContactPerson[],
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchContacts(companyId?: number) {
      try {
        const response = await axios.get(`${API_URL}/api/contact-persons`, {
          headers: authHeaders(),
          params: companyId != null ? { company_id: companyId } : undefined,
        })

        this.contacts = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať kontaktné osoby:', error)
      }
    },

    async createContact(data: ContactForm) {
      try {
        const response = await axios.post(`${API_URL}/api/contact-persons`, data, {
          headers: authHeaders(),
        })

        await this.fetchContacts(data.company_id ?? undefined)
        return response.data.id
      } catch (e) {
        if (axios.isAxiosError(e) && e.response) {
          if (e.response.data?.error) {
            throw new Error(e.response.data.error)
          }
          if (e.response.status === 422 && e.response.data.errors) {
            const errors = e.response.data.errors
            if (errors.email?.[0]) throw new Error(errors.email[0])
          }
        }
        throw new Error('Nepodarilo sa vytvoriť kontakt.')
      }
    },

    async deleteContact(contactPersonId: number, companyId?: number) {
      try {
        await axios.delete(`${API_URL}/api/contact-persons/${contactPersonId}`, {
          headers: authHeaders(),
        })
        await this.fetchContacts(companyId)
      } catch (e) {
        if (axios.isAxiosError(e) && e.response?.data?.error) {
          throw new Error(e.response.data.error)
        }
        throw new Error('Nepodarilo sa vymazať kontakt.')
      }
    },
  },
})
