<script setup lang="ts">
import { reactive, ref } from 'vue'
import type { LoginForm } from '@/types/form.ts'
import axios from 'axios'
import Input from '@/components/form/Input.vue'
import BaseButton from '@/components/atoms/BaseButton.vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user.ts'

const userStore = useUserStore()
const router = useRouter()
const route = useRoute()
const API_URL = import.meta.env.VITE_API_URL

const form = reactive<LoginForm>({
  email: '',
  password: '',
  remember: false,
})

const submitError = ref('')

const login = async () => {
  submitError.value = ''

  try {
    const response = await axios.post(`${API_URL}/api/login`, form)

    // 1. Uložíme dáta do store
    userStore.setUser(response.data)

    // 2. Získame aktuálneho užívateľa pre kontrolu
    const user = userStore.user

    // 3. LOGIKA PRESMEROVANIA PRE FIRMY
    // ZMENA: Kontrolujeme 'firma' namiesto 'company' (podľa vašej databázy)
    // A kontrolujeme, či user nemá priradenú company (je null alebo prázdna)
    if (user?.role === 'firma' && !user?.company) {
      console.log('Firma bez profilu -> presmerujem na registráciu firmy')
      await router.push('/complete-company-registration')
      return // Ukončíme funkciu tu
    }

    // 4. Štandardné presmerovanie pre ostatných (študent, garant, alebo firma s profilom)
    const redirectTo = route.query.redirect as string | undefined

    if (redirectTo) {
      router.push(redirectTo)
    } else {
      router.push('/internships')
    }
  } catch (err: unknown) {
    console.error(err) // Pre istotu vypíšeme chybu do konzoly
    submitError.value = axios.isAxiosError(err)
      ? (err.response?.data?.message ?? 'Pri prihlasovaní nastala chyba.')
      : 'Pri prihlasovaní nastala chyba.'
  }
}
</script>

<template>
  <form @submit.prevent="login" class="form-container w-full md:w-1/2 2xl:w-1/3">
    <Input v-model="form.email" id="email" label="Email" type="email" />
    <Input v-model="form.password" id="password" label="Heslo" type="password" />
    <!--<Checkbox v-model="form.remember" id="remember" label="Zapamätať si ma" />-->

    <BaseButton type="submit" class="w-[80%]">Prihlásiť sa</BaseButton>

    <p v-if="submitError" class="text-red-600">{{ submitError }}</p>
    <RouterLink to="/forgot-password" class="font-light hover:underline">Zabudli ste heslo?</RouterLink>
  </form>
</template>
