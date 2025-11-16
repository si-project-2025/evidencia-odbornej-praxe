import { defineStore } from 'pinia'
import axios from 'axios'
import type { Company, Garant, Internship, Student, Document } from '@/types/internship'
import type { InternshipForm } from '@/types/form.ts'
import { useUserStore } from '@/stores/user.ts'

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

    async fetchCompanies() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/companies`, {
          headers: this.getAuthHeaders(),
        })

        this.companies = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať firmy:', error)
      }
    },

    async fetchGarants() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/garants`, {
          headers: this.getAuthHeaders(),
        })

        this.garants = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať garantov:', error)
      }
    },

    async fetchStudents() {
      try {
        const response = await axios.get(`${API_URL}/api/internships/students`, {
          headers: this.getAuthHeaders(),
        })

        this.students = response.data
      } catch (error) {
        console.error('Nepodarilo sa načítať študentov:', error)
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

    async generateDocument() {
      try {
        const response = await axios.get(
          `${API_URL}/api/internships/${this.internshipDetail?.internships_id}/contract`,
          {
            headers: this.getAuthHeaders(),
            responseType: 'blob',
          },
        )

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'document.pdf')
        document.body.appendChild(link)

        link.click()
        link.remove()
      } catch (error) {
        console.error('Nepodarilo sa stiahnuť dokument:', error)
        throw error
      }
    },

    async downloadDocument(file: Document) {
      try {
        const response = await axios.get(
          `${import.meta.env.VITE_API_URL}/api/internships/${this.internshipDetail?.internships_id}/documents/${file.document_id}/download`,
          {
            headers: this.getAuthHeaders(),
            responseType: 'blob',
          },
        )

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', file.file_name.split('/').pop() ?? 'document.pdf')
        document.body.appendChild(link)

        link.click()
        link.remove()
      } catch (error) {
        console.error('Nepodarilo sa stiahnuť dokument:', error)
        throw error
      }
    },

    async uploadDocument(internshipId: number, file: File, type?: string) {
      try {
        const formData = new FormData()
        formData.append('file', file)
        if (type) formData.append('type', type)

        const response = await axios.post(`${API_URL}/api/internships/${internshipId}/documents`, formData, {
          headers: {
            ...this.getAuthHeaders(),
            'Content-Type': 'multipart/form-data',
          },
        })

        if (!response.data.created_at) {
          response.data.created_at = new Date().toISOString()
        }

        if (this.internshipDetail?.internships_id === internshipId) {
          this.internshipDetail.documents?.push(response.data)
        }

        return response.data
      } catch (error) {
        console.error('Nepodarilo sa nahrať dokument:', error)
        throw error
      }
    },

    async deleteDocument(internshipId: number, documentId: number) {
      try {
        await axios.delete(`${API_URL}/api/internships/${internshipId}/documents/${documentId}`, {
          headers: this.getAuthHeaders(),
        })

        if (this.internshipDetail?.documents) {
          this.internshipDetail.documents = this.internshipDetail.documents.filter((d) => d.document_id !== documentId)
        }
      } catch (error) {
        console.error('Nepodarilo sa odstrániť dokument:', error)
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
