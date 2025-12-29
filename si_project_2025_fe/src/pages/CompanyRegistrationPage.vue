<script setup lang="ts">
import EmailForm from '@/sections/company_registration/EmailForm.vue'
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">Registrovať firmu</p>
    <EmailForm />
    <div class="mt-4">
      <span class="mr-2">Už máte účet?</span>
      <RouterLink to="/login" class="font-semibold text-secondary hover:underline">
        Prihláste sa
      </RouterLink>
    </div>
  </section>
</template>
