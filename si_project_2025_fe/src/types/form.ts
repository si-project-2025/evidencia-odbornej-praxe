import type { Address } from './address'
import type { Role, Status } from '@/types/common.ts'

export interface LoginForm {
  email: string
  password: string
  remember: boolean
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
  semester: 'Z' | 'L'
  year: number
  hours_total?: number
  end_at?: string
  status: Status
  garant_id: number
}
