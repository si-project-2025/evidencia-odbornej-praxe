<script setup lang="ts">
  import { defineProps, defineEmits } from 'vue'

  const props = defineProps({
    modelValue: { type: Boolean, required: true },
    leftLabel: { type: String, required: true },
    rightLabel: { type: String, required: true },
  })

  const emit = defineEmits(['update:modelValue'])

  const toggle = () => {
    emit('update:modelValue', !props.modelValue)
  }
</script>

<template>
  <div
    class="relative flex w-40 h-9 rounded-full border border-gray-400 cursor-pointer select-none text-sm"
    @click="toggle"
  >
    <div
      class="absolute top-0 left-0 h-full w-1/2 rounded-full bg-blue-400 transition-transform duration-200"
      :class="!modelValue ? 'translate-x-full' : ''"
    ></div>

    <div
      class="flex-1 flex items-center justify-center z-10 transition-colors duration-200"
      :class="modelValue ? 'text-white font-semibold' : 'text-gray-600'"
    >
      {{ leftLabel }}
    </div>

    <div
      class="flex-1 flex items-center justify-center z-10 transition-colors duration-200"
      :class="!modelValue ? 'text-white font-semibold' : 'text-gray-600'"
    >
      {{ rightLabel }}
    </div>
  </div>
</template>
