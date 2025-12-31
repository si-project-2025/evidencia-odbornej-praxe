<script setup lang="ts">
  import { ref, computed, watch } from 'vue'
  import Select from '@/components/form/Select.vue'
  import { ArrowUp, ArrowDown } from 'lucide-vue-next'
  import type { Internship } from '@/types/internship'
  import { onMounted } from 'vue'

  const props = defineProps<{
    internships: Internship[]
    role: string
  }>()

  onMounted(() => {
    sortField.value = 'created_at'
    sortDirection.value = 'desc'
  })

  const emit = defineEmits<{
    (e: 'update', sorted: Internship[]): void
  }>()

  type SortableField =
    | 'company'
    | 'student'
    | 'semester'
    | 'year'
    | 'start_at'
    | 'end_at'
    | 'is_paid'
    | 'status'
    | 'created_at'

  const sortField = ref<SortableField | null>(null)
  const sortDirection = ref<'asc' | 'desc' | null>(null)

  const sortExtractors: Record<SortableField, (i: Internship) => string | number | null> = {
    company: (i) => i.company?.name ?? '',
    student: (i) => `${i.student?.name ?? ''} ${i.student?.surname ?? ''}`.trim(),
    semester: (i) => i.semester,
    year: (i) => i.year,
    start_at: (i) => i.start_at,
    end_at: (i) => i.end_at,
    is_paid: (i) => (i.is_paid ? 1 : 0),
    status: (i) => i.status,
    created_at: (i) => i.created_at,
  }

  const toggleSort = (field: SortableField | '') => {
    if (field === '') {
      sortField.value = null
      sortDirection.value = null
      return
    }
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

  type Column = {
    field: SortableField
    label: string
    span: number
    show: boolean
    extraClass?: string
  }

  const columns: Column[] = [
    { field: 'company', label: 'Firma', span: 3, show: props.role !== 'firma' },
    { field: 'student', label: 'Študent', span: 3, show: props.role !== 'student' },
    { field: 'semester', label: 'Semester', span: 2, show: true },
    { field: 'year', label: 'Rok', span: 1, show: true },
    { field: 'start_at', label: 'Začiatok praxe', span: 2, show: true },
    { field: 'end_at', label: 'Koniec praxe', span: 2, show: true },
    { field: 'is_paid', label: 'Typ praxe', span: 2, show: true },
    { field: 'status', label: 'Stav', span: 1, show: true, extraClass: 'justify-end mr-5' },
  ]

  const visibleColumns = computed(() => columns.filter((c) => c.show))
</script>

<template>
  <div
    :class="[
      'hidden md:grid gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200',
      role === 'garant' ? 'grid-cols-16' : 'grid-cols-13',
    ]"
  >
    <div
      v-for="col in visibleColumns"
      :key="col.field"
      class="cursor-pointer flex items-center"
      :class="[
        `col-span-${col.span}`,
        col.extraClass || '',
        sortField === col.field ? 'text-emerald-600 font-bold' : 'text-gray-500',
      ]"
      @click="toggleSort(col.field)"
    >
      {{ col.label }}

      <span v-if="sortField === col.field">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>
  </div>

  <div class="md:hidden px-4 py-3 border-b border-gray-200 flex items-center justify-between gap-1">
    <Select
      class="flex-1"
      :model-value="sortField ?? ''"
      @change="toggleSort(($event.target as HTMLSelectElement).value as SortableField)"
    >
      <option value="">Zoradiť podľa...</option>
      <option v-if="role !== 'firma'" value="company">Firma</option>
      <option v-if="role !== 'student'" value="student">Študent</option>
      <option value="semester">Semester</option>
      <option value="year">Rok</option>
      <option value="end_at">Koniec praxe</option>
      <option value="is_paid">Typ praxe</option>
      <option value="status">Stav</option>
    </Select>

    <span v-if="sortField" class="cursor-pointer text-base select-none" @click="toggleDirection">
      <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
      <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
    </span>
  </div>
</template>
