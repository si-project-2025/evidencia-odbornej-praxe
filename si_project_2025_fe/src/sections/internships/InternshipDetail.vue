<script setup lang="ts">
  import { ref, computed } from 'vue'
  import { useRoute } from 'vue-router'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '@/stores/user.ts'
  import { useInternshipStore } from '@/stores/internships'
  import {
    Building,
    Calendar,
    Clock,
    User,
    Info,
    FileText,
    Plus,
    Trash2,
    Signature,
    Users,
    Pencil,
  } from 'lucide-vue-next'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import StatusBadge from '@/components/atoms/StatusBadge.vue'
  import DocumentCard from '@/components/DocumentCard.vue'

  const userStore = useUserStore()
  const isAuthenticated = userStore.user

  const role = computed(() => userStore.user?.role ?? null)

  const isStudent = computed(() => role.value === 'student')
  const isGarant = computed(() => role.value === 'garant')

  const internshipStore = useInternshipStore()
  const route = useRoute()

  const router = useRouter()
  const deleting = ref(false)
  const deleteError = ref('')

  const sending = ref(false)
  const sendError = ref('')

  const formatDate = (date: string | null) => {
    if (!date) return '—'
    const d = new Date(date)
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' })
  }

  // odstránenie praxe
  const deleteInternship = async () => {
    if (!confirm('Naozaj chcete túto prax zmazať?')) return

    try {
      deleting.value = true
      deleteError.value = ''

      await internshipStore.deleteInternship(Number(route.params.id))

      alert('Prax bola úspešne zmazaná.')
      router.push('/internships')
    } catch {
      deleteError.value = 'Nepodarilo sa zmazať prax.'
    } finally {
      deleting.value = false
    }
  }

  // odoslanie na verifikáciu firme
  const sendToCompany = async () => {
    // overiť firmou
    if (!internshipStore.internshipDetail?.contact_persons?.length) {
      alert('Pre túto prax nie je zadaná žiadna kontaktná osoba.')
      return
    }

    if (!confirm('Odoslať email na overenie praxe kontaktným osobám?')) return

    try {
      sending.value = true
      sendError.value = ''

      await internshipStore.sendVerificationEmail(Number(route.params.id))

      alert('Email na overenie bol úspešne odoslaný.')
    } catch {
      sendError.value = 'Nepodarilo sa poslať overovací email.'
    } finally {
      sending.value = false
    }
  }

  const fileInput = ref<HTMLInputElement | null>(null)

  const chooseFile = () => {
    fileInput.value?.click()
  }

  const handleFileChange = async (e: Event) => {
    const target = e.target as HTMLInputElement
    if (!target.files?.length) return

    const file = target.files?.[0]
    if (!file) return // ✨ toto opraví problém

    try {
      await internshipStore.uploadDocument(Number(route.params.id), file)
      alert('Dokument bol nahratý')
    } catch (err) {
      console.error(err)
      alert('Chyba pri nahrávaní dokumentu')
    }
    target.value = '' // reset inputu
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

        <!-- Rok -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Rok: {{ internshipStore.internshipDetail?.year }}</span>
        </div>

        <!-- Počet hodín -->
        <div class="flex items-center gap-2">
          <Clock class="w-4 h-4 text-green-600" />
          <span>Počet hodín: {{ internshipStore.internshipDetail?.hours_total }}</span>
        </div>

        <!-- Koniec praxe -->
        <div class="flex items-center gap-2">
          <Calendar class="w-4 h-4 text-green-600" />
          <span>Koniec praxe: {{ formatDate(internshipStore.internshipDetail?.end_at ?? '') }}</span>
        </div>

        <!-- Vytvorená -->
        <div class="flex items-center gap-2">
          <Info class="w-4 h-4 text-green-600" />
          <span>Vytvorená: {{ formatDate(internshipStore.internshipDetail?.created_at ?? '') }}</span>
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
            v-if="internshipStore.internshipDetail?.contact_persons?.length"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pl-7 text-gray-700 text-sm"
          >
            <div
              v-for="person in internshipStore.internshipDetail.contact_persons"
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
      <div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-3">
          <!-- Nadpis -->
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <FileText class="w-5 h-5 text-emerald-600" />
            Dokumenty k praxi
          </h3>

          <!-- Tlačidlá -->
          <div class="flex flex-wrap justify-start md:justify-end gap-3">
            <ActionButton @click="chooseFile">
              <Plus class="w-4 h-4" />
              Pridať dokument
            </ActionButton>

            <input ref="fileInput" type="file" class="hidden" @change="handleFileChange" />

            <ActionButton color="green-light" @click="internshipStore.generateDocument">
              <FileText class="w-4 h-4" />
              Generovať dohodu
            </ActionButton>
          </div>
        </div>

        <template
          v-if="internshipStore.internshipDetail?.documents && internshipStore.internshipDetail?.documents.length"
        >
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <DocumentCard
              v-for="document in internshipStore.internshipDetail.documents"
              :key="document.document_id"
              :document="document"
            />
          </div>
        </template>

        <p v-else class="pl-7 text-gray-500 text-sm mt-2">Zatiaľ neboli pridané žiadne dokumenty.</p>
      </div>
    </div>

    <!-- Buttony -->
    <div class="pt-6 border-t border-gray-200 mt-10 flex flex-col sm:flex-row sm:items-center items-end sm:justify-end">
      <div class="flex flex-row gap-2">
        <ActionButton
          v-if="isAuthenticated && isStudent && internshipStore.internshipDetail?.status == 'Vytvorená'"
          color="yellow"
          @click="sendToCompany"
        >
          <Signature class="w-4 h-4" />
          Overiť firmou
        </ActionButton>

        <ActionButton
          v-if="isAuthenticated && (isGarant || isStudent) && internshipStore.internshipDetail?.status == 'Vytvorená'"
          color="red"
          @click="deleteInternship"
          :disabled="deleting"
        >
          <Trash2 class="w-4 h-4" />
          {{ deleting ? 'Mazanie...' : 'Zmazať prax' }}
        </ActionButton>
        <p v-if="deleteError" class="text-red-600 text-sm mt-2">{{ deleteError }}</p>

        <ActionButton :href="`/internships/${route.params.id}/edit`" v-if="isGarant">
          <Pencil class="w-4 h-4" />
          Upraviť prax
        </ActionButton>
      </div>
    </div>
  </div>
</template>
