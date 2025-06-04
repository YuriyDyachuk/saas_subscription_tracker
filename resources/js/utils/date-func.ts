export const formatDate = (dateString: string): string => {
    if (!dateString) return 'no info'

    const date = new Date(dateString)
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const year = date.getFullYear()

    return `${month}/${day}/${year}`
}

export function formatSelectedMonth(month: number | null): number | null {
    if (month === null) return null;
    return month + 1;
}

export function getSelectedYear(month: number | null, year: number): number | null {
    return month !== null ? year : null;
}
