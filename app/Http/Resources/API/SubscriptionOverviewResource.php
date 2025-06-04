<?php
declare(strict_types=1);

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionOverviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
//            "projected_30_days" => $this->projected_30_days,
//            "projected_365_days" => $this->projected_365_days,
//            "currency" => $this->currency

            'projected_30_days' => $this['projected_30_days'],
            'projected_365_days' => $this['projected_365_days'],
            'currency' => $this['currency'],
        ];
    }
}
