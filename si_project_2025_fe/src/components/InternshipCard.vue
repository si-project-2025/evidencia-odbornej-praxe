<script setup lang="ts">
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import { Building, CalendarDays, User } from 'lucide-vue-next'
  import type { Internship } from '@/types/internship.ts'

  defineProps<{
    internship: Internship
    role: 'student' | 'garant' | 'firma'
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
      <div
        :class="[
          'grid grid-cols-1 gap-2 px-8 py-5 items-center text-gray-700 text-sm',
          role === 'garant' ? 'md:grid-cols-16' : 'md:grid-cols-13',
        ]"
      >
        <!-- Firma -->
        <div
          v-if="role !== 'firma'"
          class="col-span-3 flex items-center gap-2 font-semibold text-gray-900 mb-2 md:mb-0 sm:mb-0"
        >
          <Building class="w-5 h-5 text-emerald-600 shrink-0" />
          <span class="truncate">{{ internship.company?.name || 'Neznáma firma' }}</span>
        </div>
        <!-- Študent -->
        <div
          v-if="role === 'garant' || role === 'firma'"
          class="col-span-3 flex items-center gap-2 font-medium text-gray-900"
        >
          <User class="w-5 h-5 text-emerald-600 shrink-0" />
          <span class="truncate">
            {{
              internship.student?.name ? `${internship.student.name} ${internship.student.surname}` : 'Neznámy študent'
            }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-x-3 gap-y-2 md:col-span-10 md:contents text-sm">
          <!-- Semester -->
          <div class="col-span-1 md:col-span-2 flex items-center gap-1">
            <CalendarDays class="w-4 h-4 text-gray-400" />
            <span>{{ internship.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
            <span class="md:hidden">semester</span>
          </div>

          <!-- Rok -->
          <div class="col-span-1 md:col-span-1 flex items-center">
            <span class="md:hidden text-gray-500">Rok:&nbsp;</span>
            <span>{{ internship.year }}</span>
          </div>

          <!-- Začiatok praxe -->
          <div class="col-span-1 md:col-span-2 flex items-center gap-1">
            <span class="md:hidden text-gray-500">Začiatok:&nbsp;</span>
            <CalendarDays class="hidden md:inline-block w-4 h-4 text-gray-400" />
            <span>{{ formatDate(internship.start_at) }}</span>
          </div>

          <!-- Koniec -->
          <div class="col-span-1 md:col-span-2 flex items-center">
            <span class="md:hidden text-gray-500">Koniec:&nbsp;</span>
            <span>{{ formatDate(internship.end_at) }}</span>
          </div>

          <!-- Typ praxe -->
          <div class="col-span-1 md:col-span-2 flex items-center">
            <span class="md:hidden text-gray-500">Typ:&nbsp;</span>
            <span :class="internship.is_paid ? 'text-emerald-600 font-medium' : 'text-gray-500'">
              {{ internship.is_paid ? 'Platená' : 'Neplatená' }}
            </span>
          </div>

          <!-- Stav -->
          <div class="col-span-1 flex justify-end items-center">
            <StatusBadge :status="internship.status" />
          </div>
        </div>
      </div>
    </RouterLink>
  </div>
</template>
