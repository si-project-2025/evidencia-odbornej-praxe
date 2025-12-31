<script setup lang="ts">
  import { useInternshipStore } from '@/stores/internships.ts'
  import { Building, Calendar, User, Info, Users } from 'lucide-vue-next'
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import DocumentsSection from '@/sections/internships/detail/DocumentsSection.vue'
  import ActionsSection from '@/sections/internships/detail/ActionsSection.vue'
  import { computed } from 'vue'
  import { useUserStore } from '@/stores/user.ts'
  import type { Role } from '@/types/common.ts'

  const internshipStore = useInternshipStore()

  const internship = internshipStore.internshipDetail
  const contactPerson = computed(() => internshipStore.internshipDetail?.contact_person)

  const userStore = useUserStore()
  const role = computed<Role>(() => userStore.user?.role ?? 'student')

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
          <span>{{ internship?.company?.name || 'Neznáma firma' }}</span>
          <span v-if="internship?.is_paid" class="text-lg text-green-700 italic">(Platená prax)</span>
        </h2>

        <StatusBadge :status="internship?.status" />
      </div>

      <!-- Základné info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700 text-sm">
        <!-- Semester -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Semester: {{ internship?.semester === 'Z' ? 'Zimný' : 'Letný' }}</span>
        </div>

        <!-- Začiatok praxe -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Začiatok praxe: {{ formatDate(internship?.start_at ?? '') }}</span>
        </div>

        <!-- Koniec praxe -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Koniec praxe: {{ formatDate(internship?.end_at ?? '') }}</span>
        </div>

        <!-- Rok -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Rok: {{ internship?.year }}</span>
        </div>

        <!-- Vytvorená -->
        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Pridaná: {{ formatDate(internship?.created_at ?? '') }}</span>
        </div>

        <!-- Naposledy upravená -->
        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Naposledy upravená: {{ formatDate(internship?.updated_at ?? '') }}</span>
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
              {{ internship?.student?.name || 'Neznáme meno' }}
              {{ internship?.student?.surname || '' }}
            </p>
            <p>
              <strong>Študijný program:</strong>
              {{ internship?.student?.study_program || '—' }}
            </p>
            <p>
              <strong>Email:</strong>
              {{ internship?.student?.email || '—' }}
            </p>
            <p v-if="internship?.student?.phone_number">
              <strong>Telefón:</strong>
              {{ internship?.student?.phone_number }}
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
              {{ internship?.company.name }}
            </p>
            <p>
              <strong>IČO:</strong>
              {{ internship?.company.ico }}
            </p>
            <p>
              <strong>Adresa:&nbsp;</strong>
              <span v-if="internship?.company.address">
                {{ internship.company.address.street }}
                {{ internship.company.address.house_number }},
                {{ internship.company.address.zip_code }}
                {{ internship.company.address.city }},
                {{ internship.company.address.country }}
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
              {{ internship?.garant?.name || 'Neznáme meno' }}
              {{ internship?.garant?.surname || '' }}
            </p>
            <p>
              <strong>Kontakt:</strong>
              {{ internship?.garant?.email || '—' }}
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
            v-if="contactPerson"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pl-7 text-gray-700 text-sm"
          >
            <div class="space-y-1 pb-2 border-b border-gray-100 last:border-0">
              <p>
                <strong>Meno:</strong>
                {{ contactPerson.name }} {{ contactPerson.surname }}
              </p>
              <p>
                <strong>Email:</strong>
                {{ contactPerson.email }}
              </p>
              <p v-if="contactPerson.phone">
                <strong>Telefón:</strong>
                {{ contactPerson.phone }}
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
    <ActionsSection v-if="role !== 'firma'" />
  </div>
</template>
