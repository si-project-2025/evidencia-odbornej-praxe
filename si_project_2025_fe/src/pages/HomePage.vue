<script setup lang="ts">
  import Logo from '@/assets/images/home-main.svg'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import FeatureCard from '@/components/FeatureCard.vue'
  import { NotebookPen, Building, ChartLine, Laptop, Zap, Folder } from 'lucide-vue-next'
  import { useUserStore } from '@/stores/user.ts'
  const userStore = useUserStore()
</script>

<template>
  <section class="container section-container mx-auto flex flex-col lg:flex-row justify-between gap-8">
    <div class="flex flex-col gap-8">
      <div>
        <h1>Evidencia</h1>
        <h1>odbornej praxe</h1>
        <h2 class="mt-2 font-semibold text-dark">Prehľadne a jednoducho</h2>
      </div>

      <div class="flex flex-col gap-6 text-lg md:pr-25">
        <p>
          Moderný systém na evidenciu odbornej praxe pre študentov a firmy. Umožňuje rýchlo zaznamenať priebeh praxe a
          mať všetko dôležité na jednom mieste.
        </p>

        <p>
          Sleduj pridané praxe, nahrávaj potvrdenia, kontroluj ich schválenia a udržuj si prehľad. Všetko pohodlne
          online, bez zbytočných papierov.
        </p>

        <div>
          <BaseButton v-if="!userStore.user" class="lg:w-1/3" href="/login">Prihlásiť sa</BaseButton>
          <BaseButton v-else class="lg:w-1/3" href="/internships">Moje odborné praxe</BaseButton>
        </div>
      </div>
    </div>

    <img :src="Logo" alt="Logo" class="lg:w-[40%] w-full max-w-md mx-auto" />
  </section>

  <div class="container mx-auto md:px-10">
    <div class="mb-15 text-center">
      <h2 class="mb-12 text-2xl">Ako to funguje</h2>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-0">
        <FeatureCard
          :icon="NotebookPen"
          title="Študent pridá prax"
          description="Jednoducho zadaj informácie o svojej praxi a odošli ich na schválenie."
        />
        <FeatureCard
          :icon="Building"
          title="Firma potvrdí priebeh"
          description="Firma schváli priebeh praxe online bez potreby tlačených dokumentov."
        />
        <FeatureCard
          :icon="ChartLine"
          title="Škola sleduje priebeh"
          description="Škola má prístup k záznamom a štatistikám."
        />
      </div>
    </div>

    <div class="mb-20">
      <h2 class="text-2xl text-center mb-12">Prečo používať evidenciu praxe</h2>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-0">
        <FeatureCard
          :icon="Laptop"
          title="Jednoduché rozhranie"
          description="Používateľsky prívetivé pre študentov aj firmy."
        />
        <FeatureCard
          :icon="Zap"
          title="Rýchly záznam"
          description="Zadaj prax za pár sekúnd a maj všetko na jednom mieste."
        />
        <FeatureCard
          :icon="Folder"
          title="Prehľadné údaje"
          description="Všetky praxe, potvrdenia a stavy na jednom paneli."
        />
      </div>
    </div>
  </div>
</template>
