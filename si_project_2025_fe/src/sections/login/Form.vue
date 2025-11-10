<script setup lang="ts">
  import { reactive, ref } from 'vue'
  import type { LoginForm } from '@/types/form.ts'
  import axios from 'axios'
  import Input from '@/components/form/Input.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import { RouterLink } from 'vue-router'
  import { useUserStore } from '@/stores/user.ts'
  import { useRouter } from 'vue-router'

  const userStore = useUserStore()
  const router = useRouter()
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
      userStore.setUser(response.data)
      router.push('/internships')
    } catch (err: unknown) {
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
