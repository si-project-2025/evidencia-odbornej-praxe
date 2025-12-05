<script setup lang="ts">
  import { reactive, ref } from 'vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import Input from '@/components/form/Input.vue'
  import { useCompaniesStore } from '@/stores/companies'

  const emit = defineEmits(['close', 'created'])
  const companiesStore = useCompaniesStore()

  const loading = ref(false)
  const errorMessage = ref('')

  const form = reactive({
    name: '',
    ico: '',
    address: {
      street: '',
      house_number: '',
      city: '',
      zip_code: '',
      country: '',
    },
  })

  const submit = async () => {
    errorMessage.value = ''
    loading.value = true

    try {
      const newCompanyId = await companiesStore.createCompany(form)
      emit('created', newCompanyId)
    } catch (e: unknown) {
      console.error('Error pri vytváraní firmy:', e)

      if (e instanceof Error) {
        errorMessage.value = e.message
        return
      }

      errorMessage.value = 'Nepodarilo sa vytvoriť firmu.'
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg shadow-xl space-y-4">
      <h2 class="text-lg font-semibold">Pridať novú firmu</h2>

      <Input v-model="form.name" label="Názov firmy*" />
      <Input v-model="form.ico" label="IČO*" />

      <Input v-model="form.address.country" label="Krajina*" />
      <Input v-model="form.address.city" label="Mesto*" />
      <Input v-model="form.address.zip_code" label="PSČ*" />
      <Input v-model="form.address.street" label="Ulica*" />
      <Input v-model="form.address.house_number" label="Číslo domu*" />

      <p v-if="errorMessage" class="text-red-600 text-sm">{{ errorMessage }}</p>

      <div class="flex justify-end gap-2">
        <BaseButton @click="emit('close')" type="button">Zrušiť</BaseButton>
        <BaseButton @click="submit" :disabled="loading">
          {{ loading ? 'Ukladám...' : 'Vytvoriť firmu' }}
        </BaseButton>
      </div>
    </div>
  </div>
</template>
