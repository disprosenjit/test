@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">FAQs Management</h1>
            <p class="text-gray-600 mt-1">Manage frequently asked questions for the chatbot</p>
        </div>
        <a href="/admin/faqs/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2">
            <i class="fas fa-plus"></i> Add FAQ
        </a>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex justify-between items-center">
        <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Error Messages -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <h3 class="font-bold text-red-800 mb-2">Errors:</h3>
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($faqs->count() > 0)
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($faqs as $faq)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $faq->question }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $faq->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $faq->display_order }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $faq->view_count }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($faq->is_active)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="/admin/faqs/{{ $faq->id }}/edit" class="text-blue-600 hover:text-blue-800 font-semibold text-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteFaq({{ $faq->id }})" class="text-red-600 hover:text-red-800 font-semibold text-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $faqs->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-question-circle text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg mb-4">No FAQs yet</p>
            <a href="/admin/faqs/create" class="text-blue-600 hover:underline font-semibold">Create the first FAQ →</a>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Delete FAQ?</h3>
        <p class="text-gray-600 mb-6">This action cannot be undone. The FAQ will be permanently deleted.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentDeleteId = null;

    function deleteFaq(faqId) {
        currentDeleteId = faqId;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteForm').action = `/admin/faqs/${faqId}`;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        currentDeleteId = null;
    }
</script>
@endsection
