<script setup lang="ts">
  import Button from '@/components/atoms/Button.vue'
  import Logo from '@/assets/images/logo-fpv.png'
</script>

<template>
  <nav
    class="sticky top-0 z-50 bg-white px-10 2xl:px-16 py-3 flex flex-col md:flex-row justify-between items-center"
  >
    <a href="/" class="flex flex-row items-center gap-2 mb-4 md:mb-0">
      <img :src="Logo" alt="Logo" class="size-13" />
      <div class="font-semibold text-xl">Evidencia odbornej praxe</div>
    </a>

    <div class="flex flex-col md:flex-row gap-4 w-full md:w-fit">
      <Button variant="secondary" href="/registration">Registrovať</Button>
      <Button variant="primary" href="/login">Prihlásiť sa</Button>
    </div>
  </nav>
</template>
