<script setup lang="ts">
  import { defineProps, defineEmits } from 'vue'

  defineProps({
    modelValue: { type: String, required: true },
    label: { type: String, default: '' },
    id: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    required: { type: Boolean, default: true },
  })

  const emits = defineEmits(['update:modelValue'])

  const updateValue = (event: Event) => {
    const target = event.target as HTMLInputElement
    emits('update:modelValue', target.value)
  }
</script>

<template>
  <div class="w-full">
    <label :for="id" class="font-semibold">{{ label }}</label>
    <input
      :id="id"
      :type="type"
      :placeholder="placeholder"
      :required="required"
      class="w-full input"
      :value="modelValue"
      @input="updateValue"
    />
  </div>
</template>
