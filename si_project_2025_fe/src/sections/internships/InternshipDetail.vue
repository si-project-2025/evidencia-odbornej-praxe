<script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import { useRouter } from 'vue-router'

  import { useInternshipStore } from '@/stores/internships'
  import { Building, Calendar, Clock, User, Info, ArrowLeft, FileText, Plus, Trash2 } from 'lucide-vue-next'

  const store = useInternshipStore()
  const route = useRoute()

  const router = useRouter()
  const deleting = ref(false)
  const deleteError = ref('')

  onMounted(() => {
    store.fetchInternshipDetail(Number(route.params.id))
  })

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }

  const deleteInternship = async () => {
    if (!confirm('Naozaj chcete túto prax zmazať?')) return

    try {
      deleting.value = true
      deleteError.value = ''

      await store.deleteInternship(Number(route.params.id))

      alert('Prax bola úspešne zmazaná.')
      router.push('/internships')
    } catch {
      deleteError.value = 'Nepodarilo sa zmazať prax.'
    } finally {
      deleting.value = false
    }
  }
</script>

<template>
  <div class="justify-start items-start min-h-screen">
    <!-- Späť na zoznam -->
    <RouterLink
      to="/internships"
      class="inline-flex items-center gap-2 text-green-700 font-medium hover:text-green-800 transition-colors mb-2"
    >
      <ArrowLeft class="w-4 h-4" />
      Späť na zoznam praxí
    </RouterLink>

    <div v-if="store.loading" class="text-gray-500 text-center">Načítavam...</div>
    <div v-else-if="store.error" class="text-red-600 text-center">{{ store.error }}</div>
    <div v-else-if="!store.internshipDetail" class="text-gray-500 text-center">Prax sa nenašla.</div>

    <div v-else>
      <div
        class="bg-white border-t-4 border-emerald-500 rounded-3xl shadow-md hover:shadow-lg p-6 md:p-10 sm:p-10 space-y-8 transition-all duration-200 backdrop-blur-sm"
      >
        <!-- Názov a stav -->
        <div class="flex justify-between items-center">
          <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <Building class="w-7 h-7 text-green-600" />
            {{ store.internshipDetail.company?.name || 'Neznáma firma' }}
          </h2>

          <span
            class="px-4 py-2 text-sm font-medium rounded-full shadow-sm"
            :class="{
              'bg-gray-100 text-gray-700': store.internshipDetail.status === 'Vytvorená',
              'bg-blue-100 text-blue-700': store.internshipDetail.status === 'Potvrdená',
              'bg-red-100 text-red-700': store.internshipDetail.status === 'Zamietnutá',
              'bg-yellow-100 text-yellow-800': store.internshipDetail.status === 'Schválená',
              'bg-green-100 text-green-700': store.internshipDetail.status === 'Obhájená',
            }"
          >
            {{ store.internshipDetail.status || 'Neznámy stav' }}
          </span>
        </div>

        <!-- Základné info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700 text-sm">
          <!-- Semester -->
          <div class="flex items-center gap-2">
            <Calendar class="w-4 h-4 text-green-600" />
            <span>Semester: {{ store.internshipDetail.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
          </div>

          <!-- Rok -->
          <div class="flex items-center gap-2">
            <Calendar class="w-4 h-4 text-green-600" />
            <span>Rok: {{ store.internshipDetail.year }}</span>
          </div>

          <!-- Počet hodín -->
          <div class="flex items-center gap-2">
            <Clock class="w-4 h-4 text-green-600" />
            <span>Počet hodín: {{ store.internshipDetail.hours_total }}</span>
          </div>

          <!-- Koniec praxe -->
          <div class="flex items-center gap-2">
            <Calendar class="w-4 h-4 text-green-600" />
            <span>Koniec praxe: {{ formatDate(store.internshipDetail.end_at) }}</span>
          </div>

          <!-- Vytvorená -->
          <div class="flex items-center gap-2">
            <Info class="w-4 h-4 text-green-600" />
            <span>Vytvorená: {{ formatDate(store.internshipDetail.created_at) }}</span>
          </div>

          <!-- Naposledy upravená -->
          <div class="flex items-center gap-2">
            <Info class="w-4 h-4 text-green-600" />
            <span>Naposledy upravená: {{ formatDate(store.internshipDetail.updated_at) }}</span>
          </div>
        </div>

        <hr class="border-gray-200" />
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Firma -->
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
                <strong>Adresa:&nbsp;</strong>
                <span v-if="store.internshipDetail.company.address">
                  {{ store.internshipDetail.company.address.street }}
                  {{ store.internshipDetail.company.address.house_number }},
                  {{ store.internshipDetail.company.address.zip_code }}
                  {{ store.internshipDetail.company.address.city }},
                  {{ store.internshipDetail.company.address.country }}
                </span>
                <span v-else>—</span>
              </p>
            </div>
          </div>

          <!-- Garant -->
          <div>
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
              <User class="w-5 h-5 text-green-600" />
              Garant praxe
            </h3>
            <div class="pl-7 text-gray-700 text-sm space-y-1">
              <p>
                <strong>Meno:</strong>
                {{ store.internshipDetail.garant?.name || 'Neznáme meno' }}
                {{ store.internshipDetail.garant?.surname || '' }}
              </p>
              <p>
                <strong>Kontakt:</strong>
                {{ store.internshipDetail.garant?.email || '—' }}
              </p>
            </div>
          </div>
        </div>

        <hr class="border-gray-200" />

        <!-- Dokumenty k praxi-->
        <div>
          <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-3">
            <!-- Nadpis -->
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
              <FileText class="w-5 h-5 text-emerald-600" />
              Dokumenty k praxi
            </h3>

            <!-- Tlačidlá -->
            <div class="flex flex-wrap justify-start md:justify-end gap-3">
              <button
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors"
              >
                <Plus class="w-4 h-4" />
                Pridať dokument
              </button>

              <button
                class="inline-flex items-center gap-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors"
              >
                <FileText class="w-4 h-4" />
                Generovať dohodu
              </button>
            </div>
          </div>

          <template v-if="store.internshipDetail.documents && store.internshipDetail.documents.length">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
              <div
                v-for="doc in store.internshipDetail.documents"
                :key="doc.document_id"
                class="flex items-center justify-between border border-emerald-100 rounded-xl bg-emerald-50/40 p-4 shadow-sm hover:shadow-md hover:bg-emerald-50"
              >
                <!-- info -->
                <div class="flex items-center gap-3 overflow-hidden">
                  <FileText class="h-6 text-emerald-600" />
                  <div class="flex flex-col overflow-hidden">
                    <span class="font-semibold text-gray-800 truncate">{{ doc.file_name }}</span>
                    <span class="text-xs text-gray-500 truncate">{{ doc.type }}</span>
                    <span class="text-xs text-gray-400">
                      {{ new Date(doc.created_at).toLocaleDateString('sk-SK') }}
                    </span>
                  </div>
                </div>

                <!-- stav dokumetu-->
                <span
                  v-if="doc.is_verified"
                  class="ml-2 text-xs font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full whitespace-nowrap"
                >
                  Overený
                </span>
                <span
                  v-else
                  class="ml-2 text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap"
                >
                  Neoverený
                </span>
              </div>
            </div>
          </template>

          <p v-else class="pl-7 text-gray-500 text-sm mt-2">Zatiaľ neboli pridané žiadne dokumenty.</p>
        </div>
      </div>

      <!-- Odstránenie praxe -->
      <div class="pt-6 border-t border-gray-200 mt-10 flex flex-col sm:flex-row sm:items-center justify-between">
        <p class="text-sm text-gray-500 mb-3 sm:mb-0">
          Ak bola prax vytvorená omylom alebo už nie je aktuálna, môžete ju odstrániť z evidencie.
        </p>
        <button
          @click="deleteInternship"
          class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium px-5 py-2 rounded-lg shadow-sm disabled:opacity-60"
          :disabled="deleting"
        >
          <Trash2 class="w-4 h-4" />
          {{ deleting ? 'Mazanie...' : 'Zmazať prax' }}
        </button>
        <p v-if="deleteError" class="text-red-600 text-sm mt-2">{{ deleteError }}</p>
      </div>
    </div>
  </div>
</template>
