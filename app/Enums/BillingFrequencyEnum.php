<?php
declare(strict_types=1);

namespace App\Enums;

enum BillingFrequencyEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public static function values(): array
    {
        return [
            self::DAILY->value,
            self::WEEKLY->value,
            self::MONTHLY->value,
            self::YEARLY->value,
        ];
    }

    public function toInterval(): \DateInterval
    {
        return match($this) {
            self::DAILY => \DateInterval::createFromDateString('1 day'),
            self::WEEKLY => \DateInterval::createFromDateString('1 week'),
            self::MONTHLY => \DateInterval::createFromDateString('1 month'),
            self::YEARLY => \DateInterval::createFromDateString('1 year'),
            default => throw new \InvalidArgumentException("Unsupported frequency: $this->value"),
        };
    }
}
