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
        class="border rounded-lg p-4 flex justify-between items-center bg-white shadow-sm"
      >
        <!--
        <div>
          <p class="font-semibold text-gray-800">
            {{ internship.company?.name || 'Neznáma firma' }}
          </p>
          <p class="text-sm text-gray-600">Semester: {{ internship.semester }} • Rok: {{ internship.year }}</p>
          <p class="text-sm text-gray-500">
            Hodiny: {{ internship.hours_total }} • Koniec: {{ internship.end_at || '—' }}
          </p>
        </div>
        <span
          class="px-3 py-1 text-xs font-medium rounded-full"
          :class="{
            'bg-gray-200 text-gray-700': internship.status?.type === 'Vytvorená',
            'bg-blue-200 text-blue-700': internship.status?.type === 'Potvrdená',
            'bg-red-200 text-red-700': internship.status?.type === 'Zamietnutá',
            'bg-green-200 text-green-700': internship.status?.type === 'Schválená',
            'bg-purple-200 text-purple-700': internship.status?.type === 'Obhájená',
            'bg-yellow-200 text-yellow-700': ![
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
        -->
        <div class="w-full">
          <div class="flex justify-between items-center px-10 2xl:px-16 py-4">
            <!-- ĽAVÁ STRANA -->
            <div class="flex flex-col gap-1 text-gray-700">
              <div class="flex items-center gap-2 text-gray-900 font-semibold text-lg">
                <Building class="w-5 h-5 text-gray-500" />
                <span>{{ internship.company?.name || 'Neznáma firma' }}</span>
              </div>

              <div class="flex items-center gap-2 text-sm">
                <Calendar class="w-4 h-4 text-gray-400" />
                <span>{{ internship.semester }} {{ internship.year }}</span>
              </div>

              <div class="flex items-center gap-2 text-sm">
                <Clock class="w-4 h-4 text-gray-400" />
                <span>{{ internship.hours_total }} hodín</span>
                <span class="mx-1 text-gray-400">•</span>
                <span>Koniec: {{ internship.end_at || '—' }}</span>
              </div>
            </div>

            <!-- PRAVÁ STRANA – STAV -->
            <div class="flex-shrink-0 ml-auto">
              <span
                class="px-4 py-1.5 text-xs font-medium rounded-full"
                :class="{
                  'bg-gray-200 text-gray-700': internship.status?.type === 'Vytvorená',
                  'bg-blue-200 text-blue-700': internship.status?.type === 'Potvrdená',
                  'bg-red-200 text-red-700': internship.status?.type === 'Zamietnutá',
                  'bg-green-200 text-green-700': internship.status?.type === 'Schválená',
                  'bg-purple-200 text-purple-700': internship.status?.type === 'Obhájená',
                  'bg-yellow-200 text-yellow-700': ![
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
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
