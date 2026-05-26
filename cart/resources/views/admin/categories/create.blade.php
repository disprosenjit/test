@extends('layouts.admin')

@section('title', 'Add New Category')

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Add New Category</h1>
        <p class="text-gray-600 mt-1">Create a new product category</p>
    </div>
    <a href="/admin/categories" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="/admin/categories" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-2">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @endif"
                           placeholder="e.g., Engine Parts" />
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @endif"
                              placeholder="Category description...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Parent Category (Optional)</label>
                        <select name="parent_id" class="w-full px-4 py-2 border rounded-lg @error('parent_id') border-red-500 @endif">
                            <option value="">No Parent</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0"
                               class="w-full px-4 py-2 border rounded-lg @error('display_order') border-red-500 @endif" />
                        @error('display_order')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Image URL (Optional)</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" 
                           class="w-full px-4 py-2 border rounded-lg @error('image_url') border-red-500 @endif"
                           placeholder="https://..." />
                    @error('image_url')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 rounded">
                        <span class="text-sm font-semibold">Active</span>
                    </label>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-bold text-red-800 mb-2">Please fix the following errors:</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
                        Create Category
                    </button>
                    <a href="/admin/categories" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info -->
    <div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-bold text-blue-900 mb-3">Category Guidelines</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Use clear, descriptive names</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Add meaningful descriptions</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Set display order for sorting</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Upload category image</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Activate when ready</li>
            </ul>
        </div>
    </div>
</div>
@endsection
