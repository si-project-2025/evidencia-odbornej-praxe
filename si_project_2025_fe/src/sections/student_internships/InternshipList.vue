<script setup lang="ts">
  import { onMounted } from 'vue'
  import { useInternshipStore } from '@/stores/internships'
  import { Calendar, Clock, Building } from 'lucide-vue-next'
  const store = useInternshipStore()

  onMounted(() => {
    store.fetchStudentInternships()
  })
</script>

<template>
  <div class="px-10 2xl:px-16 py-8">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Moje odborné praxe</h2>
      <p class="text-gray-500 text-sm">Zoznam všetkých praxí, ktoré ste absolvovali alebo máte naplánované.</p>
    </div>
    <div v-if="store.loading" class="text-gray-500">Načítavam...</div>
    <div v-else-if="store.error" class="text-red-600">{{ store.error }}</div>
    <div v-else-if="!store.internships.length" class="text-gray-500">Nemáte zatiaľ žiadne praxe.</div>

    <div v-else class="space-y-3">
      <div
        v-for="internship in store.internships"
        :key="internship.internships_id"
        class="border-l-4 border-secondary rounded-2xl p-6 bg-white shadow-md hover:shadow-lg hover:bg-emerald-50/50 transition-all duration-200"
      >
        <router-link :to="{ name: 'internship-detail', params: { id: internship.internships_id } }">
          <!-- GRID 12 STĹPCOV -->
          <div class="grid grid-cols-12 items-center px-4 py-2 w-full">
            <!-- ĽAVÁ STRANA -->
            <div class="col-span-9 flex flex-col text-gray-700">
              <!-- Firma -->
              <div class="flex items-center gap-2 text-gray-900 font-semibold text-lg">
                <Building class="w-5 h-5 text-gray-500" />
                <span>{{ internship.company?.name || 'Neznáma firma' }}</span>
              </div>
              <!-- Semester a rok -->
              <div class="flex items-center gap-2 text-sm">
                <Calendar class="w-4 h-4 text-gray-400" />
                <span>{{ internship.semester === 'Z' ? 'Zimný' : 'Letný' }} semester {{ internship.year }}</span>
              </div>

              <div class="flex items-center gap-2 text-sm">
                <Clock class="w-4 h-4 text-gray-400" />
                <span>{{ internship.hours_total }} hodín</span>
                <span class="mx-1 text-gray-400">•</span>
                <span>
                  Koniec:
                  {{
                    internship.end_at
                      ? new Date(internship.end_at).toLocaleDateString('sk-SK', {
                          day: '2-digit',
                          month: '2-digit',
                          year: 'numeric',
                        })
                      : '—'
                  }}
                </span>
              </div>
            </div>

            <!-- PRAVÁ STRANA -->
            <div class="col-span-3 flex justify-end">
              <span
                class="px-4 py-1.5 text-xs font-medium rounded-full"
                :class="{
                  'bg-gray-200 text-gray-700': internship.status?.type === 'Vytvorená',
                  'bg-blue-100 text-blue-700': internship.status?.type === 'Potvrdená',
                  'bg-rose-100 text-rose-700': internship.status?.type === 'Zamietnutá',
                  'bg-amber-100 text-amber-700': internship.status?.type === 'Schválená',
                  'bg-emerald-100 text-emerald-700': internship.status?.type === 'Obhájená',
                  'bg-purple-100 text-purple-700': ![
                    'Vytvorená',
                    'Potvrdená',
                    'Zamietnutá',
                    'Schválená',
                    'Obhájená',
                  ].includes(internship.status?.type),
                }"
              >
                {{ internship.status?.type || 'Neznámy stav' }}
              </span>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
