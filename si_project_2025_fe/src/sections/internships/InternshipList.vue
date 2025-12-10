<script setup lang="ts">
  import { computed, ref } from 'vue'
  import { useUserStore } from '@/stores/user'
  import InternshipCard from '@/components/InternshipCard.vue'
  import type { Internship } from '@/types/internship.ts'
  import FiltersSection from '@/sections/internships/detail/FiltersSection.vue'
  import SortSection from '@/sections/internships/detail/SortingSection.vue'

  const props = defineProps<{
    internships: Internship[]
  }>()

  const userStore = useUserStore()
  const role = computed(() => (userStore.user?.role === 'garant' ? 'garant' : 'student'))

  const filteredFromFilters = ref<Internship[]>([...props.internships])
  const updateFiltered = (list: Internship[]) => {
    filteredFromFilters.value = list
  }

  const sortedList = ref<Internship[]>([])
  const updateSorted = (list: Internship[]) => {
    sortedList.value = list
  }

  const finalList = computed(() => sortedList.value)
</script>

<template>
  <div class="space-y-4">
    <FiltersSection :internships="props.internships" :role="role" @update="updateFiltered" />

    <SortSection :internships="filteredFromFilters" :role="role" @update="updateSorted" />

    <InternshipCard v-for="i in finalList" :key="i.internships_id" :internship="i" :role="role"></InternshipCard>
  </div>
</template>
