<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index(Request $request)
    {
        $query = Category::query();

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

        $categories = $query->orderBy('display_order')->paginate(20);
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|url',
            'parent_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::create([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect('/admin/categories/' . $category->id)->with('success', 'Category created successfully');
    }

    /**
     * Show category details
     */
    public function show(Category $category)
    {
        $category->load('products', 'subcategories');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show edit form
     */
    public function edit(Category $category)
    {
        $categories = Category::where('is_active', true)->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|url',
            'parent_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', $category->is_active)
        ]);

        return redirect('/admin/categories/' . $category->id)->with('success', 'Category updated successfully');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect('/admin/categories')
                ->with('error', 'Cannot delete category with products. Move or delete products first.');
        }

        $category->delete();
        return redirect('/admin/categories')->with('success', 'Category deleted successfully');
    }
}
