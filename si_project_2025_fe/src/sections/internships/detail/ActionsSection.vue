<script setup lang="ts">
  import { Pencil, Signature, Trash2 } from 'lucide-vue-next'
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
    if (!internshipStore.internshipDetail?.contact_persons?.length) {
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
  <div class="pt-6 border-t border-gray-200 mt-10 flex flex-col sm:flex-row sm:items-center items-end sm:justify-end">
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
      <p v-if="deleteError" class="text-red-600 text-sm mt-2">{{ deleteError }}</p>

      <ActionButton :href="`/internships/${route.params.id}/edit`" v-if="isAuthenticated">
        <Pencil class="w-4 h-4" />
        Upraviť prax
      </ActionButton>
    </div>
  </div>
</template>
