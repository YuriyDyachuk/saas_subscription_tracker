<?php

namespace App\Http\Requests\Api;

use App\Enums\BillingFrequencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'currency' => ['required', 'string', 'max:3'],
            'billing_frequency' => ['required', 'string', Rule::in(BillingFrequencyEnum::values())],
            'start_date' => ['required', 'date'],
        ];
    }
}
