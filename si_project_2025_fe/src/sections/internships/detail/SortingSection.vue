<script setup lang="ts">
  import { ref, computed, watch } from 'vue'
  import Select from '@/components/form/Select.vue'
  import { ArrowUp, ArrowDown } from 'lucide-vue-next'
  import type { Internship } from '@/types/internship'

  const props = defineProps<{
    internships: Internship[]
    role: string
  }>()

  const emit = defineEmits<{
    (e: 'update', sorted: Internship[]): void
  }>()

  type SortableField = 'company' | 'student' | 'semester' | 'year' | 'start_at' | 'end_at' | 'status'

  const sortField = ref<SortableField | null>(null)
  const sortDirection = ref<'asc' | 'desc' | null>(null)

  const sortExtractors: Record<SortableField, (i: Internship) => string | number | null> = {
    company: (i) => i.company?.name ?? '',
    student: (i) => `${i.student?.name ?? ''} ${i.student?.surname ?? ''}`.trim(),
    semester: (i) => i.semester,
    year: (i) => i.year,
    start_at: (i) => i.start_at,
    end_at: (i) => i.end_at,
    status: (i) => i.status,
  }

  const toggleSort = (field: SortableField) => {
    if (sortField.value === field) {
      if (sortDirection.value === 'desc') {
        sortDirection.value = 'asc'
      } else if (sortDirection.value === 'asc') {
        sortField.value = null
        sortDirection.value = null
      }
      return
    }

    sortField.value = field
    sortDirection.value = 'desc'
  }

  const toggleDirection = () => {
    if (!sortField.value) return
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  }

  const sortedList = computed(() => {
    if (!sortField.value || !sortDirection.value) return props.internships

    const extractor = sortExtractors[sortField.value]

    return [...props.internships].sort((a, b) => {
      const fa = extractor(a)
      const fb = extractor(b)

      if (fa == null) return 1
      if (fb == null) return -1
      if (typeof fa === 'string')
        return sortDirection.value === 'asc' ? fa.localeCompare(fb as string) : (fb as string).localeCompare(fa)
      if (typeof fa === 'number') return sortDirection.value === 'asc' ? fa - (fb as number) : (fb as number) - fa

      return 0
    })
  })

  watch(sortedList, (val) => emit('update', val), { immediate: true })
</script>

<template>
  <div
    :class="[
      'hidden md:grid gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200',
      role === 'garant' ? 'grid-cols-16' : 'grid-cols-13',
    ]"
  >
    <div
      class="col-span-3 cursor-pointer flex items-center justify-start"
      @click="toggleSort('company')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'company',
        'text-gray-500': sortField !== 'company',
      }"
    >
      Firma
      <span v-if="sortField === 'company'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      v-if="role === 'garant'"
      class="col-span-3 cursor-pointer flex items-center justify-start"
      @click="toggleSort('student')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'student',
        'text-gray-500': sortField !== 'student',
      }"
    >
      Študent
      <span v-if="sortField === 'student'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      class="col-span-2 cursor-pointer flex items-center justify-start"
      @click="toggleSort('semester')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'semester',
        'text-gray-500': sortField !== 'semester',
      }"
    >
      Semester
      <span v-if="sortField === 'semester'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      class="col-span-2 cursor-pointer flex items-center justify-start"
      @click="toggleSort('year')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'year',
        'text-gray-500': sortField !== 'year',
      }"
    >
      Rok
      <span v-if="sortField === 'year'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      class="col-span-2 cursor-pointer flex items-center justify-start"
      @click="toggleSort('start_at')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'start_at',
        'text-gray-500': sortField !== 'start_at',
      }"
    >
      Začiatok praxe
      <span v-if="sortField === 'start_at'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      class="col-span-2 cursor-pointer flex items-center justify-start"
      @click="toggleSort('end_at')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'end_at',
        'text-gray-500': sortField !== 'end_at',
      }"
    >
      Koniec praxe
      <span v-if="sortField === 'end_at'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
    <div
      class="col-span-2 cursor-pointer flex items-center justify-end mr-5"
      @click="toggleSort('status')"
      :class="{
        'text-emerald-600 font-bold': sortField === 'status',
        'text-gray-500': sortField !== 'status',
      }"
    >
      Stav
      <span v-if="sortField === 'status'">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
  </div>

  <div class="md:hidden px-4 py-3 border-b border-gray-200 flex items-center justify-start">
    <Select
      class="flex-1"
      :model-value="sortField ?? ''"
      @change="toggleSort(($event.target as HTMLSelectElement).value as SortableField)"
    >
      <option value="">Zoradiť podľa...</option>
      <option value="company">Firma</option>
      <option v-if="role === 'garant'" value="student">Študent</option>
      <option value="semester">Semester</option>
      <option value="year">Rok</option>
      <option value="end_at">Koniec praxe</option>
      <option value="status">Stav</option>
    </Select>

    <span v-if="sortField" class="cursor-pointer text-base select-none" @click="toggleDirection">
      <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
      <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
    </span>
  </div>
</template>
