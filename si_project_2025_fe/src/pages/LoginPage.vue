<script setup lang="ts">
  import Form from '@/sections/login/Form.vue'
</script>

<template>
  <section class="section-container flex flex-col justify-center items-center gap-4">
    <p class="font-semibold text-4xl mb-2">Prihlásiť sa</p>

    <Form />

    <div class="mt-4">
      <span class="mr-2">Nemáte účet?</span>
      <a href="/registration" class="font-semibold text-secondary hover:underline">
        Zaregistrujte sa
      </a>
    </div>
  </section>
</template>
