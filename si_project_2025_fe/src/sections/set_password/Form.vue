<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Input from '@/components/form/Input.vue'
import BaseButton from '@/components/atoms/BaseButton.vue'
import { RouterLink } from 'vue-router'

const route = useRoute()
const router = useRouter()

// Premenné
const token = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

// TOTO VÁM CHÝBALO:
const isCompany = ref(false)

const successMessage = ref('')
const submitError = ref('')

const API_URL = import.meta.env.VITE_API_URL

const setPassword = async () => {
  successMessage.value = ''
  submitError.value = ''

  try {
    const response = await axios.post(`${API_URL}/api/set-password`, {
      token: token.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    })

    successMessage.value = response.data.message ?? 'Heslo bolo úspešne nastavené a účet aktivovaný.'

    // Presmerovanie
    setTimeout(() => {
      if (isCompany.value) {
        // Ak je to firma -> Login s hláškou pre firmu
        router.push('/login?message=password-set-company')
      } else {
        // Bežný user
        router.push('/login')
      }
    }, 3000)
  } catch (err: unknown) {
    submitError.value = axios.isAxiosError(err)
      ? (err.response?.data.message ?? 'Pri nastavovaní hesla nastala chyba.')
      : 'Pri nastavovaní hesla nastala chyba.'
  }
}

// Načítanie dát z URL po načítaní komponentu
onMounted(() => {
  // 1. Získame hodnoty z URL
  const tokenParam = route.query.token
  const emailParam = route.query.email
  const typeParam = route.query.type

  // 2. Priradíme ich do premenných
  token.value = (tokenParam as string) || ''
  email.value = (emailParam as string) || ''

  // TOTO SPÔSOBOVALO CHYBU (ak isCompany nebolo definované hore):
  isCompany.value = typeParam === 'company'

  // 3. Kontrola - ak chýba token alebo email, presmerujeme preč
  if (!token.value || !email.value) {
    console.warn('Chýba token alebo email v URL')
    // router.push('/login') // Odkomentujte, ak chcete okamžité presmerovanie
  }
})
</script>

<template>
  <form @submit.prevent="setPassword" class="form-container w-full md:w-1/2 2xl:w-1/3" v-if="!successMessage">

    <!-- Informácia pre firmu -->
    <div v-if="isCompany" class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-blue-700 text-sm mb-4">
      <p class="font-semibold mb-1">Registrácia firmy</p>
      <p>Po nastavení hesla sa prihláste a dokončite registráciu výberom alebo vytvorením vašej firmy.</p>
    </div>

    <!-- Email je disabled, aby ho nemenili -->
    <Input v-model="email" id="email" label="Email" type="email" :disabled="true" />
    <Input v-model="password" id="password" label="Heslo" type="password" placeholder="Minimálne 8 znakov" />
    <Input v-model="password_confirmation" id="password_confirmation" label="Potvrďte heslo" type="password" />

    <BaseButton type="submit" class="w-[80%] mt-4">Nastaviť heslo a aktivovať účet</BaseButton>
  </form>

  <!-- Úspešná správa -->
  <div class="form-container !gap-2 text-center" v-if="successMessage">
    <div class="flex flex-col gap-1 text-green-600 mb-4">
      <span class="font-semibold text-lg">{{ successMessage }}</span>
      <span class="text-sm text-gray-600">
        {{ isCompany ? 'Presmerovávame Vás na prihlásenie. Po prihlásení dokončíte registráciu firmy.' : 'Presmerovávame Vás na prihlásenie...' }}
      </span>
    </div>

    <p v-if="submitError" class="text-red-600 mt-2 whitespace-pre-line">{{ submitError }}</p>
    <RouterLink to="/login" class="font-light hover:underline text-primary">Späť na prihlásenie</RouterLink>
  </div>
</template>
