<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Product;
use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Audit\VesselType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
        $isDownloadable = $request->input('product_type') === 'downloadable';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku',
            'part_number' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'string', 'unique:products,part_number'],
            'description' => 'required|string',
            'product_type' => 'required|in:physical,downloadable',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'brand_id' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'exists:brands,id'],
            'category_id' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'exists:categories,id'],
            'vessel_type_id' => 'nullable|exists:vessel_types,id',
            'images' => 'nullable|string',
            'download_file' => 'nullable|file|max:102400|mimes:pdf,jpg,jpeg,png,gif,webp,mp4,mov,avi,wmv,mkv,zip,doc,docx,xls,xlsx,ppt,pptx,txt,csv',
            'specifications' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['product_type'] === 'downloadable' && !$request->hasFile('download_file')) {
            return back()->withErrors(['download_file' => 'A downloadable file is required for downloadable products.'])->withInput();
        }

        $product = Product::create($this->buildProductPayload($request, $validated));

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
        $isDownloadable = $request->input('product_type') === 'downloadable';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'part_number' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'string', 'unique:products,part_number,' . $product->id],
            'description' => 'required|string',
            'product_type' => 'required|in:physical,downloadable',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'brand_id' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'exists:brands,id'],
            'category_id' => [Rule::requiredIf(!$isDownloadable), 'nullable', 'exists:categories,id'],
            'vessel_type_id' => 'nullable|exists:vessel_types,id',
            'images' => 'nullable|string',
            'download_file' => 'nullable|file|max:102400|mimes:pdf,jpg,jpeg,png,gif,webp,mp4,mov,avi,wmv,mkv,zip,doc,docx,xls,xlsx,ppt,pptx,txt,csv',
            'specifications' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['product_type'] === 'downloadable' && !$request->hasFile('download_file') && !$product->download_file_path) {
            return back()->withErrors(['download_file' => 'A downloadable file is required for downloadable products.'])->withInput();
        }

        $product->update($this->buildProductPayload($request, $validated, $product));

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
                            'product_type' => in_array(($data['product_type'] ?? 'physical'), ['physical', 'downloadable'], true) ? $data['product_type'] : 'physical',
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

    private function buildProductPayload(Request $request, array $validated, ?Product $product = null): array
    {
        $payload = $validated;
        $productType = $validated['product_type'] ?? $product?->product_type;

        $payload['images'] = array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', (string) ($request->images ?? '')) ?: []
        )));
        $payload['specifications'] = $this->decodeSpecifications($request->input('specifications'));
        $payload['is_active'] = $request->boolean('is_active', $product?->is_active ?? true);

        if ($productType === 'downloadable') {
            $payload['part_number'] = $validated['part_number'] ?: $validated['sku'];
            $payload['brand_id'] = null;
            $payload['category_id'] = null;
            $payload['vessel_type_id'] = null;
        }

        unset($payload['download_file']);

        if ($request->hasFile('download_file')) {
            if ($product?->download_file_path) {
                Storage::disk('local')->delete($product->download_file_path);
            }

            $file = $request->file('download_file');
            $path = $file->store('products/downloads', 'local');

            $payload['download_file_path'] = $path;
            $payload['download_file_name'] = $file->getClientOriginalName();
            $payload['download_file_mime_type'] = $file->getMimeType();
            $payload['download_file_size'] = $file->getSize();
        } elseif (($validated['product_type'] ?? $product?->product_type) === 'physical') {
            if ($product?->download_file_path) {
                Storage::disk('local')->delete($product->download_file_path);
            }

            $payload['download_file_path'] = null;
            $payload['download_file_name'] = null;
            $payload['download_file_mime_type'] = null;
            $payload['download_file_size'] = null;
        }

        return $payload;
    }

    private function decodeSpecifications(?string $specifications): ?array
    {
        if (!$specifications) {
            return null;
        }

        $decoded = json_decode($specifications, true);

        return is_array($decoded) ? $decoded : null;
    }
}
