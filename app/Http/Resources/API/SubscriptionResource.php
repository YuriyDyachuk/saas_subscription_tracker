<?php
declare(strict_types=1);

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $baseCurrency = $request->query('base_currency', 'USD');
        $converter = new \App\Services\CurrencyConverter();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'currency' => $this->currency,
            'billing_frequency' => $this->billing_frequency,
            'start_date' => $this->start_date,
            'converted_price' => $converter->convert((float) $this->price, $this->currency, $baseCurrency),
            'base_currency' => $baseCurrency,
        ];
    }
}
