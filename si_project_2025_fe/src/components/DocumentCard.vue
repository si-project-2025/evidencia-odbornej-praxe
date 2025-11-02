<script setup lang="ts">
  import { FileText } from 'lucide-vue-next'
  import type { Document } from '@/types/internship'

  defineProps<{
    document: Document
  }>()
</script>

<template>
  <div
    class="flex items-center justify-between border border-emerald-100 rounded-xl bg-emerald-50/40 p-4 shadow-sm hover:shadow-md hover:bg-emerald-50"
  >
    <!-- info -->
    <div class="flex items-center gap-3 overflow-hidden">
      <FileText class="h-6 text-emerald-600" />
      <div class="flex flex-col overflow-hidden">
        <span class="font-semibold text-gray-800 truncate">{{ document.file_name }}</span>
        <span class="text-xs text-gray-500 truncate">{{ document.type }}</span>
        <span class="text-xs text-gray-400">
          {{ new Date(document.created_at).toLocaleDateString('sk-SK') }}
        </span>
      </div>
    </div>

    <!-- stav dokumetu-->
    <span
      v-if="document.is_verified"
      class="ml-2 text-xs font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full whitespace-nowrap"
    >
      Overený
    </span>
    <span v-else class="ml-2 text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
      Neoverený
    </span>
  </div>
</template>
