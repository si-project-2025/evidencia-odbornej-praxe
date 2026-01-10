<script setup lang="ts">
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import { Building, CalendarDays, User } from 'lucide-vue-next'
  import type { Internship } from '@/types/internship.ts'
  import { getColumns, getGridTemplate, getAlignClass, type Role, type Column } from '@/utils/internshipColumns.ts'

  const props = defineProps<{
    internship: Internship
    role: Role
  }>()

  const columns = getColumns(props.role)

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }

  const semesterLabel = (s?: string | null) => (s === 'Z' ? 'Zimný' : s === 'L' ? 'Letný' : (s ?? '—'))

  const textFor = (col: Column) => {
    const i = props.internship
    switch (col.field) {
      case 'company':
        return i.company?.name || 'Neznáma firma'
      case 'student':
        return i.student?.name ? `${i.student.name} ${i.student.surname}` : 'Neznámy študent'
      case 'semester':
        return semesterLabel(i.semester)
      case 'year':
        return String(i.year ?? '—')
      case 'start_at':
        return formatDate(i.start_at)
      case 'end_at':
        return formatDate(i.end_at)
      case 'is_paid':
        return i.is_paid ? 'Platená' : 'Neplatená'
      default:
        return ''
    }
  }
</script>

<template>
  <div
    class="border-l-4 border-emerald-500 rounded-2xl bg-white shadow-md hover:shadow-lg hover:bg-emerald-50/50 transition duration-500"
  >
    <RouterLink :to="`/internships/${internship.internships_id}`">
      <!-- DESKTOP: -->
      <div
        class="hidden md:grid gap-2 px-8 py-5 items-center text-gray-700 text-sm"
        :style="{ gridTemplateColumns: getGridTemplate(role) }"
      >
        <div v-for="col in columns" :key="col.field" :class="['flex items-center min-w-0', getAlignClass(col)]">
          <!-- Status -->
          <StatusBadge v-if="col.field === 'status'" :status="internship.status" />

          <!-- Firma -->
          <template v-else-if="col.field === 'company'">
            <span class="font-semibold text-gray-900 truncate">{{ textFor(col) }}</span>
          </template>

          <!-- Študent -->
          <template v-else-if="col.field === 'student'">
            <span class="font-medium text-gray-900 truncate">{{ textFor(col) }}</span>
          </template>

          <!-- Typ praxe -->
          <template v-else-if="col.field === 'is_paid'">
            <span :class="internship.is_paid ? 'text-emerald-600 font-medium' : 'text-gray-500'">
              {{ textFor(col) }}
            </span>
          </template>

          <!-- Ostatné -->
          <template v-else>
            <span class="truncate">{{ textFor(col) }}</span>
          </template>
        </div>
      </div>

      <!-- MOBILE: -->
      <div class="md:hidden px-6 py-4 space-y-2 text-sm text-gray-700">
        <div v-if="role !== 'firma'" class="flex items-center gap-2 font-semibold text-gray-900">
          <Building class="w-5 h-5 text-emerald-600 shrink-0" />
          <span class="truncate">{{ internship.company?.name || 'Neznáma firma' }}</span>
        </div>

        <div v-if="role !== 'student'" class="flex items-center gap-2 font-medium text-gray-900">
          <User class="w-5 h-5 text-emerald-600 shrink-0" />
          <span class="truncate">
            {{
              internship.student?.name ? `${internship.student.name} ${internship.student.surname}` : 'Neznámy študent'
            }}
          </span>
        </div>

        <div class="flex items-center gap-2">
          <CalendarDays class="w-4 h-4 text-gray-400" />
          <span>{{ internship.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
          <span class="text-gray-500">•</span>
          <span>{{ internship.year }}</span>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <div>
            <span class="text-gray-500">Začiatok:</span>
            {{ formatDate(internship.start_at) }}
          </div>
          <div>
            <span class="text-gray-500">Koniec:</span>
            {{ formatDate(internship.end_at) }}
          </div>
          <div>
            <span class="text-gray-500">Typ:&nbsp;</span>
            <span :class="internship.is_paid ? 'text-emerald-600 font-medium' : 'text-gray-500'">
              {{ internship.is_paid ? 'Platená' : 'Neplatená' }}
            </span>
          </div>
          <div class="flex justify-end"><StatusBadge :status="internship.status" /></div>
        </div>
      </div>
    </RouterLink>
  </div>
</template>
