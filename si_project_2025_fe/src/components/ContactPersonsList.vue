<script setup lang="ts">
  import { computed, onMounted, ref } from 'vue'
  import { useUserStore } from '@/stores/user'
  import { useContactstore } from '@/stores/contacts'
  import type { ContactPerson } from '@/types/internship'
  import { Eye, EyeOff, Mail, Phone } from 'lucide-vue-next'

  const userStore = useUserStore()
  const contactsStore = useContactstore()

  const loading = ref(false)
  const errorMessage = ref('')

  const companyId = computed(() => userStore.user?.company?.company_id ?? null)
  const contacts = computed<ContactPerson[]>(() => contactsStore.contacts ?? [])

  const loadContacts = async () => {
    if (!companyId.value) return
    loading.value = true
    try {
      await contactsStore.fetchContacts(companyId.value)
    } catch {
      errorMessage.value = 'Nepodarilo sa načítať kontaktné osoby.'
    } finally {
      loading.value = false
    }
  }

  const toggleContactVisibility = async (id: number, name: string) => {
    errorMessage.value = ''
    const ok = window.confirm(`Naozaj chcete zmeniť viditeľnosť kontaktnej osoby: ${name}?`)
    if (!ok) return

    loading.value = true
    try {
      await contactsStore.toggleContactVisibility(id, companyId.value ?? undefined)
      await loadContacts()
    } catch {
      errorMessage.value = 'Nepodarilo sa zmeniť viditeľnosť.'
    } finally {
      loading.value = false
    }
  }

  onMounted(loadContacts)
</script>

<template>
  <div>
    <div v-if="errorMessage" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
      {{ errorMessage }}
    </div>

    <div v-if="loading" class="text-gray-500 text-sm">Načítavam...</div>

    <div v-else class="rounded-2xl border border-gray-200">
      <div v-if="contacts.length === 0" class="p-6 text-gray-500 text-sm">Zatiaľ nemáte žiadne kontaktné osoby.</div>

      <div v-else class="divide-y divide-gray-100">
        <div
          v-for="p in contacts"
          :key="p.id"
          class="p-4 md:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
        >
          <div class="space-y-1">
            <div class="text-lg font-semibold text-gray-900">{{ p.name }} {{ p.surname }}</div>

            <div class="flex items-center gap-1 text-sm text-gray-600">
              <Mail class="w-4 h-4 text-gray-400" />
              <span>{{ p.email }}</span>

              <Phone class="w-4 h-4 text-gray-400 ml-2" />
              <span>{{ p.phone }}</span>
            </div>
          </div>

          <button
            class="self-end md:self-auto p-2 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 cursor-pointer"
            :disabled="loading"
            @click="toggleContactVisibility(p.id, `${p.name} ${p.surname}`)"
            :title="p.hidden ? 'Zobraziť' : 'Skryť'"
          >
            <EyeOff v-if="p.hidden" class="w-5 h-5 text-red-500" />
            <Eye v-else class="w-5 h-5 text-green-500" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
