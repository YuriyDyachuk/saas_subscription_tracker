<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import SubscriptionForm from './SubscriptionForm.vue'
import { Subscription } from '../types/subscription'

const props = defineProps<{
  id: number
}>()

const route = useRoute()
const subscription = ref<Subscription | null>(null)
const loading = ref<boolean>(false)
const error = ref<string | null>(null)

async function fetchSubscription(id: number) {
  loading.value = true
  error.value = null

  try {
    const response = await axios.get<{ data: Subscription }>(`/api/subscriptions/${id}`)

    subscription.value = response.data.data
  } catch (e: unknown) {
    error.value = 'Не удалось загрузить подписку.'
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const id = props.id || route.params.id
  if (id) fetchSubscription(id)
})
</script>

<template>
  <div>
    <h2 class="text-h5 font-weight-bold mb-4">Редактирование подписки</h2>

    <v-progress-circular indeterminate v-if="loading" />
    <v-alert type="error" v-if="error">{{ error }}</v-alert>

    <SubscriptionForm
        v-if="!loading && subscription"
        :subscription="subscription"
    />
  </div>
</template>
