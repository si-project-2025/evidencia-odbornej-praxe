<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    variant: {
      type: String,
      default: 'primary',
    },
    href: {
      type: String,
      default: '/',
    },
  })

  const variantClasses = computed(() => {
    switch (props.variant) {
      case 'secondary':
        return 'text-secondary border border-secondary hover:text-white hover:bg-secondary transition duration-500'
      default:
        return 'text-white border border-secondary bg-secondary hover:text-secondary hover:bg-white transition duration-500'
    }
  })
</script>

<template>
  <a
    :href="href"
    class="text-center font-semibold text-md rounded-md px-4 py-2"
    :class="variantClasses"
  >
    <slot></slot>
  </a>
</template>
