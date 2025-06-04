<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\SubscriptionFilterDto;
use App\Models\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubscriptionService
{
    public function __construct(
        protected CurrencyConverter $converter
    ) {}

    public function getList(SubscriptionFilterDto $filters): LengthAwarePaginator
    {
        $query = Subscription::query()
            ->select(['id', 'name', 'price', 'currency', 'billing_frequency', 'start_date'])
            ->search($filters->search)
            ->frequency($filters->frequency)
            ->priceBetween($filters->priceMin, $filters->priceMax)
            ->activeInMonth($filters->year, $filters->activeMonth)
            ->sortByColumn($filters->sortBy, $filters->sortOrder);

        return $query->paginate($filters->perPage);
    }

    public function create(array $data): void
    {
        Subscription::create($data);
    }

    public function update(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);

        return $subscription;
    }

    public function delete(Subscription $subscription): void
    {
        $subscription->delete();
    }
}
