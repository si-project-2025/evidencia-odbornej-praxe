<script setup lang="ts">
  import { FileText, Trash2, Download, Signature } from 'lucide-vue-next'
  import type { Document } from '@/types/internship'
  import { useDocumentStore } from '@/stores/documents.ts'
  import { useUserStore } from '@/stores/user.ts'
  import { computed } from 'vue'

  const props = defineProps<{
    document: Document
    email?: string
    token?: string
  }>()

  const userStore = useUserStore()
  const documentStore = useDocumentStore()

  const showVerify = computed(() => {
    return (
      !props.document.is_verified &&
      ((userStore.user?.role === 'garant' && props.document.type === 'Zmluva') ||
        (!userStore.user && props.document.type === 'Výkaz'))
    )
  })

  const downloadFile = async () => {
    if (userStore.user) {
      await documentStore.downloadDocument(props.document)
      return
    }

    if (!props.email || !props.token) {
      return
    }

    await documentStore.downloadDocument(props.document, {
      email: props.email,
      token: props.token,
    })
  }

  const verifyFile = async () => {
    if (!confirm('Naozaj chcete potvrdiť tento dokument?')) return

    if (userStore.user) {
      await documentStore.verifyDocument(props.document.document_id)
      return
    }

    if (!props.email || !props.token) {
      return
    }

    await documentStore.verifyDocument(props.document.document_id, {
      email: props.email,
      token: props.token,
    })
  }

  const deleteFile = async () => {
    if (!confirm('Naozaj chcete zmazať tento dokument?')) return

    await documentStore.deleteDocument(props.document.document_id)
  }
</script>

<template>
  <div
    class="flex items-center justify-between border border-emerald-100 rounded-xl bg-emerald-50/40 p-4 shadow-sm hover:shadow-md transition cursor-pointer"
  >
    <!-- info -->
    <div class="flex items-center gap-3 overflow-hidden">
      <FileText class="h-6 text-emerald-600" />
      <div class="flex flex-col overflow-hidden">
        <span class="font-semibold text-gray-800 truncate">
          {{ document.file_name?.split('/').pop()?.replace(/^\d+_/, '') ?? '' }}
        </span>

        <span class="text-xs text-gray-500 truncate">{{ document.type }}</span>
        <span class="text-xs text-gray-400">
          {{ new Date(document.created_at).toLocaleDateString('sk-SK') }}
        </span>
      </div>
    </div>

    <!-- actions -->
    <div class="flex items-center gap-3 ml-3">
      <button
        v-if="showVerify"
        @click.stop="verifyFile"
        class="p-2 rounded-lg transition text-blue-400 hover:text-blue-600 hover:bg-blue-100"
        title="Overiť dokument"
      >
        <Signature class="h-5 w-5" />
      </button>

      <!-- stiahnuť -->
      <button
        @click.stop="downloadFile"
        class="p-2 rounded-lg transition text-emerald-600 hover:text-emerald-800 hover:bg-emerald-100"
        title="Stiahnuť dokument"
      >
        <Download class="h-5 w-5" />
      </button>

      <!-- vymazať -->
      <button
        v-if="userStore.user?.role === 'student' && !document.is_verified"
        @click.stop="deleteFile"
        class="p-2 rounded-lg transition text-red-600 hover:text-red-800 hover:bg-red-100"
        title="Vymazať dokument"
      >
        <Trash2 class="h-5 w-5" />
      </button>

      <!-- stav -->
      <span
        v-if="document.is_verified"
        class="text-xs font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full whitespace-nowrap"
      >
        Overený
      </span>
      <span v-else class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
        Neoverený
      </span>
    </div>
  </div>
</template>
