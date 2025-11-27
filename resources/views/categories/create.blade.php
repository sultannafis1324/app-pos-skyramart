<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Create New Category') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Add a new category to organize your products
                </p>
            </div>
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-2.5 px-5 rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 sm:p-8 lg:p-10">
                    <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        
                        <!-- Name Field -->
                        <div class="mb-8">
                            <label for="name" class="block mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       class="bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 pr-4 py-3.5 transition-all duration-200 @error('name') border-red-500 ring-2 ring-red-200 dark:ring-red-800 @enderror" 
                                       placeholder="e.g., Electronics, Fashion, Home & Garden" 
                                       required>
                            </div>
                            @error('name')
                                <div class="flex items-center gap-2 mt-2.5 text-sm text-red-600 dark:text-red-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <p class="mt-2.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Enter a descriptive name for the category
                            </p>
                        </div>

                        <!-- Status Field -->
                        <div class="mb-10">
                            <label class="block mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                                Status
                            </label>
                            <div class="relative bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl p-5 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center h-6 mt-0.5">
                                            <input type="hidden" name="is_active" value="0">
                                            <input id="is_active" 
                                                   type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', 1) ? 'checked' : '' }}
                                                   class="w-5 h-5 text-blue-600 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-500 rounded-lg focus:ring-3 focus:ring-blue-500 dark:focus:ring-blue-600 cursor-pointer transition-all duration-200">
                                        </div>
                                        <div class="flex-1">
                                            <label for="is_active" class="font-semibold text-gray-900 dark:text-white cursor-pointer flex items-center gap-2">
                                                Active Category
                                                <span id="statusBadge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                    Active
                                                </span>
                                            </label>
                                            <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">
                                                Active categories are visible to users and can be assigned to products
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @error('is_active')
                                    <div class="flex items-center gap-2 mt-3 text-sm text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t-2 border-gray-100 dark:border-gray-700">
                            <a href="{{ route('categories.index') }}" 
                               class="inline-flex items-center justify-center gap-2 py-3 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300 focus:outline-none bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center gap-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 font-semibold rounded-xl text-sm px-6 py-3 focus:outline-none transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Helper Card -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-5 sm:p-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">Quick Tips</h3>
                        <ul class="space-y-1.5 text-sm text-blue-800 dark:text-blue-300">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Use clear, descriptive names that help users understand the category
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Categories can be deactivated later without deleting associated products
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Only active categories will be shown in product creation forms
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Status badge update
        const checkbox = document.getElementById('is_active');
        const statusBadge = document.getElementById('statusBadge');
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Active';
                statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            } else {
                statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Inactive';
                statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
            }
        });

        // Enhanced form validation
        const form = document.getElementById('categoryForm');
        const nameField = document.getElementById('name');
        
        form.addEventListener('submit', function(e) {
            if (nameField.value.trim() === '') {
                e.preventDefault();
                nameField.focus();
                showError(nameField, 'Category name is required');
            }
        });

        // Real-time validation with smooth animations
        nameField.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                removeError(this);
            }
        });

        nameField.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, 'Category name is required');
            } else {
                removeError(this);
            }
        });

        function showError(field, message) {
            const existingError = field.parentNode.parentNode.querySelector('.error-message');
            if (!existingError) {
                field.classList.add('border-red-500', 'ring-2', 'ring-red-200', 'dark:ring-red-800');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flex items-center gap-2 mt-2.5 text-sm text-red-600 dark:text-red-400 error-message animate-fade-in';
                errorDiv.innerHTML = `
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    ${message}
                `;
                field.parentNode.parentNode.appendChild(errorDiv);
            }
        }

        function removeError(field) {
            field.classList.remove('border-red-500', 'ring-2', 'ring-red-200', 'dark:ring-red-800');
            const existingError = field.parentNode.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
        }
    </script>
    @endpush
</x-app-layout>