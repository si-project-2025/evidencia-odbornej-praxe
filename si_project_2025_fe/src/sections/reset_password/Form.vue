<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'
  import Input from '@/components/form/Input.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import { RouterLink } from 'vue-router'

  const route = useRoute()
  const router = useRouter()
  const token = ref('')

  const email = ref('')
  const password = ref('')
  const password_confirmation = ref('')
  const successMessage = ref('')
  const submitError = ref('')

  // Načíta sa token a email z URL
  onMounted(() => {
    token.value = route.query.token as string
    email.value = route.query.email as string

    if (!token.value || !email.value) {
      router.push('/login')
    }
  })

  const resetPassword = async () => {
    successMessage.value = ''
    submitError.value = ''

    try {
      const response = await axios.post('http://localhost:8000/api/reset-password', {
        token: token.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
      })
      successMessage.value = response.data.message ?? 'Heslo bolo úspešne zmenené.'
    } catch (err: unknown) {
      if (axios.isAxiosError(err)) {
        const data = err.response?.data

        if (data?.errors) {
          if (Array.isArray(data.errors)) {
            submitError.value = data.errors.join('\n')
          } else if (typeof data.errors === 'object') {
            submitError.value = Object.values(data.errors).flat().join('\n')
          } else {
            submitError.value = String(data.errors)
          }
        } else {
          submitError.value = data?.message ?? 'Pri zmene hesla nastala chyba.'
        }
      } else {
        submitError.value = 'Pri zmene hesla nastala chyba.'
      }
    }
  }
</script>

<template>
  <form @submit.prevent="resetPassword" class="form-container w-full md:w-1/2 2xl:w-1/3">
    <template v-if="!successMessage">
      <Input v-model="password" id="password" label="Nové heslo" type="password" />
      <Input v-model="password_confirmation" id="password_confirmation" label="Potvrďte heslo" type="password" />

      <BaseButton type="submit" class="w-[80%]">Zmeniť heslo</BaseButton>
    </template>
    <p v-if="successMessage" class="text-green-600 mt-2">{{ successMessage }}</p>
    <p v-if="submitError" class="text-red-600 mt-2 whitespace-pre-line">{{ submitError }}</p>

    <RouterLink to="/login" class="font-light hover:underline">Späť na prihlásenie</RouterLink>
  </form>
</template>
