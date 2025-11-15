import { defineStore } from 'pinia'
import axios from 'axios'
import type { Status } from '@/types/common'

const API_URL = import.meta.env.VITE_API_URL

export const useStatusStore = defineStore('statuses', {
  state: () => ({
    statuses: [] as Status[],
  }),

  actions: {
    async fetchStatuses() {
      const response = await axios.get(`${API_URL}/api/statuses`)
      this.statuses = response.data.map((s: { type: Status }) => s.type)
    },

    allowedStatusesForGarant(currentStatus: Status): Status[] {
      switch (currentStatus) {
        case 'Potvrdená':
          return ['Potvrdená', 'Schválená', 'Neschválená']
        case 'Schválená':
          return ['Schválená', 'Obhájená', 'Neobhájená']
        case 'Neschválená':
          return ['Neschválená', 'Schválená']
        case 'Neobhájená':
          return ['Neobhájená', 'Obhájená']
        case 'Obhájená':
          return [currentStatus]
        default:
          return [currentStatus]
      }
    },
  },
})
