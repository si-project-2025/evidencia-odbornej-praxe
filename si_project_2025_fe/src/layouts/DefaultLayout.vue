<script setup lang="ts">
  import Navigation from '@/layouts/Navigation.vue'
  import Footer from '@/layouts/Footer.vue'
</script>

<template>
  <div class="min-h-screen pt-4 sm:pt-6 flex grow flex-col">
    <Navigation />

    <main class="flex-1 flex flex-col gap-6 w-full justify-center">
      <slot />
    </main>

    <Footer />
  </div>
</template>
