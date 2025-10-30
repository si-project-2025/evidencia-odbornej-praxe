import type { Role } from '@/types/common.ts'
import type { Address } from '@/types/address.ts'

export interface User {
  id: string
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
  address?: Address
  remember_token?: string
}
