<script setup lang="ts">
  import { computed, ref, onMounted } from 'vue'
  import { useUserStore } from '@/stores/user'
  import { useStatusStore } from '@/stores/statuses'
  import InternshipCard from '@/components/InternshipCard.vue'
  import type { Internship } from '@/types/internship.ts'
  import Input from '@/components/form/Input.vue'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import Select from '@/components/form/Select.vue'
  import { RotateCcw, ArrowUp, ArrowDown } from 'lucide-vue-next'
  import Export from '@/components/Export.vue'
  import { useCompaniesStore } from '@/stores/companies.ts'
  import { useLookupStore } from '@/stores/lookup.ts'

  const props = defineProps<{
    internships: Internship[]
  }>()

  const filtersOpen = ref(false)

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

  type SortableField = 'company' | 'student' | 'semester' | 'year' | 'start_at' | 'end_at' | 'status'

  const sortExtractors: Record<SortableField, (i: Internship) => string | number | null> = {
    company: (i) => i.company?.name ?? '',
    student: (i) => `${i.student?.name ?? ''} ${i.student?.surname ?? ''}`.trim(),
    semester: (i) => i.semester,
    year: (i) => i.year,
    start_at: (i) => i.start_at,
    end_at: (i) => i.end_at,
    status: (i) => i.status,
  }
  const sortField = ref<SortableField | null>(null)
  const sortDirection = ref<'asc' | 'desc' | null>(null)

  const toggleSort = (field: SortableField) => {
    if (sortField.value === field) {
      if (sortDirection.value === 'desc') {
        sortDirection.value = 'asc'
      } else if (sortDirection.value === 'asc') {
        sortField.value = null
        sortDirection.value = null
      }
      return
    }

    sortField.value = field
    sortDirection.value = 'desc'
  }

  //filtre
  const searchName = ref('')
  const searchCompany = ref('')
  const selectedSemester = ref('')
  const selectedYear = ref('')
  const selectedStatus = ref('')

  //filtrované praxe
  const filteredInternships = computed(() => {
    const list = props.internships.filter((internship) => {
      const matchName =
        !searchName.value ||
        `${internship.student?.name} ${internship.student?.surname}`
          .toLowerCase()
          .includes(searchName.value.toLowerCase())
      const matchCompany =
        !searchCompany.value || internship.company?.name.toLowerCase().includes(searchCompany.value.toLowerCase())
      const matchSemester = !selectedSemester.value || internship.semester === selectedSemester.value
      const matchYear = !selectedYear.value || internship.year === Number(selectedYear.value)
      const matchStatus = !selectedStatus.value || internship.status === selectedStatus.value

      return matchName && matchCompany && matchSemester && matchYear && matchStatus
    })

    //Triedenie
    if (!sortField.value || !sortDirection.value) {
      return list
    }

    const field = sortField.value as SortableField

    return [...list].sort((a, b) => {
      const fa = sortExtractors[field](a)
      const fb = sortExtractors[field](b)

      if (fa == null) return 1
      if (fb == null) return -1
      // stringy
      if (typeof fa === 'string' && typeof fb === 'string') {
        return sortDirection.value === 'asc' ? fa.localeCompare(fb) : fb.localeCompare(fa)
      }
      // čísla
      if (typeof fa === 'number' && typeof fb === 'number') {
        return sortDirection.value === 'asc' ? fa - fb : fb - fa
      }

      return 0
    })
  })

  const resetFilters = () => {
    searchName.value = ''
    searchCompany.value = ''
    selectedSemester.value = ''
    selectedYear.value = ''
    selectedStatus.value = ''
  }

  const toggleDirection = () => {
    if (!sortField.value) return

    if (sortDirection.value === 'asc') {
      sortDirection.value = 'desc'
    } else {
      sortDirection.value = 'asc'
    }
  }
</script>

<template>
  <div class="space-y-4">
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

    <!--Triedenie-->

    <div
      :class="[
        'hidden md:grid gap-2 px-8 py-3 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200',
        role === 'garant' ? 'grid-cols-16' : 'grid-cols-13',
      ]"
    >
      <div
        class="col-span-3 cursor-pointer flex items-center justify-start"
        @click="toggleSort('company')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'company',
          'text-gray-500': sortField !== 'company',
        }"
      >
        Firma
        <span v-if="sortField === 'company'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        v-if="role === 'garant'"
        class="col-span-3 cursor-pointer flex items-center justify-start"
        @click="toggleSort('student')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'student',
          'text-gray-500': sortField !== 'student',
        }"
      >
        Študent
        <span v-if="sortField === 'student'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        class="col-span-2 cursor-pointer flex items-center justify-start"
        @click="toggleSort('semester')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'semester',
          'text-gray-500': sortField !== 'semester',
        }"
      >
        Semester
        <span v-if="sortField === 'semester'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        class="col-span-2 cursor-pointer flex items-center justify-start"
        @click="toggleSort('year')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'year',
          'text-gray-500': sortField !== 'year',
        }"
      >
        Rok
        <span v-if="sortField === 'year'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        class="col-span-2 cursor-pointer flex items-center justify-start"
        @click="toggleSort('start_at')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'start_at',
          'text-gray-500': sortField !== 'start_at',
        }"
      >
        Začiatok praxe
        <span v-if="sortField === 'start_at'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        class="col-span-2 cursor-pointer flex items-center justify-start"
        @click="toggleSort('end_at')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'end_at',
          'text-gray-500': sortField !== 'end_at',
        }"
      >
        Koniec praxe
        <span v-if="sortField === 'end_at'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
      <div
        class="col-span-2 cursor-pointer flex items-center justify-end mr-5"
        @click="toggleSort('status')"
        :class="{
          'text-emerald-600 font-bold': sortField === 'status',
          'text-gray-500': sortField !== 'status',
        }"
      >
        Stav
        <span v-if="sortField === 'status'">
          <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
          <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
        </span>
      </div>
    </div>

    <div class="md:hidden px-4 py-3 border-b border-gray-200 flex items-center justify-start">
      <Select
        class="flex-1"
        :model-value="sortField ?? ''"
        @change="toggleSort(($event.target as HTMLSelectElement).value as SortableField)"
      >
        <option value="">Zoradiť podľa...</option>
        <option value="company">Firma</option>
        <option v-if="role === 'garant'" value="student">Študent</option>
        <option value="semester">Semester</option>
        <option value="year">Rok</option>
        <option value="end_at">Koniec praxe</option>
        <option value="status">Stav</option>
      </Select>

      <span v-if="sortField" class="cursor-pointer text-base select-none" @click="toggleDirection">
        <ArrowUp v-if="sortDirection === 'asc'" class="w-4 h-4 inline-block" />
        <ArrowDown v-if="sortDirection === 'desc'" class="w-4 h-4 inline-block" />
      </span>
    </div>

    <InternshipCard
      v-for="internship in filteredInternships"
      :key="internship.internships_id"
      :internship="internship"
      :role="role"
    ></InternshipCard>
  </div>
</template>
