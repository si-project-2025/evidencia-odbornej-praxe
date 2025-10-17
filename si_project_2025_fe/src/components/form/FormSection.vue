<script setup lang="ts">
  defineProps<{ title?: string }>()
</script>

<template>
  <section class="w-full space-y-4">
    <h3 v-if="title" class="font-semibold mb-2">{{ title }}</h3>
    <slot />
  </section>
</template>
