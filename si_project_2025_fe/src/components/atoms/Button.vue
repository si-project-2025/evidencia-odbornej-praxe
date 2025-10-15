<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    variant: {
      type: String,
      default: 'primary',
    },
  })

  const variantClasses = computed(() => {
    switch (props.variant) {
      case 'secondary':
        return 'text-secondary border border-secondary '
      default:
        return 'text-white border border-secondary bg-secondary'
    }
  })
</script>

<template>
  <div class="font-semibold text-md rounded-md px-4 py-2" :class="variantClasses">
    <slot></slot>
  </div>
</template>
