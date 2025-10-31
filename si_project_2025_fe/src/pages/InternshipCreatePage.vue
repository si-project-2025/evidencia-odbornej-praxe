<script setup lang="ts">
  import CreateForm from '@/sections/student_internships/CreateInternshipForm.vue'
  import { ArrowLeft } from 'lucide-vue-next'
</script>

<template>
  <div class="flex justify-center items-start min-h-screen bg-gray-50 px-4 py-10">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-lg p-8 md:p-10">
      <RouterLink
        to="/internships"
        class="inline-flex items-center gap-2 text-emerald-700 font-medium hover:text-emerald-800 transition-colors mb-4"
      >
        <ArrowLeft class="w-4 h-4" />
        Späť na zoznam praxí
      </RouterLink>

      <h2 class="text-2xl font-bold text-gray-800 mb-2">Vytvorenie novej praxe</h2>
      <p class="text-gray-500 text-sm mb-6">Vyplňte údaje o novej odbornej praxi a odošlite formulár.</p>

      <CreateForm />
    </div>
  </div>
</template>
