<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { billingFrequencies, currencyOptions } from '../constants/billing-frequency'

interface Subscription {
  id?: number
  name: string
  price: number
  currency: string
  billing_frequency: string
  start_date: string
}

const props = defineProps<{
  subscription?: Subscription | null
}>()

const router = useRouter()

const form = ref<Subscription>({
  name: '',
  price: 1,
  currency: 'USD',
  billing_frequency: 'monthly',
  start_date: new Date().toISOString().substring(0, 10),
})
const saving = ref<boolean>(false)
const error = ref<string | null>(null)

async function submitForm() {
  saving.value = true
  error.value = null

  try {
    if (form.value.id) {
      await axios.put(`/api/subscriptions/${form.value.id}`, form.value)
    } else {
      await axios.post('/api/subscriptions', form.value)
    }

    await router.push('/subscriptions')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}

watch(
    () => props.subscription,
    (value) => {
      if (value) {
        form.value = { ...value, start_date: new Date(value.start_date).toISOString().substring(0, 10) }
      }
    },
    { immediate: true }
)
</script>

<template>
  <v-form @submit.prevent="submitForm">
    <v-text-field label="Name" v-model="form.name" required />
    <v-text-field label="Price" v-model.number="form.price" min="1" type="number" required />
    <v-select
        label="Currency"
        :items="currencyOptions"
        item-title="label"
        item-value="value"
        v-model="form.currency"
        required
    />
    <v-select
        label="Billing Frequency"
        :items="billingFrequencies"
        item-title="label"
        item-value="value"
        v-model="form.billing_frequency"
        required
    />
    <v-text-field label="Start Date" v-model="form.start_date" type="date" required />

    <v-alert v-if="error" type="error" class="my-3">{{ error }}</v-alert>

    <v-btn type="submit" :loading="saving" color="primary" class="mt-3">
      {{ form.id ? 'Update' : 'Create' }} Subscription
    </v-btn>
  </v-form>
</template>

<style scoped>
.v-form {
  max-width: 500px;
  margin: auto;
}
</style>
