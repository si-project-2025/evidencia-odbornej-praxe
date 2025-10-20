<script setup lang="ts">
  import { defineProps, reactive, ref } from 'vue'
  import type { PropType } from 'vue'
  import type { RegistrationForm } from '@/types/form.ts'
  import type { Role } from '@/types/common.ts'
  import axios from 'axios'
  import Input from '@/components/form/Input.vue'
  import Button from '@/components/atoms/Button.vue'
  import FormSection from '@/components/form/FormSection.vue'

  const props = defineProps({
    role: { type: String as PropType<Role>, default: 'student' as Role },
  })

  const form = reactive<RegistrationForm>({
    name: '',
    surname: '',
    email: '',
    alt_email: '',
    phone: '',
    programme: '',
    role: props.role,
    address: props.role === 'student' ? { street: '', house_number: '', city: '', zip: '', country: '' } : undefined,
  })

  const zipError = ref('')
  const phoneError = ref('')
  const submitError = ref('')
  const registrationSuccess = ref(false)
  const registrationEmail = ref('')

  const validateForm = () => {
    if (props.role === 'student') {
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

    const roleIdMap: { [key in Role]: number } = { student: 2, garant: 1 }
    const payload = {
      name: form.name,
      surname: form.surname,
      email: form.email,
      role_id: roleIdMap[form.role],
      alt_email: form.alt_email || null,
      phone_number: form.phone || null,
      study_program: form.programme || null,

      street: props.role === 'student' ? `${form.address?.street || ''} ${form.address?.house_number || ''}`.trim() : 'N/A',
      house_number: form.address?.house_number,
      city: props.role === 'student' ? form.address?.city : '',
      zip_code: props.role === 'student' ? form.address?.zip : '',
      country: props.role === 'student' ? form.address?.country : '',
    }

    try {
      const response = await axios.post("http://localhost:8000/api/register", payload)
      console.log(response.data)
      // display registration success
      registrationSuccess.value = true
      registrationEmail.value = response.data.email

    } catch (err: unknown) {
      if (axios.isAxiosError(err)) {
        console.log(err.response?.data); // <-- toto ti ukáže presnú príčinu
        submitError.value = JSON.stringify(err.response?.data.errors);
      } else {
        submitError.value = 'Pri prihlasovaní nastala chyba.';
      }
    }
  }
</script>

<template>
  <form @submit.prevent="register" class="form-container w-full md:w-1/2">
    <template v-if="!registrationSuccess">
      <FormSection title="Osobné údaje">
        <Input v-model="form.name" id="name" label="Meno*" type="text" />
        <Input v-model="form.surname" id="surname" label="Priezvisko*" type="text" />
        <Input v-model="form.email" id="email" label="Email*" type="email" />
      </FormSection>

      <FormSection v-if="role === 'student'" title="Adresa">
        <Input v-model="form.address!.street" id="street" label="Ulica*" type="text" />
        <Input v-model="form.address!.house_number" id="house_number" label="Číslo domu*" type="number" />
        <Input v-model="form.address!.city" id="city" label="Mesto*" type="text" />
        <Input v-model="form.address!.zip" id="zip" label="PSČ*" type="number" :error="zipError" />
        <Input v-model="form.address!.country" id="country" label="Krajina*" type="text" />
      </FormSection>

      <FormSection title="Doplnkové údaje (nepovinné)">
        <Input v-model="form.alt_email!" id="alt_email" label="Alternatívny email" type="email" :required="false" />
        <Input v-model="form.phone!" id="phone" label="Telefón" type="tel" :required="false" :error="phoneError" />
        <Input
          v-if="role === 'student'"
          v-model="form.programme!"
          id="programme"
          label="Odbor"
          type="text"
          :required="false"
        />
      </FormSection>

    <Button type="submit" class="w-[80%]">Registrovať sa</Button>

    <p v-if="submitError" class="text-red-600">{{ submitError }}</p>
    </template>

      <template v-else>
        <div class="text-center p-6 bg-green-100 border border-green-400 text-green-700 rounded-md">
          <h3 class="text-xl font-semibold mb-2">Registrácia takmer hotová!</h3>
          <p>Na Váš e-mail <strong>{{ registrationEmail }}</strong> sme odoslali odkaz pre aktiváciu účtu a nastavenie hesla.</p>
          <p class="mt-2 text-sm">Prosím, skontrolujte si svoju e-mailovú schránku.</p>
        </div>
      </template>

  </form>
</template>
