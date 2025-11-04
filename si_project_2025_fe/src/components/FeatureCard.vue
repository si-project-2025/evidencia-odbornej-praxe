<script setup lang="ts">
  import type { Component } from 'vue'

  defineProps<{
    icon: Component
    title: string
    description: string
  }>()
</script>

<template>
  <div class="card p-4 text-center bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
    <component :is="icon" class="w-12 h-12 mb-4 mx-auto text-emerald-500" />

    <h3 class="font-semibold text-xl mb-2">{{ title }}</h3>

    <p>{{ description }}</p>
  </div>
</template>
