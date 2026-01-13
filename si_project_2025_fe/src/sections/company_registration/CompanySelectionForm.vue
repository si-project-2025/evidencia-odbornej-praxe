<script setup lang="ts">
  import { computed, onMounted, reactive, ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import FormSection from '@/components/form/FormSection.vue'
  import ExtendedSelect from '@/components/form/ExtendedSelect.vue'
  import CreateCompanyModal from '@/components/modals/CreateCompanyModal.vue'
  import { useCompaniesStore } from '@/stores/companies'
  import { useUserStore } from '@/stores/user.ts'

  const companiesStore = useCompaniesStore()
  const router = useRouter()
  const API_URL = import.meta.env.VITE_API_URL

  const showCompanyModal = ref(false)
  const selectedCompanyId = ref(0)
  const showCompanyDetails = ref(false)

  onMounted(async () => {
    companiesStore.onlyAvailable = true
    await companiesStore.fetchCompanies()
  })

  const selectedCompany = computed(() => {
    if (!selectedCompanyId.value) return null
    return companiesStore.companies.find((c) => c.id === selectedCompanyId.value)
  })

  const form = reactive({
    company_id: 0,
  })

  const submitError = ref('')
  const loading = ref(false)
  const completionSuccess = ref(false)

  const handleCompanySelected = () => {
    if (selectedCompanyId.value) {
      form.company_id = selectedCompanyId.value
      showCompanyDetails.value = true
      submitError.value = ''
    }
  }

  const handleCompanyCreated = (newCompanyId: number) => {
    selectedCompanyId.value = newCompanyId
    form.company_id = newCompanyId
    showCompanyModal.value = false
    showCompanyDetails.value = true
    submitError.value = ''
  }

  const submit = async () => {
    submitError.value = ''

    if (!form.company_id) {
      submitError.value = 'Vyberte firmu alebo vytvorte novú.'
      return
    }

    try {
      loading.value = true
      const response = await axios.post(`${API_URL}/api/complete-company-registration`, form)

      useUserStore().updateUser(response.data.user)
      completionSuccess.value = true

      setTimeout(() => {
        router.push('/internships')
      }, 2000)
    } catch (err: unknown) {
      submitError.value = axios.isAxiosError(err)
        ? (err.response?.data?.message ?? 'Dokončenie registrácie zlyhalo.')
        : 'Nastala neočakávaná chyba.'
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <form @submit.prevent="submit" class="form-container w-full md:w-[90%]" v-if="!completionSuccess">
    <div class="flex flex-col w-full gap-6">
      <FormSection title="Výber firmy">
        <div class="space-y-4">
          <ExtendedSelect
            v-model="selectedCompanyId"
            :options="companiesStore.companies"
            label="Vyberte vašu firmu*"
            @add-option="showCompanyModal = true"
            @update:model-value="handleCompanySelected"
          />

          <div
            v-if="showCompanyDetails && selectedCompany"
            class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-100"
          >
            <h3 class="font-semibold text-lg">{{ selectedCompany.name }}</h3>
            <p class="text-sm text-gray-600">IČO: {{ selectedCompany.ico }}</p>
            <div v-if="selectedCompany.address" class="text-sm text-gray-600">
              <p>{{ selectedCompany.address.street }} {{ selectedCompany.address.house_number }}</p>
              <p>{{ selectedCompany.address.zip_code }} {{ selectedCompany.address.city }}</p>
              <p>{{ selectedCompany.address.country }}</p>
            </div>
          </div>
        </div>
      </FormSection>
    </div>

    <div class="mt-6 w-full flex flex-col items-center gap-2">
      <BaseButton type="submit" class="w-[80%]" :disabled="loading || !selectedCompanyId">
        {{ loading ? 'Spracovávam...' : 'Dokončiť registráciu' }}
      </BaseButton>

      <p v-if="submitError" class="text-red-600 text-sm">
        {{ submitError }}
      </p>
    </div>
  </form>

  <div class="form-container" v-else>
    <div class="text-center p-6 bg-green-100 text-secondary rounded-2xl">
      <h3 class="text-lg font-semibold mb-2">Registrácia firmy úspešne dokončená!</h3>
      <p>Vaše údaje boli uložené.</p>
      <p class="mt-2 text-sm">Za chvíľu budete presmerovaný na dashboard...</p>
    </div>
  </div>

  <CreateCompanyModal v-if="showCompanyModal" @close="showCompanyModal = false" @created="handleCompanyCreated" />
</template>
