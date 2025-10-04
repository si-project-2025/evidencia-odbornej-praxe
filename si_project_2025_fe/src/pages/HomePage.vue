<script setup lang="ts">
  import axios from 'axios'
  import { onMounted } from 'vue'
  import Logo from '@/assets/images/home-main.svg'

  onMounted(async () => {
    const response = await axios.get('http://127.0.0.1:8000/api/posts')
    console.log(response.data)
  })
</script>

<template>
  <section class="section-container h-full flex flex-col items-center justify-center gap-12">
    <div class="flex flex-col gap-1 text-center">
      <h1>Evidencia odbornej praxe</h1>
      <p>Univerzita Konštantína Filozofa v Nitre</p>
    </div>
    <img :src="Logo" alt="Logo" class="w-1/2" />
  </section>
</template>
