<script setup lang="ts">
  import { onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { useInternshipStore } from '@/stores/internships'
  import { Building, Calendar, Clock, User, Info, ArrowLeft } from 'lucide-vue-next'

  const store = useInternshipStore()
  const route = useRoute()

  onMounted(() => {
    store.fetchInternshipDetail(Number(route.params.id))
  })

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }
</script>

<template>
  <div class="max-w-5xl mx-auto">
    <!-- Späť na zoznam -->
    <RouterLink
      to="/internships"
      class="inline-flex items-center gap-2 text-green-700 font-medium hover:text-green-800 transition-colors mb-6"
    >
      <ArrowLeft class="w-4 h-4" />
      Späť na zoznam praxí
    </RouterLink>

    <div v-if="store.loading" class="text-gray-500 text-center">Načítavam...</div>
    <div v-else-if="store.error" class="text-red-600 text-center">{{ store.error }}</div>
    <div v-else-if="!store.internshipDetail" class="text-gray-500 text-center">Prax sa nenašla.</div>

    <div
      v-else
      class="bg-white border border-green-100 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-10 space-y-8"
    >
      <!-- HLAVIČKA -->
      <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
          <Building class="w-7 h-7 text-green-600" />
          {{ store.internshipDetail.company?.name || 'Neznáma firma' }}
        </h2>

        <span
          class="px-4 py-2 text-sm font-medium rounded-full shadow-sm"
          :class="{
            'bg-gray-100 text-gray-700': store.internshipDetail.status?.type === 'Vytvorená',
            'bg-blue-100 text-blue-700': store.internshipDetail.status?.type === 'Potvrdená',
            'bg-red-100 text-red-700': store.internshipDetail.status?.type === 'Zamietnutá',
            'bg-yellow-100 text-yellow-800': store.internshipDetail.status?.type === 'Schválená',
            'bg-green-100 text-green-700': store.internshipDetail.status?.type === 'Obhájená',
          }"
        >
          {{ store.internshipDetail.status?.type || 'Neznámy stav' }}
        </span>
      </div>

      <!-- ZÁKLADNÉ INFO -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>
            {{ store.internshipDetail.semester === 'Z' ? 'Zimný' : 'Letný' }}
            semester {{ store.internshipDetail.year }}
          </span>
        </div>

        <div class="flex items-center gap-2">
          <Clock class="w-4 h-4 text-green-600" />
          <span>Počet hodín: {{ store.internshipDetail.hours_total }}</span>
        </div>

        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Koniec praxe: {{ formatDate(store.internshipDetail.end_at) }}</span>
        </div>

        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Vytvorená: {{ formatDate(store.internshipDetail.created_at) }}</span>
        </div>
      </div>

      <hr class="border-gray-200" />

      <!-- FIRMA -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
          <Building class="w-5 h-5 text-green-600" />
          Informácie o firme
        </h3>
        <div class="pl-7 text-gray-700 text-sm space-y-1">
          <p>
            <strong>Názov:</strong>
            {{ store.internshipDetail.company.name }}
          </p>
          <p>
            <strong>IČO:</strong>
            {{ store.internshipDetail.company.ico }}
          </p>
          <p>
            <strong>ID adresy:</strong>
            {{ store.internshipDetail.company.address_id }}
          </p>
        </div>
      </div>

      <!-- GARANT -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
          <User class="w-5 h-5 text-green-600" />
          Garant praxe
        </h3>
        <div class="pl-7 text-gray-700 text-sm space-y-1">
          <p>
            <strong>Meno:</strong>
            {{ store.internshipDetail.garant.name }}
            {{ store.internshipDetail.garant.surname }}
          </p>
          <p>
            <strong>Kontakt:</strong>
            {{ store.internshipDetail.garant.email }}
          </p>
        </div>
      </div>

      <!-- DOPLNKOVÉ INFO -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
          <Info class="w-5 h-5 text-green-600" />
          Doplňujúce informácie
        </h3>
        <p class="pl-7 text-gray-700 text-sm leading-relaxed">
          Prax je vedená ako súčasť odborného vzdelávania. Aktuálne sa nachádza v stave
          <strong>{{ store.internshipDetail.status?.type }}</strong>
          . Posledná aktualizácia: {{ formatDate(store.internshipDetail.updated_at) }}.
        </p>
      </div>
    </div>
  </div>
</template>
