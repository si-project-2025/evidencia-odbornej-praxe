<script setup lang="ts">
  import { defineProps, defineEmits } from 'vue'

  defineProps({
    modelValue: { type: Boolean, required: true },
    label: { type: String, required: true },
  })

  const emits = defineEmits(['update:modelValue'])

  const toggle = (event: Event) => {
    const target = event.target as HTMLInputElement
    emits('update:modelValue', target.checked)
  }
</script>

<template>
  <label class="flex items-center w-full cursor-pointer">
    <input type="checkbox" class="hidden peer" :checked="modelValue" @change="toggle" />

    <div class="checkbox border border-dark/50 peer-checked:bg-secondary">
      <svg
        v-if="modelValue"
        xmlns="http://www.w3.org/2000/svg"
        class="size-3 text-white"
        viewBox="0 0 24 24"
        stroke="white"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <span class="ml-2">{{ label }}</span>
  </label>
</template>
