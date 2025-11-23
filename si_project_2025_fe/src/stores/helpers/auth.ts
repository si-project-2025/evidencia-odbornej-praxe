import { useUserStore } from '@/stores/user'

export function authHeaders() {
  const user = useUserStore()
  return { Authorization: `Bearer ${user.token}` }
}
