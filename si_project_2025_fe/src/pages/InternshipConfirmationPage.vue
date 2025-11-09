<script setup lang="ts">
  import { useInternshipStore } from '@/stores/internships.ts'
  import { useRoute } from 'vue-router'
  import { onMounted } from 'vue'
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'

  const store = useInternshipStore()
  const route = useRoute()

  onMounted(() => {
    const token = route.query.token as string
    const email = route.query.email as string

    store.fetchVerificationDetails(email, token)
  })
</script>

<template>
  <div class="container mx-auto section-container">
    <div class="justify-start items-start min-h-screen">
      <div v-if="store.loading" class="text-gray-500 text-center">Načítavam...</div>
      <div v-else-if="store.error" class="text-red-600 text-center">{{ store.error }}</div>
      <div v-else-if="!store.internshipDetail" class="text-gray-500 text-center">Prax sa nenašla.</div>

      <InternshipDetail v-else />
    </div>
  </div>
</template>
