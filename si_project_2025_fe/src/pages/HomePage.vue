<script setup lang="ts">
  import Logo from '@/assets/images/home-main.svg'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import { NotebookPen, Building, ChartLine, Laptop, Zap, Folder } from 'lucide-vue-next'
  import { useUserStore } from '@/stores/user.ts'
  const userStore = useUserStore()
</script>

<template>
  <section class="container section-container mx-auto flex flex-col lg:flex-row justify-between gap-20">
    <div class="flex flex-col gap-8">
      <div>
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

      <div>
        <BaseButton v-if="!userStore.user" class="lg:w-1/3" href="/login">Prihlásiť sa</BaseButton>
        <BaseButton v-else class="lg:w-1/3" href="/internships">Moje odborné praxe</BaseButton>
      </div>
    </div>

    <img :src="Logo" alt="Logo" class="lg:w-[60%] w-full max-w-md mx-auto" />
  </section>

  <div
    class="mx-20 px-10 mb-10 bg-white border-t-4 border-emerald-500 rounded-3xl shadow-md hover:shadow-lg transition-all duration-200 backdrop-blur-sm"
  >
    <section
      class="px-5 py-5 my-10 bg-white border-t-4 border-emerald-50 rounded-3xl shadow-md hover:shadow-lg transition-all duration-200 backdrop-blur-sm"
    >
      <div class="container mx-auto text-center mt-10">
        <h2 class="mb-12">Ako to funguje</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="card">
            <NotebookPen class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-xl mb-2">Študent pridá prax</h3>
            <p>Jednoducho zadaj informácie o svojej praxi a odošli ich na schválenie.</p>
          </div>
          <div class="card">
            <Building class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-xl mb-2">Firma potvrdí priebeh</h3>
            <p>Firma schváli priebeh praxe online bez potreby tlačených dokumentov.</p>
          </div>
          <div class="card">
            <ChartLine class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-xl mb-2">Škola sleduje priebeh</h3>
            <p>Škola má prístup k záznamom a štatistikám.</p>
          </div>
        </div>
      </div>
    </section>

    <section
      class="px-5 py-5 my-10 bg-white border-t-4 border-emerald-50 rounded-3xl shadow-md hover:shadow-lg transition-all duration-200 backdrop-blur-sm"
    >
      <div class="container mx-auto text-center">
        <h2 class="text-2xl mb-12">Prečo používať evidenciu praxe</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card">
            <Laptop class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-lg mb-2">Jednoduché rozhranie</h3>
            <p>Používateľsky prívetivé pre študentov aj firmy.</p>
          </div>
          <div class="card">
            <Zap class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-lg mb-2">Rýchly záznam</h3>
            <p>Zadaj prax za pár sekúnd a maj všetko na jednom mieste.</p>
          </div>
          <div class="card">
            <Folder class="w-12 h-12 mb-4" />
            <h3 class="font-semibold text-lg mb-2">Prehľadné údaje</h3>
            <p>Všetky praxe, potvrdenia a stavy na jednom paneli.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
