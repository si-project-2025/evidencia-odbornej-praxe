import axios, { type AxiosResponse } from 'axios'
import { authHeaders } from '@/stores/helpers/auth.ts'

export function downloadBlob(blob: Blob, filename: string) {
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')

  link.href = url
  link.setAttribute('download', filename)
  document.body.appendChild(link)

  link.click()
  link.remove()
  window.URL.revokeObjectURL(url)
}

export function getFileNameFromPath(path: string): string {
  return path.split('/').pop() ?? 'document.pdf'
}

export async function postFormData<T = any>(url: string, formData: FormData): Promise<AxiosResponse<T>> {
  return axios.post<T>(url, formData, {
    headers: {
      ...authHeaders(),
      'Content-Type': 'multipart/form-data',
    },
  })
}
