<?php

namespace App\Filters;

class ProductFilter
{
    protected $query;

    public function Apply($query, array $filters)
    {
        $this->query = $query;

        if (!empty($filters['discount'])) {
            $query->whereRaw(
                // '((cost_price / compare_price) * 100) >= ?',
                '(tax_rate >= ?)',
                [intval($filters['discount'])]
            );
        }

        // Brand filter
        if (!empty($filters['brand'])) {
            $query->where('brand_id', $filters['brand']);
        }

        // Rate filter
        if (!empty($filters['rate'])) {
            $query->where('rating_count', '>=', $filters['rate']);
        }

        // Price filter
        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $query->whereBetween('cost_price', [
                $filters['min_price'],
                $filters['max_price']
            ]);
        }

        return $query;
    }
    public function sort( $type_sort ){
        match ($type_sort) {
        'price_asc' => $this->query->orderBy('price'),
        'price_desc' => $this->query->orderByDesc('price'),
        'newest' => $this->query->latest(),
        default => null,
    };
    }
}
