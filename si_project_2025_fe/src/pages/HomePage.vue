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
      </div>
      <p>
        Informatika je dnes nevyhnutnou súčasťou bežného života. Prídete s ňou do kontaktu v rôznych
        podobách avšak prakticky v každom odbore. Aplikovaná informatika svojim obsahom pokrýva
        široké spektrum aplikácií súvisiacich so získavaním, vyhľadávaním, prenosom, zhromažďovaním,
        organizáciou, ukladaním, interpretáciou, prezentovaním a využívaním informácií a znalostí v
        rôznych podobách.
      </p>
      <p>
        Informatika je dnes nevyhnutnou súčasťou bežného života. Prídete s ňou do kontaktu v rôznych
        podobách avšak prakticky v každom odbore. Aplikovaná informatika svojim obsahom pokrýva
        široké spektrum aplikácií súvisiacich so získavaním, vyhľadávaním, prenosom, zhromažďovaním,
        organizáciou, ukladaním, interpretáciou, prezentovaním a využívaním informácií a znalostí v
        rôznych podobách.
      </p>
      <p>
        Informatika je dnes nevyhnutnou súčasťou bežného života. Prídete s ňou do kontaktu v rôznych
        podobách avšak prakticky v každom odbore. Aplikovaná informatika svojim obsahom pokrýva
        široké spektrum aplikácií súvisiacich so získavaním, vyhľadávaním, prenosom, zhromažďovaním,
        organizáciou, ukladaním, interpretáciou, prezentovaním a využívaním informácií a znalostí v
        rôznych podobách.
      </p>
      <Button class="lg:w-1/3" href="/">Pridať prax</Button>
    </div>

    <img :src="Logo" alt="Logo" class="lg:w-[40%]" />
  </section>
</template>
