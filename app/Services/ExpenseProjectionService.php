<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\BillingFrequencyEnum;
use App\Models\Subscription;

class ExpenseProjectionService
{
    public function __construct(
        protected CurrencyConverter $converter
    ) {}

    public function calculate(string $baseCurrency): array
    {
        $days30 = now()->addDays(30);
        $days365 = now()->addDays(365);

        $total30 = 0;
        $total365 = 0;

        Subscription::query()->lazy()->each(function ($subscription) use (
            &$total30,
            &$total365,
            $baseCurrency,
            $days30,
            $days365
        ) {
            $price = (float) $subscription->price;
            $currency = $subscription->currency;
            $start = \Carbon\Carbon::parse($subscription->start_date);
            $frequency = $subscription->billing_frequency;

            $count30 = $this->countOccurrences($start, $frequency, now(), $days30);
            $count365 = $this->countOccurrences($start, $frequency, now(), $days365);

            $convertedPrice = $this->converter->convert($price, $currency, $baseCurrency);

            $total30 += $convertedPrice * $count30;
            $total365 += $convertedPrice * $count365;
        });

        return [
            'projected_30_days' => round($total30, 2),
            'projected_365_days' => round($total365, 2),
            'currency' => $baseCurrency,
        ];
    }

    private function countOccurrences(\Carbon\Carbon $start, BillingFrequencyEnum $frequency, \Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        if ($start->gt($to)) return 0;

        $interval = $frequency->toInterval();

        /*
         * Todo
         * Если подписка началась до $from, нужно сдвинуть current к ближайшему платежу после $from
         * */
        $current = $start->copy();
        while ($current->lt($from)) {
            $current->add($interval);
        }

        $count = 0;

        while ($current->lte($to)) {
            $count++;
            $current->add($interval);
        }

        return $count;
    }
}
