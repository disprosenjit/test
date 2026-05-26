@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Edit Product</h1>
    <p class="text-gray-600 mt-1">{{ $product->name }}</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="/admin/products/{{ $product->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Product Basic Info -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Product Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name', $product->name) }}">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">SKU *</label>
                    <input type="text" name="sku" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sku') border-red-500 @enderror" value="{{ old('sku', $product->sku) }}">
                    @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Part Number *</label>
                    <input type="text" name="part_number" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('part_number') border-red-500 @enderror" value="{{ old('part_number', $product->part_number) }}">
                    @error('part_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Brand *</label>
                    <select name="brand_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand_id') border-red-500 @enderror">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Vessel Type</label>
                    <select name="vessel_type_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Vessel Type (Optional)</option>
                        @foreach($vesselTypes as $vesselType)
                            <option value="{{ $vesselType->id }}" {{ old('vessel_type_id', $product->vessel_type_id) == $vesselType->id ? 'selected' : '' }}>{{ $vesselType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Pricing Info -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Pricing</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Price (₹) *</label>
                    <input type="number" name="price" step="0.01" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" value="{{ old('price', $product->price) }}">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Cost (₹) *</label>
                    <input type="number" name="cost" step="0.01" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost') border-red-500 @enderror" value="{{ old('cost', $product->cost) }}">
                    @error('cost') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Product Details</h3>
            <div>
                <label class="block text-sm font-semibold mb-2">Description *</label>
                <textarea name="description" required rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Specifications -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Specifications</h3>
            
            {{-- Hidden JSON field to store specifications --}}
            <input type="hidden" name="specifications" id="specificationsInput" value="{{ old('specifications', is_array($product->specifications) ? json_encode($product->specifications) : $product->specifications) }}">
            
            <div class="space-y-3" id="specificationsContainer">
                {{-- Rows will be generated here --}}
            </div>

            <button type="button" 
                    onclick="addSpecificationRow()" 
                    class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Add More
            </button>
        </div>

        <!-- Images URLs -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Images</h3>
            @php
                $imagesList = is_array($product->images) ? implode("\n", $product->images) : $product->images;
            @endphp
            <div>
                <label class="block text-sm font-semibold mb-2">Image URLs (One per line)</label>
                <textarea name="images" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('images', $imagesList) }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Enter image URLs (one URL per line)</p>
            </div>
        </div>

        <!-- Current Images -->
        @if(is_array($product->images) && count($product->images) > 0)
            <div class="space-y-4 mb-8">
                <h3 class="text-lg font-semibold text-gray-900">Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ $image }}" alt="Product" class="w-full h-32 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <p class="text-white text-xs text-center px-2 break-all">{{ $image }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Active Status -->
        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded">
                <span class="ml-3 text-sm font-semibold">Active (Visible to customers)</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                Update Product
            </button>
            <a href="/admin/products" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold">
                Cancel
            </a>
            <a href="/admin/products/{{ $product->id }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold">
                View
            </a>
        </div>
    </form>
</div>

<script>
// Initialize specifications from JSON
document.addEventListener('DOMContentLoaded', function() {
    const specInput = document.getElementById('specificationsInput');
    const container = document.getElementById('specificationsContainer');
    
    let specifications = {};
    
    // Parse existing specifications
    if (specInput.value) {
        try {
            specifications = JSON.parse(specInput.value);
        } catch (e) {
            specifications = {};
        }
    }
    
    // Clear container
    container.innerHTML = '';
    
    // Add rows for existing specifications
    if (Object.keys(specifications).length > 0) {
        Object.entries(specifications).forEach(([spec, value]) => {
            addSpecificationRow(spec, value);
        });
    } else {
        // Add one empty row if no specifications
        addSpecificationRow('', '');
    }
});

function addSpecificationRow(spec = '', value = '') {
    const container = document.getElementById('specificationsContainer');
    const rowId = 'spec-row-' + Date.now();
    
    const row = document.createElement('div');
    row.id = rowId;
    row.className = 'flex gap-3 items-end';
    row.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Spec</label>
            <input type="text" 
                   class="spec-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   placeholder="e.g., Weight, Color, Size" 
                   value="${spec}">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Value</label>
            <input type="text" 
                   class="value-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   placeholder="e.g., 5kg, Red, Large" 
                   value="${value}">
        </div>
        <button type="button" 
                onclick="removeSpecificationRow('${rowId}')" 
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition-colors duration-200">
            <i class="fas fa-trash text-sm"></i>
        </button>
    `;
    
    container.appendChild(row);
    
    // Add input event listener to update hidden field
    row.querySelector('.spec-input').addEventListener('input', updateSpecificationsJSON);
    row.querySelector('.value-input').addEventListener('input', updateSpecificationsJSON);
}

function removeSpecificationRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
        updateSpecificationsJSON();
    }
}

function updateSpecificationsJSON() {
    const container = document.getElementById('specificationsContainer');
    const specInput = document.getElementById('specificationsInput');
    
    const specifications = {};
    
    // Collect all spec/value pairs
    const rows = container.querySelectorAll('[id^="spec-row-"]');
    rows.forEach(row => {
        const spec = row.querySelector('.spec-input').value.trim();
        const value = row.querySelector('.value-input').value.trim();
        
        if (spec && value) {
            specifications[spec] = value;
        }
    });
    
    // Update hidden input with JSON
    specInput.value = JSON.stringify(specifications);
}

// Update JSON before form submission
document.querySelector('form').addEventListener('submit', function() {
    updateSpecificationsJSON();
});
</script>
</div>

@endsection
