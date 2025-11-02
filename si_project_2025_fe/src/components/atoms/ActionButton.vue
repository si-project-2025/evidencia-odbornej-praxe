<script setup lang="ts">
  import { computed } from 'vue'

  const props = defineProps({
    color: {
      type: String,
      default: 'green',
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

  const colorClasses = computed(() => {
    switch (props.color) {
      case 'red':
        return 'bg-rose-600 hover:bg-rose-700 text-white'
      case 'green-light':
        return 'bg-emerald-100 hover:bg-emerald-200 text-emerald-700'
      case 'yellow':
        return 'bg-amber-200 hover:bg-amber-100 text-amber-700'
      default:
        return 'bg-emerald-600 hover:bg-emerald-700 text-white '
    }
  })
</script>

<template>
  <RouterLink v-if="href" :to="href" class="button text-sm disabled:opacity-60" :class="colorClasses">
    <slot></slot>
  </RouterLink>

  <button v-else :type="type" class="button text-sm disabled:opacity-60" :class="colorClasses">
    <slot></slot>
  </button>
</template>
