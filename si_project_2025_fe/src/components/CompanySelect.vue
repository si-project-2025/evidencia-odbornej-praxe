<script setup lang="ts">
  import { ref, computed } from 'vue'
  import type { Company } from '@/types/internship'

  const props = defineProps<{
    modelValue: number | null
    companies: Company[]
    label?: string
  }>()

  const emit = defineEmits(['update:modelValue', 'add-company'])

  const open = ref(false)
  const search = ref('')

  const filtered = computed(() => {
    return props.companies.filter((c) => c.name.toLowerCase().includes(search.value.toLowerCase()))
  })

  const selectCompany = (id: number) => {
    emit('update:modelValue', id)
    open.value = false
  }
</script>

<template>
  <div class="relative">
    <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>

    <!-- Trigger -->
    <div
      class="border rounded-xl px-3 py-2 bg-white flex justify-between items-center cursor-pointer"
      @click="open = !open"
    >
      <span>
        {{ companies.find((c) => c.company_id === modelValue)?.name || 'Vyberte firmu' }}
      </span>
      <span class="text-gray-500">▾</span>
    </div>

    <!-- DROPDOWN -->
    <div
      v-if="open"
      class="absolute left-0 right-0 bg-white border rounded-xl mt-1 shadow-xl max-h-72 overflow-auto z-50"
    >
      <!-- Search -->
      <input v-model="search" type="text" class="border-b p-2 w-full" placeholder="Hľadať firmu..." />

      <!-- Add new company -->
      <div class="px-4 py-2 text-blue-600 font-medium cursor-pointer hover:bg-blue-50" @click="emit('add-company')">
        + Pridať firmu
      </div>

      <!-- Companies -->
      <div
        v-for="company in filtered"
        :key="company.company_id"
        class="px-4 py-2 cursor-pointer hover:bg-gray-100"
        @click="selectCompany(company.company_id)"
      >
        {{ company.name }}
      </div>

      <div v-if="filtered.length === 0" class="px-4 py-2 text-sm text-gray-500">Žiadne výsledky</div>
    </div>
  </div>
</template>
