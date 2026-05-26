<?php

namespace App\Http\Controllers\Web;

use App\Models\Commerce\Product;
use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Audit\VesselType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display product listing
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Search
        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('sku', 'like', $searchTerm)
                  ->orWhere('part_number', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Filter by brand
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by vessel type
        if ($request->vessel_type_id) {
            $query->where('vessel_type_id', $request->vessel_type_id);
        }

        // Price range filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // In stock filter
        if ($request->in_stock) {
            $query->where('stock_qty', '>', 0);
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'relevance';
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(24);
        
        // Load relationships
        $products->load(['brand', 'category', 'vesselType', 'inventory']);
        
        // Get filter options
        $brands = Brand::all();
        $categories = Category::all();
        $vesselTypes = VesselType::all();

        return view('frontend.products.index', compact('products', 'brands', 'categories', 'vesselTypes'));
    }

    /**
     * Display product detail
     */
    public function show(Product $product)
    {
        // Increment view count
        $product->increment('view_count');

        // Get related products
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
