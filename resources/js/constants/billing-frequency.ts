export interface BillingFrequencyOption {
    label: string
    value: string
}

export const billingFrequencies: BillingFrequencyOption[] = [
    { label: 'Daily', value: 'daily' },
    { label: 'Weekly', value: 'weekly' },
    { label: 'Monthly', value: 'monthly' },
    { label: 'Yearly', value: 'yearly' },
]

export interface CurrencyOption {
    label: string
    value: string
}

export const currencyOptions: CurrencyOption[] = [
    { label: 'US Dollar (USD)', value: 'USD' },
    { label: 'Euro (EUR)', value: 'EUR' },
    { label: 'British Pound (GBP)', value: 'GBP' },
    { label: 'Japanese Yen (JPY)', value: 'JPY' },
    { label: 'Chinese Yuan (CNY)', value: 'CAD' },
    { label: 'Australian Dollar (AUD)', value: 'AUD' },
]

export interface FrequencyOption {
    label: string
    value: string
}

export const frequencyOptions: FrequencyOption[] = [
    { label: 'Daily', value: 'daily' },
    { label: 'Weekly', value: 'weekly' },
    { label: 'Monthly', value: 'monthly' },
    { label: 'Yearly', value: 'yearly' },
]
