@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Create New FAQ</h1>
    <p class="text-gray-600 mt-1">Add a new frequently asked question</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-4">
                @csrf

                <!-- Question -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Question *</label>
                    <input 
                        type="text" 
                        name="question" 
                        placeholder="Enter FAQ question..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 @enderror"
                        value="{{ old('question') }}"
                        maxlength="500"
                    />
                    @error('question')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Max 500 characters</p>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Category *</label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="category" 
                            placeholder="Select or create category..."
                            list="categories"
                            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror"
                            value="{{ old('category') }}"
                        />
                        <datalist id="categories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Answer -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Answer *</label>
                    <textarea 
                        name="answer" 
                        placeholder="Enter detailed answer..."
                        rows="8"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answer') border-red-500 @enderror"
                    >{{ old('answer') }}</textarea>
                    @error('answer')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keywords -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Keywords</label>
                    <input 
                        type="text" 
                        name="keywords" 
                        placeholder="Comma-separated keywords (e.g., pricing, shipping, delivery)..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('keywords') }}"
                    />
                    <p class="text-gray-500 text-xs mt-1">Help users find this answer by adding relevant keywords</p>
                </div>

                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Display Order</label>
                    <input 
                        type="number" 
                        name="display_order" 
                        placeholder="0"
                        min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('display_order', 0) }}"
                    />
                    <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center gap-3">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        />
                        <span class="text-sm font-semibold">Active</span>
                    </label>
                    <p class="text-gray-500 text-xs mt-1">Inactive FAQs won't be shown to users</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                        <i class="fas fa-save mr-2"></i>Create FAQ
                    </button>
                    <a href="/admin/faqs" class="flex-1 text-center border rounded-lg text-gray-700 hover:bg-gray-50 py-2 font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-bold text-blue-900 mb-3">Tips for FAQs</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li>✓ Use clear, concise questions</li>
                <li>✓ Provide detailed and helpful answers</li>
                <li>✓ Add keywords for better searching</li>
                <li>✓ Organize by logical categories</li>
                <li>✓ Keep answers under 500 words</li>
                <li>✓ Test with real users if possible</li>
            </ul>
        </div>
    </div>
</div>
@endsection
