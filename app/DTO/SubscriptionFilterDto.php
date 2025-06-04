<?php
declare(strict_types=1);

namespace App\DTO;

class SubscriptionFilterDto
{
    public ?string $search;
    public ?string $sortBy;
    public ?string $sortOrder;
    public ?int $perPage;
    public ?string $frequency;
    public ?float $priceMin;
    public ?float $priceMax;
    public ?string $activeMonth;
    public ?int $top;

    public function __construct(array $data)
    {
        $this->search = $data['search'] ?? null;
        $this->sortBy = $data['sort_by'] ?? null;
        $this->sortOrder = $data['sort_order'] ?? 'asc';
        $this->perPage = isset($data['per_page']) ? (int) $data['per_page'] : null;

        $this->frequency = $data['frequency'] ?? null;
        $this->priceMin = isset($data['price_min']) ? (float)$data['price_min'] : null;
        $this->priceMax = isset($data['price_max']) ? (float)$data['price_max'] : null;

        $this->activeMonth = $data['active_month'] ?? null;
        $this->top = isset($data['top']) ? (int)$data['top'] : null;
    }
}
