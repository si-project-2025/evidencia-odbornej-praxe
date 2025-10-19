<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    variant: {
      type: String,
      default: 'primary',
    },
    href: {
      type: String,
      default: '',
    },
    type: {
      type: String as () => 'button' | 'submit',
      default: 'button',
    },
  })

  const variantClasses = computed(() => {
    switch (props.variant) {
      case 'secondary':
        return 'text-secondary border border-secondary hover:text-white hover:bg-secondary'
      default:
        return 'text-white bg-secondary border border-secondary hover:text-secondary hover:bg-white'
    }
  })
</script>

<template>
  <RouterLink v-if="href" :to="href" class="button" :class="variantClasses">
    <slot></slot>
  </RouterLink>

  <button v-else :type="type" class="button" :class="variantClasses">
    <slot></slot>
  </button>
</template>
