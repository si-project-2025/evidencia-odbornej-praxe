<script setup lang="ts">
  import { computed } from 'vue'
  import type { Status } from '@/types/common'

  const props = withDefaults(
    defineProps<{
      status?: Status
    }>(),
    {
      status: 'Vytvorená',
    },
  )

  const colorClasses = computed(() => {
    switch (props.status) {
      case 'Vytvorená':
        return 'bg-gray-200 text-gray-700'
      case 'Potvrdená':
        return 'bg-blue-100 text-blue-700'
      case 'Schválená':
        return 'bg-amber-100 text-amber-700'
      case 'Obhájená':
        return 'bg-emerald-100 text-emerald-700'
      case 'Zamietnutá':
      case 'Neschválená':
      case 'Neobhájená':
        return 'bg-rose-100 text-rose-700'
      default:
        return 'bg-gray-300 text-gray-700'
    }
  })
</script>

<template>
  <span class="px-4 py-1.5 text-xs font-medium rounded-full shadow-sm" :class="colorClasses">
    {{ status || 'Neznámy stav' }}
  </span>
</template>
