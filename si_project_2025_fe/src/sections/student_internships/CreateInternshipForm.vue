<script setup lang="ts">
  import { onMounted, reactive, ref } from 'vue'
  import Input from '@/components/form/Input.vue'
  import Button from '@/components/atoms/Button.vue'
  import FormSection from '@/components/form/FormSection.vue'
  import { Save } from 'lucide-vue-next'
  import { useInternshipStore } from '@/stores/internships'
  import type { InternshipCreateInput } from '@/types/internship'

  const storedUser = localStorage.getItem('user')
  const userId = storedUser ? JSON.parse(storedUser).users_id : null

  const store = useInternshipStore()

  onMounted(() => {
    store.fetchCompaniesAndGarants()
  })

  const form = reactive<InternshipCreateInput>({
    users_id: userId || 0,
    company_id: 0,
    semester: 'Z',
    year: new Date().getFullYear(),
    hours_total: 0,
    end_at: '',
    status_id: 1,
    garant_id: 0,
  })

  const errorMessage = ref('')
  const successMessage = ref('')
  const loading = ref(false)

  const submit = async () => {
    errorMessage.value = ''
    successMessage.value = ''

    if (!form.company_id || !form.year || !form.semester || !form.garant_id) {
      errorMessage.value = 'Vyplňte všetky povinné polia.'
      return
    }

    try {
      loading.value = true
      const payload = {
        users_id: Number(userId),
        company_id: Number(form.company_id),
        semester: form.semester as 'Z' | 'L',
        year: Number(form.year),
        hours_total: Number(form.hours_total),
        end_at: form.end_at + ' 00:00:00',
        status_id: 1,
        garant_id: Number(form.garant_id),
      }
      await store.createInternship(payload)
      successMessage.value = 'Prax bola úspešne vytvorená!'
    } catch {
      errorMessage.value = 'Nepodarilo sa vytvoriť prax.'
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <!-- FORM -->
  <form @submit.prevent="submit" class="space-y-6" v-if="!successMessage">
    <!-- Základné info -->
    <FormSection title="Základné informácie o praxi">
      <div class="space-y-4">
        <div>
          <label for="company_id" class="text-sm font-medium text-gray-700">Firma*</label>
          <select
            id="company_id"
            v-model.number="form.company_id"
            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
          >
            <option disabled value="0" v-if="!store.companies.length">Načítavam firmy...</option>
            <option value="0" disabled v-else>Vyberte firmu</option>
            <option v-for="company in store.companies" :key="company.company_id" :value="company.company_id">
              {{ company.name }}
            </option>
          </select>
        </div>

        <Input v-model.number="form.year" id="year" label="Rok*" type="number" min="2020" max="2100" />

        <div>
          <label for="semester" class="text-sm font-medium text-gray-700">Semester*</label>
          <select
            id="semester"
            v-model="form.semester"
            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
          >
            <option value="Z">Zimný</option>
            <option value="L">Letný</option>
          </select>
        </div>
        <div>
          <label for="garant_id" class="text-sm font-medium text-gray-700">Garant praxe*</label>
          <select
            id="garant_id"
            v-model.number="form.garant_id"
            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500"
          >
            <option disabled value="0" v-if="!store.garants.length">Načítavam garantov...</option>
            <option value="0" disabled v-else>Vyberte garanta</option>
            <option v-for="garant in store.garants" :key="garant.users_id" :value="garant.users_id">
              {{ garant.name }} {{ garant.surname }}
            </option>
          </select>
        </div>
      </div>

      <div class="space-y-4">
        <Input
          :model-value="form.hours_total ?? 0"
          @update:model-value="(val) => (form.hours_total = val ?? 0)"
          id="hours_total"
          label="Počet hodín"
          type="number"
          placeholder="Zadajte počet hodín"
        />

        <Input
          :model-value="form.end_at ?? ''"
          @update:model-value="(val) => (form.end_at = val ?? '')"
          id="end_at"
          label="Dátum ukončenia"
          type="date"
        />
      </div>
    </FormSection>

    <!-- Chyba -->
    <p v-if="errorMessage" class="text-red-600 text-sm text-center">
      {{ errorMessage }}
    </p>

    <!-- Tlačidlo -->
    <div class="text-center pt-4">
      <Button
        type="submit"
        class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all duration-200"
      >
        <Save class="w-5 h-5" />
        {{ loading ? 'Ukladám...' : 'Uložiť prax' }}
      </Button>
    </div>
  </form>

  <!-- Úspech -->
  <div v-else class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow-sm">
    <h3 class="text-lg font-semibold text-green-700 mb-1">Prax bola úspešne vytvorená!</h3>
    <p class="text-gray-700 text-sm">Vaša prax bola uložená do systému a bude viditeľná v zozname praxí.</p>
  </div>
</template>
