@props([
    'viewRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteMessage' => 'Are you sure you want to delete this item?',
    'size' => 'sm' // sm, md, lg
])

@php
    $sizeClasses = match($size) {
        'lg' => 'text-lg',
        'md' => 'text-base',
        default => 'text-sm',
    };
@endphp

<div class="flex items-center justify-center gap-3">
    {{-- View Action --}}
    @if($viewRoute)
        <a href="{{ $viewRoute }}" 
           class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-md transition-colors duration-200"
           title="View">
            <i class="fas fa-eye {{ $sizeClasses }}"></i>
        </a>
    @endif

    {{-- Edit Action --}}
    @if($editRoute)
        <a href="{{ $editRoute }}" 
           class="text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 p-2 rounded-md transition-colors duration-200"
           title="Edit">
            <i class="fas fa-edit {{ $sizeClasses }}"></i>
        </a>
    @endif

    {{-- Delete Action --}}
    @if($deleteRoute)
        <button type="button"
                onclick="openDeleteModal('delete-modal-{{ uniqid() }}', '{{ $deleteMessage }}')"
                class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-md transition-colors duration-200"
                title="Delete">
            <i class="fas fa-trash {{ $sizeClasses }}"></i>
        </button>

        {{-- Hidden Form for Delete --}}
        <form id="delete-modal-{{ uniqid() }}" method="POST" action="{{ $deleteRoute }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

@pushOnce('scripts')
<script>
    function openDeleteModal(formId, message) {
        const modal = document.getElementById('deleteConfirmModal');
        const modalMessage = document.getElementById('deleteModalMessage');
        const confirmBtn = document.getElementById('deleteConfirmBtn');
        
        // Set the message
        if (modalMessage) {
            modalMessage.textContent = message;
        }
        
        // Update the confirm button to submit the specific form
        confirmBtn.onclick = function() {
            document.getElementById(formId).submit();
        };
        
        // Show modal
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteConfirmModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeDeleteModal();
                }
            });
        }
    });
</script>
@endPushOnce
