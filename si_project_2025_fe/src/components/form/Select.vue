<script setup lang="ts">
  import { defineProps, defineEmits } from 'vue'

  defineProps({
    modelValue: { type: [String, Number, null], required: true },
    label: { type: String, default: '' },
    id: { type: String, default: '' },
    required: { type: Boolean, default: true },
    error: { type: [String, null], default: null },
  })

  const emits = defineEmits(['update:modelValue'])

  const updateValue = (event: Event) => {
    const target = event.target as HTMLSelectElement
    emits('update:modelValue', target.value)
  }
</script>

<template>
  <div class="w-full">
    <label :for="id" class="font-semibold">{{ label }}</label>
    <select
      :id="id"
      :required="required"
      class="w-full input mb-1 rounded-lg"
      :class="{
        'text-gray-400': modelValue === '' || modelValue === null,
        'text-gray-900': modelValue !== '' && modelValue !== null,
      }"
      :value="modelValue"
      @change="updateValue"
    >
      <slot />
    </select>
    <span v-if="error" class="text-red-600 text-sm">{{ error }}</span>
  </div>
</template>
