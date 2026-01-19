<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    protected Request $request;

    protected array $allowedSorts = [
        'price_asc',
        'price_desc',
        'rating_desc',
        'newest',
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
         $this->search($query)
            ->price($query)
            ->category($query)
            ->inStock($query)
            ->rating($query)
            ->sort($query);

         return $query;
    }

    protected function search(Builder $query): self
    {
        if ($this->request->filled('q')) {
            $query->where('name', 'LIKE', '%' . $this->request->q . '%');
        }

        return $this;
    }

    protected function price(Builder $query): self
    {
        if ($this->request->filled('price_from')) {
            $query->where('price', '>=', $this->request->price_from);
        }

        if ($this->request->filled('price_to')) {
            $query->where('price', '<=', $this->request->price_to);
        }

        return $this;
    }

    protected function category(Builder $query): self
    {
        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }

        return $this;
    }

    protected function inStock(Builder $query): self
    {
        if ($this->request->filled('in_stock')) {
            $query->where(
                'in_stock',
                filter_var($this->request->in_stock, FILTER_VALIDATE_BOOLEAN)
            );
        }

        return $this;
    }

    protected function rating(Builder $query): self
    {
        if ($this->request->filled('rating_from')) {
            $query->where('rating', '>=', $this->request->rating_from);
        }
        if ($this->request->filled('rating_to')) {
            $query->where('rating', '<=', $this->request->rating_to);
        }

        return $this;
    }

    protected function sort(Builder $query): self
    {
        if (!in_array($this->request->sort, $this->allowedSorts)) {
            return $this;
        }

        match ($this->request->sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating_desc' => $query->orderBy('rating', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
        };

        return $this;
    }
}

