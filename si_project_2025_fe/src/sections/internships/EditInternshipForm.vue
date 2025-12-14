<script setup lang="ts">
  import { reactive, ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import Input from '@/components/form/Input.vue'
  import Select from '@/components/form/Select.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import FormSection from '@/components/form/FormSection.vue'
  import { Save } from 'lucide-vue-next'
  import { useInternshipStore } from '@/stores/internships'
  import { useUserStore } from '@/stores/user'
  import type { InternshipForm } from '@/types/form'
  import type { Internship } from '@/types/internship'
  import { useStatusStore } from '@/stores/statuses'
  import { useCompaniesStore } from '@/stores/companies.ts'
  import { useLookupStore } from '@/stores/lookup.ts'

  import CompanySelect from '@/components/CompanySelect.vue'
  import CreateCompanyModal from '@/components/modals/CreateCompanyModal.vue'

  const props = defineProps<{
    internship: Internship | null
  }>()

  const internshipStore = useInternshipStore()
  const companiesStore = useCompaniesStore()
  const lookupStore = useLookupStore()
  const userStore = useUserStore()
  const statusStore = useStatusStore()

  const route = useRoute()

  const showCompanyModal = ref(false)

  const form = reactive<InternshipForm>({
    company_id: 0,
    users_id: 0,
    semester: 'Z',
    year: new Date().getFullYear(),
    start_at: '',
    end_at: '',
    status: 'Vytvorená',
    garant_id: 0,
  })

  const errorMessage = ref('')
  const successMessage = ref('')
  const loading = ref(false)
  const originalStatus = ref<string | null>(null)

  const isGarant = computed(() => userStore.user?.role === 'garant')

  onMounted(async () => {
    await companiesStore.fetchCompanies()
    await lookupStore.fetchStudents()
    await lookupStore.fetchGarants()
    await statusStore.fetchStatuses()

    if (props.internship) {
      const internship = props.internship
      form.company_id = internship.company?.company_id || 0
      form.users_id = internship.student?.users_id || 0
      form.semester = internship.semester
      form.year = internship.year
      form.start_at = internship.start_at?.split('T')[0] || ''
      form.end_at = internship.end_at?.split('T')[0] || ''
      form.status = internship.status || 'Vytvorená'
      form.garant_id = internship.garant?.users_id || 0
    }
  })

  const availableStatuses = computed(() => {
    if (userStore.user?.role === 'garant') {
      return statusStore.allowedStatusesForGarant(form.status)
    }
    return statusStore.statuses
  })

  const handleCompanyCreated = (newCompanyId: number) => {
    form.company_id = newCompanyId
    showCompanyModal.value = false
  }

  const submit = async () => {
    errorMessage.value = ''
    successMessage.value = ''

    originalStatus.value = props.internship?.status ?? form.status

    if (!form.company_id || !form.year || !form.semester || !form.garant_id) {
      errorMessage.value = 'Vyplňte všetky povinné polia.'
      return
    }
    if (form.end_at) {
      const start = new Date(String(form.start_at))
      const end = new Date(String(form.end_at))

      const diffDays = (end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24)

      if (diffDays < 30) {
        errorMessage.value = 'Dátum ukončenia musí byť aspoň 30 dní po začiatku praxe.'
        return
      }
    }

    try {
      loading.value = true
      const id = Number(route.params.id)
      await internshipStore.updateInternship(id, form)
      successMessage.value = 'Zmeny boli úspešne uložené!'
      await internshipStore.fetchInternshipDetail(id)
    } catch (error: unknown) {
      if (originalStatus.value) {
        form.status = originalStatus.value
      }

      if (error instanceof Error) {
        errorMessage.value = error.message
      } else {
        errorMessage.value = 'Nepodarilo sa upraviť prax.'
      }
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <!-- Formulár -->
  <form @submit.prevent="submit" class="space-y-6" v-if="!successMessage">
    <FormSection title="Údaje o praxi">
      <div class="space-y-4">
        <!-- Firma -->
        <CompanySelect
          v-model="form.company_id"
          :companies="companiesStore.companies"
          label="Firma*"
          @add-company="showCompanyModal = true"
        />

        <!-- Študent -->
        <Select v-model.number="form.users_id" id="users_id" label="Študent*">
          <option disabled value="0" v-if="!lookupStore.students.length">Načítavam študentov...</option>
          <option value="0" disabled v-else>Vyberte študenta</option>
          <option v-for="student in lookupStore.students" :key="student.users_id" :value="student.users_id">
            {{ student.name }} {{ student.surname }}
          </option>
        </Select>

        <!-- Semester -->
        <Select v-model="form.semester" id="semester" label="Semester*">
          <option value="Z">Zimný</option>
          <option value="L">Letný</option>
        </Select>

        <!-- Rok -->
        <Input v-model.number="form.year" id="year" label="Rok*" type="number" min="2020" max="2100" />

        <!-- Garant -->
        <Select v-model.number="form.garant_id" id="garant_id" label="Garant praxe*">
          <option disabled value="0" v-if="!lookupStore.garants.length">Načítavam garantov...</option>
          <option value="0" disabled v-else>Vyberte garanta</option>
          <option v-for="garant in lookupStore.garants" :key="garant.users_id" :value="garant.users_id">
            {{ garant.name }} {{ garant.surname }}
          </option>
        </Select>

        <!-- Dátum začiatku -->
        <Input
          :model-value="form.start_at ?? ''"
          @update:model-value="(val) => (form.start_at = val ?? '')"
          id="start_at"
          label="Dátum začiatku*"
          type="date"
          required
        />

        <!-- Dátum ukončenia -->
        <Input
          :model-value="form.end_at ?? ''"
          @update:model-value="(val) => (form.end_at = val ?? '')"
          id="end_at"
          label="Dátum ukončenia"
          type="date"
          :required="false"
        />

        <!-- Stav -->
        <Select v-model="form.status" id="status" label="Stav praxe" v-if="isGarant">
          <option v-for="status in availableStatuses" :key="status" :value="status">{{ status }}</option>
        </Select>
      </div>
    </FormSection>

    <!-- Chyba -->
    <p v-if="errorMessage" class="text-red-600 text-sm text-center">
      {{ errorMessage }}
    </p>

    <!-- Tlačidlo -->
    <div class="text-center pt-4">
      <BaseButton type="submit" class="w-full">
        <Save class="w-5 h-5" />
        {{ loading ? 'Ukladám...' : 'Uložiť zmeny' }}
      </BaseButton>
    </div>
  </form>

  <!-- Úspech -->
  <div v-else class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow-sm">
    <h3 class="text-lg font-semibold text-green-700 mb-1">Zmeny boli úspešne uložené!</h3>
    <p class="text-gray-700 text-sm">Prax bola aktualizovaná v systéme.</p>
  </div>

  <!-- Modal na vytvorenie firmy -->
  <CreateCompanyModal v-if="showCompanyModal" @close="showCompanyModal = false" @created="handleCompanyCreated" />
</template>
