<script setup lang="ts">
  import { ref, computed } from 'vue'
  import { useUserStore } from '@/stores/user.ts'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { FileText, Plus } from 'lucide-vue-next'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import DocumentCard from '@/components/DocumentCard.vue'
  import { useDocumentStore } from '@/stores/documents.ts'

  const userStore = useUserStore()
  const isGarant = computed(() => userStore.user?.role === 'garant')

  const fileInput = ref<HTMLInputElement | null>(null)

  const internshipStore = useInternshipStore()
  const documentStore = useDocumentStore()

  const chooseFile = () => {
    fileInput.value?.click()
  }

  const handleFileChange = async (e: Event) => {
    const target = e.target as HTMLInputElement
    if (!target.files?.length) return

    const file = target.files?.[0]
    if (!file) return

    try {
      await documentStore.uploadDocument(file)
      alert('Dokument bol nahratý')
    } catch (err) {
      console.error(err)
      alert('Chyba pri nahrávaní dokumentu')
    }
    target.value = ''
  }
</script>

<template>
  <div>
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-3">
      <!-- Nadpis -->
      <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
        <FileText class="w-5 h-5 text-emerald-600" />
        Dokumenty k praxi
      </h3>

      <!-- Tlačidlá -->
      <div class="flex flex-wrap justify-start md:justify-end gap-3">
        <ActionButton v-if="!isGarant" @click="chooseFile">
          <Plus class="w-4 h-4" />
          Pridať dokument
        </ActionButton>

        <input v-if="!isGarant" ref="fileInput" type="file" class="hidden" @change="handleFileChange" />

        <ActionButton color="green-light" @click="documentStore.generateDocument()">
          <FileText class="w-4 h-4" />
          Generovať dohodu
        </ActionButton>
      </div>
    </div>

    <template v-if="internshipStore.internshipDetail?.documents && internshipStore.internshipDetail?.documents.length">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <DocumentCard
          v-for="document in internshipStore.internshipDetail.documents"
          :key="document.document_id"
          :document="document"
        />
      </div>
    </template>

    <p v-else class="pl-7 text-gray-500 text-sm mt-2">Zatiaľ neboli pridané žiadne dokumenty.</p>
  </div>
</template>
