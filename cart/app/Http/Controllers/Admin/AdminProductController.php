<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Product;
use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Audit\VesselType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    /**
     * Display all products
     */
    public function index()
    {
        $products = Product::with(['brand', 'category', 'inventory'])->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $vesselTypes = VesselType::all();
        return view('admin.products.create', compact('brands', 'categories', 'vesselTypes'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku',
            'part_number' => 'required|string|unique:products,part_number',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'vessel_type_id' => 'nullable|exists:vessel_types,id',
            'images' => 'nullable|string',
            'specifications' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Parse images from textarea (one per line)
        $images = array_filter(array_map('trim', explode('\n', $request->images ?? '')));
        
        // Parse specifications JSON
        $specifications = null;
        if ($request->specifications) {
            try {
                $specifications = json_decode($request->specifications, true);
            } catch (\Exception $e) {
                $specifications = null;
            }
        }

        $product = Product::create([
            ...$validated,
            'images' => $images,
            'specifications' => $specifications,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect('/admin/products/' . $product->id)->with('success', 'Product created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $vesselTypes = VesselType::all();
        return view('admin.products.edit', compact('product', 'brands', 'categories', 'vesselTypes'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'part_number' => 'required|unique:products,part_number,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'vessel_type_id' => 'nullable|exists:vessel_types,id',
            'images' => 'nullable|string',
            'specifications' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Parse images from textarea (one per line)
        $images = array_filter(array_map('trim', explode('\n', $request->images ?? '')));
        
        // Parse specifications JSON
        $specifications = null;
        if ($request->specifications) {
            try {
                $specifications = json_decode($request->specifications, true);
            } catch (\Exception $e) {
                $specifications = null;
            }
        }

        $product->update([
            ...$validated,
            'images' => $images,
            'specifications' => $specifications,
            'is_active' => $request->boolean('is_active', $product->is_active)
        ]);

        return redirect('/admin/products/' . $product->id)->with('success', 'Product updated successfully');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/products')->with('success', 'Product deleted successfully');
    }

    /**
     * Show bulk upload form
     */
    public function bulkUploadForm()
    {
        return view('admin.products.bulk-upload');
    }

    /**
     * Bulk upload via CSV
     */
    public function bulkUpload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $file = fopen($request->file('file')->getRealPath(), 'r');
            if (!$file) {
                throw new \Exception('Unable to open file');
            }

            $header = fgetcsv($file);
            if (!$header) {
                fclose($file);
                return back()->withErrors(['file' => 'CSV file is empty or invalid']);
            }

            $imported = 0;
            $rowNum = 2;
            $errors = [];

            while (($row = fgetcsv($file)) !== false) {
                try {
                    // Skip empty rows
                    if (count(array_filter($row)) === 0) {
                        continue;
                    }

                    $data = array_combine($header, $row);
                    
                    if (empty($data['sku']) || empty($data['name'])) {
                        $errors[] = "Row $rowNum: SKU and Name are required";
                        $rowNum++;
                        continue;
                    }

                    Product::updateOrCreate(
                        ['sku' => trim($data['sku'])],
                        [
                            'name' => trim($data['name']),
                            'part_number' => trim($data['part_number'] ?? $data['sku']),
                            'description' => trim($data['description'] ?? ''),
                            'price' => floatval($data['price'] ?? 0),
                            'cost' => floatval($data['cost'] ?? 0),
                            'brand_id' => intval($data['brand_id'] ?? 1),
                            'category_id' => intval($data['category_id'] ?? 1),
                            'vessel_type_id' => (!empty($data['vessel_type_id']) && $data['vessel_type_id'] != '' && $data['vessel_type_id'] != '0') ? intval($data['vessel_type_id']) : null,
                            'is_active' => true
                        ]
                    );
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNum: " . $e->getMessage();
                }
                
                $rowNum++;
            }

            fclose($file);
            
            if ($imported === 0) {
                return back()->withErrors(['file' => 'No valid products found in the file. ' . implode(' | ', $errors)]);
            }

            $message = "$imported products imported successfully";
            if (!empty($errors)) {
                $message .= ". Some rows had warnings.";
            }
            
            return redirect('/admin/products')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'vesselType', 'inventory']);
        return view('admin.products.show', compact('product'));
    }
}
