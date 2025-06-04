<?php

namespace App\Http\Requests\Api;

use App\DTO\SubscriptionFilterDto;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort_by' => ['nullable', 'string', 'in:name,price,currency,billing_frequency,start_date'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1'],

            'frequency' => ['nullable', 'string', 'in:daily,weekly,monthly,yearly'],
            'price_min' => ['nullable', 'numeric', 'min:1'],
            'price_max' => ['nullable', 'numeric', 'min:1'],

            'active_month' => ['nullable', 'string'],
            'selected_year' => ['nullable', 'string', 'regex:/^\d{4}$/'],
            'top' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toDto(): SubscriptionFilterDto
    {
        return new SubscriptionFilterDto($this->validated());
    }
}
