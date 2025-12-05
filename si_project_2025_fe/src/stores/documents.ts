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

    async downloadDocument(file: Document) {
      try {
        const blob = await this.fetchBlob(
          `${API_URL}/api/internships/${this.internshipDetail?.internships_id}/documents/${file.document_id}/download`,
        )
        downloadBlob(blob, getFileNameFromPath(file.file_name))
      } catch (error) {
        console.error('Nepodarilo sa stiahnuť dokument:', error)
        throw error
      }
    },

    async uploadDocument(file: File, type?: string) {
      try {
        const formData = new FormData()
        formData.append('file', file)
        if (type) formData.append('type', type)

        const url = `${API_URL}/api/internships/${this.internshipDetail?.internships_id}/documents`
        const response = await postFormData(url, formData)

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

    async verifyDocument(documentId: number) {
      try {
        const internshipId = this.internshipDetail!.internships_id!

        await axios.delete(`${API_URL}/api/internships/${internshipId}/documents/${documentId}/verify`, {
          headers: authHeaders(),
        })

        await useInternshipStore().fetchInternshipDetail(internshipId)
      } catch (error) {
        console.error('Nepodarilo sa odstrániť dokument:', error)
        throw error
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
