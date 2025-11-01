<script setup lang="ts">
  import { onMounted, computed } from 'vue'
  import { useInternshipStore } from '@/stores/internships'
  import { Clock, Building, CalendarDays, Plus } from 'lucide-vue-next'
  const store = useInternshipStore()

  onMounted(() => {
    store.fetchInternships()
  })

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }

  const latestInternship = computed(() => {
    if (!store.internships.length) return null
    return [...store.internships].sort((a, b) => b.year - a.year)[0]
  })

  const getStatusClass = (status?: string) => {
    switch (status) {
      case 'Vytvorená':
        return 'bg-gray-200 text-gray-700'
      case 'Potvrdená':
        return 'bg-blue-100 text-blue-700'
      case 'Zamietnutá':
        return 'bg-rose-100 text-rose-700'
      case 'Schválená':
        return 'bg-amber-100 text-amber-700'
      case 'Obhájená':
        return 'bg-emerald-100 text-emerald-700'
      default:
        return 'bg-purple-100 text-purple-700'
    }
  }
</script>

<template>
  <div class="flex-col justify-start items-start min-h-screen">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <div>
        <h2 class="normal-case text-2xl text-gray-800">Moje odborné praxe</h2>
        <p class="text-gray-500 text-sm">Zoznam všetkých praxí, ktoré ste absolvovali alebo máte naplánované.</p>
      </div>
      <RouterLink
        to="/internships/create"
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-all duration-200"
      >
        <Plus class="w-4 h-4" />
        Pridať prax
      </RouterLink>
    </div>

    <div v-if="store.loading" class="text-gray-500">Načítavam...</div>
    <div v-else-if="store.error" class="text-red-600">{{ store.error }}</div>
    <div v-else-if="!store.internships.length" class="text-gray-500">Nemáte zatiaľ žiadne praxe.</div>

    <div v-else class="space-y-4">
      <!-- Štatistiky -->
      <div v-if="store.internships.length" class="w-full grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <!-- Počet praxí -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 text-center shadow-sm">
          <p class="text-3xl font-bold text-emerald-700">
            {{ store.internships.length }}
          </p>
          <p class="text-gray-600 text-sm font-medium">Praxí spolu</p>
        </div>
        <!-- Hodiny -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 text-center shadow-sm">
          <p class="text-3xl font-bold text-emerald-700">
            {{ store.internships.reduce((sum, internship) => sum + (internship.hours_total || 0), 0) }}
          </p>
          <p class="text-gray-600 text-sm font-medium">Odpracovaných hodín</p>
        </div>
        <!-- Najnovšia prax -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 text-center shadow-sm">
          <p v-if="latestInternship" class="text-3xl font-bold text-emerald-700">
            {{ latestInternship.semester }} - {{ latestInternship.year }}
          </p>
          <p v-if="latestInternship" class="text-gray-600 text-sm font-medium">Najnovšia prax</p>
          <p v-else class="text-gray-500 text-sm">Žiadne údaje</p>
        </div>
      </div>

      <div
        class="hidden md:grid grid-cols-13 gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200"
      >
        <div class="col-span-3">Firma</div>
        <div class="col-span-2">Semester</div>
        <div class="col-span-2">Rok</div>
        <div class="col-span-2">Hodiny</div>
        <div class="col-span-2">Koniec praxe</div>
        <div class="col-span-2 text-center">Stav</div>
      </div>

      <div
        v-for="internship in store.internships"
        :key="internship.internships_id"
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
                <span
                  class="px-4 py-1.5 text-xs font-medium rounded-full shadow-sm"
                  :class="getStatusClass(internship.status)"
                >
                  {{ internship.status || 'Neznámy stav' }}
                </span>
              </div>
            </div>
          </div>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
