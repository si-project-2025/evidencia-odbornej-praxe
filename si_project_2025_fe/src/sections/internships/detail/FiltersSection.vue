<script setup lang="ts">
  import { ref, computed, watch, onMounted } from 'vue'
  import Select from '@/components/form/Select.vue'
  import Input from '@/components/form/Input.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import Export from '@/components/Export.vue'
  import { RotateCcw } from 'lucide-vue-next'
  import { useCompaniesStore } from '@/stores/companies'
  import { useLookupStore } from '@/stores/lookup'
  import { useStatusStore } from '@/stores/statuses'
  import type { Internship } from '@/types/internship'

  const props = defineProps<{
    internships: Internship[]
    role: string
  }>()

  const filtersOpen = ref(false)

  const emit = defineEmits<{
    (e: 'update', filtered: Internship[]): void
  }>()

  const companiesStore = useCompaniesStore()
  const lookupStore = useLookupStore()
  const statusStore = useStatusStore()

  onMounted(async () => {
    await companiesStore.fetchCompanies()
    await lookupStore.fetchStudents()
    await statusStore.fetchStatuses()
  })

  const searchName = ref('')
  const searchCompany = ref('')
  const selectedSemester = ref('')
  const selectedYear = ref('')
  const selectedStatus = ref('')
  const selectedIsPaid = ref('')

  const filteredInternships = computed(() => {
    return props.internships.filter((internship) => {
      const matchName =
        !searchName.value ||
        `${internship.student?.name} ${internship.student?.surname}`
          .toLowerCase()
          .includes(searchName.value.toLowerCase())
      const matchCompany =
        !searchCompany.value || internship.company?.name.toLowerCase().includes(searchCompany.value.toLowerCase())
      const matchSemester = !selectedSemester.value || internship.semester === selectedSemester.value
      const matchYear = !selectedYear.value || internship.year === Number(selectedYear.value)
      const matchIsPaid =
        !selectedIsPaid.value ||
        (selectedIsPaid.value === 'paid' && internship.is_paid) ||
        (selectedIsPaid.value === 'unpaid' && !internship.is_paid)
      const matchStatus = !selectedStatus.value || internship.status === selectedStatus.value
      return matchName && matchCompany && matchSemester && matchYear && matchStatus && matchIsPaid
    })
  })

  watch(filteredInternships, (val) => emit('update', val), { immediate: true })
  const resetFilters = () => {
    searchName.value = ''
    searchCompany.value = ''
    selectedSemester.value = ''
    selectedYear.value = ''
    selectedIsPaid.value = ''
    selectedStatus.value = ''
  }
</script>

<template>
  <div v-if="role === 'garant'" class="md:hidden px-4">
    <ActionButton class="w-full justify-center" @click="filtersOpen = !filtersOpen">
      {{ filtersOpen ? 'Skryť filtre' : 'Zobraziť filtre' }}
    </ActionButton>
  </div>

  <!--Filtre-->
  <div
    v-if="role === 'garant'"
    :class="[
      'bg-gray-50 p-4 rounded-xl shadow-sm border border-gray-200',
      filtersOpen ? 'block md:block' : 'hidden md:block',
    ]"
  >
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
      <!-- Typ praxe -->
      <Select v-model="selectedIsPaid" class="mt-2">
        <option value="" class="text-gray-400">Všetky typy praxe</option>
        <option value="paid" class="text-gray-900">Platená</option>
        <option value="unpaid" class="text-gray-900">Neplatená</option>
      </Select>
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
</template>
