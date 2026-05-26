<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Commerce\Product;
use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Audit\VesselType;
use App\Services\ProductSearchService;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'vessel_type_id' => 'nullable|integer|exists:vessel_types,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'in_stock' => 'nullable|boolean',
            'sort_by' => 'nullable|in:relevance,price_asc,price_desc,newest,popular,rating',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $search = new ProductSearchService();

        $search->applyFilters([
            'search' => $request->get('q'),
            'brand_id' => $request->get('brand_id'),
            'category_id' => $request->get('category_id'),
            'vessel_type_id' => $request->get('vessel_type_id'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'in_stock' => $request->boolean('in_stock', false),
        ]);

        $search->sortBy($request->get('sort_by', 'relevance'));
        $products = $search->paginate($request->get('per_page', 24));

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $search = new ProductSearchService();
        $results = $search->search($request->get('q'))->paginate();

        return response()->json($results);
    }

    public function show(int $id)
    {
        $product = Product::with(['brand', 'category', 'vesselType', 'inventory'])
            ->active()
            ->findOrFail($id);

        // Increment view count (can be queued for performance)
        $product->incrementViewCount();

        return response()->json($product);
    }

    public function relatedProducts(int $id)
    {
        $product = Product::active()->findOrFail($id);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(8)
            ->get();

        return response()->json($related);
    }

    public function getBrands()
    {
        $brands = Brand::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json($brands);
    }

    public function getCategories()
    {
        $categories = Category::active()
            ->with('subcategories')
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get();

        return response()->json($categories);
    }

    public function getVesselTypes()
    {
        $types = VesselType::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json($types);
    }
}
