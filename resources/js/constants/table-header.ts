export interface TableHeader {
    title: string
    key: string
    sortable?: boolean
}

export function getTableHeaders(): TableHeader[] {
    return [
        { title: 'Name', key: 'name', sortable: true },
        { title: 'Price', key: 'price', sortable: true },
        { title: 'Currency', key: 'currency', sortable: true },
        { title: 'Converted Price', key: 'converted_price', sortable: false },
        { title: 'Base Currency', key: 'base_currency', sortable: false },
        { title: 'Billing Frequency', key: 'billing_frequency', sortable: true },
        { title: 'Start Date', key: 'start_date', sortable: true },
        { title: 'Actions', key: 'actions', sortable: false },
    ]
}
