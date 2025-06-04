<script setup lang="ts">
import { ref, watch, watchEffect, onMounted } from 'vue'
import { mdiPencil, mdiDelete } from '@mdi/js'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { getTableHeaders, TableHeader } from '../constants/table-header'
import {Subscription, OverviewSubscription} from "../types/subscription"
import { useBaseCurrency } from '../composables/useBaseCurrency'
import {currencyOptions, frequencyOptions} from "../constants/billing-frequency"

const router = useRouter()
const { baseCurrency } = useBaseCurrency()

const subscriptions = ref<Subscription[]>([])
const overviewData = ref<OverviewSubscription>({})
const totalItems = ref<number>(0)
const loading = ref<boolean>(false)
const selectedFrequency = ref<string | null>(null)
const priceMin = ref<number | null>(null)
const priceMax = ref<number | null>(null)
const selectedMonth = ref<number | null>(null)
const selectedYear = ref<number>(new Date().getFullYear())

const options = ref({
  page: 1,
  itemsPerPage: 10,
  sortBy: [],
  sortDesc: [],
})

const headers = ref<TableHeader[]>(getTableHeaders())

function onCurrencyChange(newCurrency: string) {
  baseCurrency.value = newCurrency
}

function onMonthChange(val: number) {
  selectedMonth.value = val
}

function getApiParams() {
  const sortBy = options.value.sortBy?.[0]

  /*
  * TODO: add more params
  * const activeMonth = formatSelectedMonth(selectedMonth.value)
  * const selectedYearValue = getSelectedYear(selectedMonth.value, selectedYear.value)
  * active_month: activeMonth,
  * selected_year: selectedYearValue,
  * */

  return {
    page: options.value.page,
    per_page: options.value.itemsPerPage,
    sort_by: typeof sortBy === 'string' ? sortBy : sortBy?.key ?? null,
    sort_order: options.value.sortDesc?.[0] ? 'asc' : 'desc',
    base_currency: baseCurrency.value,
    frequency: selectedFrequency.value,
    price_min: priceMin.value,
    price_max: priceMax.value,
  }
}

function handleError(message: string, error: unknown) {
  console.error(message, error)
  alert(message)
}

async function overviewSubscriptions() {
  loading.value = true
  try {
    const response = await axios.get(`/api/overview`, {
      params: { base_currency: baseCurrency.value }
    });
    overviewData.value = response.data.data
  } catch (error) {
    handleError('Failed to overview subscriptions.', error)
  } finally {
    loading.value = false
  }
}

async function loadSubscriptions() {
  loading.value = true
  try {
    const response = await axios.get('/api/subscriptions', { params: getApiParams() })
    subscriptions.value = response.data.data
    totalItems.value = response.data.total
  } catch (error) {
    handleError('Failed to load subscriptions.', error)
  } finally {
    loading.value = false
  }
}

function editSubscription(item: any) {
  router.push(`/subscriptions/${item.id}/edit`)
}

async function deleteSubscription(item: any) {
  if (!confirm(`Delete subscription "${item.name}"?`)) return

  try {
    const response = await axios.delete(`/api/subscriptions/${item.id}`)
    alert(response.data?.message)
    await loadSubscriptions()
  } catch (error) {
    handleError('Failed to delete subscription.', error)
  }
}

watch([
    selectedFrequency, priceMin, priceMax, baseCurrency,
  () => options.value.page,
  () => options.value.itemsPerPage,
  () => options.value.sortBy,
  () => options.value.sortDesc,
], () => {
  loadSubscriptions()
})

watchEffect(() => {
  overviewSubscriptions()
})

onMounted(() => {
  overviewSubscriptions()
  loadSubscriptions()
})
</script>

<template>
  <VCard class="mb-12">
    <v-select
        label="Base Currency"
        :items="currencyOptions"
        v-model="baseCurrency"
        @change="onCurrencyChange"
        item-title="label"
        item-value="value"
    />
  </VCard>

  <VCard class="mb-12">
    <VCardText>
      <div class="d-flex flex-wrap justify-space-between align-center gap-4">
        <h5 class="text-h5 font-weight-medium">
          Overview
        </h5>
      </div>
    </VCardText>
    <VCardText>
      <div class="d-flex flex-wrap justify-space-between align-center gap-4">
        <div class="d-flex flex-wrap justify-space-between align-center gap-4">
          <h5 class="text-h5 font-weight-medium">
            Currency: {{ overviewData.currency }}
          </h5>
        </div>
        <div class="d-flex flex-wrap justify-space-between align-center gap-4">
          <h5 class="text-h5 font-weight-medium">
            Monthly: {{ overviewData.projected_30_days }}
          </h5>
        </div>
        <div class="d-flex flex-wrap justify-space-between align-center gap-4">
          <h5 class="text-h5 font-weight-medium">
            Yearly: {{ overviewData.projected_365_days }}
          </h5>
        </div>
      </div>
    </VCardText>
  </VCard>

  <VCard class="mb-6 pa-4">
    <div class="d-flex flex-wrap gap-4 align-start">

      <v-select
          label="Filter by Frequency"
          :items="frequencyOptions"
          v-model="selectedFrequency"
          clearable
          dense
          style="max-width: 200px"
          item-title="label"
          item-value="value"
      />

      <v-text-field
          label="Min Price (in base currency)"
          type="number"
          v-model.number="priceMin"
          clearable
          dense
          style="max-width: 150px"
      />

      <v-text-field
          label="Max Price (in base currency)"
          type="number"
          v-model.number="priceMax"
          clearable
          dense
          style="max-width: 150px"
      />

      <div class="ma-4">
        <h5>Monthly Activity Filter</h5>
        <v-date-picker-months
            v-model="selectedMonth"
            @update:model-value="onMonthChange"
            year-icon="mdi-calendar-blank"
            prev-icon="mdi-skip-previous"
            next-icon="mdi-skip-next"
        ></v-date-picker-months>
      </div>
    </div>

  </VCard>

  <VCardText>
    <div class="d-flex flex-wrap justify-space-between align-center gap-4">
      <h5 class="text-h5 font-weight-medium">
        Subscriptions
      </h5>
      <div class="d-flex gap-4 flex-wrap align-center">
        <VBtn
            color="primary"
            @click="router.push('/subscriptions/create')"
        >
          Add
        </VBtn>
      </div>
    </div>
  </VCardText>
  <v-data-table-server
      :headers="headers"
      :items="subscriptions"
      :server-items-length="totalItems"
      :items-length="totalItems"
      :loading="loading"
      v-model:options="options"
      class="text-no-wrap"
  >
    <template #item.start_date="{ item }">
      {{ item.start_date }}
    </template>
    <template #item.actions="{ item }">
      <v-btn class="ma-1" icon @click="editSubscription(item)">
        <v-icon :icon="mdiPencil" />
      </v-btn>
      <v-btn class="ma-1" icon @click="deleteSubscription(item)">
        <v-icon :icon="mdiDelete" />
      </v-btn>
    </template>
  </v-data-table-server>
</template>

<style lang="scss">
.v-date-picker-months__content {
  grid-template-columns: repeat(4, 1fr) !important;
}
</style>
