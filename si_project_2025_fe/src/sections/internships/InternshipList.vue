<script setup lang="ts">
  import { computed, ref, onMounted } from 'vue'
  import { useUserStore } from '@/stores/user'
  import { useStatusStore } from '@/stores/statuses'
  import InternshipCard from '@/components/InternshipCard.vue'
  import type { Internship } from '@/types/internship.ts'
  import Input from '@/components/form/Input.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import Select from '@/components/form/Select.vue'
  import { RotateCcw } from 'lucide-vue-next'
  import Export from '@/components/Export.vue'
  import { useCompaniesStore } from '@/stores/companies.ts'
  import { useLookupStore } from '@/stores/lookup.ts'

  const props = defineProps<{
    internships: Internship[]
  }>()

  const userStore = useUserStore()
  const role = computed(() => (userStore.user?.role === 'garant' ? 'garant' : 'student'))

  const companiesStore = useCompaniesStore()
  const lookupStore = useLookupStore()
  const statusStore = useStatusStore()

  onMounted(async () => {
    await companiesStore.fetchCompanies()
    await lookupStore.fetchStudents()
    await statusStore.fetchStatuses()
  })

  //filtre
  const searchName = ref('')
  const searchCompany = ref('')
  const selectedSemester = ref('')
  const selectedYear = ref('')
  const selectedStatus = ref('')

  //filtrované praxe
  const filteredInternships = computed(() => {
    return props.internships.filter((internship) => {
      // Študent
      const matchName =
        !searchName.value ||
        `${internship.student?.name} ${internship.student?.surname}`
          .toLowerCase()
          .includes(searchName.value.toLowerCase())
      // Firma
      const matchCompany =
        !searchCompany.value || internship.company?.name.toLowerCase().includes(searchCompany.value.toLowerCase())
      // Semester
      const matchSemester = !selectedSemester.value || internship.semester === selectedSemester.value
      // Rok
      const matchYear = !selectedYear.value || internship.year === Number(selectedYear.value)
      // Stav
      const matchStatus = !selectedStatus.value || internship.status === selectedStatus.value

      return matchName && matchCompany && matchSemester && matchYear && matchStatus
    })
  })
  const resetFilters = () => {
    searchName.value = ''
    searchCompany.value = ''
    selectedSemester.value = ''
    selectedYear.value = ''
    selectedStatus.value = ''
  }
</script>

<template>
  <div class="space-y-4">
    <!--Filtre-->
    <div v-if="role === 'garant'" class="bg-gray-50 p-4 rounded-xl shadow-sm border border-gray-200">
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Firma -->
        <Select v-model="searchCompany" class="mt-2">
          <option value="" class="text-gray-400">Všetky firmy</option>
          <option
            v-for="company in companiesStore.companies"
            :key="company.company_id"
            :value="company.name"
            class="text-gray-900"
          >
            {{ company.name }}
          </option>
        </Select>
        <!-- Študent -->
        <Select v-model="searchName" class="mt-2">
          <option value="" class="text-gray-400">Všetci študenti</option>
          <option
            v-for="student in lookupStore.students"
            :key="student.users_id"
            :value="`${student.name} ${student.surname}`"
            class="text-gray-900"
          >
            {{ student.name }} {{ student.surname }}
          </option>
        </Select>
        <!-- Semester -->
        <Select v-model="selectedSemester" class="mt-2">
          <option value="" class="text-gray-400">Všetky semestre</option>
          <option value="Z" class="text-gray-900">Zimný</option>
          <option value="L" class="text-gray-900">Letný</option>
        </Select>
        <!-- Rok -->
        <Input v-model="selectedYear" type="number" placeholder="Rok" class="mt-2" />
        <!-- Stav -->
        <Select v-model="selectedStatus" class="mt-2">
          <option value="" class="text-gray-400">Všetky stavy</option>
          <option v-for="status in statusStore.statuses" :key="status" :value="status" class="text-gray-900">
            {{ status }}
          </option>
        </Select>

        <!-- Reset filtrov -->

        <div class="flex flex-row gap-2 items-center">
          <ActionButton @click="resetFilters">
            <RotateCcw class="w-5" />
            Reset
          </ActionButton>

          <Export :internships="filteredInternships" />
        </div>
      </div>
    </div>

    <div
      :class="[
        'hidden md:grid gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200',
        role === 'garant' ? 'grid-cols-16' : 'grid-cols-13',
      ]"
    >
      <div class="col-span-3">Firma</div>
      <div v-if="role === 'garant'" class="col-span-3">Študent</div>
      <div class="col-span-2">Semester</div>
      <div class="col-span-2">Rok</div>
      <div class="col-span-2">Hodiny</div>
      <div class="col-span-2">Koniec praxe</div>
      <div class="col-span-2 text-center">Stav</div>
    </div>

    <InternshipCard
      v-for="internship in filteredInternships"
      :key="internship.internships_id"
      :internship="internship"
      :role="role"
    ></InternshipCard>
  </div>
</template>
