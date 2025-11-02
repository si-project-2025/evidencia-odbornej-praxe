<script setup lang="ts">
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import { Building, CalendarDays, Clock } from 'lucide-vue-next'
  import type { Internship } from '@/types/internship.ts'

  defineProps<{
    internship: Internship
  }>()

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }
</script>

<template>
  <div
    class="border-l-4 border-emerald-500 rounded-2xl bg-white shadow-md hover:shadow-lg hover:bg-emerald-50/50 transition duration-500"
  >
    <RouterLink :to="`/internships/${internship.internships_id}`">
      <!-- Tabuľka -->
      <div class="grid grid-cols-1 md:grid-cols-13 gap-2 px-8 py-5 items-center text-gray-700 text-sm">
        <!-- Firma -->
        <div class="col-span-3 flex items-center gap-2 font-semibold text-gray-900 mb-2 md:mb-0 sm:mb-0">
          <Building class="w-5 h-5 text-emerald-600 shrink-0" />
          <span class="truncate">{{ internship.company?.name || 'Neznáma firma' }}</span>
        </div>

        <div class="md:col-span-10 grid grid-cols-2 md:grid-cols-10 gap-y-2 text-sm">
          <!-- Semester -->
          <div class="col-span-1 md:col-span-2 flex items-center gap-1">
            <CalendarDays class="w-4 h-4 text-gray-400" />
            <span>{{ internship.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
            <span class="md:hidden">semester</span>
          </div>

          <!-- Rok -->
          <div class="col-span-1 md:col-span-2 font-medium text-gray-800">
            <span class="md:hidden text-gray-500">Rok:&nbsp;</span>
            <span>{{ internship.year }}</span>
          </div>

          <!-- Počet hodín -->
          <div class="col-span-1 md:col-span-2 flex items-center gap-1">
            <Clock class="w-4 h-4 text-gray-400" />
            <span>{{ internship.hours_total }} hodín</span>
          </div>

          <!-- Koniec -->
          <div class="col-span-1 md:col-span-2">
            <span class="md:hidden text-gray-500">Koniec:&nbsp;</span>
            <span class="font-medium">{{ formatDate(internship.end_at) }}</span>
          </div>

          <!-- Stav -->
          <div class="col-span-2 md:col-span-2 flex justify-start sm:justify-end mt-2 md:mt-0">
            <StatusBadge :status="internship.status" />
          </div>
        </div>
      </div>
    </RouterLink>
  </div>
</template>
