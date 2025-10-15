<script setup lang="ts">
  import Button from '@/components/atoms/Button.vue'
  import Logo from '@/assets/images/logo-fpv.png'
</script>

<template>
  <nav class="mx-10 my-3 flex justify-between items-center rounded-xl">
    <div class="flex flex-row items-center gap-2">
      <img :src="Logo" alt="Logo" class="size-13" />
      <div class="font-semibold text-xl">Evidencia odbornej praxe</div>
    </div>

    <div class="flex flex-row gap-2">
      <Button variant="secondary">Registrovať</Button>
      <Button variant="primary">Prihlásiť sa</Button>
    </div>
  </nav>
</template>
