<script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import { useInternshipStore } from '@/stores/internships.ts'

  const store = useInternshipStore()
  const route = useRoute()

  const confirmationMessage = ref<string | null>(null)

  const email = route.query.email as string
  const token = route.query.token as string

  onMounted(() => {
    store.fetchVerificationDetails(email, token)
  })

  /**
   * Jedna funkcia na potvrdenie alebo zamietnutie praxe
   */
  const handleInternshipAction = async (action: 'confirm' | 'reject') => {
    if (action === 'reject' && !confirm('Naozaj chcete zamietnuť túto prax?')) return

    try {
      confirmationMessage.value = await store.handleInternshipAction(email, token, action)
    } catch {
      confirmationMessage.value =
        action === 'confirm' ? 'Nepodarilo sa potvrdiť prax.' : 'Nepodarilo sa zamietnuť prax.'
    }
  }
</script>

<template>
  <div class="container mx-auto section-container">
    <div class="min-h-screen flex flex-col items-center justify-start">
      <div v-if="store.loading" class="text-gray-500 text-center mt-10">Načítavam...</div>

      <div v-else-if="store.error" class="text-red-600 text-center mt-10">
        {{ store.error }}
      </div>

      <InternshipDetail v-if="store.internshipDetail" :internship="store.internshipDetail" />

      <div v-if="store.internshipDetail && !confirmationMessage" class="mt-6 flex flex-col gap-3">
        <BaseButton variant="primary" @click="handleInternshipAction('confirm')">Potvrdiť prax</BaseButton>

        <ActionButton color="red" @click="handleInternshipAction('reject')">Zamietnuť prax</ActionButton>
      </div>

      <div v-if="confirmationMessage" class="mt-6 text-green-700 font-semibold text-center">
        {{ confirmationMessage }}
      </div>
    </div>
  </div>
</template>
