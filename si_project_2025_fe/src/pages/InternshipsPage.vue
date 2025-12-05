<script setup lang="ts">
  import InternshipList from '@/sections/internships/InternshipList.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import { Plus } from 'lucide-vue-next'
  import StatsCard from '@/components/StatsCard.vue'
  import { useInternshipStore } from '@/stores/internships.ts'
  import { useUserStore } from '@/stores/user.ts'
  import { computed, onMounted } from 'vue'

  const internshipStore = useInternshipStore()
  const userStore = useUserStore()

  onMounted(() => {
    internshipStore.fetchInternships()
  })

  const latestInternship = computed(() => {
    if (!internshipStore.internships.length) return null
    return [...internshipStore.internships].sort((a, b) => b.year - a.year)[0]
  })
</script>

<template>
  <div class="container mx-auto section-container">
    <div class="flex-col justify-start items-start min-h-screen">
      <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
          <h2 class="normal-case text-2xl text-gray-800">Moje odborné praxe</h2>
          <p class="text-gray-500 text-sm">Zoznam všetkých praxí, ktoré ste absolvovali alebo máte naplánované.</p>
        </div>
        <ActionButton href="/internships/create" v-if="userStore.user?.role === 'student'">
          <Plus class="w-4 h-4" />
          Pridať prax
        </ActionButton>
      </div>

      <div v-if="internshipStore.loading" class="text-gray-500">Načítavam...</div>
      <div v-else-if="internshipStore.error" class="text-red-600">{{ internshipStore.error }}</div>
      <div v-else-if="!internshipStore.internships.length" class="text-gray-500">Nemáte zatiaľ žiadne praxe.</div>

      <div v-else class="space-y-4">
        <!-- Štatistiky -->
        <div v-if="internshipStore.internships.length" class="w-full grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
          <!-- Počet praxí -->
          <StatsCard :statistic="internshipStore.internships.length" title="Praxí spolu" />
          <!-- Najnovšia prax -->
          <StatsCard
            v-if="latestInternship"
            :statistic="latestInternship.semester + '-' + latestInternship.year"
            title="Najnovšia prax"
          />
        </div>

        <InternshipList :internships="internshipStore.internships" />
      </div>
    </div>
  </div>
</template>
