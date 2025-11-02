<script setup lang="ts">
  defineProps<{
    statistic: string | number
    title: string
  }>()
</script>

<template>
  <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 text-center shadow-sm">
    <p class="text-3xl font-bold text-emerald-700">
      {{ statistic }}
    </p>

    <p class="text-gray-600 text-sm font-medium">
      {{ title }}
    </p>
  </div>
</template>
