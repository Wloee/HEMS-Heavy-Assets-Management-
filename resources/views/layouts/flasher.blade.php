{{-- resources/views/partials/flash-messages.blade.php --}}

{{-- Flash Messages Container --}}
<div id="flash-messages-container" class="fixed top-20 right-4 z-[9999] space-y-3">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="flash-message bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="success" id="flash-success">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Berhasil!</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-4 text-green-400 hover:text-green-600 focus:outline-none" onclick="closeFlashMessage('flash-success')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="flash-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="error" id="flash-error">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Terjadi Kesalahan!</h4>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-4 text-red-400 hover:text-red-600 focus:outline-none" onclick="closeFlashMessage('flash-error')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Warning Message --}}
    @if(session('warning'))
        <div class="flash-message bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="warning" id="flash-warning">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Peringatan!</h4>
                    <p class="text-sm">{{ session('warning') }}</p>
                </div>
                <button type="button" class="ml-4 text-yellow-400 hover:text-yellow-600 focus:outline-none" onclick="closeFlashMessage('flash-warning')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Info Message --}}
    @if(session('info'))
        <div class="flash-message bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="info" id="flash-info">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Informasi</h4>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
                <button type="button" class="ml-4 text-blue-400 hover:text-blue-600 focus:outline-none" onclick="closeFlashMessage('flash-info')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="flash-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="validation" id="flash-validation">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Validasi Error!</h4>
                    <ul class="text-sm mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="ml-4 text-red-400 hover:text-red-600 focus:outline-none" onclick="closeFlashMessage('flash-validation')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript untuk Flash Messages --}}
<script>
// Auto hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');

    flashMessages.forEach(function(message) {
        // Auto hide after 5 seconds (except for error messages)
        const messageType = message.getAttribute('data-type');
        if (messageType !== 'error' && messageType !== 'validation') {
            setTimeout(function() {
                hideFlashMessage(message);
            }, 5000);
        }
    });
});

// Function to close flash message manually
function closeFlashMessage(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        hideFlashMessage(element);
    }
}

// Function to hide flash message with animation
function hideFlashMessage(element) {
    element.style.transform = 'translateX(100%)';
    element.style.opacity = '0';

    setTimeout(function() {
        element.remove();
    }, 300);
}

// Function to show flash message programmatically (for AJAX requests)
function showFlashMessage(type, title, message, autoHide = true) {
    const container = document.getElementById('flash-messages-container');
    if (!container) return;

    const colors = {
        success: { bg: 'bg-green-100', border: 'border-green-500', text: 'text-green-700', icon: 'text-green-400' },
        error: { bg: 'bg-red-100', border: 'border-red-500', text: 'text-red-700', icon: 'text-red-400' },
        warning: { bg: 'bg-yellow-100', border: 'border-yellow-500', text: 'text-yellow-700', icon: 'text-yellow-400' },
        info: { bg: 'bg-blue-100', border: 'border-blue-500', text: 'text-blue-700', icon: 'text-blue-400' }
    };

    const color = colors[type] || colors.info;
    const uniqueId = 'flash-' + type + '-' + Date.now();

    const icons = {
        success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
        error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
        warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
        info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
    };

    const flashHTML = `
        <div class="flash-message ${color.bg} border-l-4 ${color.border} ${color.text} p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="${type}" id="${uniqueId}">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        ${icons[type]}
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">${title}</h4>
                    <p class="text-sm">${message}</p>
                </div>
                <button type="button" class="ml-4 ${color.icon} hover:opacity-75 focus:outline-none" onclick="closeFlashMessage('${uniqueId}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', flashHTML);

    // Auto hide if specified
    if (autoHide && type !== 'error') {
        setTimeout(function() {
            const element = document.getElementById(uniqueId);
            if (element) {
                hideFlashMessage(element);
            }
        }, 5000);
    }
}

// For AJAX error handling
function showValidationErrors(errors) {
    let errorMessages = '';
    Object.keys(errors).forEach(function(key) {
        errors[key].forEach(function(error) {
            errorMessages += `<li>${error}</li>`;
        });
    });

    const container = document.getElementById('flash-messages-container');
    if (!container) return;

    const uniqueId = 'flash-validation-' + Date.now();
    const flashHTML = `
        <div class="flash-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ease-in-out"
             data-type="validation" id="${uniqueId}">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium">Validasi Error!</h4>
                    <ul class="text-sm mt-1 list-disc list-inside">
                        ${errorMessages}
                    </ul>
                </div>
                <button type="button" class="ml-4 text-red-400 hover:text-red-600 focus:outline-none" onclick="closeFlashMessage('${uniqueId}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', flashHTML);
}
</script>

{{-- CSS untuk animasi tambahan --}}
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

    }
.flash-message {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.flash-message:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
</style>
