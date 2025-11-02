<script setup lang="ts">
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'
  import { ArrowLeft } from 'lucide-vue-next'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { onMounted } from 'vue'
  import { useRoute } from 'vue-router'

  const store = useInternshipStore()
  const route = useRoute()

  onMounted(() => {
    store.fetchInternshipDetail(Number(route.params.id))
  })
</script>

<template>
  <div class="container mx-auto section-container">
    <div class="justify-start items-start min-h-screen">
      <!-- Späť na zoznam -->
      <RouterLink
        to="/internships"
        class="inline-flex items-center gap-2 text-green-700 font-medium hover:text-green-800 transition-colors mb-2"
      >
        <ArrowLeft class="w-4 h-4" />
        Späť na zoznam praxí
      </RouterLink>

      <div v-if="store.loading" class="text-gray-500 text-center">Načítavam...</div>
      <div v-else-if="store.error" class="text-red-600 text-center">{{ store.error }}</div>
      <div v-else-if="!store.internshipDetail" class="text-gray-500 text-center">Prax sa nenašla.</div>

      <InternshipDetail v-else />
    </div>
  </div>
</template>
