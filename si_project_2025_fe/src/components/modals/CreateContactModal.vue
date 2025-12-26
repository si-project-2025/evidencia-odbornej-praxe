<script setup lang="ts">
  import { reactive, ref } from 'vue'
  import BaseButton from '@/components/atoms/BaseButton.vue'
  import Input from '@/components/form/Input.vue'
  import { useContactstore } from '@/stores/contacts.ts'

  const props = defineProps<{
    company_id: number
  }>()

  const emit = defineEmits(['close', 'created'])
  const contactsStore = useContactstore()

  const loading = ref(false)
  const errorMessage = ref('')

  const form = reactive({
    name: '',
    surname: '',
    email: '',
    phone: '',
    company_id: props.company_id,
  })

  const submit = async () => {
    errorMessage.value = ''
    loading.value = true

    try {
      const newContactId = await contactsStore.createContact(form)
      emit('created', newContactId)
    } catch (e: unknown) {
      console.error('Error pri vytváraní kontaktu:', e)

      if (e instanceof Error) {
        errorMessage.value = e.message
        return
      }

      errorMessage.value = 'Nepodarilo sa vytvoriť kontakt.'
    } finally {
      loading.value = false
    }
  }
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg shadow-xl space-y-4">
      <h2 class="text-lg font-semibold">Pridať novú kontaktnú osobu</h2>

      <Input v-model="form.name" label="Meno*" />
      <Input v-model="form.surname" label="Priezvisko*" />

      <Input v-model="form.email" label="Email*" type="email" />
      <Input v-model="form.phone" label="Telefón" type="tel" :required="false" />

      <p v-if="errorMessage" class="text-red-600 text-sm">{{ errorMessage }}</p>

      <div class="flex justify-end gap-2">
        <BaseButton @click="emit('close')" type="button">Zrušiť</BaseButton>
        <BaseButton @click="submit" :disabled="loading">
          {{ loading ? 'Ukladám...' : 'Vytvoriť kontakt' }}
        </BaseButton>
      </div>
    </div>
  </div>
</template>
