<script setup lang="ts">
  import axios from 'axios'
  import { reactive, ref } from 'vue'
  import Button from '@/components/atoms/Button.vue'
  import Checkbox from '@/components/form/Checkbox.vue'
  import Input from '@/components/form/Input.vue'

  const form = reactive({
    email: '',
    password: '',
    remember: false,
  })

  const error = ref('')

  const login = async () => {
    error.value = ''

    try {
      const response = await axios.post('/login', form)
      // store token
      // redirect
      console.log(response.data)
    } catch (err: unknown) {
      if (axios.isAxiosError(err)) {
        if (err.response?.data?.message) {
          error.value = err.response.data.message
        } else {
          error.value = 'Pri prihlasovaní nastala chyba.'
        }
      } else {
        error.value = 'Pri prihlasovaní nastala chyba.'
      }
    }
  }
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">Prihlásiť sa</p>

    <form @submit.prevent="login" class="form-container w-full md:w-1/2 2xl:w-1/3">
      <Input v-model="form.email" id="email" label="Email" type="email" />
      <Input v-model="form.password" id="password" label="Heslo" type="password" />
      <Checkbox v-model="form.remember" id="remember" label="Zapamätať si ma" />

      <Button type="submit" class="w-[80%]">Prihlásiť sa</Button>

      <p v-if="error" class="text-red-600">{{ error }}</p>
      <a href="/" class="font-light hover:underline">Zabudli ste heslo?</a>
    </form>

    <div class="mt-4">
      <span class="mr-2">Nemáte účet?</span>
      <a href="/" class="font-semibold text-secondary hover:underline">Zaregistrujte sa</a>
    </div>
  </section>
</template>
