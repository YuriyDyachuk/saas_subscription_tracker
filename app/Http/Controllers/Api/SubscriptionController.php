<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriptionFilterRequest;
use App\Http\Requests\Api\SubscriptionStoreRequest;
use App\Http\Requests\Api\SubscriptionUpdateRequest;
use App\Http\Resources\API\SubscriptionCollection;
use App\Http\Resources\API\SubscriptionResource;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $service,
    ) {}

    public function index(SubscriptionFilterRequest $request): SubscriptionCollection
    {
        $subscriptions = $this->service->getList($request->toDto());

        return new SubscriptionCollection($subscriptions);
    }

    public function store(SubscriptionStoreRequest $request): void
    {
        $this->service->create($request->validated());
    }

    public function show(Subscription $subscription): SubscriptionResource
    {
        return SubscriptionResource::make($subscription);
    }

    public function update(SubscriptionUpdateRequest $request, Subscription $subscription): SubscriptionResource
    {
        $subscription = $this->service->update($subscription, $request->validated());

        return SubscriptionResource::make($subscription);
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $this->service->delete($subscription);

        return response()->json(['message' => 'Subscription deleted successfully.']);
    }
}
