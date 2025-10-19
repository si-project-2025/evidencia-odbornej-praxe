<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { RouterLink } from 'vue-router'
  import Input from '@/components/form/Input.vue'
  import Button from '@/components/atoms/Button.vue'

  const email = ref('')
  const successMessage = ref('')
  const submitError = ref('')

  const submitEmail = async () => {
    successMessage.value = ''
    submitError.value = ''

    try {
      const response = await axios.post('http://localhost:8000/api/forgot-password', { email: email.value })
      successMessage.value = response.data.message ?? 'Odkaz na reset hesla bol odoslaný.'
    } catch (err: unknown) {
      submitError.value = axios.isAxiosError(err)
        ? (err.response?.data?.message ?? 'Pri odosielaní e-mailu nastala chyba.')
        : 'Pri odosielaní e-mailu nastala chyba.'
    }
  }
</script>

<template>
  <form @submit.prevent="submitEmail" class="form-container w-full md:w-1/2 2xl:w-1/3">
    <Input v-model="email" id="email" label="Email" type="email" />

    <Button type="submit" class="w-[80%]">Odoslať odkaz na reset hesla</Button>

    <p v-if="successMessage" class="text-green-600 mt-2">{{ successMessage }}</p>
    <p v-if="submitError" class="text-red-600 mt-2">{{ submitError }}</p>

    <RouterLink to="/login" class="font-light hover:underline">Späť na prihlásenie</RouterLink>
  </form>
</template>
