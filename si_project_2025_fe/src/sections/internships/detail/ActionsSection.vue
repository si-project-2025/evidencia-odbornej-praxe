<script setup lang="ts">
  import { Check, Pencil, Signature, Trash2, X } from 'lucide-vue-next'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import { useUserStore } from '@/stores/user.ts'
  import { computed, ref } from 'vue'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { useRoute, useRouter } from 'vue-router'

  const userStore = useUserStore()
  const internshipStore = useInternshipStore()

  const route = useRoute()
  const router = useRouter()

  const sending = ref(false)
  const sendError = ref('')
  const deleting = ref(false)
  const deleteError = ref('')

  const isAuthenticated = userStore.user
  const isStudent = computed(() => userStore.user?.role === 'student')
  const isGarant = computed(() => userStore.user?.role === 'garant')

  const approving = ref(false)
  const rejecting = ref(false)

  const approveError = ref('')
  const rejectError = ref('')

  const handleGarantAction = async (action: 'approve' | 'reject') => {
    const isApprove = action === 'approve'

    if (!confirm(`Naozaj chcete ${isApprove ? 'schváliť' : 'zamietnuť'} túto prax?`)) {
      return
    }

    try {
      if (isApprove) {
        approving.value = true
        approveError.value = ''
        await internshipStore.approveInternship(Number(route.params.id))
        alert('Prax bola úspešne schválená.')
      } else {
        rejecting.value = true
        rejectError.value = ''
        await internshipStore.rejectInternship(Number(route.params.id))
        alert('Prax bola zamietnutá.')
      }
    } catch (error) {
      const message =
        error instanceof Error
          ? error.message
          : isApprove
            ? 'Nepodarilo sa schváliť prax.'
            : 'Nepodarilo sa zamietnuť prax.'

      if (isApprove) approveError.value = message
      else rejectError.value = message
    } finally {
      if (isApprove) approving.value = false
      else rejecting.value = false
    }
  }

  const deleteInternship = async () => {
    if (!confirm('Naozaj chcete túto prax zmazať?')) return

    try {
      deleting.value = true
      deleteError.value = ''

      await internshipStore.deleteInternship(Number(route.params.id))

      alert('Prax bola úspešne zmazaná.')
      router.push('/internships')
    } catch {
      deleteError.value = 'Nepodarilo sa zmazať prax.'
    } finally {
      deleting.value = false
    }
  }

  // odoslanie na verifikáciu firme
  const sendToCompany = async () => {
    // overiť firmou
    if (!internshipStore.internshipDetail?.contact_person) {
      alert('Pre túto prax nie je zadaná žiadna kontaktná osoba.')
      return
    }

    if (!confirm('Odoslať email na overenie praxe kontaktným osobám?')) return

    try {
      sending.value = true
      sendError.value = ''

      await internshipStore.sendVerificationEmail(Number(route.params.id))

      alert('Email na overenie bol úspešne odoslaný.')
    } catch {
      sendError.value = 'Nepodarilo sa poslať overovací email.'
    } finally {
      sending.value = false
    }
  }
</script>

<template>
  <div
    class="pt-6 border-t border-gray-200 mt-10 flex flex-col sm:flex-row sm:items-center items-end sm:justify-center"
  >
    <div class="flex flex-row gap-2">
      <ActionButton
        v-if="isStudent && internshipStore.internshipDetail?.status == 'Vytvorená'"
        color="yellow"
        @click="sendToCompany"
      >
        <Signature class="w-4 h-4" />
        Overiť firmou
      </ActionButton>

      <ActionButton
        v-if="isAuthenticated && internshipStore.internshipDetail?.status === 'Vytvorená'"
        color="red"
        @click="deleteInternship"
        :disabled="deleting"
      >
        <Trash2 class="w-4 h-4" />
        {{ deleting ? 'Mazanie...' : 'Zmazať prax' }}
      </ActionButton>

      <ActionButton :href="`/internships/${route.params.id}/edit`" v-if="isAuthenticated">
        <Pencil class="w-4 h-4" />
        Upraviť prax
      </ActionButton>

      <ActionButton
        v-if="isGarant && internshipStore.internshipDetail?.status === 'Potvrdená'"
        variant="primary"
        @click="handleGarantAction('approve')"
        :disabled="approving"
      >
        <Check class="w-4 h-4" />
        {{ approving ? 'Schvaľujem...' : 'Schváliť prax' }}
      </ActionButton>

      <ActionButton
        v-if="isGarant && internshipStore.internshipDetail?.status === 'Potvrdená'"
        color="red"
        @click="handleGarantAction('reject')"
        :disabled="rejecting"
      >
        <X class="w-4 h-4" />
        {{ rejecting ? 'Zamietam...' : 'Neschváliť prax' }}
      </ActionButton>
    </div>
  </div>
  <div class="mt-10 flex flex-col sm:flex-row sm:items-center items-end sm:justify-center">
    <p v-if="deleteError" class="text-red-600 text-sm mt-2">{{ deleteError }}</p>
    <p v-if="approveError" class="text-red-600 text-sm mt-2">{{ approveError }}</p>
    <p v-if="rejectError" class="text-red-600 text-sm mt-2">{{ rejectError }}</p>
  </div>
</template>
