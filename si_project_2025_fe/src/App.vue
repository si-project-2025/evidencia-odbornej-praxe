<script setup lang="ts">
  import DefaultLayout from '@/layouts/DefaultLayout.vue'
</script>

<template>
  <router-view v-slot="{ Component }">
    <DefaultLayout>
      <component :is="Component" />
    </DefaultLayout>
  </router-view>
</template>
