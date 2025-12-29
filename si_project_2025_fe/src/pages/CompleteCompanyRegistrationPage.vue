<script setup lang="ts">
import CompanySelectionForm from '@/sections/company_registration/CompanySelectionForm.vue'
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">Dokončte registráciu</p>
    <p class="text-gray-600 text-center max-w-md">
      Vyberte vašu firmu zo zoznamu alebo vytvorte novú.
    </p>
    <CompanySelectionForm />
  </section>
</template>
