<script setup lang="ts">
  import Logo from '@/assets/images/home-main.svg'
  import Button from '@/components/atoms/Button.vue'
  import { useUserStore } from '@/stores/user.ts'

  const userStore = useUserStore()
</script>

<template>
  <section class="container section-container mx-auto h-full flex flex-col lg:flex-row justify-between gap-12">
    <div class="flex flex-col gap-6">
      <div class="space-y-1.5">
        <h1>Evidencia</h1>
        <h1>odbornej praxe</h1>
        <h2>Prehľadne a jednoducho</h2>
      </div>

      <p>
        Moderný systém na evidenciu odbornej praxe pre študentov a firmy. Umožňuje rýchlo zaznamenať priebeh praxe a mať
        všetko dôležité na jednom mieste.
      </p>

      <p>
        Sleduj pridané praxe, nahrávaj potvrdenia, kontroluj ich schválenia a udržuj si prehľad. Všetko pohodlne online,
        bez zbytočných papierov.
      </p>

      <Button v-if="!userStore.user" class="lg:w-1/3" href="/login">Prihlásiť sa</Button>
      <Button v-else class="lg:w-1/3" href="/internships">Moje odborné praxe</Button>
    </div>

    <img :src="Logo" alt="Logo" class="lg:w-[35%]" />
  </section>
</template>
