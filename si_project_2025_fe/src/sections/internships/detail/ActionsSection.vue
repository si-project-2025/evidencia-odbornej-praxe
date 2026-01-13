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

  const updating = ref(false)
  const updateError = ref('')

  const internship = computed(() => internshipStore.internshipDetail)
  const isStudent = computed(() => userStore.user?.role === 'student')
  const isGarant = computed(() => userStore.user?.role === 'garant')
  const isCompany = computed(() => userStore.user?.role === 'firma')

  const handleGarantAction = async (action: 'approve' | 'reject') => {
    const isApprove = action === 'approve'

    if (!confirm(`Naozaj chcete ${isApprove ? 'schváliť' : 'neschváliť'} túto prax?`)) {
      return
    }

    try {
      updating.value = true
      await internshipStore.verifyInternship(Number(route.params.id), isApprove)
      alert(isApprove ? 'Prax bola úspešne schválená.' : 'Prax bola zamietnutá.')
    } catch (error) {
      updateError.value =
        error instanceof Error
          ? error.message
          : isApprove
            ? 'Nepodarilo sa schváliť prax.'
            : 'Nepodarilo sa neschváliť prax.'
    } finally {
      updating.value = false
    }
  }

  const handleCompanyAction = async (action: 'confirm' | 'reject') => {
    const isConfirm = action === 'confirm'

    if (!confirm(`Naozaj chcete ${isConfirm ? 'potvrdiť' : 'zamietnuť'} túto prax?`)) {
      return
    }

    try {
      updating.value = true
      await internshipStore.verifyInternship(Number(route.params.id), isConfirm)
      alert(isConfirm ? 'Prax bola úspešne potvrdená.' : 'Prax bola zamietnutá.')
    } catch (error) {
      updateError.value =
        error instanceof Error
          ? error.message
          : isConfirm
            ? 'Nepodarilo sa potvrdiť prax.'
            : 'Nepodarilo sa zamietnuť prax.'
    } finally {
      updating.value = false
    }
  }

  const deleteInternship = async () => {
    if (!confirm('Naozaj chcete túto prax zmazať?')) return

    try {
      updating.value = true
      updateError.value = ''

      await internshipStore.deleteInternship(Number(route.params.id))

      alert('Prax bola úspešne zmazaná.')
      router.push('/internships')
    } catch {
      updateError.value = 'Nepodarilo sa zmazať prax.'
    } finally {
      updating.value = false
    }
  }

  // odoslanie na verifikáciu firme
  const sendToCompany = async () => {
    // overiť firmou
    if (!internship.value?.contact_person) {
      alert('Pre túto prax nie je zadaná žiadna kontaktná osoba.')
      return
    }

    if (!confirm('Odoslať email na overenie praxe kontaktným osobám?')) return

    try {
      updating.value = true
      updateError.value = ''

      await internshipStore.sendVerificationEmail(Number(route.params.id))

      alert('Email na overenie bol úspešne odoslaný.')
    } catch {
      updateError.value = 'Nepodarilo sa poslať overovací email.'
    } finally {
      updating.value = false
    }
  }
</script>

<template>
  <div
    class="pt-6 border-t border-gray-200 mt-10 flex flex-col gap-2 sm:flex-row items-end"
    :class="
      internship?.is_paid || isStudent || (isGarant && internship?.status !== 'Potvrdená')
        ? 'sm:justify-end'
        : 'sm:justify-between'
    "
  >
    <div class="flex flex-row gap-2" v-if="isGarant && internship?.status === 'Potvrdená' && !internship?.is_paid">
      <ActionButton variant="primary" @click="handleGarantAction('approve')" :disabled="updating">
        <Check class="w-4 h-4" />
        Schváliť prax
      </ActionButton>

      <ActionButton color="red" @click="handleGarantAction('reject')" :disabled="updating">
        <X class="w-4 h-4" />
        Neschváliť prax
      </ActionButton>
    </div>

    <div v-if="isCompany && internship?.status === 'Vytvorená' && !internship?.is_paid" class="flex flex-row gap-3">
      <ActionButton variant="primary" @click="handleCompanyAction('confirm')" :disabled="updating">
        <Check class="w-4 h-4" />
        Potvrdiť prax
      </ActionButton>
      <ActionButton color="red" @click="handleCompanyAction('reject')" :disabled="updating">
        <X class="w-4 h-4" />
        Zamietnuť prax
      </ActionButton>
    </div>

    <div class="flex flex-row gap-2" v-if="isStudent || isGarant">
      <ActionButton
        v-if="isStudent && internship?.status == 'Vytvorená' && !internship.is_paid"
        color="yellow"
        @click="sendToCompany"
      >
        <Signature class="w-4 h-4" />
        Overiť firmou
      </ActionButton>

      <ActionButton :href="`/internships/${route.params.id}/edit`" :disabled="updating">
        <Pencil class="w-4 h-4" />
        Upraviť prax
      </ActionButton>

      <ActionButton color="red" @click="deleteInternship" :disabled="updating">
        <Trash2 class="w-4 h-4" />
        Zmazať prax
      </ActionButton>
    </div>
  </div>

  <div class="mt-2 flex flex-col sm:flex-row sm:items-center items-end sm:justify-center">
    <p v-if="updateError" class="text-red-600 text-sm mt-2">{{ updateError }}</p>
  </div>
</template>
