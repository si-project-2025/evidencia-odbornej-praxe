<script setup lang="ts">
  import axios from 'axios'
  import { onMounted } from 'vue'
  import Logo from '@/assets/images/home-main.svg'
  import Button from '@/components/atoms/Button.vue'

  onMounted(async () => {
    const response = await axios.get('http://127.0.0.1:8000/api/posts')
    console.log(response.data)
  })
</script>

<template>
  <section class="section-container h-full flex flex-col lg:flex-row justify-between gap-12">
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

      <Button class="lg:w-1/3" href="/login">Prihlásiť sa</Button>
    </div>

    <img :src="Logo" alt="Logo" class="lg:w-[40%]" />
  </section>
</template>
