<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Edit Category') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update category information and settings
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
            <!-- Category Info Card -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-800 border-2 border-blue-200 dark:border-gray-600 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Category ID</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">#{{ $category->id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Created</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Products</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->products_count ?? $category->products()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 sm:p-8 lg:p-10">
                    <form action="{{ route('categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
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
                                       value="{{ old('name', $category->name) }}"
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
                        <div class="mb-8">
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
                                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                                   class="w-5 h-5 text-blue-600 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-500 rounded-lg focus:ring-3 focus:ring-blue-500 dark:focus:ring-blue-600 cursor-pointer transition-all duration-200">
                                        </div>
                                        <div class="flex-1">
                                            <label for="is_active" class="font-semibold text-gray-900 dark:text-white cursor-pointer flex items-center gap-2">
                                                Active Category
                                                <span id="statusBadge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ old('is_active', $category->is_active) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ old('is_active', $category->is_active) ? 'bg-green-500 animate-pulse' : 'bg-gray-500' }}"></span>
                                                    {{ old('is_active', $category->is_active) ? 'Active' : 'Inactive' }}
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

                        <!-- Warning for categories with products -->
                        @if($category->products()->count() > 0)
                            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl p-5 shadow-sm">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-800 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-yellow-900 dark:text-yellow-200 mb-1.5">
                                            Warning: Category Has Associated Products
                                        </h4>
                                        <p class="text-sm text-yellow-800 dark:text-yellow-300 leading-relaxed">
                                            This category has <strong>{{ $category->products()->count() }} associated product(s)</strong>. 
                                            Deactivating this category may affect product visibility. Deleting is not allowed while products are assigned.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 pt-6 border-t-2 border-gray-100 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Update Category
                                </button>
                            </div>
                            
                            @if($category->products()->count() === 0)
                                <button type="button" 
                                        onclick="deleteCategory()"
                                        class="inline-flex items-center justify-center gap-2 text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 font-semibold rounded-xl text-sm px-6 py-3 focus:outline-none transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Category
                                </button>
                            @else
                                <div class="relative group">
                                    <button type="button" 
                                            disabled
                                            class="inline-flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500 bg-gray-200 dark:bg-gray-700 cursor-not-allowed font-semibold rounded-xl text-sm px-6 py-3 opacity-60">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Delete Category
                                    </button>
                                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block z-10">
                                        <div class="bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap shadow-lg">
                                            Cannot delete category with products
                                            <svg class="absolute text-gray-900 dark:text-gray-700 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255">
                                                <polygon class="fill-current" points="0,0 127.5,127.5 255,0"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">Important Notes</h3>
                        <ul class="space-y-1.5 text-sm text-blue-800 dark:text-blue-300">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Changes will affect all products using this category
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Inactive categories won't appear in product forms
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Remove all products before deleting the category
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm transition-all duration-300">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md px-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all scale-95 opacity-0" id="modalContent">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-t-2xl px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Delete Category</h3>
                            <p class="text-red-100 text-sm">This action cannot be undone</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl p-4 mb-5">
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            Are you sure you want to delete the category 
                            <strong class="font-bold text-gray-900 dark:text-white">"{{ $category->name }}"</strong>?
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Once deleted, this category will be permanently removed from the system
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 rounded-b-2xl flex flex-col-reverse sm:flex-row gap-3">
                    <button type="button" 
                            onclick="cancelDelete()"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <form id="deleteForm" action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Permanently
                        </button>
                    </form>
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

        // Modal functions with smooth animations
        function deleteCategory() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function cancelDelete() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal on backdrop click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelDelete();
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
                errorDiv.className = 'flex items-center gap-2 mt-2.5 text-sm text-red-600 dark:text-red-400 error-message';
                errorDiv.style.animation = 'fadeIn 0.3s ease-in';
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
                existingError.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => existingError.remove(), 300);
            }
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
</x-app-layout>