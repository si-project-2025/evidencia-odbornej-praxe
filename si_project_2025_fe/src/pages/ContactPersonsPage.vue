<script setup lang="ts">
  import { computed, ref } from 'vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import ContactPersonsList from '@/components/ContactPersonsList.vue'
  import CreateContactPersonModal from '@/components/modals/CreateContactModal.vue'
  import { useUserStore } from '@/stores/user'

  const userStore = useUserStore()
  const showCreateModal = ref(false)
  const companyId = computed(() => userStore.user?.company?.company_id)
</script>

<template>
  <div class="px-4 md:px-15 py-8">
    <div class="bg-white rounded-3xl shadow-md border-t-4 border-emerald-500 p-6 md:p-10 space-y-6">
      <div class="flex justify-between items-start gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold">Kontaktné osoby</h1>
          <p class="text-gray-500 text-sm">Pridávajte a spravujte kontaktné osoby vašej firmy.</p>
        </div>
        <BaseButton variant="primary" @click="showCreateModal = true">Pridať</BaseButton>
      </div>

      <ContactPersonsList />
    </div>
    <CreateContactPersonModal
      v-if="showCreateModal && companyId"
      :company_id="companyId"
      @close="showCreateModal = false"
      @created="showCreateModal = false"
    />
  </div>
</template>
