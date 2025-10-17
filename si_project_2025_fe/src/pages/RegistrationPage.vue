<script setup lang="ts">
  import { ref } from 'vue'
  import type { Role } from '@/types/common.ts'
  import Form from '@/sections/registration/Form.vue'
  import RoleSelector from '@/sections/registration/RoleSelector.vue'

  const role = ref<Role>('student')
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">Registrovať sa</p>

    <RoleSelector v-model="role" />

    <Form :role="role" />

    <div class="mt-4">
      <span class="mr-2">Už máte účet?</span>
      <a href="/login" class="font-semibold text-secondary hover:underline">Prihláste sa</a>
    </div>
  </section>
</template>
