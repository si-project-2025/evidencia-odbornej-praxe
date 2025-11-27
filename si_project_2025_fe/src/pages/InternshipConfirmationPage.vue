<script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { Check, X } from 'lucide-vue-next'

  const store = useInternshipStore()
  const route = useRoute()

  const confirmationMessage = ref<string | null>(null)

  const email = route.query.email as string
  const token = route.query.token as string

  onMounted(() => {
    store.fetchVerificationDetails(email, token)
  })

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

      <div v-if="store.internshipDetail && !confirmationMessage" class="flex flex-row gap-3">
        <ActionButton variant="primary" @click="handleInternshipAction('confirm')">
          <Check class="w-4 h-4" />
          Potvrdiť prax
        </ActionButton>
        <ActionButton color="red" @click="handleInternshipAction('reject')">
          <X class="w-4 h-4" />
          Zamietnuť prax
        </ActionButton>
      </div>

      <div v-if="confirmationMessage" class="mt-6 text-green-700 font-semibold text-center">
        {{ confirmationMessage }}
      </div>
    </div>
  </div>
</template>
