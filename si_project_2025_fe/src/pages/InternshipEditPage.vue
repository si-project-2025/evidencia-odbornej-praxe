<script setup lang="ts">
  import EditForm from '@/sections/internships/EditInternshipForm.vue'
  import { ArrowLeft } from 'lucide-vue-next'
  import { useRoute } from 'vue-router'
  import { useInternshipStore } from '@/stores/internships'
  import { onMounted, ref } from 'vue'

  const route = useRoute()
  const internshipStore = useInternshipStore()
  const isLoading = ref(true)
  const errorMessage = ref('')

  onMounted(async () => {
    try {
      const id = Number(route.params.id)
      await Promise.all([
        internshipStore.fetchCompanies(),
        internshipStore.fetchGarants(),
        internshipStore.fetchStudents(),
        internshipStore.fetchInternshipDetail(id),
      ])
    } catch (e) {
      console.error('Nepodarilo sa načítať prax:', e)
      errorMessage.value = 'Nepodarilo sa načítať údaje o praxi.'
    } finally {
      isLoading.value = false
    }
  })
</script>

<template>
  <div class="container section-container mx-auto flex flex-col items-center min-h-screen">
    <div class="self-start">
      <RouterLink
        :to="`/internships/${route.params.id}`"
        class="inline-flex items-center gap-2 text-emerald-700 font-medium hover:text-emerald-800 transition-colors"
      >
        <ArrowLeft class="w-4 h-4" />
        Späť na prax
      </RouterLink>
    </div>

    <div class="w-full max-w-lg text-center mb-6 mt-8">
      <h2 class="text-2xl font-bold text-gray-800">Úprava praxe</h2>
      <p class="text-gray-500 text-sm">Aktualizujte údaje o praxi a uložte zmeny.</p>
    </div>

    <div
      class="w-full max-w-3xl bg-white border-l-4 border-r-4 border-emerald-500 rounded-3xl shadow-lg p-8 md:p-10 text-center"
    >
      <div v-if="isLoading" class="text-gray-500 text-sm py-8">Načítavam údaje o praxi...</div>
      <div v-else-if="errorMessage" class="text-red-600 text-sm py-8">
        {{ errorMessage }}
      </div>
      <EditForm v-else :internship="internshipStore.internshipDetail" />
    </div>
  </div>
</template>
