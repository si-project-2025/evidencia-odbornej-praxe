<script setup lang="ts">
  import { computed, onMounted, reactive, ref } from 'vue'
  import Input from '@/components/form/Input.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import FormSection from '@/components/form/FormSection.vue'
  import Select from '@/components/form/Select.vue'
  import { Save } from 'lucide-vue-next'
  import { useUserStore } from '@/stores/user'
  import { useInternshipStore } from '@/stores/internships'
  import { useCompaniesStore } from '@/stores/companies'
  import { useLookupStore } from '@/stores/lookup'

  import CreateCompanyModal from '@/components/modals/CreateCompanyModal.vue'
  import ExtendedSelect from '@/components/form/ExtendedSelect.vue'

  import type { InternshipForm } from '@/types/form'
  import { useContactstore } from '@/stores/contacts.ts'
  import CreateContactModal from '@/components/modals/CreateContactModal.vue'
  import Checkbox from '@/components/form/Checkbox.vue'

  const userStore = useUserStore()
  const internshipStore = useInternshipStore()
  const companiesStore = useCompaniesStore()
  const contactsStore = useContactstore()
  const lookupStore = useLookupStore()

  const showCompanyModal = ref(false)
  const showContactModal = ref(false)

  onMounted(async () => {
    await companiesStore.fetchCompanies()
    await contactsStore.fetchContacts()
    await lookupStore.fetchGarants()
  })

  const form = reactive<InternshipForm>({
    users_id: userStore.user?.users_id || 0,
    company_id: 0,
    contact_person_id: 0,
    semester: 'Z',
    year: new Date().getFullYear(),
    start_at: '',
    end_at: '',
    garant_id: 0,
    is_paid: false,
  })

  const errorMessage = ref('')
  const successMessage = ref('')
  const loading = ref(false)

  const filteredContacts = computed(() => {
    if (!form.company_id) return contactsStore.contacts
    return contactsStore.contacts.filter((c) => c.company_id === form.company_id)
  })

  const handleCompanyCreated = (newCompanyId: number) => {
    form.company_id = newCompanyId
    showCompanyModal.value = false
  }

  const handleContactCreated = (newContactId: number) => {
    form.contact_person_id = newContactId
    showContactModal.value = false
  }

  const submit = async () => {
    errorMessage.value = ''
    successMessage.value = ''

    if (!form.company_id || !form.year || !form.semester || !form.garant_id || !form.start_at) {
      errorMessage.value = 'Vyplňte všetky povinné polia.'
      return
    }

    if (form.end_at) {
      const start = new Date(form.start_at)
      const end = new Date(form.end_at)

      const diffDays = (end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24)

      if (diffDays < 30) {
        errorMessage.value = 'Dátum ukončenia musí byť aspoň 30 dní po začiatku praxe.'
        return
      }
    }

    try {
      loading.value = true
      await internshipStore.createInternship(form)
      successMessage.value = 'Prax bola úspešne vytvorená!'
    } catch {
      errorMessage.value = 'Nepodarilo sa vytvoriť prax.'
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <!-- Formulár -->
  <form @submit.prevent="submit" class="space-y-6" v-if="!successMessage">
    <FormSection title="Základné informácie o praxi">
      <div class="space-y-4">
        <ExtendedSelect
          v-model="form.company_id"
          :options="companiesStore.companies"
          label="Firma*"
          @add-option="showCompanyModal = true"
        />

        <ExtendedSelect
          v-model="form.contact_person_id"
          :options="filteredContacts"
          label="Kontaktná osoba (firma)*"
          @add-option="showContactModal = true"
        />

        <!-- Rok -->
        <Input v-model.number="form.year" id="year" label="Rok*" type="number" min="2020" max="2100" />

        <!-- Semester -->
        <Select v-model="form.semester" id="semester" label="Semester*">
          <option value="Z">Zimný</option>
          <option value="L">Letný</option>
        </Select>

        <!-- Typ praxe -->
        <div class="space-y-1">
          <label for="is_paid" class="block text-sm font-medium text-gray-800">Typ praxe</label>

          <div
            :class="[
              'flex items-center justify-between rounded-md border px-4 py-3 cursor-pointer select-none transition-colors',
              form.is_paid ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-white',
            ]"
            @click="form.is_paid = !form.is_paid"
          >
            <span class="w-full text-base text-gray-700">
              Platená prax – prax vykonávaná na základe pracovnej zmluvy, dohody alebo živnosti
            </span>

            <Checkbox v-model="form.is_paid" id="is_paid" tabindex="-1" class="w-4 pointer-events-none" />
          </div>
        </div>

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
          label="Dátum začiatku praxe*"
          type="date"
          required
        />

        <!-- Koniec -->
        <Input
          :model-value="form.end_at ?? ''"
          @update:model-value="(val) => (form.end_at = val ?? '')"
          id="end_at"
          label="Dátum ukončenia"
          type="date"
          :required="false"
        />
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
        {{ loading ? 'Ukladám...' : 'Uložiť prax' }}
      </BaseButton>
    </div>
  </form>

  <!-- Úspech -->
  <div v-else class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow-sm">
    <h3 class="text-lg font-semibold text-green-700 mb-1">Prax bola úspešne vytvorená!</h3>
    <p class="text-gray-700 text-sm">Vaša prax bola uložená do systému.</p>
  </div>

  <!-- MODAL -->
  <CreateCompanyModal v-if="showCompanyModal" @close="showCompanyModal = false" @created="handleCompanyCreated" />
  <CreateContactModal
    v-if="showContactModal"
    @close="showContactModal = false"
    @created="handleContactCreated"
    :company_id="form.company_id"
  />
</template>
