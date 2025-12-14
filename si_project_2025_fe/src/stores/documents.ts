import { defineStore } from 'pinia'
import axios from 'axios'
import type { Document } from '@/types/internship'
import { useInternshipStore } from '@/stores/internships.ts'
import { authHeaders } from '@/stores/helpers/auth.ts'
import { API_URL } from '@/stores/helpers/env'
import { downloadBlob, getFileNameFromPath, postFormData } from '@/stores/helpers/files.ts'

export const useDocumentStore = defineStore('documents', {
  state: () => ({
    loading: false,
    error: null as string | null,
  }),

  getters: {
    internshipDetail: () => useInternshipStore().internshipDetail,
  },

  actions: {
    async generateDocument() {
      try {
        const blob = await this.fetchBlob(
          `${API_URL}/api/internships/${this.internshipDetail?.internships_id}/contract`,
        )
        downloadBlob(blob, 'dohoda.pdf')
      } catch (error) {
        console.error('Nepodarilo sa stiahnuť dokument:', error)
        throw error
      }
    },

    async downloadDocument(file: Document, publicAccess?: { email: string; token: string }) {
      const base = publicAccess ? `${API_URL}/api/public/internships` : `${API_URL}/api/internships`

      const params = publicAccess
        ? `?email=${encodeURIComponent(publicAccess.email)}&token=${encodeURIComponent(publicAccess.token)}`
        : ''

      const blob = await this.fetchBlob(
        `${base}/${this.internshipDetail?.internships_id}/documents/${file.document_id}/download${params}`,
      )

      downloadBlob(blob, getFileNameFromPath(file.file_name))
    },

    async uploadDocument(file: File, type?: string, publicAccess?: { email: string; token: string }) {
      try {
        const formData = new FormData()
        formData.append('file', file)
        if (type) formData.append('type', type)

        const base = publicAccess ? `${API_URL}/api/public/internships` : `${API_URL}/api/internships`

        const url = `${base}/${this.internshipDetail?.internships_id}/documents`

        const config = publicAccess ? { params: publicAccess } : { headers: authHeaders() }

        const response = await postFormData<Document>(url, formData, config)

        const document = {
          ...response.data,
          created_at: response.data.created_at ?? new Date().toISOString(),
        }

        this.internshipDetail?.documents?.push(document)
        return document
      } catch (error) {
        console.error('Nepodarilo sa nahrať dokument:', error)
        throw error
      }
    },

    async verifyDocument(documentId: number, publicAccess?: { email: string; token: string }) {
      const internshipId = this.internshipDetail!.internships_id!

      const base = publicAccess ? `${API_URL}/api/public/internships` : `${API_URL}/api/internships`

      const config = publicAccess ? { params: publicAccess } : { headers: authHeaders() }

      await axios.patch(`${base}/${internshipId}/documents/${documentId}/verify`, null, config)

      if (publicAccess) {
        window.location.reload()
      } else {
        await useInternshipStore().fetchInternshipDetail(internshipId)
      }
    },

    async deleteDocument(documentId: number) {
      try {
        const internshipId = this.internshipDetail?.internships_id

        await axios.delete(`${API_URL}/api/internships/${internshipId}/documents/${documentId}`, {
          headers: authHeaders(),
        })

        this.internshipDetail!.documents = this.internshipDetail!.documents?.filter((d) => d.document_id !== documentId)
      } catch (error) {
        console.error('Nepodarilo sa odstrániť dokument:', error)
        throw error
      }
    },

    async fetchBlob(url: string) {
      const response = await axios.get(url, {
        headers: authHeaders(),
        responseType: 'blob',
      })

      return new Blob([response.data])
    },
  },
})
