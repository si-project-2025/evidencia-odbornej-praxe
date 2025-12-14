<script setup lang="ts">
  import { ref, computed } from 'vue'
  import type { Company, ContactPerson } from '@/types/internship.ts'

  const props = defineProps<{
    modelValue: number | null
    options: Company[] | ContactPerson[]
    label?: string
  }>()

  const emit = defineEmits(['update:modelValue', 'add-option'])

  const open = ref(false)
  const search = ref('')

  const filtered = computed(() => {
    return props.options.filter((c) => c.name.toLowerCase().includes(search.value.toLowerCase()))
  })

  const selectOption = (id: number) => {
    emit('update:modelValue', id)
    open.value = false
  }
</script>

<template>
  <div class="relative">
    <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>

    <div
      class="border rounded-lg px-3 py-2 bg-white flex justify-between items-center cursor-pointer"
      @click="open = !open"
    >
      <span>
        {{ options.find((c) => c.id === modelValue)?.name || 'Vyberte zo zoznamu' }}
      </span>
      <span class="text-gray-500">▾</span>
    </div>

    <div
      v-if="open"
      class="absolute left-0 right-0 bg-white border rounded-xl mt-1 shadow-xl max-h-72 overflow-auto z-50"
    >
      <input v-model="search" type="text" class="border-b p-2 w-full" placeholder="Hľadať..." />

      <div class="px-4 py-2 text-blue-600 font-medium cursor-pointer hover:bg-blue-50" @click="emit('add-option')">
        + Pridať
      </div>

      <div
        v-for="option in filtered"
        :key="option.id"
        class="px-4 py-2 cursor-pointer hover:bg-gray-100"
        @click="selectOption(option.id)"
      >
        {{ option.name }}
      </div>

      <div v-if="filtered.length === 0" class="px-4 py-2 text-sm text-gray-500">Žiadne výsledky</div>
    </div>
  </div>
</template>
