import type { Address } from '@/types/address.ts'
import type { Status } from '@/types/common.ts'

export interface ContactPerson {
  id: number
  name: string
  surname: string
  email: string
  phone: string | null
}

export interface Company {
  company_id: number
  name: string
  ico: string
  address: Address
}

export interface Garant {
  users_id: number
  name: string
  surname: string
  email: string
}

export interface Document {
  document_id: number
  type: string
  file_name: string
  is_verified: boolean
  internships_id: number
  created_at: string
}

export interface Internship {
  users_id: number
  internships_id: number
  semester: 'Z' | 'L'
  hours_total: number
  year: number
  created_at: string | null
  updated_at: string | null
  end_at: string | null
  company: Company
  status: Status
  garant: Garant
  documents?: Document[]
  contact_persons?: ContactPerson[]
}
