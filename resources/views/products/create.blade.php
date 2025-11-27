<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Add New Product') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new product in your inventory</p>
            </div>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8">
                    
                    <form id="product-form" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-blue-500 rounded-full mr-3"></div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Basic Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Product Code -->
                                <div class="form-group">
                                    <label for="product_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Product Code <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                        </div>
                                        <input type="text" name="product_code" id="product_code" required
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="e.g., PRD-001">
                                    </div>
                                    <div id="product_code_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="product_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Product Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <input type="text" name="product_name" id="product_name" required
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="Enter product name">
                                    </div>
                                    <div id="product_name_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <select name="category_id" id="category_id" required
                                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                    <div id="category_id_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Supplier -->
                                <div class="form-group">
                                    <label for="supplier_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Supplier
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <select name="supplier_id" id="supplier_id"
                                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200">
                                            <option value="">Select Supplier</option>
                                        </select>
                                    </div>
                                    <div id="supplier_id_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-green-500 rounded-full mr-3"></div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pricing Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Purchase Price -->
                                <div class="form-group">
                                    <label for="purchase_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Purchase Price
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                                               class="block w-full pl-12 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="0.00">
                                    </div>
                                    <div id="purchase_price_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Selling Price -->
                                <div class="form-group">
                                    <label for="selling_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Selling Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="selling_price" id="selling_price" step="0.01" min="0" required
                                               class="block w-full pl-12 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="0.00">
                                    </div>
                                    <div id="selling_price_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Inventory Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-purple-500 rounded-full mr-3"></div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Inventory Management</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <!-- Stock -->
                                <div class="form-group">
                                    <label for="stock" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Stock Quantity
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <input type="number" name="stock" id="stock" min="0"
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="0">
                                    </div>
                                    <div id="stock_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Minimum Stock -->
                                <div class="form-group">
                                    <label for="minimum_stock" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Minimum Stock
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <input type="number" name="minimum_stock" id="minimum_stock" min="0"
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="0">
                                    </div>
                                    <div id="minimum_stock_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                                <!-- Unit -->
                                <div class="form-group">
                                    <label for="unit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Unit
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                        </div>
                                        <input type="text" name="unit" id="unit" maxlength="10"
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                               placeholder="pcs, kg, liter">
                                    </div>
                                    <div id="unit_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Expiry Date Section -->
<div class="mb-8">
    <div class="flex items-center mb-6">
        <div class="w-1 h-8 bg-red-500 rounded-full mr-3"></div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Expiry Information</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Has Expiry Toggle -->
        <div class="form-group md:col-span-2">
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Product Has Expiry Date</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Enable if this product has expiration date</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <label for="has_expiry" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="has_expiry" id="has_expiry" value="1" class="sr-only">
                            <div class="block bg-gray-300 dark:bg-gray-600 w-14 h-8 rounded-full toggle-bg"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Expiry Date Field -->
        <div class="form-group md:col-span-2" id="expiry-date-container" style="display: none;">
            <label for="expiry_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Expiry Date <span class="text-red-500" id="expiry-required-star">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="date" name="expiry_date" id="expiry_date"
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            <div id="expiry_date_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span></span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Expiry date must be a future date
            </p>
        </div>
    </div>
</div>

                        <!-- Additional Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-orange-500 rounded-full mr-3"></div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Additional Information</h3>
                            </div>
                            
                            <!-- Barcode -->
                            <div class="form-group mb-6">
                                <label for="barcode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Barcode
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="barcode" id="barcode"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                           placeholder="Enter barcode number">
                                </div>
                                <div id="barcode_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-6">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="4"
                                          class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-all duration-200"
                                          placeholder="Enter product description..."></textarea>
                                <div id="description_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span></span>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                    <label for="image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Product Image
                                    </label>
                                    <div class="flex items-start space-x-6">
                                        <div id="image-preview" class="shrink-0 hidden">
                                            <img id="preview_img" src="" alt="Image Preview" class="h-32 w-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <label for="image" class="cursor-pointer">
                                            <div class="mt-1 flex-grow justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex justify-center text-sm text-gray-600 dark:text-gray-400">
                                                        <p class="relative font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none">
                                                            <span>Upload a file</span>
                                                            <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                                                        </p>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="image_error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <span></span>
                                    </div>
                                </div>
                            </div>

                        <!-- Status Section -->
                        <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Product Status</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Enable this product for sale</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label for="is_active" class="flex items-center cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only">
                                            <div class="block bg-gray-300 dark:bg-gray-600 w-14 h-8 rounded-full toggle-bg"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="window.history.back()" 
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" id="submit-btn"
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span id="submit-text">Save Product</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>

        /* Preserve line breaks di textarea */
        #description {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        /* Custom toggle switch styling */
        input:checked ~ .toggle-bg {
            background-color: #3B82F6;
        }
        input:checked ~ .dot {
            transform: translateX(100%);
        }
        
        /* Smooth transitions for form inputs */
        input, select, textarea {
            transition: all 0.2s ease-in-out;
        }
        
        input:focus, select:focus, textarea:focus {
            transform: translateY(-1px);
        }
        
        /* Error message animation */
        [id$="_error"]:not(.hidden) {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Image preview styling */
        #image-preview img {
            transition: all 0.3s ease-in-out;
        }
        
        #image-preview img:hover {
            transform: scale(1.05);
        }
    </style>

    <script>

        const imageInput = document.getElementById('image');
    const imagePreviewContainer = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview_img');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            // Tampilkan container preview
            imagePreviewContainer.classList.remove('hidden');

            reader.onload = function(e) {
                // Set src gambar dengan data dari file yang dipilih
                previewImage.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    });

    // Fungsi untuk generate product code otomatis
function generateProductCode(productName) {
    if (!productName) return '';
    
    // Ambil huruf kapital dan huruf pertama dari setiap kata
    let words = productName.trim().toUpperCase().split(/\s+/);
    let code = '';
    
    // Strategi 1: Ambil huruf pertama setiap kata (max 3 huruf)
    if (words.length >= 2) {
        code = words.slice(0, 3).map(word => word[0]).join('');
    } else {
        // Strategi 2: Ambil 2-3 huruf pertama dari kata tunggal
        code = words[0].substring(0, 3);
    }
    
    // Generate angka random 3 digit
    const randomNum = Math.floor(Math.random() * 900) + 100; // 100-999
    
    return `${code}-${randomNum}`;
}

// Event listener untuk auto-generate saat user mengetik nama produk
document.getElementById('product_name').addEventListener('input', function(e) {
    const productName = e.target.value;
    const productCodeInput = document.getElementById('product_code');
    
    // Hanya generate jika field product_code masih kosong atau belum diubah manual
    if (!productCodeInput.dataset.manuallyEdited) {
        productCodeInput.value = generateProductCode(productName);
    }
});

// Tandai jika user mengedit product_code secara manual
document.getElementById('product_code').addEventListener('input', function(e) {
    if (e.target.value) {
        e.target.dataset.manuallyEdited = 'true';
    }
});

// Optional: Reset manual edit flag saat product_name diubah
document.getElementById('product_name').addEventListener('change', function() {
    const productCodeInput = document.getElementById('product_code');
    delete productCodeInput.dataset.manuallyEdited;
});

// Fungsi generate barcode (EAN-13 format atau custom)
function generateBarcode(productCode) {
    if (!productCode) return '';
    
    // Opsi 1: Based on timestamp + random (13 digit)
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return timestamp + random;
    
    // Opsi 2: Based on product code (lebih simple)
    // const cleanCode = productCode.replace(/[^A-Z0-9]/g, '');
    // const random = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
    // return cleanCode + random;
}

// Update event listener product_name
document.getElementById('product_name').addEventListener('input', function(e) {
    const productName = e.target.value;
    const productCodeInput = document.getElementById('product_code');
    const barcodeInput = document.getElementById('barcode');
    
    if (!productCodeInput.dataset.manuallyEdited) {
        const newCode = generateProductCode(productName);
        productCodeInput.value = newCode;
        
        // Auto-generate barcode juga jika belum diisi manual
        if (!barcodeInput.dataset.manuallyEdited) {
            barcodeInput.value = generateBarcode(newCode);
        }
    }
});

// Tandai jika user mengedit barcode secara manual
document.getElementById('barcode').addEventListener('input', function(e) {
    if (e.target.value) {
        e.target.dataset.manuallyEdited = 'true';
    }
});

// Auto-generate barcode saat product_code berubah
document.getElementById('product_code').addEventListener('change', function(e) {
    const barcodeInput = document.getElementById('barcode');
    if (!barcodeInput.dataset.manuallyEdited) {
        barcodeInput.value = generateBarcode(e.target.value);
    }
});
    
       // Load categories and suppliers
async function loadFormData() {
    try {
        console.log('Starting to load form data...');
        
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        // Load categories
        console.log('Loading categories...');
        const categoriesResponse = await fetch('/api/categories', {
            method: 'GET',
            headers: headers
        });
        
        console.log('Categories response status:', categoriesResponse.status);
        
        if (!categoriesResponse.ok) {
            const errorText = await categoriesResponse.text();
            console.error('Categories error response:', errorText);
            throw new Error(`Categories request failed: ${categoriesResponse.status}`);
        }
        
        const categories = await categoriesResponse.json();
        console.log('Categories loaded:', categories);
        
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">Select Category</option>';
        
        if (Array.isArray(categories) && categories.length > 0) {
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        } else {
            console.warn('No categories found or invalid format');
        }

        // Load suppliers
        console.log('Loading suppliers...');
        const suppliersResponse = await fetch('/api/suppliers', {
            method: 'GET',
            headers: headers
        });
        
        console.log('Suppliers response status:', suppliersResponse.status);
        
        if (!suppliersResponse.ok) {
            const errorText = await suppliersResponse.text();
            console.error('Suppliers error response:', errorText);
            throw new Error(`Suppliers request failed: ${suppliersResponse.status}`);
        }
        
        const suppliers = await suppliersResponse.json();
        console.log('Suppliers loaded:', suppliers);
        
        const supplierSelect = document.getElementById('supplier_id');
        supplierSelect.innerHTML = '<option value="">Select Supplier</option>';
        
        if (Array.isArray(suppliers) && suppliers.length > 0) {
            suppliers.forEach(supplier => {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.textContent = supplier.name || supplier.supplier_name || 'Unknown Supplier';
                supplierSelect.appendChild(option);
            });
        } else {
            console.warn('No suppliers found or invalid format');
        }
        
        console.log('Form data loaded successfully');
        
    } catch (error) {
        console.error('Error loading form data:', error);
        showNotification('Error loading categories and suppliers', 'error');
    }
}

// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const img = preview.querySelector('img');
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Show notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Handle form submission
document.getElementById('product-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const originalText = submitText.textContent;
    
    submitText.textContent = 'Saving...';
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

    // Clear previous errors
    document.querySelectorAll('[id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        const span = el.querySelector('span');
        if (span) span.textContent = '';
    });

    const formData = new FormData(this);

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                         document.querySelector('input[name="_token"]')?.value;
        
        const response = await fetch('/api/products', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            showNotification('Product created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/products';
            }, 1500);
        } else {
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const errorEl = document.getElementById(field + '_error');
                    if (errorEl) {
                        const span = errorEl.querySelector('span');
                        if (span) {
                            span.textContent = result.errors[field][0];
                        }
                        errorEl.classList.remove('hidden');
                    }
                });
                showNotification('Please fix the errors in the form', 'error');
            } else {
                showNotification(result.message || 'Error creating product', 'error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error creating product', 'error');
    } finally {
        submitText.textContent = originalText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
});

// Load form data when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, loading form data...');
    setTimeout(loadFormData, 100);
});

// Toggle expiry date field
document.getElementById('has_expiry').addEventListener('change', function() {
    const expiryContainer = document.getElementById('expiry-date-container');
    const expiryInput = document.getElementById('expiry_date');
    const expiryRequiredStar = document.getElementById('expiry-required-star');
    
    if (this.checked) {
        expiryContainer.style.display = 'block';
        expiryInput.setAttribute('required', 'required');
        expiryRequiredStar.style.display = 'inline';
    } else {
        expiryContainer.style.display = 'none';
        expiryInput.removeAttribute('required');
        expiryInput.value = '';
        expiryRequiredStar.style.display = 'none';
        
        // Clear error jika ada
        const errorEl = document.getElementById('expiry_date_error');
        errorEl.classList.add('hidden');
    }
});
    </script>

</x-app-layout>