<script setup lang="ts">
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import Logo from '@/assets/images/logo-fpv.png'
  import { CircleUser, CirclePlus, LogOut, Menu, X } from 'lucide-vue-next'
  import { computed, ref } from 'vue'
  import { useUserStore } from '@/stores/user.ts'

  const userStore = useUserStore()

  const menuOpen = ref(false)
  const isGarant = computed(() => userStore.user?.role === 'garant')
</script>

<template>
  <nav class="sticky top-0 z-50 bg-white px-6 2xl:px-16 py-3 flex flex-col md:flex-row justify-between items-center">
    <div class="flex flex-row gap-6 items-center mb-6 md:mb-0">
      <a href="/" class="flex flex-row items-center gap-4">
        <img :src="Logo" alt="Logo" class="size-10 md:size-13" />
        <div class="font-semibold text-lg md:text-xl">Evidencia odbornej praxe</div>
      </a>

      <button @click="menuOpen = !menuOpen" class="md:hidden text-emerald-700 focus:outline-none">
        <Menu v-if="!menuOpen" class="size-7" />
        <X v-else class="size-7" />
      </button>
    </div>

    <template v-if="!userStore.user">
      <div :class="menuOpen ? 'block' : 'hidden md:flex'" class="flex flex-col md:flex-row gap-4 w-full md:w-fit">
        <BaseButton variant="secondary" href="/registration">Registrovať</BaseButton>
        <BaseButton variant="primary" href="/login">Prihlásiť sa</BaseButton>
      </div>
    </template>

    <template v-else>
      <div
        :class="menuOpen ? 'block' : 'hidden md:flex'"
        class="flex flex-col md:flex-row gap-4 w-full md:w-fit items-center"
      >
        <div class="flex flex-row items-center gap-2">
          <CircleUser class="size-6" :class="isGarant ? 'text-amber-500' : 'text-emerald-600'" />
          <span class="text-primary-dark">
            {{ userStore.user.name + ' ' + userStore.user.surname }}
          </span>
        </div>

        <RouterLink to="/registration">
          <CirclePlus v-if="isGarant" class="size-6 text-emerald-600 hover:scale-105 transition duration-500" />
        </RouterLink>

        <BaseButton class="group" @click="userStore.logout()" variant="secondary">
          <LogOut class="size-6 text-emerald-600 group-hover:text-white" />
          <span class="text-primary-dark">Odhlásiť</span>
        </BaseButton>
      </div>
    </template>
  </nav>
</template>
