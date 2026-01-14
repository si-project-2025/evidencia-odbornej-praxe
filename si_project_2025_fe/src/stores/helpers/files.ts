import axios, { type AxiosRequestConfig, type AxiosResponse } from 'axios'

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

export async function postFormData<T = unknown>(
  url: string,
  formData: FormData,
  config?: AxiosRequestConfig,
): Promise<AxiosResponse<T>> {
  return axios.post<T>(url, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
      ...(config?.headers ?? {}),
    },
    ...config,
  })
}
