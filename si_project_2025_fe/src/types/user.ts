import type { Role } from '@/types/common.ts'
import type { Address } from '@/types/address.ts'

export interface Company {
  company_id: number
  name: string
  ico: string
  address: {
    country: string
    city: string
    zip_code: string
    street: string
    house_number: string
  } | null
}
export interface User {
  users_id: number
  email: string
  password: string
  name: string
  surname: string
  study_program?: string
  alt_email?: string
  phone_number?: string
  created_at: Date
  last_login?: Date
  role: Role
  company?: []
  address?: Address
  remember_token?: string
  company?: Company | null
}
