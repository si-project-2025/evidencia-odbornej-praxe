<script setup lang="ts">
  import Form from '@/sections/registration/Form.vue'
  import { useUserStore } from '@/stores/user.ts'

  const userStore = useUserStore()
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">{{ userStore.user ? 'Pridať garanta' : 'Registrovať sa' }}</p>
    <Form />
    <div v-if="!userStore.user" class="mt-4">
      <span class="mr-2">Už máte účet?</span>
      <RouterLink to="/login" class="font-semibold text-secondary hover:underline">Prihláste sa</RouterLink>
    </div>
  </section>
</template>
