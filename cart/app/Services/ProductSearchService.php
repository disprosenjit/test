<?php

namespace App\Services;

use App\Models\Commerce\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

class ProductSearchService
{
    protected Builder $query;

    public function __construct()
    {
        $this->query = Product::query()->active();
    }

    /**
     * Search on product name, description, sku, and part number
     */
    public function search(string $searchTerm): self
    {
        if (empty($searchTerm)) {
            return $this;
        }

        $searchPattern = "%{$searchTerm}%";
        $this->query->where(function (Builder $query) use ($searchPattern) {
            $query->where('name', 'LIKE', $searchPattern)
                ->orWhere('description', 'LIKE', $searchPattern)
                ->orWhere('sku', 'LIKE', $searchPattern)
                ->orWhere('part_number', 'LIKE', $searchPattern);
        });

        return $this;
    }

    /**
     * Filter by brand
     */
    public function filterByBrand(int $brandId): self
    {
        if ($brandId) {
            $this->query->where('brand_id', $brandId);
        }
        return $this;
    }

    /**
     * Filter by category
     */
    public function filterByCategory(int $categoryId): self
    {
        if ($categoryId) {
            $this->query->where('category_id', $categoryId);
        }
        return $this;
    }

    /**
     * Filter by vessel type
     */
    public function filterByVesselType(int $vesselTypeId): self
    {
        if ($vesselTypeId) {
            $this->query->where('vessel_type_id', $vesselTypeId);
        }
        return $this;
    }

    /**
     * Filter by price range
     */
    public function filterByPrice(float $minPrice, float $maxPrice): self
    {
        if ($minPrice >= 0 && $maxPrice > 0) {
            $this->query->whereBetween('price', [$minPrice, $maxPrice]);
        }
        return $this;
    }

    /**
     * Filter by stock availability
     */
    public function inStockOnly(bool $inStock = true): self
    {
        if ($inStock) {
            $this->query->where('stock_qty', '>', 0);
        }
        return $this;
    }

    /**
     * Filter by multiple attributes at once
     */
    public function applyFilters(array $filters): self
    {
        if (isset($filters['search'])) {
            $this->search($filters['search']);
        }

        if (isset($filters['brand_id'])) {
            $this->filterByBrand($filters['brand_id']);
        }

        if (isset($filters['category_id'])) {
            $this->filterByCategory($filters['category_id']);
        }

        if (isset($filters['vessel_type_id'])) {
            $this->filterByVesselType($filters['vessel_type_id']);
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $this->filterByPrice($filters['min_price'], $filters['max_price']);
        }

        if (isset($filters['in_stock'])) {
            $this->inStockOnly((bool) $filters['in_stock']);
        }

        return $this;
    }

    /**
     * Sort results
     */
    public function sortBy(string $sortBy = 'relevance'): self
    {
        switch ($sortBy) {
            case 'price_asc':
                $this->query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $this->query->orderBy('price', 'desc');
                break;
            case 'newest':
                $this->query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $this->query->orderBy('view_count', 'desc');
                break;
            case 'rating':
                $this->query->orderBy('rating', 'desc');
                break;
            case 'name_asc':
                $this->query->orderBy('name', 'asc');
                break;
            case 'relevance':
            default:
                // Relevance is handled by full-text search scoring
                break;
        }

        return $this;
    }

    /**
     * Get paginated results
     */
    public function paginate(int $perPage = 24, string $pageName = 'page', int $page = null)
    {
        $page = $page ?? Paginator::resolveCurrentPage($pageName);
        return $this->query->paginate($perPage, ['*'], $pageName, $page);
    }

    /**
     * Get all results
     */
    public function get()
    {
        return $this->query->get();
    }

    /**
     * Get total count without pagination
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Execute the query and return results
     */
    public function execute()
    {
        return $this->query;
    }
}
