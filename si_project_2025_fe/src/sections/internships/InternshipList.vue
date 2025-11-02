<script setup lang="ts">
  import InternshipCard from '@/components/InternshipCard.vue'
  import type { Internship } from '@/types/internship.ts'

  defineProps<{
    internships: Internship[]
  }>()
</script>

<template>
  <div class="space-y-4">
    <div
      class="hidden md:grid grid-cols-13 gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200"
    >
      <div class="col-span-3">Firma</div>
      <div class="col-span-2">Semester</div>
      <div class="col-span-2">Rok</div>
      <div class="col-span-2">Hodiny</div>
      <div class="col-span-2">Koniec praxe</div>
      <div class="col-span-2 text-center">Stav</div>
    </div>

    <InternshipCard
      v-for="internship in internships"
      :key="internship.internships_id"
      :internship="internship"
    ></InternshipCard>
  </div>
</template>
