<script setup lang="ts">
  import { Download } from 'lucide-vue-next'
  import ActionButton from '@/components/atoms/ActionButton.vue'
  import type { Internship } from '@/types/internship.ts'

  const props = defineProps<{
    internships: Internship[]
  }>()

  const exportToCsv = () => {
    if (!props.internships.length) {
      alert('Nie sú žiadne praxe na export.')
      return
    }

    const headers = ['Firma', 'Študent', 'Semester', 'Rok', 'Hodiny spolu', 'Koniec praxe', 'Stav']

    const rows = props.internships.map((row) => [
      row.company?.name || '',
      `${row.student?.name || ''} ${row.student?.surname || ''}`.trim(),
      row.semester || '',
      row.year || '',
      row.end_at ? new Date(row.end_at).toLocaleDateString() : '',
      row.status || '',
    ])

    const csvContent = [headers, ...rows]
      .map((row) => row.map((cell) => `"${String(cell).replace(/"/g, '""')}"`).join(','))
      .join('\n')

    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' })
    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `internships_${new Date().toISOString().slice(0, 10)}.csv`)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
  }
</script>

<template>
  <ActionButton @click="exportToCsv">
    <Download class="w-5" />
    Export
  </ActionButton>
</template>
