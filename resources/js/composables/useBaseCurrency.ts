import { ref, watch } from 'vue'

const STORAGE_KEY = 'base_currency'

export function useBaseCurrency() {
    const baseCurrency = ref(localStorage.getItem(STORAGE_KEY) || 'USD')

    watch(baseCurrency, (newVal) => {
        localStorage.setItem(STORAGE_KEY, newVal)
    })

    return {
        baseCurrency,
    }
}
