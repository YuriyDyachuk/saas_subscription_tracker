<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\SubscriptionOverviewResource;
use App\Services\ExpenseProjectionService;
use Illuminate\Http\Request;

class SubscriptionOverviewController extends Controller
{
    public function __invoke(Request $request, ExpenseProjectionService $service): SubscriptionOverviewResource
    {
        $baseCurrency = $request->query('base_currency', 'USD');

        $data = $service->calculate($baseCurrency);

        return SubscriptionOverviewResource::make($data);
    }
}
