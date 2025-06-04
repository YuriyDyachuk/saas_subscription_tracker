<?php
declare(strict_types=1);

namespace App\Services;

class CurrencyConverter
{
    protected array $rates;
    protected string $baseCurrency;

    public function __construct()
    {
        $this->rates = config('exchange.rates');
        $this->baseCurrency = config('exchange.base_currency');
    }

    public function convert(float $amount, string $from, string $to): float
    {
        if (!isset($this->rates[$from]) || !isset($this->rates[$to])) {
            throw new \InvalidArgumentException("Unsupported currency.");
        }

        $amountInBase = $amount / $this->rates[$from];

        return round($amountInBase * $this->rates[$to], 2);
    }
}