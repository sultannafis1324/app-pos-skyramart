<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <div class="flex space-x-3">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('products.edit', ['product' => ':id']) }}" id="edit-link"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Product
                </a>
                @endif
                <a href="{{ route('products.barcode.print', ['product' => ':id']) }}" 
                    id="print-barcode-link"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white border border-transparent rounded-lg text-sm font-medium hover:bg-green-700 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Barcode
                </a>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Loading indicator -->
            <div id="loading" class="text-center py-20">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-indigo-200 border-t-indigo-600"></div>
                <p id="loading-text" class="mt-4 text-lg text-gray-600 dark:text-gray-400 font-medium">Loading product details...</p>
            </div>

            <!-- Product Details -->
            <div id="product-details" class="hidden space-y-6">
                
                <!-- Main Product Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8">
                        
                        <!-- Product Header -->
                        <div class="flex flex-col lg:flex-row gap-8 mb-8">
                            <!-- Product Image - RESPONSIVE -->
                            <div class="w-full sm:w-64 md:w-72 lg:w-1/4 mx-auto lg:mx-0">
                                <div id="product-image-container" class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 sm:p-6 text-center shadow-inner border border-gray-200 dark:border-gray-600 aspect-square flex items-center justify-center overflow-hidden">
                                    <div id="no-image" class="text-gray-400 dark:text-gray-500">
                                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-xs font-medium">No Image</p>
                                    </div>
                                    <img id="product-image" class="hidden w-full h-full rounded-lg object-contain" alt="Product Image">
                                </div>
                            </div>
                            
                            <!-- Product Info - EXPANDED -->
                            <div class="lg:w-3/4">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <h1 id="product-name" class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2"></h1>
                                        <p id="product-code" class="text-base text-gray-500 dark:text-gray-400 font-medium"></p>
                                    </div>
                                    <span id="product-status" class="px-4 py-2 text-sm font-bold rounded-full shadow-sm ml-4"></span>
                                </div>
                                
                                <div id="product-description" class="text-gray-600 dark:text-gray-300 mb-6 text-sm leading-relaxed bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600"></div>
                                
                                <!-- Price & Stock Info -->
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-blue-800/40 p-4 rounded-xl border border-blue-200 dark:border-blue-700 shadow-sm">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="text-xs font-semibold text-blue-700 dark:text-blue-200 uppercase tracking-wide">Selling Price</h3>
                                        </div>
                                        <p id="selling-price" class="text-xl font-bold text-blue-900 dark:text-blue-100"></p>
                                    </div>
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-green-800/40 p-4 rounded-xl border border-green-200 dark:border-green-700 shadow-sm">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h3 class="text-xs font-semibold text-green-700 dark:text-green-200 uppercase tracking-wide">Purchase Price</h3>
                                        </div>
                                        <p id="purchase-price" class="text-xl font-bold text-green-900 dark:text-green-100"></p>
                                    </div>
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/40 dark:to-purple-800/40 p-4 rounded-xl border border-purple-200 dark:border-purple-700 shadow-sm">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <h3 class="text-xs font-semibold text-purple-700 dark:text-purple-200 uppercase tracking-wide">Current Stock</h3>
                                        </div>
                                        <p id="current-stock" class="text-xl font-bold text-purple-900 dark:text-purple-100"></p>
                                    </div>
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/40 dark:to-orange-800/40 p-4 rounded-xl border border-orange-200 dark:border-orange-700 shadow-sm">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <h3 class="text-xs font-semibold text-orange-700 dark:text-orange-200 uppercase tracking-wide">Min Stock</h3>
                                        </div>
                                        <p id="minimum-stock" class="text-xl font-bold text-orange-900 dark:text-orange-100"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Product Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Product Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Category
                                    </span>
                                    <span id="product-category" class="text-gray-900 dark:text-gray-100 font-medium text-sm"></span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Supplier
                                    </span>
                                    <span id="product-supplier" class="text-gray-900 dark:text-gray-100 font-medium text-sm"></span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                        </svg>
                                        Unit
                                    </span>
                                    <span id="product-unit" class="text-gray-900 dark:text-gray-100 font-medium text-sm"></span>
                                </div>
                                <!-- Update bagian Barcode di Product Information -->
                                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                        Barcode
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <span id="product-barcode" class="text-gray-900 dark:text-gray-100 font-medium font-mono text-xs"></span>
                                        <button onclick="showBarcodePreview()" class="text-blue-600 hover:text-blue-700 text-xs font-semibold">
                                            View
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Created
                                    </span>
                                    <span id="created-at" class="text-gray-900 dark:text-gray-100 font-medium text-xs"></span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Updated
                                    </span>
                                    <span id="updated-at" class="text-gray-900 dark:text-gray-100 font-medium text-xs"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Stock Status & Statistics
                            </h3>
                        </div>
                        <div class="p-6">
                            <div id="stock-status" class="space-y-4 mb-6"></div>
                            
                            <!-- Statistics -->
                            <div class="grid grid-cols-3 gap-3 mt-6">
                                <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg text-center">
                                    <p class="text-xs text-blue-600 dark:text-blue-300 font-semibold mb-1">Total Sold</p>
                                    <p id="total-sold" class="text-lg font-bold text-blue-900 dark:text-blue-100">-</p>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/30 p-3 rounded-lg text-center">
                                    <p class="text-xs text-green-600 dark:text-green-300 font-semibold mb-1">Total Purchased</p>
                                    <p id="total-purchased" class="text-lg font-bold text-green-900 dark:text-green-100">-</p>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/30 p-3 rounded-lg text-center">
                                    <p class="text-xs text-purple-600 dark:text-purple-300 font-semibold mb-1">Revenue</p>
                                    <p id="total-revenue" class="text-sm font-bold text-purple-900 dark:text-purple-100">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ TAMBAHKAN: Active Promotions Section -->
                <div id="promotions-section" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden" style="display: none;">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                            Active Promotions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="promotions-container" class="space-y-4"></div>
                    </div>
                </div>

                <!-- Batch Information Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Batch Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="batch-info-container" class="space-y-4">
                            <!-- Batch details will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Expiry Information (jika ada) -->
                <div id="expiry-info-section" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden" style="display: none;">
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Expiry Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="expiry-status-container"></div>
                    </div>
                </div>

                <!-- Transaction History Tabs -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Transaction History
                        </h3>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="flex -mb-px">
                            <button id="tab-sales" class="tab-btn active px-6 py-4 text-sm font-semibold border-b-2 transition-colors">
                                Recent Sales
                            </button>
                            <button id="tab-purchases" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 transition-colors">
                                Recent Purchases
                            </button>
                            <button id="tab-stock" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 transition-colors">
                                Stock History
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Contents -->
                    <div class="p-6">
                        <!-- Recent Sales -->
                        <div id="content-sales" class="tab-content">
                            <!-- Pagination Controls -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600 dark:text-gray-400">Show:</label>
                                    <select id="sales-per-page" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-6 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
                                </div>
                                <div id="sales-info" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                            
                            <div id="recent-sales" class="space-y-3 mb-4"></div>
                            
                            <!-- Pagination Buttons -->
                            <div id="sales-pagination" class="flex justify-center gap-2"></div>
                        </div>

                        <!-- Recent Purchases -->
                        <div id="content-purchases" class="tab-content hidden">
                            <!-- Pagination Controls -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600 dark:text-gray-400">Show:</label>
                                    <select id="purchases-per-page" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-6 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
                                </div>
                                <div id="purchases-info" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                            
                            <div id="recent-purchases" class="space-y-3 mb-4"></div>
                            
                            <!-- Pagination Buttons -->
                            <div id="purchases-pagination" class="flex justify-center gap-2"></div>
                        </div>

                        <!-- Stock History -->
                        <div id="content-stock" class="tab-content hidden">
                            <!-- Pagination Controls -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600 dark:text-gray-400">Show:</label>
                                    <select id="stock-per-page" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-6 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
                                </div>
                                <div id="stock-info" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                            
                            <div id="stock-history" class="space-y-3 mb-4"></div>
                            
                            <!-- Pagination Buttons -->
                            <div id="stock-pagination" class="flex justify-center gap-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pathParts = window.location.pathname.split('/');
        const productId = pathParts[pathParts.length - 2];
        
        let productData = null;
        let paginationState = {
            sales: { currentPage: 1, perPage: 10 },
            purchases: { currentPage: 1, perPage: 10 },
            stock: { currentPage: 1, perPage: 10 }
        };

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active from all
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active', 'border-pink-500', 'text-pink-600', 'dark:text-pink-400');
                    b.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

                // Add active to clicked
                this.classList.add('active', 'border-pink-500', 'text-pink-600', 'dark:text-pink-400');
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                
                const tabId = this.id.replace('tab-', 'content-');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Pagination change handlers
        document.getElementById('sales-per-page').addEventListener('change', (e) => {
            paginationState.sales.perPage = parseInt(e.target.value);
            paginationState.sales.currentPage = 1;
            renderSales();
        });

        document.getElementById('purchases-per-page').addEventListener('change', (e) => {
            paginationState.purchases.perPage = parseInt(e.target.value);
            paginationState.purchases.currentPage = 1;
            renderPurchases();
        });

        document.getElementById('stock-per-page').addEventListener('change', (e) => {
            paginationState.stock.perPage = parseInt(e.target.value);
            paginationState.stock.currentPage = 1;
            renderStockHistory();
        });

        async function loadProduct() {
            try {
                const response = await fetch(`/api/products/${productId}`);
                if (!response.ok) throw new Error('Product not found');
                
                const data = await response.json();
                productData = data.product;
                const stats = data.stats;
                
                document.getElementById('loading-text').textContent = `Loading ${productData.product_name} details...`;
                await new Promise(resolve => setTimeout(resolve, 300));
                
                displayProduct(productData, stats);
                renderSales();
                renderPurchases();
                renderStockHistory();
            } catch (error) {
                console.error('Error loading product:', error);
                alert('Error loading product details');
                window.location.href = '/products';
            } finally {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('product-details').classList.remove('hidden');
            }
        }

        function displayProduct(product, stats) {
    @if(Auth::user()->role === 'admin')
    const editLink = document.getElementById('edit-link');
    editLink.href = editLink.href.replace(':id', product.id);
    @endif

    // Basic info
    document.getElementById('product-name').textContent = product.product_name;
    document.getElementById('product-code').textContent = `SKU: ${product.product_code}`;
    const descElement = document.getElementById('product-description');
    if (product.description) {
        descElement.innerHTML = product.description.replace(/\n/g, '<br>');
    } else {
        descElement.textContent = 'No description available';
    }

    // Status
    const statusEl = document.getElementById('product-status');
    statusEl.textContent = product.is_active ? 'Active' : 'Inactive';
    statusEl.className = `px-4 py-2 text-sm font-bold rounded-full shadow-sm ml-4 ${product.is_active ? 'bg-green-100 text-green-800 border-2 border-green-300' : 'bg-red-100 text-red-800 border-2 border-red-300'}`;
    displayBatchInformation(product);

    // Image
    if (product.image) {
        document.getElementById('no-image').classList.add('hidden');
        const img = document.getElementById('product-image');
        img.src = `/storage/${product.image}`;
        img.classList.remove('hidden');
    }

    // Prices and stock
    document.getElementById('selling-price').textContent = `Rp ${parseFloat(product.selling_price).toLocaleString('id-ID')}`;
    document.getElementById('purchase-price').textContent = product.purchase_price ? `Rp ${parseFloat(product.purchase_price).toLocaleString('id-ID')}` : '-';
    document.getElementById('current-stock').textContent = `${product.stock} ${product.unit || ''}`;
    document.getElementById('minimum-stock').textContent = `${product.minimum_stock || 0} ${product.unit || ''}`;

    // Additional info
    document.getElementById('product-category').textContent = product.category?.name || '-';
    document.getElementById('product-supplier').textContent = product.supplier?.supplier_name || '-';
    document.getElementById('product-unit').textContent = product.unit || '-';
    document.getElementById('product-barcode').textContent = product.barcode || '-';
    
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    
    document.getElementById('created-at').textContent = formatDate(product.created_at);
    document.getElementById('updated-at').textContent = formatDate(product.updated_at);

    // ✅ TAMBAHKAN: Display active promotions
    displayPromotions(product);

    // Display expiry information
    if (product.has_expiry && product.expiry_date) {
        const expirySection = document.getElementById('expiry-info-section');
        const expiryContainer = document.getElementById('expiry-status-container');
        expirySection.style.display = 'block';
        
        const expiryDate = new Date(product.expiry_date);
        const today = new Date();
        const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
        const isExpired = daysUntilExpiry < 0;
        const isNearExpiry = daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
        
        let expiryHtml = '';
        
        if (isExpired) {
            expiryHtml = `
                <div class="relative overflow-hidden bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/40 dark:to-red-800/40 border-2 border-red-300 dark:border-red-600 text-red-800 dark:text-red-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-300 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-lg font-bold mb-1">⚠️ PRODUCT EXPIRED!</h4>
                            <p class="text-sm mb-2">This product has expired on <span class="font-bold">${expiryDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                            <p class="text-xs">Expired ${Math.abs(daysUntilExpiry)} day(s) ago. Please remove from inventory.</p>
                        </div>
                    </div>
                </div>
            `;
        } else if (isNearExpiry) {
            expiryHtml = `
                <div class="relative overflow-hidden bg-gradient-to-r from-orange-50 to-amber-100 dark:from-orange-900/40 dark:to-amber-800/40 border-2 border-orange-300 dark:border-orange-600 text-orange-800 dark:text-orange-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-orange-600 dark:text-orange-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-lg font-bold mb-1">⚠️ Near Expiry Warning!</h4>
                            <p class="text-sm mb-2">This product will expire on <span class="font-bold">${expiryDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                            <p class="text-xs">Only <span class="font-bold text-lg">${daysUntilExpiry}</span> day(s) remaining. Consider promotional pricing to move inventory.</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            expiryHtml = `
                <div class="relative overflow-hidden bg-gradient-to-r from-green-50 to-emerald-100 dark:from-green-900/40 dark:to-emerald-800/40 border-2 border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-lg font-bold mb-1">✓ Good Shelf Life</h4>
                            <p class="text-sm mb-2">This product will expire on <span class="font-bold">${expiryDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                            <p class="text-xs"><span class="font-bold text-lg">${daysUntilExpiry}</span> day(s) remaining until expiry. Product is safe for sale.</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        expiryContainer.innerHTML = expiryHtml;
    }

    // Stock status
    displayStockStatus(product);

    // Statistics
    document.getElementById('total-sold').textContent = stats.total_sold || 0;
    document.getElementById('total-purchased').textContent = stats.total_purchased || 0;
    document.getElementById('total-revenue').textContent = stats.total_revenue 
        ? `Rp ${parseFloat(stats.total_revenue).toLocaleString('id-ID')}` 
        : 'Rp 0';
}

// ✅ TAMBAHKAN: Function baru untuk display promotions
function displayPromotions(product) {
    // Fetch promotions detail dari API
    fetch(`/api/products/${product.id}/promotions`)
        .then(response => response.json())
        .then(data => {
            const promotions = data.promotions || [];
            
            if (promotions.length === 0) {
                return; // Tidak ada promo, section tetap hidden
            }
            
            const section = document.getElementById('promotions-section');
            const container = document.getElementById('promotions-container');
            section.style.display = 'block';
            
            let promotionsHtml = '';
            
            promotions.forEach(promo => {
                const startDate = new Date(promo.start_date);
                const endDate = promo.end_date ? new Date(promo.end_date) : null;
                const now = new Date();
                const daysLeft = endDate ? Math.ceil((endDate - now) / (1000 * 60 * 60 * 24)) : null;
                
                // Determine badge color (use promo badge_color or default)
                const badgeColor = promo.badge_color || '#8B5CF6';
                const r = parseInt(badgeColor.slice(1, 3), 16);
                const g = parseInt(badgeColor.slice(3, 5), 16);
                const b = parseInt(badgeColor.slice(5, 7), 16);
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                const textColor = brightness > 155 ? '#000000' : '#FFFFFF';
                
                // Determine promo type display
                let promoTypeText = '';
                let promoDetailText = '';
                let promoIcon = '';
                
                if (promo.type === 'percentage') {
                    promoTypeText = 'Diskon Persentase';
                    promoDetailText = `Hemat ${promo.discount_value}%`;
                    if (promo.max_discount) {
                        promoDetailText += ` (Max. Rp ${parseFloat(promo.max_discount).toLocaleString('id-ID')})`;
                    }
                    promoIcon = `
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    `;
                } else if (promo.type === 'fixed') {
                    promoTypeText = 'Diskon Nominal';
                    promoDetailText = `Hemat Rp ${parseFloat(promo.discount_value).toLocaleString('id-ID')}`;
                    promoIcon = `
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    `;
                } else if (promo.type === 'buy_x_get_y') {
                    promoTypeText = 'Promo Bundling';
                    promoDetailText = `Beli ${promo.buy_quantity} Gratis ${promo.get_quantity}`;
                    promoIcon = `
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    `;
                }
                
                // Build promo card HTML
                promotionsHtml += `
                    <div class="relative overflow-hidden border-2 rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                         style="border-color: ${badgeColor}; background: linear-gradient(135deg, ${badgeColor}15 0%, ${badgeColor}05 100%);">
                        
                        <!-- Promo Badge -->
                        <div class="absolute top-0 right-0 px-4 py-2 rounded-bl-xl font-bold text-xs shadow-lg"
                             style="background: linear-gradient(135deg, ${badgeColor} 0%, ${adjustColor(badgeColor, -20)} 100%); color: ${textColor};">
                            ${promo.badge_text || 'PROMO'}
                        </div>
                        
                        <!-- Promo Header -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 p-3 rounded-xl" style="background: ${badgeColor}20; color: ${badgeColor};">
                                ${promoIcon}
                            </div>
                            <div class="flex-1 pr-20">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1">
                                    ${promo.name}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ${promoTypeText}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Promo Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Discount Info -->
                            <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" style="background: ${badgeColor}20;">
                                    <svg class="w-5 h-5" style="color: ${badgeColor};" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Keterangan Diskon</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">${promoDetailText}</p>
                                </div>
                            </div>
                            
                            <!-- Period Info -->
                            <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" style="background: ${badgeColor}20;">
                                    <svg class="w-5 h-5" style="color: ${badgeColor};" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Periode Promo</p>
                                    <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">
                                        ${startDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                                        ${endDate ? ' - ' + endDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : ' (Tanpa Batas)'}
                                    </p>
                                    ${daysLeft !== null && daysLeft > 0 ? `
                                        <p class="text-xs text-orange-600 dark:text-orange-400 font-bold mt-1">
                                            ⏰ ${daysLeft} hari lagi
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        ${promo.description ? `
                            <div class="mb-4 p-3 bg-white dark:bg-gray-700/50 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Deskripsi:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">${promo.description}</p>
                            </div>
                        ` : ''}
                        
                        <!-- Terms & Conditions -->
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">📋 Syarat & Ketentuan:</p>
                            <ul class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                ${promo.min_purchase ? `
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">✓</span>
                                        <span>Minimal pembelian: <strong>Rp ${parseFloat(promo.min_purchase).toLocaleString('id-ID')}</strong></span>
                                    </li>
                                ` : ''}
                                ${promo.usage_limit ? `
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">✓</span>
                                        <span>Maksimal penggunaan: <strong>${promo.usage_limit}x per customer</strong></span>
                                    </li>
                                ` : `
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">✓</span>
                                        <span>Tidak ada batas penggunaan</span>
                                    </li>
                                `}
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 dark:text-green-400">✓</span>
                                    <span>Promo berlaku untuk produk ini</span>
                                </li>
                                ${promo.is_combinable ? `
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">✓</span>
                                        <span>Dapat digabung dengan promo lain</span>
                                    </li>
                                ` : `
                                `}
                            </ul>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = promotionsHtml;
        })
        .catch(error => {
            console.error('Error loading promotions:', error);
        });
}

// Helper function untuk adjust color (sama seperti di index)
function adjustColor(color, amount) {
    const clamp = (val) => Math.min(Math.max(val, 0), 255);
    const num = parseInt(color.slice(1), 16);
    const r = clamp((num >> 16) + amount);
    const g = clamp(((num >> 8) & 0x00FF) + amount);
    const b = clamp((num & 0x0000FF) + amount);
    return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
}

        function displayStockStatus(product) {
            const stockStatusEl = document.getElementById('stock-status');
            const isLowStock = product.stock <= product.minimum_stock;
            const isOutOfStock = product.stock <= 0;

            let statusHtml = '';
            if (isOutOfStock) {
                statusHtml = `
                    <div class="relative overflow-hidden bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/40 dark:to-red-800/40 border-2 border-red-300 dark:border-red-600 text-red-800 dark:text-red-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h4 class="text-base font-bold mb-1">Out of Stock!</h4>
                                <p class="text-xs">This product is currently out of stock. Please reorder immediately.</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (isLowStock) {
                const stockPercentage = Math.round((product.stock / product.minimum_stock) * 100);
                statusHtml = `
                    <div class="relative overflow-hidden bg-gradient-to-r from-yellow-50 to-amber-100 dark:from-yellow-900/40 dark:to-amber-800/40 border-2 border-yellow-300 dark:border-yellow-600 text-yellow-800 dark:text-yellow-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h4 class="text-base font-bold mb-1">Low Stock Warning!</h4>
                                <p class="text-xs mb-2">Current stock is at or below minimum level. Consider restocking soon.</p>
                                <div class="flex items-center">
                                    <div class="flex-1 bg-yellow-200 dark:bg-yellow-700 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-600 dark:bg-yellow-400 h-2 rounded-full transition-all duration-500" style="width: ${stockPercentage}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">${stockPercentage}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                const stockRatio = product.stock / product.minimum_stock;
                const stockPercentage = Math.min(Math.round((product.stock / (product.minimum_stock * 3)) * 100), 100);
                statusHtml = `
                    <div class="relative overflow-hidden bg-gradient-to-r from-green-50 to-emerald-100 dark:from-green-900/40 dark:to-emerald-800/40 border-2 border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h4 class="text-base font-bold mb-1">Good Stock Level</h4>
                                <p class="text-xs mb-2">Current stock is ${stockRatio.toFixed(1)}x above minimum level. Stock is healthy.</p>
                                <div class="flex items-center">
                                    <div class="flex-1 bg-green-200 dark:bg-green-700 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 dark:bg-green-400 h-2 rounded-full transition-all duration-500" style="width: ${stockPercentage}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">${stockPercentage}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            stockStatusEl.innerHTML = statusHtml;
        }

        function renderSales() {
            const { currentPage, perPage } = paginationState.sales;
            const sales = productData.sale_details || [];
            const totalItems = sales.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            const paginatedSales = sales.slice(startIndex, endIndex);

            const container = document.getElementById('recent-sales');
            
            if (paginatedSales.length > 0) {
                container.innerHTML = paginatedSales.map(detail => `
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">${detail.sale?.transaction_number || 'N/A'}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Customer: ${detail.sale?.customer?.customer_name || 'Walk-in'}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded-full ${detail.sale?.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${detail.sale?.status || 'N/A'}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Qty:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">${detail.quantity}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Total:</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400">Rp ${parseFloat(detail.subtotal).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            ${new Date(detail.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                        </p>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm font-medium">No sales history yet</p>
                    </div>
                `;
            }

            // Update info
            const start = totalItems > 0 ? startIndex + 1 : 0;
            const end = Math.min(endIndex, totalItems);
            document.getElementById('sales-info').textContent = `Showing ${start} to ${end} of ${totalItems} entries`;

            // Render pagination
            renderPagination('sales', currentPage, totalPages);
        }

        function renderPurchases() {
            const { currentPage, perPage } = paginationState.purchases;
            const purchases = productData.purchase_details || [];
            const totalItems = purchases.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            const paginatedPurchases = purchases.slice(startIndex, endIndex);

            const container = document.getElementById('recent-purchases');
            
            if (paginatedPurchases.length > 0) {
                container.innerHTML = paginatedPurchases.map(detail => `
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">${detail.purchase?.purchase_number || 'N/A'}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Supplier: ${detail.purchase?.supplier?.supplier_name || 'N/A'}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded-full ${detail.purchase?.status === 'received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${detail.purchase?.status || 'N/A'}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Qty:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">${detail.quantity}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Total:</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">Rp ${parseFloat(detail.subtotal).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            ${new Date(detail.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                        </p>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-sm font-medium">No purchase history yet</p>
                    </div>
                `;
            }

            const start = totalItems > 0 ? startIndex + 1 : 0;
            const end = Math.min(endIndex, totalItems);
            document.getElementById('purchases-info').textContent = `Showing ${start} to ${end} of ${totalItems} entries`;

            renderPagination('purchases', currentPage, totalPages);
        }

        function renderStockHistory() {
            const { currentPage, perPage } = paginationState.stock;
            const stockHistories = productData.stock_histories || [];
            const totalItems = stockHistories.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            const paginatedStock = stockHistories.slice(startIndex, endIndex);

            const container = document.getElementById('stock-history');
            
            if (paginatedStock.length > 0) {
                container.innerHTML = paginatedStock.map(history => {
                    const isIncrease = history.type === 'in' || (history.type === 'adjustment' && history.stock_after > history.stock_before);
                    const iconColor = isIncrease ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                    const bgColor = isIncrease ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
                    const arrow = isIncrease ? '↑' : '↓';
                    
                    return `
                    <div class="${bgColor} border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="${iconColor} font-bold text-lg">${arrow}</span>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm capitalize">${history.type}</p>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">${history.description || 'No description'}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                ${history.quantity} ${productData.unit || 'pcs'}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Before:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">${history.stock_before}</span>
                            </div>
                            <span class="text-gray-400">→</span>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">After:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">${history.stock_after}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                ${new Date(history.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                By: ${history.user?.name || 'System'}
                            </p>
                        </div>
                    </div>
                `;
                }).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm font-medium">No stock history yet</p>
                    </div>
                `;
            }

            const start = totalItems > 0 ? startIndex + 1 : 0;
            const end = Math.min(endIndex, totalItems);
            document.getElementById('stock-info').textContent = `Showing ${start} to ${end} of ${totalItems} entries`;

            renderPagination('stock', currentPage, totalPages);
        }

        function renderPagination(type, currentPage, totalPages) {
            const paginationContainer = document.getElementById(`${type}-pagination`);
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let buttonsHtml = '';

            // Previous button
            buttonsHtml += `
                <button onclick="changePage('${type}', ${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''}
                        class="px-3 py-1 text-sm rounded-lg border ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50 text-gray-700'} dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    Previous
                </button>
            `;

            // Page numbers
            const maxVisible = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let endPage = Math.min(totalPages, startPage + maxVisible - 1);
            
            if (endPage - startPage < maxVisible - 1) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            if (startPage > 1) {
                buttonsHtml += `
                    <button onclick="changePage('${type}', 1)" 
                            class="px-3 py-1 text-sm rounded-lg border bg-white hover:bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        1
                    </button>
                `;
                if (startPage > 2) {
                    buttonsHtml += `<span class="px-2 text-gray-500">...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                buttonsHtml += `
                    <button onclick="changePage('${type}', ${i})" 
                            class="px-3 py-1 text-sm rounded-lg border ${i === currentPage ? 'bg-pink-500 text-white border-pink-500' : 'bg-white hover:bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300'}">
                        ${i}
                    </button>
                `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    buttonsHtml += `<span class="px-2 text-gray-500">...</span>`;
                }
                buttonsHtml += `
                    <button onclick="changePage('${type}', ${totalPages})" 
                            class="px-3 py-1 text-sm rounded-lg border bg-white hover:bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        ${totalPages}
                    </button>
                `;
            }

            // Next button
            buttonsHtml += `
                <button onclick="changePage('${type}', ${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''}
                        class="px-3 py-1 text-sm rounded-lg border ${currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50 text-gray-700'} dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    Next
                </button>
            `;

            paginationContainer.innerHTML = buttonsHtml;
        }

        function changePage(type, page) {
            paginationState[type].currentPage = page;
            
            if (type === 'sales') {
                renderSales();
            } else if (type === 'purchases') {
                renderPurchases();
            } else if (type === 'stock') {
                renderStockHistory();
            }
            
            // Scroll to top of content
            document.getElementById(`content-${type}`).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        document.addEventListener('DOMContentLoaded', loadProduct);

        function displayBatchInformation(product) {
    if (!product.has_expiry) {
        document.querySelector('#batch-info-container').closest('.bg-white').style.display = 'none';
        return;
    }
    
    const container = document.getElementById('batch-info-container');
    
    if (!product.batches || product.batches.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-sm font-medium">No batch information available</p>
            </div>
        `;
        return;
    }
    
    let batchesHtml = '';
    
    product.batches.forEach((batch, index) => {
        const isExpired = batch.is_expired;
        const isNearExpiry = batch.is_near_expiry && !isExpired;
        const daysLeft = batch.days_until_expiry;
        
        let badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200';
        let badgeText = 'Good';
        let badgeIcon = `
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        `;
        
        if (isExpired) {
            badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200 animate-pulse';
            badgeText = 'Expired';
            badgeIcon = `
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            `;
        } else if (isNearExpiry) {
            badgeClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200 animate-pulse';
            badgeText = `${daysLeft}d left`;
            badgeIcon = `
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            `;
        }
        
        const expiryDate = new Date(batch.expiry_date);
        const formattedDate = expiryDate.toLocaleDateString('id-ID', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        batchesHtml += `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all ${isExpired ? 'bg-red-50 dark:bg-red-900/10' : isNearExpiry ? 'bg-orange-50 dark:bg-orange-900/10' : 'bg-gray-50 dark:bg-gray-700/30'}">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-sm mb-1">
                            Batch #${batch.batch_number || batch.id}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Batch ID: ${batch.id}
                        </p>
                    </div>
                    <span class="${badgeClass} px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5">
                        ${badgeIcon}
                        ${badgeText}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Expiry Date</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${formattedDate}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Stock Remaining</p>
                        <p class="text-sm font-semibold ${batch.quantity_remaining > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">
                            ${batch.quantity_remaining} ${product.unit || 'pcs'}
                        </p>
                    </div>
                </div>
                
                ${!isExpired && daysLeft !== null ? `
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 dark:text-gray-400">Days until expiry:</span>
                            <span class="font-bold ${isNearExpiry ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400'}">
                                ${daysLeft} days
                            </span>
                        </div>
                    </div>
                ` : ''}
                
                ${isExpired ? `
                    <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-700">
                        <div class="flex items-center gap-2 text-xs text-red-600 dark:text-red-400 font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Expired ${Math.abs(daysLeft)} days ago - Remove from inventory</span>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    container.innerHTML = batchesHtml;
}

// Set print barcode link
document.getElementById('print-barcode-link').href = `/products/${productId}/barcode/print`;

// Function untuk show barcode preview
async function showBarcodePreview() {
    try {
        const response = await fetch(`/products/${productId}/barcode/image`);
        const data = await response.json();
        
        if (data.barcode) {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Product Barcode</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-center">
                        <div class="bg-white p-4 rounded-lg inline-block mb-4">
                            ${data.barcode}
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-mono">${data.barcode_number}</p>
                        <a href="/products/${productId}/barcode/print" target="_blank" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Barcode
                        </a>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
    } catch (error) {
        console.error('Error loading barcode:', error);
        alert('Failed to load barcode preview');
    }
}
    </script>

    <style>
        .tab-btn.active {
            @apply border-pink-500 text-pink-600 dark:text-pink-400;
        }
        .tab-btn:not(.active) {
            @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300;
        }
    </style>
</x-app-layout>