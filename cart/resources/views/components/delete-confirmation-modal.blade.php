{{-- Delete Confirmation Modal --}}
<div id="deleteConfirmModal" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-200 flex items-center">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-4 text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Delete</h3>
            <p id="deleteModalMessage" class="text-gray-600 text-sm">
                Are you sure you want to delete this item? This action cannot be undone.
            </p>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 flex gap-3 justify-end">
            <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                Cancel
            </button>
            <button type="button"
                    id="deleteConfirmBtn"
                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-trash mr-2"></i> Delete
            </button>
        </div>
    </div>
</div>
