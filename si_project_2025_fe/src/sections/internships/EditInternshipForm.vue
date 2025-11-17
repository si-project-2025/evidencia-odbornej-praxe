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

  const props = defineProps<{
    internship: Internship | null
  }>()

  const internshipStore = useInternshipStore()
  const userStore = useUserStore()
  const route = useRoute()

  const form = reactive<InternshipForm>({
    company_id: 0,
    users_id: 0,
    semester: 'Z',
    year: new Date().getFullYear(),
    hours_total: 0,
    end_at: '',
    status: 'Vytvorená',
    garant_id: 0,
  })

  const errorMessage = ref('')
  const successMessage = ref('')
  const loading = ref(false)

  const statusStore = useStatusStore()
  onMounted(async () => {
    await statusStore.fetchStatuses()

    if (props.internship) {
      const internship = props.internship
      form.company_id = internship.company?.company_id || 0
      form.users_id = internship.student?.users_id || 0
      form.semester = internship.semester
      form.year = internship.year
      form.hours_total = internship.hours_total
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

  const submit = async () => {
    errorMessage.value = ''
    successMessage.value = ''

    if (!form.company_id || !form.year || !form.semester || !form.garant_id) {
      errorMessage.value = 'Vyplňte všetky povinné polia.'
      return
    }
    try {
      loading.value = true
      const id = Number(route.params.id)
      await internshipStore.updateInternship(id, form)
      successMessage.value = 'Zmeny boli úspešne uložené!'
      await internshipStore.fetchInternshipDetail(id)
    } catch {
      errorMessage.value = 'Nepodarilo sa upraviť prax.'
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
        <Select v-model.number="form.company_id" id="company_id" label="Firma*">
          <option disabled value="0" v-if="!internshipStore.companies.length">Načítavam firmy...</option>
          <option value="0" disabled v-else>Vyberte firmu</option>
          <option v-for="company in internshipStore.companies" :key="company.company_id" :value="company.company_id">
            {{ company.name }}
          </option>
        </Select>

        <!-- Študent -->
        <Select v-model.number="form.users_id" id="users_id" label="Študent*">
          <option disabled value="0" v-if="!internshipStore.students.length">Načítavam študentov...</option>
          <option value="0" disabled v-else>Vyberte študenta</option>
          <option v-for="student in internshipStore.students" :key="student.users_id" :value="student.users_id">
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
          <option disabled value="0" v-if="!internshipStore.garants.length">Načítavam garantov...</option>
          <option value="0" disabled v-else>Vyberte garanta</option>
          <option v-for="garant in internshipStore.garants" :key="garant.users_id" :value="garant.users_id">
            {{ garant.name }} {{ garant.surname }}
          </option>
        </Select>

        <!-- Počet hodín -->
        <Input
          :model-value="form.hours_total ?? 0"
          @update:model-value="(val) => (form.hours_total = val ?? 0)"
          id="hours_total"
          label="Počet hodín"
          type="number"
        />

        <!-- Dátum ukončenia -->
        <Input
          :model-value="form.end_at ?? ''"
          @update:model-value="(val) => (form.end_at = val ?? '')"
          id="end_at"
          label="Dátum ukončenia"
          type="date"
        />

        <!-- Stav -->
        <Select v-model="form.status" id="status" label="Stav praxe">
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
</template>
