<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    /**
     * Display all brands
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $brands = $query->orderBy('name')->paginate(20);
        $stats = [
            'total' => Brand::count(),
            'active' => Brand::where('is_active', true)->count(),
            'inactive' => Brand::where('is_active', false)->count(),
        ];

        return view('admin.brands.index', compact('brands', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store new brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string|url',
            'is_active' => 'nullable|boolean',
        ]);

        $brand = Brand::create([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect('/admin/brands/' . $brand->id)->with('success', 'Brand created successfully');
    }

    /**
     * Show brand details
     */
    public function show(Brand $brand)
    {
        $brand->load('products');
        $brand->product_count = $brand->products()->count();
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show edit form
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string|url',
            'is_active' => 'nullable|boolean',
        ]);

        $brand->update([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', $brand->is_active)
        ]);

        return redirect('/admin/brands/' . $brand->id)->with('success', 'Brand updated successfully');
    }

    /**
     * Delete brand
     */
    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect('/admin/brands')
                ->with('error', 'Cannot delete brand with products. Move or delete products first.');
        }

        $brand->delete();
        return redirect('/admin/brands')->with('success', 'Brand deleted successfully');
    }
}
