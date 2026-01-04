import type { Address } from './address'
import type { Role } from '@/types/common.ts'

export interface LoginForm {
  email: string
  password: string
}

export interface RegistrationForm {
  name: string
  surname: string
  email: string
  alt_email: string | null
  phone_number: string | null
  study_program: string | null
  address?: Address
  role: Role
}

export interface InternshipForm {
  users_id: number
  company_id: number
  contact_person_id: number
  semester: 'Z' | 'L'
  year: number
  start_at?: string
  end_at?: string
  garant_id: number
  is_paid: boolean
}

export interface CompanyForm {
  name: string
  ico: string
  address: Address
}

export interface ContactForm {
  name: string
  surname: string
  email: string
  phone?: string
  company_id: number
}
