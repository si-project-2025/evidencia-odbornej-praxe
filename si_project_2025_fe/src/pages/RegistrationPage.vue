<script setup lang="ts">
import Form from '@/sections/registration/Form.vue'
import { useUserStore } from '@/stores/user.ts'

const userStore = useUserStore()
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">{{ userStore.user ? 'Pridať garanta' : 'Registrovať sa' }}</p>
    <Form />
    <div v-if="!userStore.user" class="mt-4 text-center space-y-2">
      <div>
        <span class="mr-2">Už máte účet?</span>
        <RouterLink to="/login" class="font-semibold text-secondary hover:underline">
          Prihláste sa
        </RouterLink>
      </div>
      <div class="pt-2 border-t border-gray-200">
        <span class="mr-2">Ste firma?</span>
        <RouterLink to="/register-company" class="font-semibold text-secondary hover:underline">
          Zaregistrujte sa ako firma
        </RouterLink>
      </div>
    </div>
  </section>
</template>
