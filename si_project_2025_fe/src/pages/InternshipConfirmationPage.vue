<script setup lang="ts">
  import { onMounted, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import InternshipDetail from '@/sections/internships/InternshipDetail.vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { Trash2 } from 'lucide-vue-next'

  const store = useInternshipStore()
  const route = useRoute()

  const confirmationMessage = ref<string | null>(null)
  const confirming = ref(false)
  const rejecting = ref(false)
  const rejectError = ref('')

  const email = route.query.email as string
  const token = route.query.token as string

  onMounted(() => {
    store.fetchVerificationDetails(email, token)
  })

  const confirmInternship = async () => {
    if (!store.internshipDetail) return
    try {
      confirming.value = true
      confirmationMessage.value = await store.confirmInternship(email, token)
    } catch {
      confirmationMessage.value = 'Nepodarilo sa potvrdiť prax.'
    } finally {
      confirming.value = false
    }
  }

  const rejectInternship = async () => {
    if (!confirm('Naozaj chcete zamietnuť túto prax?')) return

    try {
      rejecting.value = true
      rejectError.value = ''

      confirmationMessage.value = await store.rejectInternship(email, token)
    } catch {
      rejectError.value = 'Nepodarilo sa zamietnuť prax.'
    } finally {
      rejecting.value = false
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
        <!-- Confirm -->
        <BaseButton variant="primary" :disabled="confirming" @click="confirmInternship">
          {{ confirming ? 'Potvrdzovanie...' : 'Potvrdiť prax' }}
        </BaseButton>

        <!-- Reject -->
        <ActionButton color="red" @click="rejectInternship" :disabled="rejecting">
          <Trash2 class="w-4 h-4" />
          {{ rejecting ? 'Zamietanie...' : 'Zamietnuť prax' }}
        </ActionButton>

        <p v-if="rejectError" class="text-red-600 text-sm mt-2">
          {{ rejectError }}
        </p>
      </div>

      <div v-if="confirmationMessage" class="mt-6 text-green-700 font-semibold text-center">
        {{ confirmationMessage }}
      </div>
    </div>
  </div>
</template>
