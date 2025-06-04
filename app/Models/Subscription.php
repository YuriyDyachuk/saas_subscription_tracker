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

    public function scopeActiveInMonth(Builder $query, ?string $yearMonth): Builder
    {
        if (!$yearMonth) {
            return $query;
        }

        $startOfMonth = \Carbon\Carbon::parse($yearMonth . '-01')->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        return $query->where('start_date', '<=', $endOfMonth);
    }

    /* Accessors */
    public function getStartDateAttribute(): ?string
    {
        return \Carbon\Carbon::parse($this->attributes['start_date'])->format('Y-m-d') ?? null;
    }
}
