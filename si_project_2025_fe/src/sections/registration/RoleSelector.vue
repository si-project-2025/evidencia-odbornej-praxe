<script setup lang="ts">
  import { defineEmits, type PropType } from 'vue'
  import type { Role } from '@/types/common.ts'
  import Button from '@/components/atoms/Button.vue'

  defineProps({
    modelValue: { type: String as PropType<Role>, default: 'student' as Role },
  })

  const emits = defineEmits(['update:modelValue'])

  const selectRole = (newRole: Role) => {
    emits('update:modelValue', newRole)
  }
</script>

<template>
  <div class="flex flex-row gap-3">
    <Button
      type="button"
      variant="secondary"
      class="rounded-full"
      :class="{ 'bg-secondary text-white': modelValue === 'student' }"
      @click="selectRole('student')"
    >
      Som študent
    </Button>
    <Button
      type="button"
      variant="secondary"
      class="rounded-full"
      :class="{ 'bg-secondary text-white': modelValue === 'garant' }"
      @click="selectRole('garant')"
    >
      Som garant
    </Button>
  </div>
</template>
