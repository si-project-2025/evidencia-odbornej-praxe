<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Input from '@/components/form/Input.vue'
import Button from '@/components/atoms/Button.vue'
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

const setPassword = async () => {
  successMessage.value = ''
  submitError.value = ''

  try {
    const response = await axios.post('http://localhost:8000/api/set-password', {
      token: token.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    })
    successMessage.value = response.data.message ?? 'Heslo bolo úspešne nastavené a účet aktivovaný.'

    // Presmerovanie na login po 3 sekundách
    setTimeout(() => {
      router.push('/login')
    }, 3000)
  } catch (err: unknown) {
    if (axios.isAxiosError(err)) {
      // Ak backend poslal pole chýb
      if (err.response?.data?.errors) {
        submitError.value = err.response.data.errors.join('\n')
      } else {
        submitError.value = err.response?.data?.message ?? 'Pri nastavení hesla nastala chyba.'
      }
    } else {
      submitError.value = 'Pri nastavení hesla nastala chyba.'
    }
  }
}
</script>

<template>
  <form @submit.prevent="setPassword" class="form-container w-full md:w-1/2 2xl:w-1/3">
    <template v-if="!successMessage">
      <Input v-model="email" id="email" label="Email" type="email" :disabled="true" />
      <Input v-model="password" id="password" label="Heslo" type="password" placeholder="Minimálne 8 znakov" />
      <Input v-model="password_confirmation" id="password_confirmation" label="Potvrďte heslo" type="password" />

      <Button type="submit" class="w-[80%]">Nastaviť heslo a aktivovať účet</Button>
    </template>

    <p v-if="successMessage" class="text-green-600 mt-2">
      {{ successMessage }}
      <br />
      <span class="text-sm">Presmerovávame vás na prihlásenie...</span>
    </p>
    <p v-if="submitError" class="text-red-600 mt-2 whitespace-pre-line">{{ submitError }}</p>

    <RouterLink to="/login" class="font-light hover:underline">Späť na prihlásenie</RouterLink>
  </form>
</template>
