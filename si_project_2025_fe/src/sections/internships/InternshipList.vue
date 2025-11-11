<script setup lang="ts">
  import { computed, ref, onMounted } from 'vue'
  import { useUserStore } from '@/stores/user'
  import { useInternshipStore } from '@/stores/internships'
  import InternshipCard from '@/components/InternshipCard.vue'
  import type { Internship } from '@/types/internship.ts'
  import Input from '@/components/form/Input.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import Select from '@/components/form/Select.vue'
  import { RotateCcw } from 'lucide-vue-next'

  const props = defineProps<{
    internships: Internship[]
  }>()

  const userStore = useUserStore()
  const role = computed(() => (userStore.user?.role === 'garant' ? 'garant' : 'student'))

  const internshipStore = useInternshipStore()

  onMounted(async () => {
    await internshipStore.fetchCompanies()
    await internshipStore.fetchStudents()
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
      <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <!-- Firma -->
        <Select v-model="searchCompany" class="mt-2">
          <option value="">Všetky firmy</option>
          <option v-for="company in internshipStore.companies" :key="company.company_id" :value="company.name">
            {{ company.name }}
          </option>
        </Select>
        <!-- Študent -->
        <Select v-model="searchName" class="mt-2">
          <option value="">Všetci študenti</option>
          <option
            v-for="student in internshipStore.students"
            :key="student.users_id"
            :value="`${student.name} ${student.surname}`"
          >
            {{ student.name }} {{ student.surname }}
          </option>
        </Select>
        <!-- Semester -->
        <Select v-model="selectedSemester" class="mt-2">
          <option value="">Všetky semestre</option>
          <option value="Z">Zimný</option>
          <option value="L">Letný</option>
        </Select>
        <!-- Rok -->
        <Input v-model="selectedYear" type="number" placeholder="Rok" class="mt-2" />
        <!-- Stav -->
        <Select v-model="selectedStatus" class="mt-2">
          <option value="">Všetky stavy</option>
          <option value="Vytvorená">Vytvorená</option>
          <option value="Potvrdená">Potvrdená</option>
          <option value="Zamietnutá">Zamietnutá</option>
          <option value="Schválená">Schválená</option>
          <option value="Obhájená">Obhájená</option>
        </Select>

        <!-- Reset filtrov -->

        <div class="flex items-center">
          <ActionButton @click="resetFilters">
            <RotateCcw class="w-5" />
            Vymazať filtre
          </ActionButton>
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
