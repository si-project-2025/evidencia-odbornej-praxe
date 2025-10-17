import type { Address } from './address'
import type { Role } from './common'

export interface LoginForm {
  email: string
  password: string
  remember: boolean
}

export interface RegistrationForm {
  name: string
  surname: string
  email: string
  alt_email?: string
  phone?: string
  programme?: string
  address?: Address
  role: Role
}
