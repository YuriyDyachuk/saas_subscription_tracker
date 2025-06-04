<?php

namespace Database\Seeders;

use App\Enums\BillingFrequencyEnum;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subscription::insert([
            [
                'name' => 'Netflix',
                'price' => 15.99,
                'currency' => 'USD',
                'billing_frequency' => BillingFrequencyEnum::MONTHLY->value,
                'start_date' => now()->subMonths(2),
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'name' => 'Spotify',
                'price' => 9.99,
                'currency' => 'EUR',
                'billing_frequency' => BillingFrequencyEnum::WEEKLY->value,
                'start_date' => now()->subMonths(1),
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subMonths(1),
            ],
            [
                'name' => 'AWS',
                'price' => 100.00,
                'currency' => 'USD',
                'billing_frequency' => BillingFrequencyEnum::YEARLY->value,
                'start_date' => now()->subYears(1),
                'created_at' => now()->subYear(),
                'updated_at' => now()->subYear(),
            ],
            [
                'name' => 'Adobe Creative Cloud',
                'price' => 52.99,
                'currency' => 'GBP',
                'billing_frequency' => BillingFrequencyEnum::DAILY->value,
                'start_date' => now()->subDays(4),
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'Notion',
                'price' => 5.00,
                'currency' => 'USD',
                'billing_frequency' => BillingFrequencyEnum::MONTHLY->value,
                'start_date' => now()->subDays(10),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
        ]);
    }
}
