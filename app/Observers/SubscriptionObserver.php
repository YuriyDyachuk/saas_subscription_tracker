<?php

namespace App\Observers;

use App\Models\Subscription;

class SubscriptionObserver
{
    public function created(Subscription $subscription): void
    {
        $this->clearOverviewCache();
    }

    public function updated(Subscription $subscription): void
    {
        $this->clearOverviewCache();
    }

    public function deleted(Subscription $subscription): void
    {
        $this->clearOverviewCache();
    }

    private function clearOverviewCache(): void
    {
        foreach (array_keys(config('app.currencies')) as $currency) {
            \Cache::forget("subscriptions:overview:$currency");
        }
    }
}
