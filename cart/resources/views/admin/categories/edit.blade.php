@extends('layouts.admin')

@section('title', 'Edit Category - ' . $category->name)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Edit Category</h1>
        <p class="text-gray-600 mt-1">{{ $category->name }}</p>
    </div>
    <a href="/admin/categories/{{ $category->id }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="/admin/categories/{{ $category->id }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold mb-2">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                           class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @endif" />
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @endif">{{ old('description', $category->description) }}</textarea>
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
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
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
                        <input type="number" name="display_order" value="{{ old('display_order', $category->display_order ?? 0) }}" min="0"
                               class="w-full px-4 py-2 border rounded-lg @error('display_order') border-red-500 @endif" />
                        @error('display_order')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Image URL (Optional)</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $category->image_url) }}" 
                           class="w-full px-4 py-2 border rounded-lg @error('image_url') border-red-500 @endif" />
                    @if($category->image_url)
                        <div class="mt-2 p-3 bg-gray-50 rounded flex items-center gap-3">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-12 h-12 rounded object-cover">
                            <span class="text-sm text-gray-600">Current image</span>
                        </div>
                    @endif
                    @error('image_url')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
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
                        Update Category
                    </button>
                    <a href="/admin/categories/{{ $category->id }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info -->
    <div>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Category Info</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Slug</p>
                    <p class="font-mono font-semibold">{{ $category->slug }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Status</p>
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="font-semibold">{{ $category->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Updated</p>
                    <p class="font-semibold">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-bold text-red-900 mb-3">Danger Zone</h3>
            <form method="POST" action="/admin/categories/{{ $category->id }}" class="inline-block w-full">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this category?')" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold text-sm">
                    <i class="fas fa-trash mr-2"></i>Delete Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
