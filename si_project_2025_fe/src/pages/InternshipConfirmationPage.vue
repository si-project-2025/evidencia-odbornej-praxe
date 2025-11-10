<script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import { useInternshipStore } from '@/stores/internships.ts'

  const store = useInternshipStore()
  const route = useRoute()

  const confirmationMessage = ref<string | null>(null)
  const confirming = ref(false)

  const email = route.query.email as string
  const token = route.query.token as string

  onMounted(() => {
    store.fetchVerificationDetails(email, token)
  })

  const confirmInternship = async () => {
    if (!store.internshipDetail) return
    try {
      confirming.value = true
      const message = await store.confirmInternship(email, token)
      confirmationMessage.value = message
    } catch (err: any) {
      confirmationMessage.value = err.response?.data?.message || 'Nepodarilo sa potvrdiť prax.'
    } finally {
      confirming.value = false
    }
  }
</script>

<template>
  <div class="container mx-auto section-container">
    <div class="min-h-screen flex flex-col items-center justify-start">
      <div v-if="store.loading" class="text-gray-500 text-center mt-10">Načítavam...</div>
      <div v-else-if="store.error" class="text-red-600 text-center mt-10">{{ store.error }}</div>

      <InternshipDetail v-if="store.internshipDetail" :internship="store.internshipDetail" />

      <div v-if="store.internshipDetail && !confirmationMessage" class="mt-6">
        <BaseButton variant="primary" :disabled="confirming" @click="confirmInternship">
          {{ confirming ? 'Potvrdzovanie...' : 'Potvrdiť prax' }}
        </BaseButton>
      </div>

      <div v-if="confirmationMessage" class="mt-6 text-green-700 font-semibold">
        {{ confirmationMessage }}
      </div>
    </div>
  </div>
</template>
