
export interface Subscription {
    id: number
    name: string
    price: number
    currency: string
    billing_frequency: string
    start_date: string | null
    base_currency?: string
    converted_price?: number
}

export interface OverviewSubscription {
    projected_30_days: number
    projected_365_days: number
    currency: string
}