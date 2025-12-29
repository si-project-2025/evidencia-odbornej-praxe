<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'
import Input from '@/components/form/Input.vue'
import BaseButton from '@/components/atoms/BaseButton.vue'

const API_URL = import.meta.env.VITE_API_URL

const form = reactive({
  email: ''
})

const submitError = ref('')
const registrationSuccess = ref(false)
const loading = ref(false)

const submit = async () => {
  submitError.value = ''

  if (!form.email) {
    submitError.value = 'Email je povinný.'
    return
  }

  try {
    loading.value = true
    await axios.post(`${API_URL}/api/register-company-email`, form)
    registrationSuccess.value = true
  } catch (err: unknown) {
    submitError.value = axios.isAxiosError(err)
      ? (err.response?.data?.message ?? 'Registrácia zlyhala.')
      : 'Nastala neočakávaná chyba.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <form @submit.prevent="submit" class="form-container w-full md:w-[90%]" v-if="!registrationSuccess">
    <div class="flex flex-col w-full gap-6">
      <Input
        v-model="form.email"
        id="email"
        label="Email*"
        type="email"
        placeholder="firma@example.com"
        required
      />
    </div>

    <div class="mt-6 w-full flex flex-col items-center gap-2">
      <BaseButton type="submit" class="w-[80%]" :disabled="loading">
        {{ loading ? 'Odosielam...' : 'Zaregistrovať sa' }}
      </BaseButton>

      <p v-if="submitError" class="text-red-600 text-sm">
        {{ submitError }}
      </p>
    </div>
  </form>

  <div class="form-container" v-else>
    <div class="text-center p-6 bg-green-100 text-secondary rounded-2xl">
      <h3 class="text-lg font-semibold mb-2">Registrácia úspešná!</h3>
      <p>
        Na email <strong>{{ form.email }}</strong> sme vám poslali odkaz na nastavenie hesla.
      </p>
      <p class="mt-2 text-sm">Skontrolujte si email a dokončite registráciu.</p>
    </div>
  </div>
</template>
