<script setup lang="ts">
  import type { RegistrationForm } from '@/types/form.ts'
  import axios from 'axios'
  import Input from '@/components/form/Input.vue'
  import Button from '@/components/atoms/Button.vue'
  import FormSection from '@/components/form/FormSection.vue'
  import RoleSelector from '@/sections/registration/RoleSelector.vue'
  import { reactive, ref } from 'vue'

  const form = reactive<RegistrationForm>({
    name: '',
    surname: '',
    email: '',
    alt_email: undefined,
    phone: undefined,
    programme: undefined,
    role: 'student',
    address: { street: '', house_number: '', city: '', zip: '', country: '' },
  })

  const zipError = ref('')
  const phoneError = ref('')
  const submitError = ref('')
  const registrationSuccess = ref(false)
  const registrationEmail = ref('')

  const validateForm = () => {
    if (form.role === 'student') {
      validateAddress()
    }

    if (form.phone && form.phone.trim() !== '') {
      const phonePattern = /^\+?[0-9\s\-()]{7,15}$/
      if (!phonePattern.test(form.phone!)) {
        phoneError.value = 'Zadajte platný formát čísla'
        return false
      }
    }

    return true
  }

  const validateAddress = () => {
    const zipPattern = /^\d{3}\s?\d{2}$/
    if (form.address?.zip && !zipPattern.test(form.address.zip.toString())) {
      zipError.value = 'Zadajte platné PSČ'
      return false
    }
  }

  const register = async () => {
    submitError.value = ''

    if (!validateForm()) return

    try {
      console.log(form.role)
      const response = await axios.post('http://localhost:8000/api/register', form)
      registrationSuccess.value = true
      registrationEmail.value = response.data.email
    } catch (err: unknown) {
      submitError.value = axios.isAxiosError(err)
        ? (err.response?.data?.message ?? 'Pri prihlasovaní nastala chyba.')
        : 'Pri prihlasovaní nastala chyba.'
    }
  }
</script>

<template>
  <form @submit.prevent="register" class="form-container w-full md:w-1/2" v-if="!registrationSuccess">
    <RoleSelector v-model="form.role" />

    <FormSection title="Osobné údaje">
      <Input v-model="form.name" id="name" label="Meno*" type="text" />
      <Input v-model="form.surname" id="surname" label="Priezvisko*" type="text" />
      <Input v-model="form.email" id="email" label="Email*" type="email" />
    </FormSection>

    <FormSection v-if="form.role === 'student'" title="Adresa">
      <Input v-model="form.address!.street" id="street" label="Ulica*" type="text" />
      <Input v-model="form.address!.house_number" id="house_number" label="Číslo domu*" type="text" />
      <Input v-model="form.address!.city" id="city" label="Mesto*" type="text" />
      <Input v-model="form.address!.zip" id="zip" label="PSČ*" type="number" :error="zipError" />
      <Input v-model="form.address!.country" id="country" label="Krajina*" type="text" />
    </FormSection>

    <FormSection title="Doplnkové údaje (nepovinné)">
      <Input v-model="form.alt_email!" id="alt_email" label="Alternatívny email" type="email" :required="false" />
      <Input v-model="form.phone!" id="phone" label="Telefón" type="tel" :required="false" :error="phoneError" />
      <Input
        v-if="form.role === 'student'"
        v-model="form.programme!"
        id="programme"
        label="Odbor"
        type="text"
        :required="false"
      />
    </FormSection>

    <Button type="submit" class="w-[80%]">Registrovať sa</Button>
    <p v-if="submitError" class="text-red-600">{{ submitError }}</p>
  </form>

  <div class="rounded-md px-12 py-8 border border-dark/40" v-else>
    <div class="text-center p-6 bg-green-100 border border-b-secondary/80 text-secondary rounded-md">
      <h3 class="text-semibold mb-2">Registrácia takmer hotová!</h3>
      <p>
        Na Váš e-mail
        <strong>{{ registrationEmail }}</strong>
        sme odoslali odkaz pre aktiváciu účtu a nastavenie hesla.
      </p>
      <p class="mt-2 text-sm">Prosím, skontrolujte si svoju e-mailovú schránku.</p>
    </div>
  </div>
</template>
