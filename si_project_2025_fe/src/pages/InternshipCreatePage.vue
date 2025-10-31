<script setup lang="ts">
  import CreateForm from '@/sections/student_internships/CreateInternshipForm.vue'
  import { ArrowLeft } from 'lucide-vue-next'
</script>

<template>
  <div class="flex flex-col items-center min-h-screen bg-gray-50 px-4 py-10 relative">
    <div class="absolute top-6 left-10 md:left-15">
      <RouterLink
        to="/internships"
        class="inline-flex items-center gap-2 text-emerald-700 font-medium hover:text-emerald-800 transition-colors"
      >
        <ArrowLeft class="w-4 h-4" />
        Späť na zoznam praxí
      </RouterLink>
    </div>

    <div class="w-full max-w-lg text-center mb-6 mt-8">
      <h2 class="text-2xl font-bold text-gray-800">Vytvorenie novej praxe</h2>
      <p class="text-gray-500 text-sm">Vyplňte údaje o novej odbornej praxi a odošlite formulár.</p>
    </div>

    <div class="w-full max-w-lg bg-white border-l-4 border-r-4 border-emerald-500 rounded-3xl shadow-lg p-8 md:p-10">
      <CreateForm />
    </div>
  </div>
</template>
