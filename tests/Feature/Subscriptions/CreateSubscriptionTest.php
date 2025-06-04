<?php

use App\Models\Subscription;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a subscription', function () {
    $response = $this->post('/subscriptions', [
        'name' => 'Notion',
        'cost' => 10,
        'billing_frequency' => 'monthly',
        'currency' => 'USD',
        'start_date' => '2024-01-01',
    ]);

    $response->assertRedirect();
    expect(Subscription::where('name', 'Notion')->exists())->toBeTrue();
});

it('calculates 30 day expense with currency conversion', function () {
    Setting::create(['base_currency' => 'EUR']);

    Subscription::create([
        'name' => 'Netflix',
        'cost' => 10,
        'billing_frequency' => 'monthly',
        'currency' => 'USD',
        'start_date' => now()->subMonth(),
    ]);

    $response = $this->get('/expenses/summary');

    $response->assertStatus(200);
    $response->assertSee('9.00'); // assuming USD->EUR = 0.9
});
