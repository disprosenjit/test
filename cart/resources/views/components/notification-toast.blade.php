<!-- Toast Notification Component -->
<div id="notification-toast" class="fixed top-4 right-4 z-50 hidden">
    <div id="toast-content" class="bg-white rounded-lg shadow-lg p-4 min-w-80 flex items-start gap-3">
        <div id="toast-icon" class="mt-0.5"></div>
        <div class="flex-1">
            <h3 id="toast-title" class="font-semibold mb-1"></h3>
            <p id="toast-message" class="text-sm"></p>
        </div>
        <button id="toast-close" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
    function showNotification(message, type = 'info', title = '') {
        const toast = document.getElementById('notification-toast');
        const toastContent = document.getElementById('toast-content');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        const toastClose = document.getElementById('toast-close');

        // Set title if provided, otherwise use type as title
        if (title) {
            toastTitle.textContent = title;
        } else {
            toastTitle.textContent = type.charAt(0).toUpperCase() + type.slice(1);
        }
        
        toastMessage.textContent = message;

        // Set colors and icon based on type
        toastContent.className = 'bg-white rounded-lg shadow-lg p-4 min-w-80 flex items-start gap-3 border-l-4';
        
        switch(type) {
            case 'success':
                toastContent.classList.add('border-green-500');
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                toastTitle.classList.add('text-green-800');
                toastMessage.classList.add('text-green-700');
                break;
            case 'error':
                toastContent.classList.add('border-red-500');
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                toastTitle.classList.add('text-red-800');
                toastMessage.classList.add('text-red-700');
                break;
            case 'warning':
                toastContent.classList.add('border-yellow-500');
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                toastTitle.classList.add('text-yellow-800');
                toastMessage.classList.add('text-yellow-700');
                break;
            case 'info':
            default:
                toastContent.classList.add('border-blue-500');
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
                toastTitle.classList.add('text-blue-800');
                toastMessage.classList.add('text-blue-700');
        }

        // Show the toast
        toast.classList.remove('hidden');

        // Close button handler
        toastClose.onclick = () => {
            toast.classList.add('hidden');
        };

        // Auto-hide after 5 seconds
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 5000);
    }

    // Make function globally available
    window.showNotification = showNotification;
</script>
