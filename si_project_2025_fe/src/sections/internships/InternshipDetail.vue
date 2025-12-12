<script setup lang="ts">
  import { useInternshipStore } from '@/stores/internships.ts'
  import { Building, Calendar, Clock, User, Info, Users } from 'lucide-vue-next'
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import DocumentsSection from '@/sections/internships/detail/DocumentsSection.vue'
  import ActionsSection from '@/sections/internships/detail/ActionsSection.vue'

  const internshipStore = useInternshipStore()

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }
</script>

<template>
  <div>
    <div
      class="bg-white border-t-4 border-emerald-500 rounded-3xl shadow-md hover:shadow-lg p-6 md:p-10 sm:p-10 space-y-8 transition-all duration-200 backdrop-blur-sm"
    >
      <!-- Názov a stav -->
      <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
          <Building class="w-7 h-7 text-green-600" />
          {{ internshipStore.internshipDetail?.company?.name || 'Neznáma firma' }}
        </h2>

        <StatusBadge :status="internshipStore.internshipDetail?.status" />
      </div>

      <!-- Základné info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700 text-sm">
        <!-- Semester -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Semester: {{ internshipStore.internshipDetail?.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
        </div>

        <!-- Začiatok praxe -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Začiatok praxe: {{ formatDate(internshipStore.internshipDetail?.start_at ?? '') }}</span>
        </div>

        <!-- Koniec praxe -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Koniec praxe: {{ formatDate(internshipStore.internshipDetail?.end_at ?? '') }}</span>
        </div>

        <!-- Rok -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Rok: {{ internshipStore.internshipDetail?.year }}</span>
        </div>

        <!-- Vytvorená -->
        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Pridaná: {{ formatDate(internshipStore.internshipDetail?.created_at ?? '') }}</span>
        </div>

        <!-- Naposledy upravená -->
        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Naposledy upravená: {{ formatDate(internshipStore.internshipDetail?.updated_at ?? '') }}</span>
        </div>
      </div>

      <hr class="border-gray-200" />
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Študent -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
            <User class="w-5 h-5 text-green-600" />
            Informácie o študentovi
          </h3>
          <div class="pl-7 text-gray-700 text-sm space-y-1">
            <p>
              <strong>Meno:</strong>
              {{ internshipStore.internshipDetail?.student?.name || 'Neznáme meno' }}
              {{ internshipStore.internshipDetail?.student?.surname || '' }}
            </p>
            <p>
              <strong>Študijný program:</strong>
              {{ internshipStore.internshipDetail?.student?.study_program || '—' }}
            </p>
            <p>
              <strong>Email:</strong>
              {{ internshipStore.internshipDetail?.student?.email || '—' }}
            </p>
            <p v-if="internshipStore.internshipDetail?.student?.phone_number">
              <strong>Telefón:</strong>
              {{ internshipStore.internshipDetail?.student?.phone_number }}
            </p>
          </div>
        </div>

        <!-- Firma -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
            <Building class="w-5 h-5 text-green-600" />
            Informácie o firme
          </h3>
          <div class="pl-7 text-gray-700 text-sm space-y-1">
            <p>
              <strong>Názov:</strong>
              {{ internshipStore.internshipDetail?.company.name }}
            </p>
            <p>
              <strong>IČO:</strong>
              {{ internshipStore.internshipDetail?.company.ico }}
            </p>
            <p>
              <strong>Adresa:&nbsp;</strong>
              <span v-if="internshipStore.internshipDetail?.company.address">
                {{ internshipStore.internshipDetail.company.address.street }}
                {{ internshipStore.internshipDetail.company.address.house_number }},
                {{ internshipStore.internshipDetail.company.address.zip_code }}
                {{ internshipStore.internshipDetail.company.address.city }},
                {{ internshipStore.internshipDetail.company.address.country }}
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
              {{ internshipStore.internshipDetail?.garant?.name || 'Neznáme meno' }}
              {{ internshipStore.internshipDetail?.garant?.surname || '' }}
            </p>
            <p>
              <strong>Kontakt:</strong>
              {{ internshipStore.internshipDetail?.garant?.email || '—' }}
            </p>
          </div>
        </div>
      </div>

      <hr class="border-gray-200" />
      <!-- Kontaktné osoby -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-2">
          <Users class="w-5 h-5 text-green-600" />
          Kontaktné osoby firmy
        </h3>
        <div class="text-gray-700 text-sm space-y-3">
          <div
            v-if="internshipStore.internshipDetail?.contact_person?.length"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pl-7 text-gray-700 text-sm"
          >
            <div
              v-for="person in internshipStore.internshipDetail.contact_person"
              :key="person.id"
              class="space-y-1 pb-2 border-b border-gray-100 last:border-0"
            >
              <p>
                <strong>Meno:</strong>
                {{ person.name }} {{ person.surname }}
              </p>
              <p>
                <strong>Email:</strong>
                {{ person.email }}
              </p>
              <p v-if="person.phone">
                <strong>Telefón:</strong>
                {{ person.phone }}
              </p>
            </div>
          </div>
          <p v-else class="text-gray-500 pl-7">Žiadne kontaktné osoby</p>
        </div>
      </div>

      <hr class="border-gray-200" />

      <!-- Dokumenty k praxi-->
      <DocumentsSection />
    </div>

    <!-- Buttony -->
    <ActionsSection />
  </div>
</template>
