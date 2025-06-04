<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
        'currency',
        'billing_frequency',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'price' => 'decimal:2',
        'billing_frequency' => \App\Enums\BillingFrequencyEnum::class,
    ];

    /* Scopes */
    public function scopeSearch($query, ?string $term): Builder
    {
        return $term
            ? $query->where('name', 'like', '%' . $term . '%')
            : $query;
    }

    public function scopeSortByColumn($query, ?string $column, string $direction = 'asc'): Builder
    {
        return $column
            ? $query->orderBy($column, $direction)
            : $query;
    }

    public function scopeFrequency(Builder $query, ?string $frequency): Builder
    {
        return $frequency
            ? $query->where('billing_frequency', $frequency)
            : $query;
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null && $max !== null) {
            return $query->whereBetween('price', [$min, $max]);
        } elseif ($min !== null) {
            return $query->where('price', '>=', $min);
        } elseif ($max !== null) {
            return $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeActiveInMonth($query, $year, $month)
    {
        if (!$month) {
            return $query;
        }

        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $startOfMonth = "$year-$month-01";
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

        return $query->whereBetween('start_date', [$startOfMonth, $endOfMonth]);
    }

    /* Accessors */
    public function getStartDateAttribute(): ?string
    {
        return \Carbon\Carbon::parse($this->attributes['start_date'])->format('Y-m-d') ?? null;
    }
}
