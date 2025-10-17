import type { Role } from '@/types/common.ts'
import type { Address } from '@/types/address.ts'

export interface User {
  id: string
  name: string
  email: string
  role: Role
}
