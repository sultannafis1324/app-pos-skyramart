<x-app-layout>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
          data-client-key="{{ config('services.midtrans.client_key') }}">
  </script>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Sales Create Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Point of Sale - Cashier') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Efficient and modern point of sale system</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-full">
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300" id="currentDateTime"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 lg:p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                        @csrf
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6">
                            <!-- Left Panel - Product Selection -->
                            <div class="xl:col-span-2">
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 lg:p-6 mb-4 shadow-lg border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-gray-100">
                                            Product Search</h3>
                                        <div
                                            class="flex items-center space-x-2 bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-full">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Search
                                                Products</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                                        <div class="flex-1">
                                            <div class="relative">
                                                <input type="text" id="productSearch"
                                                    placeholder="Search products by name, code, or barcode..."
                                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white transition-all duration-200 shadow-sm">
                                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-56">
                                            <select id="categoryFilter" onchange="searchProducts()"
                                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white transition-all duration-200 shadow-sm">
                                                <option value="">All Categories</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}n
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Search Button - Ganti yang lama dengan yang baru -->
<button type="button" id="searchButton" onclick="handleSearch()"
    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-md">
    <svg class="w-5 h-5 sm:hidden" id="searchIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
    <svg class="w-5 h-5 sm:hidden hidden" id="resetIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
    <span class="hidden sm:inline" id="searchButtonText">Search</span>
</button>

                                    <!-- Tambahkan setelah search button -->
<button type="button" id="barcodeBtn" onclick="toggleBarcodeScanner()"
    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-md">
    <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
    </svg>
    <span class="hidden sm:inline" id="barcodeBtnText">Scan Barcode</span>
</button>
                                    </div>

                                    <!-- Product Results -->
                                    <div id="productResults"
                                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-3 lg:gap-4 max-h-96 overflow-y-auto custom-scrollbar">
                                        @if ($products->isEmpty())
                                            <!-- Empty State -->
                                            <div
                                                class="col-span-full flex flex-col items-center justify-center py-12 px-4">
                                                <svg class="w-24 h-24 text-gray-300 dark:text-gray-600 mb-4"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">No
                                                    Products Available</h3>
                                                <p class="text-gray-500 dark:text-gray-400 text-center mb-6 max-w-md">
                                                    There are no products in your inventory yet. Add products or update
                                                    stock to start selling.
                                                </p>
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <a href="{{ route('products.create') }}"
                                                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all shadow-md">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Add New Product
                                                    </a>
                                                    <a href="{{ route('purchases.create') }}"
                                                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg transition-all shadow-md">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                            </path>
                                                        </svg>
                                                        Update Stock via Purchase
                                                    </a>
                                                </div>
                                            </div>
                                        @else
@foreach ($products as $product)
    @php
        // ✅ Get BOTH promotions
        $promotions = $product->getActivePromotions();
        $pricePromotion = $promotions['price_promotion'];
        $quantityPromotion = $promotions['quantity_promotion'];
        
        // Calculate price with BOTH promotions for quantity = 1
        $priceData = $product->getPriceWithMultiPromotions(1);
        
        $hasPromotion = $pricePromotion || $quantityPromotion;
        $finalPrice = $priceData['final_price_per_unit'];
        $originalPrice = $priceData['original_price'];
        $priceDiscount = $priceData['price_discount_per_unit'];
    @endphp
    
    <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-3 lg:p-4 cursor-pointer hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-500 product-card transition-all duration-300 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-1' }} bg-white dark:bg-gray-800 relative"
        onclick="{{ $product->stock > 0 ? 
            "addProduct(
                '{$product->id}', 
                '" . addslashes($product->product_name) . "', 
                '{$finalPrice}', 
                '{$product->stock}',
                " . ($pricePromotion ? "'{$pricePromotion->id}'" : 'null') . ",
                '{$priceDiscount}',
                " . ($pricePromotion ? "'{$pricePromotion->type}'" : 'null') . ",
                " . ($quantityPromotion ? "'{$quantityPromotion->id}'" : 'null') . ",
                " . ($quantityPromotion ? "'{$quantityPromotion->buy_quantity}'" : '0') . ",
                " . ($quantityPromotion ? "'{$quantityPromotion->get_quantity}'" : '0') . ",
                " . ($quantityPromotion ? "'{$quantityPromotion->type}'" : 'null') . ",
                '{$originalPrice}'
            )" : '' }}"
        data-stock="{{ $product->stock }}"
        data-product-id="{{ $product->id }}"
        data-minimum-stock="{{ $product->minimum_stock }}"
        {{-- ✅ CRITICAL: Store promo data in attributes --}}
        data-product-name="{{ addslashes($product->product_name) }}"
        data-final-price="{{ $finalPrice }}"
        data-original-price="{{ $originalPrice }}"
        data-price-promo-id="{{ $pricePromotion ? $pricePromotion->id : '' }}"
        data-price-discount="{{ $priceDiscount }}"
        data-price-promo-type="{{ $pricePromotion ? $pricePromotion->type : '' }}"
        data-qty-promo-id="{{ $quantityPromotion ? $quantityPromotion->id : '' }}"
        data-buy-qty="{{ $quantityPromotion ? $quantityPromotion->buy_quantity : 0 }}"
        data-get-qty="{{ $quantityPromotion ? $quantityPromotion->get_quantity : 0 }}"
        data-qty-promo-type="{{ $quantityPromotion ? $quantityPromotion->type : '' }}">
        
        {{-- BADGE PROMO - Show BOTH if exist --}}
        @if($hasPromotion)
            <div class="absolute top-1 right-1 z-10 flex flex-col gap-1">
                @if($pricePromotion)
                    <span class="inline-block text-xs font-bold px-2 py-1 rounded-full text-white shadow-lg animate-pulse" 
                          style="background-color: {{ $pricePromotion->badge_color ?? '#FF0000' }};">
                        {{ $pricePromotion->badge_text }}
                    </span>
                @endif
                @if($quantityPromotion)
                    <span class="inline-block text-xs font-bold px-2 py-1 rounded-full text-white shadow-lg animate-pulse" 
                          style="background-color: {{ $quantityPromotion->badge_color ?? '#00AA00' }};">
                        {{ $quantityPromotion->badge_text }}
                    </span>
                @endif
            </div>
        @endif
        
        <div class="flex flex-col space-y-3">
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}"
                    alt="{{ $product->product_name }}"
                    class="w-full h-32 object-contain rounded-lg bg-white dark:bg-white p-2">
            @else
                <div class="w-full h-32 bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            
            <div class="space-y-1">
                <h4 class="font-semibold text-sm lg:text-base text-gray-800 dark:text-gray-100 line-clamp-2 min-h-[2.5rem]">
                    {{ $product->product_name }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $product->product_code }}</p>
                
                {{-- HARGA - Show both promotions info --}}
                <div class="flex items-center justify-between">
                    @if($hasPromotion)
                        <div class="flex flex-col">
                            @if($pricePromotion)
                                <span class="text-xs text-gray-400 line-through">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </span>
                                <span class="text-sm lg:text-base font-bold text-red-600 dark:text-red-400">
                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-green-600">
                                    Hemat Rp {{ number_format($priceDiscount, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-sm lg:text-base font-bold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                </span>
                            @endif
                            
                            @if($quantityPromotion)
                                <span class="text-xs text-purple-600 font-bold mt-1">
                                    Beli {{ $quantityPromotion->buy_quantity }} Gratis {{ $quantityPromotion->get_quantity }}!
                                </span>
                            @endif
                        </div>
                    @else
                        <p class="text-sm lg:text-base font-bold text-green-600 dark:text-green-400">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </p>
                    @endif
                    
                    {{-- ✅ STOCK BADGE - Will be updated by JavaScript --}}
                    <span class="stock-badge px-2 py-1 text-xs rounded-full font-medium {{ $product->stock <= 0 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ($product->stock <= $product->minimum_stock ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                        Stock: {{ $product->stock }}
                    </span>
                </div>
                
                {{-- ✅ STATUS BADGES - Will be updated by JavaScript --}}
                <div class="status-container">
                    @if ($product->stock <= 0)
                        <div class="text-center mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Out of Stock
                            </span>
                        </div>
                    @elseif($product->stock <= $product->minimum_stock)
                        <div class="text-center mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Low Stock (Min: {{ $product->minimum_stock }})
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right Panel - Cart & Payment -->
                            <div class="xl:col-span-1">
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 lg:p-6 mb-4 shadow-lg border border-gray-200 dark:border-gray-600 sticky top-4">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-gray-100">
                                            Shopping Cart</h3>
                                        <div
                                            class="flex items-center space-x-2 bg-green-100 dark:bg-green-900 px-3 py-1 rounded-full">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5.5M7 13l2.5 5.5m0 0L12 21l2.5-2.5M17 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2">
                                                </path>
                                            </svg>
                                            <span id="cartCount"
                                                class="text-sm font-medium text-green-700 dark:text-green-300">0
                                                items</span>
                                        </div>
                                    </div>

                                    <!-- Customer Selection -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Customer</label>
        <button type="button" onclick="openQuickCreateCustomerModal()" 
            class="text-xs sm:text-sm px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all shadow-sm flex items-center">
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="hidden sm:inline">Add New</span>
            <span class="sm:hidden">Add</span>
        </button>
    </div>
    <div class="relative">
        <input type="text" id="customerSearch"
            placeholder="Search customer by name or phone..."
            onkeyup="searchCustomers(this.value)"
            onclick="toggleCustomerDropdown(true)"
            class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white transition-all duration-200 shadow-sm">
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
            </path>
        </svg>
        <button type="button" onclick="toggleCustomerDropdown()"
            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <input type="hidden" name="customer_id" id="selectedCustomerId">

        <!-- Customer Dropdown -->
        <div id="customerDropdown"
            class="absolute z-20 w-full mt-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto custom-scrollbar">
            <div class="p-3 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors duration-150"
                onclick="selectCustomer(null, 'Walk-in Customer')">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div
                            class="font-semibold text-gray-800 dark:text-gray-200">
                            Walk-in Customer</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            General customer</div>
                    </div>
                </div>
            </div>
            <div id="customerList">
                @foreach ($customers as $customer)
                    <div class="customer-item p-3 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors duration-150"
                        onclick="selectCustomer('{{ $customer->id }}', '{{ addslashes($customer->customer_name) }}', '{{ $customer->phone_number }}', '{{ $customer->photo_profile ? asset('storage/' . $customer->photo_profile) : '' }}', '{{ $customer->loyalty_points }}')">
                        <div class="flex items-center space-x-3">
                            @if ($customer->photo_profile)
                                <img src="{{ asset('storage/' . $customer->photo_profile) }}"
                                    alt="{{ $customer->customer_name }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                            @else
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                                    <span
                                        class="text-white font-semibold text-sm">{{ substr($customer->customer_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <div
                                    class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                                    {{ $customer->customer_name }}</div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $customer->phone_number }}</div>
                                <div
                                    class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                    {{ $customer->loyalty_points }} points</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Selected Customer Info -->
<div id="customerInfo"
    class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border-2 border-blue-200 dark:border-blue-700 hidden">
    <div class="flex items-center space-x-4">
        <div id="customerPhoto"
            class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
            <span class="text-white font-semibold" id="customerInitial">?</span>
        </div>
        <div class="flex-1">
            <div class="font-semibold text-gray-800 dark:text-gray-200"
                id="customerName">-</div>
            <div class="text-sm text-gray-500 dark:text-gray-400"
                id="customerPhone">-</div>
            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium"
                id="customerLoyalty">0 loyalty points</div>
        </div>
    </div>
</div>

                                    <!-- Selected Customer Info -->
                                    <div id="customerInfo"
                                        class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border-2 border-blue-200 dark:border-blue-700 hidden">
                                        <div class="flex items-center space-x-4">
                                            <div id="customerPhoto"
                                                class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                                                <span class="text-white font-semibold" id="customerInitial">?</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800 dark:text-gray-200"
                                                    id="customerName">-</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400"
                                                    id="customerPhone">-</div>
                                                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium"
                                                    id="customerLoyalty">0 loyalty points</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cart Items -->
                                    <div id="cartItems"
                                        class="mb-6 max-h-64 overflow-y-auto custom-scrollbar space-y-3">
                                        <div id="emptyCart" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5.5M7 13l2.5 5.5m0 0L12 21l2.5-2.5M17 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2">
                                                </path>
                                            </svg>
                                            <p class="font-medium">Your cart is empty</p>
                                            <p class="text-sm">Add products to get started</p>
                                        </div>
                                    </div>

                                    <!-- Totals -->
                                    <div class="border-t-2 border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium">Subtotal:</span>
                                            <span id="subtotal" class="font-semibold text-lg">Rp 0</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                                            <div class="flex flex-col">
                                                <label class="font-medium text-green-700 dark:text-green-300">Auto
                                                    Discount:</label>
                                                <span class="text-xs text-green-600 dark:text-green-400">(<span
                                                        id="discountPercentage">0</span>% - Rp 100k = 1.5%)</span>
                                            </div>
                                            <input type="text" id="discount" readonly value="0"
                                                class="w-32 px-3 py-2 border border-green-300 dark:border-green-600 rounded-lg text-right dark:bg-gray-600 dark:text-white bg-green-100 cursor-not-allowed">
                                        </div>

                                        <!-- Loyalty Points Usage -->
                                        <div class="flex justify-between items-center" id="loyaltySection"
                                            style="display: none;">
                                            <label for="loyaltyPoints"
                                                class="font-medium text-purple-600 dark:text-purple-400">Loyalty
                                                Points:</label>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="loyalty_points_used" id="loyaltyPoints"
                                                    value="0" min="0" max="0"
                                                    onchange="calculateTotal()"
                                                    class="w-20 px-2 py-2 border border-purple-300 dark:border-purple-600 rounded-lg text-right dark:bg-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                <button type="button" onclick="useMaxLoyaltyPoints()"
                                                    class="px-3 py-2 bg-purple-500 text-white rounded-lg text-xs hover:bg-purple-600 transition-colors">Max</button>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <label for="tax" class="font-medium">Tax:</label>
                                            <input type="number" name="tax" id="tax" value="0"
                                                min="0" step="0.01" onchange="calculateTotal()"
                                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-right dark:bg-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div
                                            class="flex justify-between text-xl font-bold border-t-2 border-gray-300 dark:border-gray-600 pt-3 text-gray-800 dark:text-gray-200">
                                            <span>Total:</span>
                                            <span id="totalPrice" class="text-green-600 dark:text-green-400">Rp
                                                0</span>
                                        </div>
                                    </div>

                                    <!-- Hidden Fields for discount -->
                                    <input type="hidden" name="discount" id="hiddenDiscount" value="0">
                                    <input type="hidden" name="discount_percentage" id="hiddenDiscountPercentage"
                                        value="0">

                                    <!-- Payment Methods -->
                                    <div class="mt-6 space-y-4">
    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Payment Methods</h4>
    <div class="grid grid-cols-2 gap-2" id="paymentMethods">
        <!-- Cash -->
        <button type="button" onclick="selectPaymentMethod('cash')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="cash">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-green-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="text-xs font-medium">Cash</span>
            </div>
        </button>

        <!-- Credit/Debit Card -->
        <button type="button" onclick="selectPaymentMethod('card')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="card">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-blue-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
                <span class="text-xs font-medium">Card</span>
            </div>
        </button>

        <!-- QRIS -->
        <button type="button" onclick="selectPaymentMethod('qris')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="qris">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-indigo-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                <span class="text-xs font-medium">QRIS</span>
            </div>
        </button>

        <!-- Bank Transfer -->
        <button type="button" onclick="selectPaymentMethod('transfer')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="transfer">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-purple-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                    </path>
                </svg>
                <span class="text-xs font-medium">Transfer</span>
            </div>
        </button>

        <!-- E-Wallet -->
        <button type="button" onclick="selectPaymentMethod('ewallet')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="ewallet">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-orange-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="text-xs font-medium">E-Wallet</span>
            </div>
        </button>

        <!-- Loyalty Points -->
        <button type="button" onclick="selectPaymentMethod('loyalty')"
            class="payment-method-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200"
            data-method="loyalty" id="loyaltyPaymentBtn" style="display: none;">
            <div class="text-center">
                <svg class="w-6 h-6 mx-auto mb-1 text-purple-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                <span class="text-xs font-medium">Points</span>
            </div>
        </button>
    </div>
    <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="cash">
    <!-- ✅ REMOVED: payment_channel hidden input - Snap will handle it -->
</div>

                                    <!-- Payment Amount -->
                                    <div class="mt-4" id="paymentAmountSection">
                                        <label
                                            class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Payment
                                            Amount</label>
                                        <input type="number" id="paymentAmount" name="amount_paid" step="0.01"
                                            onchange="calculateChange()"
                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white transition-all duration-200 shadow-sm text-lg font-semibold">
                                        <div class="flex gap-2 mt-3">
                                            <button type="button" onclick="setExactAmount()"
                                                class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Exact</button>
                                            <button type="button" onclick="setRoundAmount(50000)"
                                                class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">50k</button>
                                            <button type="button" onclick="setRoundAmount(100000)"
                                                class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">100k</button>
                                            <button type="button" onclick="setRoundAmount(200000)"
                                                class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">200k</button>
                                        </div>
                                    </div>

                                    <!-- Change -->
                                    <div class="mt-4" id="changeSection">
                                        <div
                                            class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                                            <span
                                                class="font-semibold text-green-700 dark:text-green-300">Change:</span>
                                            <span id="change"
                                                class="text-xl font-bold text-green-600 dark:text-green-400">Rp
                                                0</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-6 space-y-3">
                                        <button type="submit"
                                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-all duration-300 shadow-lg transform hover:scale-105">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Complete Sale</span>
                                            </div>
                                        </button>
                                        <button type="button" onclick="clearCart()"
                                            class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-all duration-300 shadow-lg">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                <span>Clear Cart</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="transaction_number"
                            value="{{ 'TXN-' . date('YmdHis') . '-' . rand(1000, 9999) }}">
                        <input type="hidden" name="sale_date" value="{{ now() }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="subtotal" id="hiddenSubtotal" value="0">
                        <input type="hidden" name="total_price" id="hiddenTotalPrice" value="0">
                        <input type="hidden" name="status" value="completed">

                        <!-- Sale Details (will be populated by JavaScript) -->
                        <div id="saleDetailsInputs"></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Payment QR Modal -->
        <div id="paymentQrModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold">FreshMart</h3>
                        <button onclick="closePaymentQrModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Total Amount -->
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100" id="modalTotalAmount">Rp 47.025</div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400" id="modalOrderId">Order ID #QRIS-TXN-1780458903-4863-1780458903</div>
                            <button onclick="copyOrderId()" class="text-blue-600 hover:text-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="toggleDetails()" class="text-blue-600 hover:text-blue-700 transition-colors flex items-center">
                                <span class="text-sm font-medium mr-1">Details</span>
                                <svg class="w-4 h-4 transition-transform" id="detailsArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Order Details (collapsible) -->
                    <div id="orderDetails" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="font-semibold" id="detailSubtotal">Rp 50.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                                <span class="font-semibold text-green-600" id="detailDiscount">- Rp 2.975</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-600 dark:text-gray-400 font-semibold">Total:</span>
                                <span class="font-bold" id="detailTotal">Rp 47.025</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Timer -->
                    <div class="mb-6 text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Pay within</div>
                        <div class="text-2xl font-bold text-red-600" id="paymentTimer">00:14:55</div>
                    </div>

                    <!-- Payment Method Icons -->
                    <div class="flex items-center justify-center gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">GoPay/GoPay Later</div>
                            <div class="flex gap-2">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/200px-Gopay_logo.svg.png" alt="GoPay" class="h-8 object-contain">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Gojek_logo_2019.svg/200px-Gojek_logo_2019.svg.png" alt="Gojek" class="h-8 object-contain">
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="relative mb-6 flex justify-center">
                        <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-red-500">
                            <div class="flex items-start gap-2">
                                <!-- QRIS & GPN Logos -->
                                <div class="text-xs space-y-1">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/200px-QRIS_logo.svg.png" alt="QRIS" class="h-8">
                                    <img src="https://logowik.com/content/uploads/images/gpn-gerbang-pembayaran-nasional1132.jpg" alt="GPN" class="h-8">
                                </div>
                               
                                <!-- QR Code -->
                                <div class="flex-1 flex justify-center items-center" id="qrCodeContainer">
                                    <div class="w-48 h-48 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Red Arrow -->
                                <div class="text-red-500 text-6xl">›</div>
                            </div>
                           
                            <div class="text-center mt-3">
                                <div class="text-xs text-gray-600">Dicatat oleh: <span class="font-semibold">GoPay</span></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">FreshMart</div>
                            </div>
                        </div>
                    </div>

                    <!-- How to Pay (collapsible) -->
                    <div class="mb-6">
                        <button onclick="toggleHowToPay()" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors w-full">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">How to pay</span>
                            <svg class="w-4 h-4 transition-transform ml-auto" id="howToPayArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                       
                        <div id="howToPayContent" class="hidden mt-3 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Open your e-wallet app (GoPay, OVO, DANA, ShopeePay, etc.)</li>
                                <li>Select "Scan QR" or "Pay with QR"</li>
                                <li>Point your camera at the QR code above</li>
                                <li>Confirm the payment amount</li>
                                <li>Complete the payment</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button onclick="downloadQris()" class="w-full bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-6 rounded-xl transition-all">
                            Download QRIS
                        </button>
                       
                        <button onclick="checkPaymentStatusManualFromModal()" id="checkStatusBtn" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 hidden animate-spin" id="checkStatusSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span id="checkStatusText">Check status</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Notification System -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    {{-- SUCCESS MODAL - Dengan Dynamic Status Check (FINAL VERSION) --}}
@php
    // Get dari query parameter (setelah redirect dari Snap)
    $paymentSuccess = request()->query('payment_success');
    $saleId = request()->query('sale_id');
    $transactionNumber = request()->query('transaction_number');
@endphp

@if ($paymentSuccess === 'true' && $saleId && $transactionNumber)
    <div id="successModal" class="success-modal-overlay" onclick="closeModalOnOutsideClick(event)">
        <div class="success-modal">
            <!-- Loading State -->
            <div id="loadingState" class="p-8 text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                    Verifying Payment Status...
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Please wait while we confirm your payment
                </p>
            </div>

            <!-- Success State (Hidden initially) -->
            <div id="successState" class="hidden">
                <div class="success-icon-wrapper">
                    <div class="success-icon">
                        <svg class="w-12 h-12" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark" fill="none" d="M14 27l7.5 7.5L38 18" />
                        </svg>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2 text-center">
                        Payment Successful!
                    </h3>
                    <p class="text-center text-gray-600 dark:text-gray-400 mb-6">
                        Transaction: <span class="font-semibold text-green-600">{{ $transactionNumber }}</span>
                    </p>

                    <div class="space-y-3">
                        <a href="{{ route('sales.show', $saleId) }}"
                            class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Details
                        </a>

                        <button onclick="printReceiptFromModal({{ $saleId }})"
                            class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg transition-all shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Receipt
                        </button>

                        <button onclick="closeSuccessModal()"
                            class="w-full px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pending State (Hidden initially) -->
            <div id="pendingState" class="hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8 text-center">
                    <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center animate-pulse">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2 text-center">
                        ⏳ Payment Pending
                    </h3>
                    <p class="text-center text-gray-600 dark:text-gray-400 mb-4">
                        Transaction: <span class="font-semibold text-blue-600">{{ $transactionNumber }}</span>
                    </p>

                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border-2 border-yellow-200 dark:border-yellow-700">
                        <p class="text-yellow-800 dark:text-yellow-200 text-center">
                            Your payment is still being processed. Please complete the payment or check back later.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <button type="button" onclick="checkPaymentStatusFromModal({{ $saleId }})" id="checkStatusBtnModal"
                            class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg transition-all shadow-md">
                            <svg class="w-5 h-5 mr-2 animate-spin hidden" id="checkSpinnerModal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span id="checkButtonTextModal">Check Payment Status</span>
                        </button>

                        <a href="{{ route('sales.show', $saleId) }}"
                            class="w-full flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all">
                            View Transaction Details
                        </a>

                        <button onclick="closeSuccessModal()"
                            class="w-full px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all">
                            I'll Pay Later
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 🔥 AUTO CHECK STATUS SETELAH MODAL MUNCUL
        document.addEventListener('DOMContentLoaded', function() {
            const saleId = {{ $saleId }};
            
            console.log('🔄 Checking payment status for sale:', saleId);
            
            // Check status immediately
            setTimeout(() => {
                checkPaymentStatusFromModal(saleId, true);
            }, 500);
        });

        // Function to check payment status from modal
        function checkPaymentStatusFromModal(saleId, isAutoCheck = false) {
            const loadingState = document.getElementById('loadingState');
            const successState = document.getElementById('successState');
            const pendingState = document.getElementById('pendingState');
            const checkBtn = document.getElementById('checkStatusBtnModal');
            const checkBtnText = document.getElementById('checkButtonTextModal');
            const checkSpinner = document.getElementById('checkSpinnerModal');

            if (!isAutoCheck && checkBtn) {
                checkBtnText.textContent = 'Checking...';
                checkSpinner.classList.remove('hidden');
            }

            fetch(`/api/sales/${saleId}/check-payment`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ Payment status response:', data);

                if (checkSpinner) {
                    checkSpinner.classList.add('hidden');
                }

                if (data.success && data.status === 'completed') {
                    // ✅ PAYMENT COMPLETED
                    console.log('💚 Payment completed!');
                    
                    if (loadingState) loadingState.classList.add('hidden');
                    if (pendingState) pendingState.classList.add('hidden');
                    if (successState) successState.classList.remove('hidden');
                    
                    if (!isAutoCheck) {
                        showNotification('✅ Payment confirmed successfully!', 'success');
                    }
                } 
                else if (data.status === 'pending') {
                    // ⏳ STILL PENDING
                    console.log('⏳ Payment still pending');
                    
                    if (loadingState) loadingState.classList.add('hidden');
                    if (successState) successState.classList.add('hidden');
                    if (pendingState) pendingState.classList.remove('hidden');
                    
                    if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
                    
                    if (!isAutoCheck) {
                        showNotification('⏳ Payment is still pending', 'warning');
                    }
                }
                else if (data.status === 'failed' || data.status === 'expired' || data.status === 'cancelled') {
                    // ❌ PAYMENT FAILED
                    console.log('❌ Payment failed/expired');
                    
                    if (loadingState) loadingState.classList.add('hidden');
                    if (successState) successState.classList.add('hidden');
                    if (pendingState) pendingState.classList.remove('hidden');
                    
                    if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
                    
                    if (!isAutoCheck) {
                        showNotification('❌ Payment ' + data.status, 'error');
                    }
                }
                else {
                    // UNKNOWN STATUS
                    console.log('❓ Unknown status:', data.status);
                    
                    if (loadingState) loadingState.classList.add('hidden');
                    if (successState) successState.classList.add('hidden');
                    if (pendingState) pendingState.classList.remove('hidden');
                    
                    if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
                }
            })
            .catch(error => {
                console.error('❌ Error checking payment status:', error);
                
                if (checkSpinner) checkSpinner.classList.add('hidden');
                if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
                
                // Show pending state on error
                if (loadingState) loadingState.classList.add('hidden');
                if (successState) successState.classList.add('hidden');
                if (pendingState) pendingState.classList.remove('hidden');
                
                if (!isAutoCheck) {
                    showNotification('Failed to check payment status', 'error');
                }
            });
        }
    </script>
@endif

<!-- Cancel/Change Payment Confirmation Modal -->
<div id="cancelPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-xl font-bold">Payment Window Closed</h3>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                You closed the payment window. What would you like to do?
            </p>

            <div class="space-y-3">
                <!-- Change Payment Method Button -->
                <button onclick="openChangePaymentMethodModal()" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Change Payment Method
                </button>

                <!-- Cancel Sale Button -->
                <button onclick="confirmCancelSale()" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg transition-all shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel Sale
                </button>

                <!-- Back Button -->
                <button onclick="closeCancelPaymentModal()" class="w-full px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all">
                    Go Back
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change Payment Method Modal - FIXED RESPONSIVE VERSION -->
<div id="changePaymentMethodModal" class="fixed inset-0 bg-black bg-opacity-50 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 sm:p-6 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <h3 class="text-lg sm:text-xl font-bold">Select New Payment Method</h3>
                <button onclick="closeChangePaymentMethodModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6">
            <div class="mb-4">
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 truncate">
                    Transaction: <span class="font-semibold" id="changePaymentTransactionNumber">-</span>
                </p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100" id="changePaymentTotalAmount">Rp 0</p>
            </div>

            <!-- Payment Methods Grid - RESPONSIVE -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-4 sm:mb-6">
                <!-- Cash -->
                <button type="button" onclick="selectNewPaymentMethod('cash')" 
                    class="new-payment-method-btn p-3 sm:p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-all" 
                    data-method="cash">
                    <div class="text-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1 sm:mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">Cash</span>
                    </div>
                </button>

                <!-- Card -->
                <button type="button" onclick="selectNewPaymentMethod('card')" 
                    class="new-payment-method-btn p-3 sm:p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-all" 
                    data-method="card">
                    <div class="text-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1 sm:mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">Card</span>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1">Choose in Snap</p>
                    </div>
                </button>

                <!-- QRIS -->
                <button type="button" onclick="selectNewPaymentMethod('qris')" 
                    class="new-payment-method-btn p-3 sm:p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-all" 
                    data-method="qris">
                    <div class="text-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1 sm:mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">QRIS</span>
                    </div>
                </button>

                <!-- Transfer -->
                <button type="button" onclick="selectNewPaymentMethod('transfer')" 
                    class="new-payment-method-btn p-3 sm:p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-all" 
                    data-method="transfer">
                    <div class="text-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1 sm:mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">Transfer</span>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1">Choose bank</p>
                    </div>
                </button>

                <!-- E-Wallet -->
                <button type="button" onclick="selectNewPaymentMethod('ewallet')" 
                    class="new-payment-method-btn p-3 sm:p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-all col-span-2" 
                    data-method="ewallet">
                    <div class="text-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1 sm:mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">E-Wallet</span>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1">GoPay, ShopeePay, DANA</p>
                    </div>
                </button>
            </div>

            <!-- Info Box - RESPONSIVE -->
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="flex items-start">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                        <strong>E-Wallet:</strong> Choose GoPay, ShopeePay, or DANA in the Midtrans payment screen.
                        <br><strong>Other methods:</strong> Select specific channels in the next step.
                    </div>
                </div>
            </div>

            <!-- Confirm Button - RESPONSIVE -->
            <button onclick="confirmChangePaymentMethod()" id="confirmChangePaymentBtn" disabled 
                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 sm:px-6 rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline mr-2 hidden animate-spin" id="confirmChangeSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span id="confirmChangePaymentText">Confirm Payment Method</span>
            </button>

            <!-- Cancel Button - RESPONSIVE -->
            <button onclick="closeChangePaymentMethodModal()" 
                class="w-full mt-3 px-4 sm:px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all text-sm sm:text-base">
                Cancel
            </button>
        </div>
    </div>
</div>

@if(session('show_cancel_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalData = @json(session('show_cancel_modal'));
            console.log('Opening cancel modal from session:', modalData);
            
            setTimeout(() => {
                openCancelPaymentModal(
                    modalData.sale_id, 
                    modalData.transaction_number, 
                    modalData.total_amount
                );
            }, 500);
        });
    </script>
@endif

<!-- Barcode Scanner Modal - RESPONSIVE VERSION -->
<div id="barcodeScannerModal" class="fixed inset-0 bg-black bg-opacity-50 z-[80] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[95vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 sm:p-6 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <h3 class="text-lg sm:text-xl font-bold flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span class="hidden sm:inline">Barcode Scanner</span>
                    <span class="sm:hidden">Scan Barcode</span>
                </h3>
                <button onclick="closeBarcodeScanner()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6">
            <!-- Tabs - Responsive -->
            <div class="flex gap-2 mb-4">
                <button onclick="switchScannerMode('hardware')" id="hardwareTab" 
                    class="flex-1 px-3 sm:px-4 py-2 sm:py-3 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-lg font-semibold transition-all text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 inline sm:hidden mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span class="hidden sm:inline">Hardware Scanner</span>
                    <span class="sm:hidden">Hardware</span>
                </button>
                <button onclick="switchScannerMode('camera')" id="cameraTab" 
                    class="flex-1 px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold transition-all text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 inline sm:hidden mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Camera Scanner</span>
                    <span class="sm:hidden">Camera</span>
                </button>
            </div>

            <!-- Hardware Scanner Mode -->
            <div id="hardwareMode">
                <div class="mb-4 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                        <strong>Instructions:</strong> Scan barcode using your USB/Bluetooth scanner device
                    </p>
                </div>

                <div class="relative">
                    <input type="text" id="hardwareBarcodeInput" placeholder="Scan or type barcode here..." autofocus
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-purple-300 dark:border-purple-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white text-base sm:text-lg font-mono">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500 absolute right-3 top-2.5 sm:top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>

                <div id="hardwareStatus" class="mt-3 sm:mt-4 text-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    Ready to scan...
                </div>
            </div>

            <!-- Camera Scanner Mode - RESPONSIVE -->
            <div id="cameraMode" class="hidden">
                <div class="mb-4 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                        <strong>Instructions:</strong> Click "Start Camera" and point your device camera at the barcode
                    </p>
                </div>

                <!-- Camera Container - Responsive Height -->
                <div id="interactive" class="relative bg-black rounded-lg overflow-hidden mb-4" style="width: 100%; aspect-ratio: 16/9; min-height: 200px; max-height: 400px;">
                    <div id="cameraOverlay" class="absolute inset-0 flex items-center justify-center z-10 bg-black bg-opacity-50">
                        <button onclick="startCamera()" id="startCameraBtn"
                            class="px-4 sm:px-6 py-2 sm:py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition-all shadow-lg text-sm sm:text-base">
                            <span class="hidden sm:inline">Start Camera</span><span class="sm:hidden">Start</span>
                        </button>
                    </div>
                    <canvas class="drawingBuffer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                </div>

                <div id="cameraStatus" class="text-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    Camera not started
                </div>
            </div>

            <!-- Recent Scans - Responsive -->
            <div id="recentScans" class="mt-4 hidden">
                <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recent Scans:</h4>
                <div id="recentScansList" class="space-y-2 max-h-32 sm:max-h-40 overflow-y-auto"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Create Customer Modal -->
<div id="quickCreateCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 z-[80] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 sm:p-6 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <h3 class="text-lg sm:text-xl font-bold flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Quick Add Customer
                </h3>
                <button type="button" onclick="closeQuickCreateCustomerModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="quickCreateCustomerForm" class="p-4 sm:p-6 space-y-4">
            <!-- Customer Name (Required) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Customer Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="quick_customer_name" name="customer_name" required
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="Enter full name">
                <p class="mt-1 text-xs text-red-600 hidden" id="error_customer_name"></p>
            </div>

            <!-- Phone Number -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Phone Number
                </label>
                <input type="text" id="quick_phone_number" name="phone_number"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="+62 812-3456-7890">
                <p class="mt-1 text-xs text-red-600 hidden" id="error_phone_number"></p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Email
                </label>
                <input type="email" id="quick_email" name="email"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="john@example.com">
                <p class="mt-1 text-xs text-red-600 hidden" id="error_email"></p>
            </div>

            <!-- Gender & Birth Date Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Gender -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Gender
                    </label>
                    <select id="quick_gender" name="gender"
                        class="w-full px-3 py-2 sm:py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all">
                        <option value="">Select</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>

                <!-- Birth Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Birth Date
                    </label>
                    <input type="date" id="quick_birth_date" name="birth_date"
                        class="w-full px-3 py-2 sm:py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all">
                </div>
            </div>

            <!-- Info Box -->
            <div class="p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                    <strong>Note:</strong> This is a quick create. For advanced options (address, photo), use the full customer form.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="button" onclick="closeQuickCreateCustomerModal()"
                    class="flex-1 px-4 py-2.5 sm:py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition-all">
                    Cancel
                </button>
                <button type="submit" id="quickCreateCustomerBtn"
                    class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg transition-all shadow-md flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 hidden animate-spin" id="quickCreateSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span id="quickCreateBtnText">Create Customer</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* ✅ RESPONSIVE Barcode Scanner Video Styles */
#interactive {
    position: relative;
    width: 100%;
}

#interactive video,
#interactive canvas {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
}

#interactive canvas.drawingBuffer {
    position: absolute;
    top: 0;
    left: 0;
}

/* Scanning guide animation - RESPONSIVE */
.scan-guide {
    animation: scanPulse 2s ease-in-out infinite;
}

@keyframes scanPulse {
    0%, 100% { 
        border-color: #00ff00;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
    }
    50% { 
        border-color: #00ff00;
        box-shadow: 0 0 20px #00ff00, 0 0 0 9999px rgba(0,0,0,0.5);
    }
}

/* Barcode Scanner Modal Responsive Styles */
#barcodeScannerModal {
    animation: fadeIn 0.3s ease-out;
}

#hardwareBarcodeInput:focus {
    border-color: #9333ea;
    box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
}

/* Recent scans animation - RESPONSIVE */
#recentScansList > div {
    animation: slideInRight 0.3s ease-out;
    font-size: 0.75rem;
}

@keyframes slideInRight {
    from {
        transform: translateX(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Mobile Specific Adjustments */
@media (max-width: 640px) {
    #barcodeScannerModal > div {
        margin: 0.5rem;
        max-height: calc(100vh - 1rem);
        border-radius: 1rem;
    }
    
    #interactive {
        min-height: 180px;
        max-height: 300px;
    }
    
    .scan-guide {
        width: 85% !important;
        height: 45% !important;
    }
}

/* Tablet Specific Adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    #barcodeScannerModal > div {
        max-width: 85%;
    }
    
    #interactive {
        min-height: 250px;
        max-height: 350px;
    }
}

/* Desktop Specific Adjustments */
@media (min-width: 1025px) {
    #interactive {
        min-height: 300px;
        max-height: 400px;
    }
}
    /* Barcode Scanner Styles */
#cameraPreview {
    transform: scaleX(-1); /* Mirror camera for better UX */
}

#barcodeScannerModal {
    animation: fadeIn 0.3s ease-out;
}

#hardwareBarcodeInput:focus {
    border-color: #9333ea;
    box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
}

/* Recent scans animation */
#recentScansList > div {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

    #searchButton {
    position: relative;
    overflow: hidden;
}

#searchButton svg {
    transition: all 0.3s ease;
}

#searchButton span {
    transition: all 0.3s ease;
}

#searchIcon, #resetIcon {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

#resetIcon {
    transform: rotate(0deg);
    transition: transform 0.3s ease;
}

#searchButton:hover #resetIcon {
    transform: rotate(180deg);
}

    @media (max-width: 640px) {
    #changePaymentMethodModal > div {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
    
    .new-payment-method-btn {
        min-height: 90px;
    }
}

@media (min-width: 641px) {
    .new-payment-method-btn {
        min-height: 110px;
    }
}

/* Editable quantity input styling */
.quantity-input {
    width: 50px;
    text-align: center;
    font-weight: bold;
    padding: 0.25rem 0.5rem;
    background-color: #f3f4f6;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.dark .quantity-input {
    background-color: #374151;
    border-color: #4b5563;
    color: #f3f4f6;
}

.quantity-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

    .new-payment-method-btn.selected {
    border-color: #3b82f6 !important;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.new-payment-method-btn {
    transition: all 0.2s ease;
}

.new-payment-method-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
/* Success Modal Additional Styles */
#loadingState {
    animation: fadeIn 0.3s ease-out;
}

#successState, #pendingState {
    animation: slideUp 0.3s ease-out;
}

/* Checkmark Circle Animation */
.checkmark-circle {
    stroke: #10b981;
    stroke-width: 3;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: drawCircle 0.6s ease-out forwards;
}

@keyframes drawCircle {
    to {
        stroke-dashoffset: 0;
    }
}

/* Checkmark Animation */
.checkmark {
    stroke: #10b981;
    stroke-width: 3;
    stroke-dasharray: 50;
    stroke-dashoffset: 50;
    animation: drawCheck 0.5s ease-out 0.6s forwards;
}

@keyframes drawCheck {
    to {
        stroke-dashoffset: 0;
    }
}

/* Rotate animation for arrow */
.rotate-180 {
    transform: rotate(180deg);
}

/* Smooth transitions */
.transition-transform {
    transition: transform 0.3s ease;
}

/* Success Icon Animation */
.success-icon {
    animation: scaleIn 0.5s ease-out 0.2s both;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}
</style>

    <!-- Custom Styles -->
    <style>
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .payment-method-btn.selected {
            border-color: #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
        }

        /* Update di bagian <style> */
        .product-card img {
            object-fit: contain !important;
            background-color: #ffffff !important;
            padding: 0.5rem;
        }

        .dark .product-card img {
            background-color: #ffffff !important;
        }

        @media (max-width: 640px) {
            #productResults {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }

            .product-card img {
                height: 140px !important;
            }
        }

        @media (min-width: 641px) and (max-width: 1023px) {
            #productResults {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 1rem !important;
            }
        }

        @media (min-width: 1536px) {
            #productResults {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }
        }

        @media (min-width: 1280px) and (max-width: 1535px) {
            #productResults {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            }
        }

        @media (min-width: 1024px) and (max-width: 1279px) {
            #productResults {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        /* Success Modal Styles */
        .success-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-out;
        }

        .success-modal {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.3s ease-out;
            overflow: hidden;
        }

        .dark .success-modal {
            background: #1f2937;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon-wrapper {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 2rem;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        .checkmark {
            stroke: #10b981;
            stroke-width: 3;
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: drawCheck 0.5s ease-out 0.5s forwards;
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        .channel-btn.selected {
            border-color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .channel-btn {
            transition: all 0.2s ease;
        }

        .channel-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Tambahkan di bagian <style> */
        .channel-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin: 0 auto 0.5rem;
        }

        .channel-btn {
            transition: all 0.2s ease;
            min-height: 100px;
        }

        .channel-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .channel-btn.selected {
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        #pageLoader {
            transition: opacity 0.3s ease-out;
        }

        #pageLoader.hide {
            opacity: 0;
            pointer-events: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Payment QR Modal Animations */
#paymentQrModal {
    animation: fadeIn 0.3s ease-out;
}

#paymentQrModal > div {
    animation: slideUp 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Timer animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

#paymentTimer {
    animation: pulse 2s ease-in-out infinite;
}

/* New Payment Method Button Styles */
.new-payment-method-btn.selected,
.new-channel-btn.selected {
    border-color: #3b82f6 !important;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.new-payment-method-btn,
.new-channel-btn {
    transition: all 0.2s ease;
}

.new-payment-method-btn:hover,
.new-channel-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.channel-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin: 0 auto;
}

/* Modal z-index layering */
#cancelPaymentModal {
    z-index: 60;
}

#changePaymentMethodModal {
    z-index: 70;
}

/* Spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
    </style>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<!-- QuaggaJS for Barcode Scanner -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
// ============================================
// GLOBAL VARIABLES
// ============================================
let cart = [];
let selectedCustomer = null;
let availableProducts = @json($products);
let allCustomers = @json($customers);
let selectedPaymentMethod = 'cash';
let selectedPaymentChannel = null;

let addProductDebounce = {};
let currentNotification = null;

// Cancel/Change Payment Modal Variables
let currentPendingSaleId = null;
let currentPendingTransactionNumber = null;
let currentPendingTotalAmount = null;
let newSelectedPaymentMethod = null;
let newSelectedPaymentChannel = null;

// ✅ NEW: Store original product cards data INCLUDING promotions
let originalProductsData = [];

// ============================================
// NOTIFICATION SYSTEM
// ============================================
function showNotification(message, type = 'info', duration = 4000) {
    if (currentNotification && currentNotification.parentNode) {
        currentNotification.remove();
    }

    const container = document.getElementById('notificationContainer');
    const notification = document.createElement('div');
    currentNotification = notification;

    const typeConfig = {
        success: {
            bg: 'from-green-500 to-green-600',
            icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>`
        },
        error: {
            bg: 'from-red-500 to-red-600',
            icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>`
        },
        warning: {
            bg: 'from-yellow-500 to-yellow-600',
            icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>`
        },
        info: {
            bg: 'from-blue-500 to-blue-600',
            icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>`
        }
    };

    const config = typeConfig[type] || typeConfig.info;

    notification.className = 'transform transition-all duration-300 ease-in-out translate-x-full opacity-0';
    notification.innerHTML = `
        <div class="bg-gradient-to-r ${config.bg} text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-white border-opacity-30">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${config.icon}
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-sm">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 10);

    setTimeout(() => {
        if (notification === currentNotification && notification.parentNode) {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
                if (currentNotification === notification) {
                    currentNotification = null;
                }
            }, 300);
        }
    }, duration);
}

// ============================================
// ✅ NEW: EXTRACT PROMOTION DATA FROM DOM
// ============================================
function extractPromotionDataFromDOM() {
    const productCards = document.querySelectorAll('[data-product-id]');
    originalProductsData = [];
    
    productCards.forEach(card => {
        const productId = card.getAttribute('data-product-id');
        const product = availableProducts.find(p => p.id == productId);
        
        if (product) {
            const promotionData = {
                id: product.id,
                product_name: card.getAttribute('data-product-name') || product.product_name,
                product_code: product.product_code,
                image: product.image,
                category_id: product.category_id,
                minimum_stock: parseInt(card.getAttribute('data-minimum-stock')) || product.minimum_stock,
                
                // Stock data
                stock: parseInt(card.getAttribute('data-stock')) || product.stock,
                
                // Price data
                selling_price: product.selling_price,
                final_price: parseFloat(card.getAttribute('data-final-price')) || product.selling_price,
                original_price: parseFloat(card.getAttribute('data-original-price')) || product.selling_price,
                
                // Price promotion
                price_promo_id: card.getAttribute('data-price-promo-id') || null,
                price_discount: parseFloat(card.getAttribute('data-price-discount')) || 0,
                price_promo_type: card.getAttribute('data-price-promo-type') || null,
                
                // Quantity promotion
                qty_promo_id: card.getAttribute('data-qty-promo-id') || null,
                buy_qty: parseInt(card.getAttribute('data-buy-qty')) || 0,
                get_qty: parseInt(card.getAttribute('data-get-qty')) || 0,
                qty_promo_type: card.getAttribute('data-qty-promo-type') || null,
                
                // Badge data
                price_badge_text: '',
                price_badge_color: '',
                qty_badge_text: '',
                qty_badge_color: ''
            };
            
            // Extract badge data
            const badges = card.querySelectorAll('.absolute.top-1.right-1 span');
            if (badges.length > 0) {
                promotionData.price_badge_text = badges[0].textContent.trim();
                promotionData.price_badge_color = badges[0].style.backgroundColor;
                
                if (badges.length > 1) {
                    promotionData.qty_badge_text = badges[1].textContent.trim();
                    promotionData.qty_badge_color = badges[1].style.backgroundColor;
                }
            }
            
            originalProductsData.push(promotionData);
        }
    });
    
    console.log('✅ Extracted promotion data from', originalProductsData.length, 'products');
}

// ============================================
// PRODUCT MANAGEMENT
// ============================================
function getProductData(productId) {
    // ✅ First check originalProductsData (has promotion info)
    let product = originalProductsData.find(p => p.id == productId);
    if (product) return product;
    
    // Fallback to availableProducts
    return availableProducts.find(p => p.id == productId);
}

function getProductCurrentStock(productId) {
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (productCard) {
        return parseInt(productCard.getAttribute('data-stock') || 0);
    }

    const product = getProductData(productId);
    return product ? parseInt(product.stock) : 0;
}

function isLowStock(productId, currentStock) {
    const product = getProductData(productId);
    if (!product) return false;

    const minimumStock = parseInt(product.minimum_stock) || 0;
    return currentStock <= minimumStock && currentStock > 0;
}

function updateProductStock(productId, newStock) {
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (!productCard) return;

    const product = getProductData(productId);
    if (!product) return;

    const minimumStock = parseInt(product.minimum_stock) || 0;
    const isOutOfStock = newStock <= 0;
    const isLowStock = newStock > 0 && newStock <= minimumStock;

    productCard.setAttribute('data-stock', newStock);

    const stockBadge = productCard.querySelector('.stock-badge');
    if (stockBadge) {
        stockBadge.textContent = `Stock: ${newStock}`;
        stockBadge.className = `stock-badge px-2 py-1 text-xs rounded-full font-medium ${
            isOutOfStock 
                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' 
                : (isLowStock 
                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' 
                    : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')
        }`;
    }

    let statusContainer = productCard.querySelector('.status-container');
    if (!statusContainer) {
        const parentDiv = productCard.querySelector('.flex.flex-col.space-y-3 > .space-y-1');
        if (parentDiv) {
            statusContainer = document.createElement('div');
            statusContainer.className = 'status-container';
            parentDiv.appendChild(statusContainer);
        }
    }

    if (statusContainer) {
        if (isOutOfStock) {
            statusContainer.innerHTML = `
                <div class="text-center mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Out of Stock
                    </span>
                </div>
            `;
        } else if (isLowStock) {
            statusContainer.innerHTML = `
                <div class="text-center mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Low Stock (Min: ${minimumStock})
                    </span>
                </div>
            `;
        } else {
            statusContainer.innerHTML = '';
        }
    }

    if (isOutOfStock) {
        productCard.classList.add('opacity-50', 'cursor-not-allowed');
        productCard.classList.remove('hover:-translate-y-1', 'hover:shadow-lg', 'hover:border-blue-300', 'dark:hover:border-blue-500');
        productCard.onclick = null;
        productCard.style.pointerEvents = 'none';
    } else {
        productCard.classList.remove('opacity-50', 'cursor-not-allowed');
        productCard.classList.add('hover:-translate-y-1', 'hover:shadow-lg', 'hover:border-blue-300', 'dark:hover:border-blue-500');
        productCard.style.pointerEvents = 'auto';
        
        const pricePromoId = productCard.getAttribute('data-price-promo-id') || null;
        const priceDiscount = parseFloat(productCard.getAttribute('data-price-discount')) || 0;
        const pricePromoType = productCard.getAttribute('data-price-promo-type') || null;
        const qtyPromoId = productCard.getAttribute('data-qty-promo-id') || null;
        const buyQty = parseInt(productCard.getAttribute('data-buy-qty')) || 0;
        const getQty = parseInt(productCard.getAttribute('data-get-qty')) || 0;
        const qtyPromoType = productCard.getAttribute('data-qty-promo-type') || null;
        const finalPrice = parseFloat(productCard.getAttribute('data-final-price')) || product.selling_price;
        const originalPrice = parseFloat(productCard.getAttribute('data-original-price')) || product.selling_price;
        const productName = productCard.getAttribute('data-product-name') || product.product_name;
        
        productCard.onclick = () => addProduct(
            productId,
            productName,
            finalPrice,
            newStock,
            pricePromoId,
            priceDiscount,
            pricePromoType,
            qtyPromoId,
            buyQty,
            getQty,
            qtyPromoType,
            originalPrice
        );
    }
}

function addProduct(productId, productName, price, initialStock, 
                   pricePromotionId = null, priceDiscount = 0, pricePromotionType = null,
                   quantityPromotionId = null, buyQty = 0, getQty = 0, quantityPromotionType = null,
                   originalPrice = null) {
    
    if (!selectedCustomer) {
        showNotification('Please select a customer first', 'warning');
        return;
    }

    const currentStock = getProductCurrentStock(productId);
    const product = getProductData(productId);

    if (currentStock <= 0) {
        showNotification(`${productName} is out of stock!`, 'error');
        return;
    }

    pricePromotionId = (pricePromotionId === 'null' || pricePromotionId === null) ? null : pricePromotionId;
    quantityPromotionId = (quantityPromotionId === 'null' || quantityPromotionId === null) ? null : quantityPromotionId;
    pricePromotionType = (pricePromotionType === 'null' || pricePromotionType === null) ? null : pricePromotionType;
    quantityPromotionType = (quantityPromotionType === 'null' || quantityPromotionType === null) ? null : quantityPromotionType;
    buyQty = parseInt(buyQty) || 0;
    getQty = parseInt(getQty) || 0;
    priceDiscount = parseFloat(priceDiscount) || 0;
    originalPrice = originalPrice ? parseFloat(originalPrice) : parseFloat(price);
    const finalPricePerUnit = parseFloat(price);

    let existingItem = cart.find(item => item.product_id == productId);

    if (existingItem) {
        const oldQuantity = existingItem.quantity;
        const oldFreeQuantity = existingItem.free_quantity || 0;
        const oldTotalQuantity = oldQuantity + oldFreeQuantity;
        
        const newQuantity = oldQuantity + 1;
        
        let newFreeQuantity = 0;
        if (quantityPromotionId && buyQty > 0 && newQuantity >= buyQty) {
            newFreeQuantity = Math.floor(newQuantity / buyQty) * getQty;
        }
        
        const newTotalQuantity = newQuantity + newFreeQuantity;
        const requiredStock = newTotalQuantity - oldTotalQuantity;
        
        if (currentStock < requiredStock) {
            showNotification(`${productName} insufficient stock! Available: ${currentStock}`, 'error');
            return;
        }
        
        existingItem.quantity = newQuantity;
        existingItem.free_quantity = newFreeQuantity;
        existingItem.price_discount_amount = priceDiscount * newQuantity;
        existingItem.quantity_discount_amount = newFreeQuantity * finalPricePerUnit;
        existingItem.subtotal = (finalPricePerUnit * newQuantity);
        
        const newStock = currentStock - requiredStock;
        updateProductStock(productId, newStock);
        
        if (isLowStock(productId, newStock)) {
            showNotification(`${productName} quantity updated to ${newQuantity}${newFreeQuantity > 0 ? ' (+' + newFreeQuantity + ' FREE)' : ''} - ⚠️ Low Stock!`, 'warning');
        } else {
            showNotification(`${productName} quantity updated to ${newQuantity}${newFreeQuantity > 0 ? ' (+' + newFreeQuantity + ' FREE)' : ''}`, 'success');
        }
        
    } else {
        let freeQuantity = 0;
        if (quantityPromotionId && buyQty > 0 && 1 >= buyQty) {
            freeQuantity = Math.floor(1 / buyQty) * getQty;
        }
        
        const totalQuantityNeeded = 1 + freeQuantity;
        
        if (currentStock < totalQuantityNeeded) {
            showNotification(`${productName} insufficient stock!`, 'error');
            return;
        }
        
        const cartItem = {
            product_id: productId,
            product_name: productName,
            original_price: originalPrice,
            unit_price: finalPricePerUnit,
            quantity: 1,
            
            price_promotion_id: pricePromotionId,
            price_discount_amount: priceDiscount,
            price_promotion_type: pricePromotionType,
            
            quantity_promotion_id: quantityPromotionId,
            free_quantity: freeQuantity,
            quantity_discount_amount: freeQuantity * finalPricePerUnit,
            quantity_promotion_type: quantityPromotionType,
            buy_quantity: buyQty,
            get_quantity: getQty,
            
            item_discount: 0,
            subtotal: finalPricePerUnit,
            stock: currentStock
        };
        
        cart.push(cartItem);
        
        const newStock = currentStock - totalQuantityNeeded;
        updateProductStock(productId, newStock);
        
        let promoMessage = `${productName} added`;
        if (pricePromotionId && quantityPromotionId) {
            promoMessage += ' with 2 promotions!';
        } else if (pricePromotionId || quantityPromotionId) {
            promoMessage += ' with promotion!';
        }
        
        if (isLowStock(productId, newStock)) {
            showNotification(promoMessage + ' - ⚠️ Low Stock!', 'warning');
        } else {
            showNotification(promoMessage, 'success');
        }
    }

    updateCartDisplay();
    calculateTotal();
}

function removeItem(index) {
    const item = cart[index];
    const currentStock = getProductCurrentStock(item.product_id);
    const totalQuantityToRestore = item.quantity + (item.free_quantity || 0);
    
    const restoredStock = currentStock + totalQuantityToRestore;
    updateProductStock(item.product_id, restoredStock);

    cart.splice(index, 1);
    
    updateCartDisplay();
    calculateTotal();
    showNotification(`${item.product_name} removed - Stock restored to ${restoredStock}`, 'info');
}

function clearCart() {
    if (cart.length === 0) {
        showNotification('Cart is already empty', 'info');
        return;
    }

    cart.forEach(item => {
        const currentStock = getProductCurrentStock(item.product_id);
        const totalQuantityToRestore = item.quantity + (item.free_quantity || 0);
        
        const restoredStock = currentStock + totalQuantityToRestore;
        updateProductStock(item.product_id, restoredStock);
    });

    cart = [];
    updateCartDisplay();
    calculateTotal();
    showNotification('Cart cleared and all stock restored!', 'success');
}

function updateQuantity(index, change) {
    const item = cart[index];
    const newQuantity = item.quantity + change;

    if (newQuantity <= 0) {
        removeItem(index);
        return;
    }

    const currentStock = getProductCurrentStock(item.product_id);

    const oldFreeItems = item.free_quantity || 0;
    let newFreeItems = 0;
    
    if (item.quantity_promotion_id && item.buy_quantity > 0 && newQuantity >= item.buy_quantity) {
        newFreeItems = Math.floor(newQuantity / item.buy_quantity) * item.get_quantity;
    }
    
    const stockChange = change + (newFreeItems - oldFreeItems);
    
    if (stockChange > 0 && currentStock < stockChange) {
        showNotification(`${item.product_name} insufficient stock! Available: ${currentStock}`, 'error');
        return;
    }

    item.quantity = newQuantity;
    item.free_quantity = newFreeItems;
    item.price_discount_amount = (item.price_discount_amount / (item.quantity - change) || 0) * newQuantity;
    item.quantity_discount_amount = newFreeItems * item.unit_price;
    item.subtotal = (item.unit_price * newQuantity);

    const newStock = currentStock - stockChange;
    updateProductStock(item.product_id, newStock);

    if (stockChange > 0 && isLowStock(item.product_id, newStock)) {
        showNotification(`${item.product_name} quantity updated to ${newQuantity}${newFreeItems > 0 ? ' (+' + newFreeItems + ' FREE)' : ''} - ⚠️ Low Stock!`, 'warning');
    } else {
        showNotification(`${item.product_name} quantity updated to ${newQuantity}${newFreeItems > 0 ? ' (+' + newFreeItems + ' FREE)' : ''}`, 'info');
    }

    updateCartDisplay();
    calculateTotal();
}

function updateQuantityByInput(index, newValue) {
    const newQuantity = parseInt(newValue);
    
    if (isNaN(newQuantity) || newQuantity < 1) {
        showNotification('Quantity must be at least 1', 'warning');
        updateCartDisplay();
        return;
    }

    const item = cart[index];
    const currentStock = getProductCurrentStock(item.product_id);
    
    const oldFreeItems = item.free_quantity || 0;
    let newFreeItems = 0;
    
    if (item.quantity_promotion_id && item.buy_quantity > 0 && newQuantity >= item.buy_quantity) {
        newFreeItems = Math.floor(newQuantity / item.buy_quantity) * item.get_quantity;
    }
    
    const oldTotalQty = item.quantity + oldFreeItems;
    const newTotalQty = newQuantity + newFreeItems;
    const stockChange = newTotalQty - oldTotalQty;
    const newStock = currentStock - stockChange;

    if (newStock < 0) {
        showNotification(`Insufficient stock! Available: ${currentStock + oldTotalQty}`, 'error');
        updateCartDisplay();
        return;
    }

    item.quantity = newQuantity;
    item.free_quantity = newFreeItems;
    item.price_discount_amount = (item.price_promotion_id && item.unit_price < item.original_price) 
        ? (item.original_price - item.unit_price) * newQuantity 
        : 0;
    item.quantity_discount_amount = newFreeItems * item.unit_price;
    item.subtotal = item.unit_price * newQuantity;

    updateProductStock(item.product_id, newStock);

    let message = `${item.product_name} quantity updated to ${newQuantity}`;
    if (newFreeItems > 0) {
        message += ` (+${newFreeItems} FREE)`;
    }
    
    if (stockChange > 0 && isLowStock(item.product_id, newStock)) {
        showNotification(message + ' - ⚠️ Low Stock!', 'warning');
    } else {
        showNotification(message, 'success');
    }

    updateCartDisplay();
    calculateTotal();
}

// ============================================
// CUSTOMER MANAGEMENT
// ============================================
function searchCustomers(query) {
    if (!query) {
        displayAllCustomers();
        return;
    }

    const filtered = allCustomers.filter(customer =>
        customer.customer_name.toLowerCase().includes(query.toLowerCase()) ||
        (customer.phone_number && customer.phone_number.includes(query))
    );

    displayCustomers(filtered);
}

function displayAllCustomers() {
    displayCustomers(allCustomers);
}

function displayCustomers(customers) {
    const customerList = document.getElementById('customerList');
    customerList.innerHTML = '';

    customers.forEach(function(customer) {
        const customerItem = document.createElement('div');
        customerItem.className = 'customer-item p-3 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors duration-150';

        customerItem.onclick = function() {
            selectCustomer(
                customer.id,
                customer.customer_name,
                customer.phone_number || '',
                customer.photo_profile ? '/storage/' + customer.photo_profile : '',
                customer.loyalty_points,
                customer.bank_name || ''
            );
        };

        const photoHtml = customer.photo_profile ?
            '<img src="/storage/' + customer.photo_profile + '" alt="' + customer.customer_name + '" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">' :
            '<div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600"><span class="text-white font-semibold text-sm">' + customer.customer_name.charAt(0) + '</span></div>';

        customerItem.innerHTML =
            '<div class="flex items-center space-x-3">' +
            photoHtml +
            '<div class="flex-1">' +
            '<div class="font-semibold text-gray-800 dark:text-gray-200 text-sm">' + customer.customer_name + '</div>' +
            '<div class="text-xs text-gray-500 dark:text-gray-400">' + (customer.phone_number || '') + '</div>' +
            '<div class="text-xs text-blue-600 dark:text-blue-400 font-medium">' + customer.loyalty_points + ' points</div>' +
            '</div>' +
            '</div>';

        customerList.appendChild(customerItem);
    });
}

function toggleCustomerDropdown(show = null) {
    const dropdown = document.getElementById('customerDropdown');
    if (show === null) {
        dropdown.classList.toggle('hidden');
    } else {
        dropdown.classList.toggle('hidden', !show);
    }
}

function selectCustomer(id, name, phone = '', photo = '', loyaltyPoints = 0) {
    const parsedLoyaltyPoints = parseInt(loyaltyPoints) || 0;
    selectedCustomer = {
        id,
        name,
        phone,
        photo,
        loyaltyPoints: parsedLoyaltyPoints,
    };

    document.getElementById('customerSearch').value = name;
    document.getElementById('selectedCustomerId').value = id || '';

    const customerInfo = document.getElementById('customerInfo');
    const customerPhoto = document.getElementById('customerPhoto');
    const customerName = document.getElementById('customerName');
    const customerPhoneEl = document.getElementById('customerPhone');
    const customerLoyalty = document.getElementById('customerLoyalty');
    const loyaltyPaymentBtn = document.getElementById('loyaltyPaymentBtn');

    customerInfo.classList.remove('hidden');
    customerName.textContent = name;

    if (!id) {
        customerPhoneEl.style.display = 'none';
        customerLoyalty.style.display = 'none';
        customerPhoto.innerHTML = '<div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>';
    } else {
        customerPhoneEl.style.display = 'block';
        customerLoyalty.style.display = 'block';
        
        customerPhoneEl.textContent = phone || 'No phone';
        customerLoyalty.textContent = parsedLoyaltyPoints + ' loyalty points';

        if (photo) {
            customerPhoto.innerHTML = '<img src="' + photo + '" alt="' + name + '" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">';
        } else {
            customerPhoto.innerHTML = '<span class="text-white font-semibold">' + name.charAt(0) + '</span>';
        }
    }

    const loyaltySection = document.getElementById('loyaltySection');
    const loyaltyInput = document.getElementById('loyaltyPoints');
    if (id && parsedLoyaltyPoints > 0) {
        loyaltySection.style.display = 'flex';
        loyaltyInput.max = parsedLoyaltyPoints;
        loyaltyPaymentBtn.style.display = 'block';
    } else {
        loyaltySection.style.display = 'none';
        loyaltyInput.value = 0;
        loyaltyPaymentBtn.style.display = 'none';

        if (selectedPaymentMethod === 'loyalty') {
            selectPaymentMethod('cash');
        }
    }

    toggleCustomerDropdown(false);
    calculateTotal();

    if (id) {
        showNotification('Customer "' + name + '" selected successfully!', 'success');
    } else {
        showNotification('Walk-in customer selected', 'info');
    }
}

function useMaxLoyaltyPoints() {
    if (!selectedCustomer || !selectedCustomer.id) {
        showNotification('Please select a customer first!', 'error');
        return;
    }

    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const totalAfterDiscountAndTax = Math.max(0, subtotal - discount + tax);

    const maxPointsToUse = Math.min(selectedCustomer.loyaltyPoints, Math.floor(totalAfterDiscountAndTax));

    if (maxPointsToUse <= 0) {
        showNotification('No loyalty points available to use', 'warning');
        return;
    }

    document.getElementById('loyaltyPoints').value = maxPointsToUse;
    calculateTotal();
    showNotification(`Using ${maxPointsToUse} loyalty points`, 'info');
}

// ============================================
// PAYMENT METHODS
// ============================================
function selectPaymentMethod(method) {
    if (method === 'loyalty') {
        if (!selectedCustomer || !selectedCustomer.id) {
            showNotification('Please select a customer with loyalty points first!', 'error');
            return;
        }

        if (selectedCustomer.loyaltyPoints <= 0) {
            showNotification('Selected customer has no loyalty points available!', 'error');
            return;
        }

        const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const total = Math.max(0, subtotal - discount + tax);

        if (selectedCustomer.loyaltyPoints < total) {
            showNotification(`Insufficient loyalty points for full payment. Available: ${selectedCustomer.loyaltyPoints}, Required: ${Math.ceil(total)}`, 'error');
            return;
        }
    }

    selectedPaymentMethod = method;
    selectedPaymentChannel = null;
    document.getElementById('selectedPaymentMethod').value = method;

    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('selected');
    });

    document.querySelector(`[data-method="${method}"]`)?.classList.add('selected');

    calculateTotal();

    const paymentAmountSection = document.getElementById('paymentAmountSection');
    const changeSection = document.getElementById('changeSection');
    const total = parseFloat(document.getElementById('hiddenTotalPrice').value) || 0;

    if (method === 'loyalty') {
        paymentAmountSection.style.display = 'none';
        changeSection.style.display = 'none';

        const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const originalTotal = Math.max(0, subtotal - discount + tax);

        document.getElementById('paymentAmount').value = originalTotal;
        document.getElementById('loyaltyPoints').value = originalTotal;

        calculateTotal();

        showNotification(`Using ${originalTotal} loyalty points for full payment`, 'success');
    } else if (method === 'card' || method === 'transfer' || method === 'ewallet' || method === 'qris') {
        paymentAmountSection.style.display = 'block';
        changeSection.style.display = 'block';
        document.getElementById('paymentAmount').value = total;
        calculateChange();
        
        const methodInfo = {
            'card': 'Credit/Debit Card - Choose card type in next step',
            'qris': 'QRIS - Scan QR in next step',
            'transfer': 'Bank Transfer - Choose bank in next step',
            'ewallet': 'E-Wallet - Choose GoPay, ShopeePay, or DANA in next step'
        };
        
        showNotification(methodInfo[method], 'info', 5000);
    } else {
        paymentAmountSection.style.display = 'block';
        changeSection.style.display = 'block';
        calculateChange();
    }

    showNotification(`Payment method: ${method.charAt(0).toUpperCase() + method.slice(1)} selected`, 'info');
}

// ============================================
// CART DISPLAY & CALCULATIONS
// ============================================
function updateCartDisplay() {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');

    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div id="emptyCart" class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5.5M7 13l2.5 5.5m0 0L12 21l2.5-2.5M17 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path>
                </svg>
                <p class="font-medium">Your cart is empty</p>
                <p class="text-sm">Add products to get started</p>
            </div>
        `;
        cartCount.textContent = '0 items';
        return;
    }

    cartItemsDiv.innerHTML = '';

    cart.forEach((item, index) => {
        const totalQuantity = item.quantity + (item.free_quantity || 0);
        
        let promotionBadges = '';
        if (item.quantity_promotion_id) {
            promotionBadges += `<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full ml-1">+${item.free_quantity} FREE</span>`;
        }
        
        const cartItem = document.createElement('div');
        cartItem.className = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-3 shadow-sm';
        cartItem.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="flex-1 pr-3">
                    <h5 class="font-semibold text-sm text-gray-800 dark:text-gray-200 mb-1">${item.product_name}</h5>
                    
                    ${item.price_promotion_id || item.quantity_promotion_id ? `
                    <div class="flex flex-wrap gap-1 mb-2">
                        ${promotionBadges}
                    </div>
                    ` : ''}
                    
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="updateQuantity(${index}, -1)" class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold">-</button>
                            <input type="number" 
                                id="quantity-${index}"
                                class="quantity-input w-12 text-center font-bold" 
                                value="${item.quantity}" 
                                min="1"
                                onchange="updateQuantityByInput(${index}, this.value)">
                            <button type="button" onclick="updateQuantity(${index}, 1)" class="w-7 h-7 bg-green-500 hover:bg-green-600 text-white rounded-full text-sm font-bold">+</button>
                        </div>
                        <div class="text-right">
                            ${item.original_price > item.unit_price ? `
                            <div class="text-xs text-gray-400 line-through">Rp ${number_format(item.original_price)}</div>
                            ` : ''}
                            <div class="text-xs text-gray-600 dark:text-gray-400">Rp ${number_format(item.unit_price)}/pc</div>
                        </div>
                    </div>
                    
                    ${item.free_quantity > 0 ? `
                    <div class="text-xs text-green-600 font-medium mb-2">
                        Total: ${totalQuantity} items (Buy ${item.quantity} + Get ${item.free_quantity} FREE)
                    </div>
                    ` : ''}
                    
                    <button type="button" onclick="removeItem(${index})" class="text-xs text-red-500 hover:text-red-700 font-medium">
                        Remove
                    </button>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-green-600 dark:text-green-400">Rp ${number_format(item.subtotal)}</div>
                    ${(item.price_discount_amount + item.quantity_discount_amount) > 0 ? `
                    <div class="text-xs text-green-600">Saved: Rp ${number_format(item.price_discount_amount + item.quantity_discount_amount)}</div>
                    ` : ''}
                </div>
            </div>
        `;
        cartItemsDiv.appendChild(cartItem);
    });

    const totalItems = cart.reduce((sum, item) => sum + item.quantity + (item.free_quantity || 0), 0);
    cartCount.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;

    updateSaleDetailsInputs();
}

function calculateAutoDiscount(subtotal) {
    if (!selectedCustomer || !selectedCustomer.id) {
        return {
            percentage: 0,
            amount: 0
        };
    }

    const discountPercentage = Math.floor(subtotal / 100000) * 1.5;
    const discountAmount = (subtotal * discountPercentage) / 100;

    return {
        percentage: discountPercentage,
        amount: discountAmount
    };
}

function calculateTotal() {
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);

    const autoDiscount = calculateAutoDiscount(subtotal);
    const discount = autoDiscount.amount;
    const discountPercentage = autoDiscount.percentage;

    document.getElementById('discount').value = discount.toFixed(2);
    document.getElementById('discountPercentage').textContent = discountPercentage.toFixed(1);

    const loyaltyPointsUsed = parseFloat(document.getElementById('loyaltyPoints').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;

    const totalBeforeLoyalty = Math.max(0, subtotal - discount + tax);
    const loyaltyDiscount = loyaltyPointsUsed;
    const finalTotal = Math.max(0, totalBeforeLoyalty - loyaltyDiscount);

    document.getElementById('subtotal').textContent = 'Rp ' + number_format(subtotal);
    document.getElementById('totalPrice').textContent = 'Rp ' + number_format(finalTotal);

    document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
    document.getElementById('hiddenDiscount').value = discount.toFixed(2);
    document.getElementById('hiddenDiscountPercentage').value = discountPercentage.toFixed(2);
    document.getElementById('hiddenTotalPrice').value = finalTotal.toFixed(2);

    if (selectedPaymentMethod === 'loyalty') {
        document.getElementById('paymentAmount').value = totalBeforeLoyalty.toFixed(2);
        document.getElementById('loyaltyPoints').value = Math.floor(totalBeforeLoyalty);
    } else if (selectedPaymentMethod !== 'cash') {
        document.getElementById('paymentAmount').value = finalTotal.toFixed(2);
    }

    calculateChange();
}

function calculateChange() {
    const total = parseFloat(document.getElementById('hiddenTotalPrice').value) || 0;
    const payment = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const change = payment - total;

    const changeElement = document.getElementById('change');
    changeElement.textContent = 'Rp ' + number_format(Math.max(0, change));

    if (change < 0) {
        changeElement.className = 'text-xl font-bold text-red-600 dark:text-red-400';
    } else {
        changeElement.className = 'text-xl font-bold text-green-600 dark:text-green-400';
    }
}

function setExactAmount() {
    const total = parseFloat(document.getElementById('hiddenTotalPrice').value) || 0;
    document.getElementById('paymentAmount').value = total;
    calculateChange();
    showNotification('Exact amount set', 'info');
}

function setRoundAmount(amount) {
    document.getElementById('paymentAmount').value = amount;
    calculateChange();
    showNotification(`Payment amount set to Rp ${number_format(amount)}`, 'info');
}

function updateSaleDetailsInputs() {
    const saleDetailsDiv = document.getElementById('saleDetailsInputs');
    saleDetailsDiv.innerHTML = '';

    cart.forEach((item, index) => {
        const detailHtml = `
            <input type="hidden" name="sale_details[${index}][product_id]" value="${item.product_id}">
            <input type="hidden" name="sale_details[${index}][original_price]" value="${item.original_price}">
            <input type="hidden" name="sale_details[${index}][unit_price]" value="${item.unit_price}">
            <input type="hidden" name="sale_details[${index}][quantity]" value="${item.quantity}">
            
            <input type="hidden" name="sale_details[${index}][price_promotion_id]" value="${item.price_promotion_id || ''}">
            <input type="hidden" name="sale_details[${index}][price_discount_amount]" value="${item.price_discount_amount || 0}">
            <input type="hidden" name="sale_details[${index}][price_promotion_type]" value="${item.price_promotion_type || ''}">
            
            <input type="hidden" name="sale_details[${index}][quantity_promotion_id]" value="${item.quantity_promotion_id || ''}">
            <input type="hidden" name="sale_details[${index}][free_quantity]" value="${item.free_quantity || 0}">
            <input type="hidden" name="sale_details[${index}][quantity_discount_amount]" value="${item.quantity_discount_amount || 0}">
            <input type="hidden" name="sale_details[${index}][quantity_promotion_type]" value="${item.quantity_promotion_type || ''}">
            
            <input type="hidden" name="sale_details[${index}][item_discount]" value="${item.item_discount || 0}">
            <input type="hidden" name="sale_details[${index}][subtotal]" value="${item.subtotal}">
        `;
        saleDetailsDiv.innerHTML += detailHtml;
    });
}

function number_format(number) {
    return new Intl.NumberFormat('id-ID').format(Math.round(number));
}

// ============================================
// ✅ FIXED: PRODUCT SEARCH & DISPLAY
// ============================================
function searchProducts() {
    const searchQuery = document.getElementById('productSearch').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;

    const hasSearch = searchQuery.length > 0 || categoryFilter.length > 0;

    // ✅ Use originalProductsData (contains promo info) instead of availableProducts
    let filtered = originalProductsData.filter(product => {
        const matchesSearch = !searchQuery ||
            product.product_name.toLowerCase().includes(searchQuery) ||
            product.product_code.toLowerCase().includes(searchQuery);

        const matchesCategory = !categoryFilter || product.category_id == categoryFilter;

        return matchesSearch && matchesCategory;
    });

    displayProducts(filtered);
    
    if (hasSearch) {
        setSearchMode(true);
    }
}

function displayProducts(products) {
    const resultsDiv = document.getElementById('productResults');
    resultsDiv.innerHTML = '';

    if (products.length === 0) {
        resultsDiv.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-12 px-4">
                <svg class="w-24 h-24 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">No Products Found</h3>
                <p class="text-gray-500 dark:text-gray-400 text-center mb-6 max-w-md">
                    No products match your search criteria. Try adjusting your filters or add new products.
                </p>
            </div>
        `;
        return;
    }

    products.forEach(product => {
        // ✅ Get current stock from DOM or product data
        const currentStock = getProductCurrentStock(product.id) || product.stock;
        const isOutOfStock = currentStock <= 0;
        const minimumStock = parseInt(product.minimum_stock) || 0;
        const isProductLowStock = currentStock > 0 && currentStock <= minimumStock;

        // ✅ Use promotion data from originalProductsData
        const pricePromoId = product.price_promo_id;
        const priceDiscount = product.price_discount || 0;
        const pricePromoType = product.price_promo_type;
        const qtyPromoId = product.qty_promo_id;
        const buyQty = product.buy_qty || 0;
        const getQty = product.get_qty || 0;
        const qtyPromoType = product.qty_promo_type;
        const finalPrice = product.final_price || product.selling_price;
        const originalPrice = product.original_price || product.selling_price;
        const hasPromotion = (pricePromoId && pricePromoId !== '') || (qtyPromoId && qtyPromoId !== '');

        const productCard = document.createElement('div');
        productCard.className = `border-2 border-gray-200 dark:border-gray-600 rounded-xl p-3 lg:p-4 cursor-pointer hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-500 product-card transition-all duration-300 ${isOutOfStock ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-1'} bg-white dark:bg-gray-800 relative`;
        
        // ✅ Store ALL promotion data in attributes
        productCard.setAttribute('data-stock', currentStock);
        productCard.setAttribute('data-product-id', product.id);
        productCard.setAttribute('data-minimum-stock', minimumStock);
        productCard.setAttribute('data-product-name', product.product_name);
        productCard.setAttribute('data-final-price', finalPrice);
        productCard.setAttribute('data-original-price', originalPrice);
        productCard.setAttribute('data-price-promo-id', pricePromoId || '');
        productCard.setAttribute('data-price-discount', priceDiscount);
        productCard.setAttribute('data-price-promo-type', pricePromoType || '');
        productCard.setAttribute('data-qty-promo-id', qtyPromoId || '');
        productCard.setAttribute('data-buy-qty', buyQty);
        productCard.setAttribute('data-get-qty', getQty);
        productCard.setAttribute('data-qty-promo-type', qtyPromoType || '');

        if (!isOutOfStock) {
            productCard.onclick = () => addProduct(
                product.id,
                product.product_name,
                finalPrice,
                currentStock,
                pricePromoId,
                priceDiscount,
                pricePromoType,
                qtyPromoId,
                buyQty,
                getQty,
                qtyPromoType,
                originalPrice
            );
        }

        // ✅ Build promotion badges HTML
        let promoBadgesHtml = '';
        if (hasPromotion) {
            promoBadgesHtml = '<div class="absolute top-1 right-1 z-10 flex flex-col gap-1">';
            
            if (pricePromoId && product.price_badge_text) {
                promoBadgesHtml += `
                    <span class="inline-block text-xs font-bold px-2 py-1 rounded-full text-white shadow-lg animate-pulse" 
                          style="background-color: ${product.price_badge_color || '#FF0000'};">
                        ${product.price_badge_text}
                    </span>
                `;
            }
            
            if (qtyPromoId && product.qty_badge_text) {
                promoBadgesHtml += `
                    <span class="inline-block text-xs font-bold px-2 py-1 rounded-full text-white shadow-lg animate-pulse" 
                          style="background-color: ${product.qty_badge_color || '#00AA00'};">
                        ${product.qty_badge_text}
                    </span>
                `;
            }
            
            promoBadgesHtml += '</div>';
        }

        // ✅ Build price HTML with promotion info
        let priceHtml = '';
        if (hasPromotion) {
            priceHtml = '<div class="flex flex-col">';
            
            if (pricePromoId) {
                priceHtml += `
                    <span class="text-xs text-gray-400 line-through">
                        Rp ${number_format(originalPrice)}
                    </span>
                    <span class="text-sm lg:text-base font-bold text-red-600 dark:text-red-400">
                        Rp ${number_format(finalPrice)}
                    </span>
                    <span class="text-xs text-green-600">
                        Hemat Rp ${number_format(priceDiscount)}
                    </span>
                `;
            } else {
                priceHtml += `
                    <span class="text-sm lg:text-base font-bold text-blue-600 dark:text-blue-400">
                        Rp ${number_format(finalPrice)}
                    </span>
                `;
            }
            
            if (qtyPromoId) {
                priceHtml += `
                    <span class="text-xs text-purple-600 font-bold mt-1">
                        Beli ${buyQty} Gratis ${getQty}!
                    </span>
                `;
            }
            
            priceHtml += '</div>';
        } else {
            priceHtml = `
                <p class="text-sm lg:text-base font-bold text-green-600 dark:text-green-400">
                    Rp ${number_format(product.selling_price)}
                </p>
            `;
        }

        let statusBadgeHtml = '';
        if (isOutOfStock) {
            statusBadgeHtml = `
                <div class="text-center mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Out of Stock
                    </span>
                </div>
            `;
        } else if (isProductLowStock) {
            statusBadgeHtml = `
                <div class="text-center mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Low Stock (Min: ${minimumStock})
                    </span>
                </div>
            `;
        }

        productCard.innerHTML = `
            ${promoBadgesHtml}
            <div class="flex flex-col space-y-3">
                ${product.image ?
                    `<img src="/storage/${product.image}" alt="${product.product_name}" class="w-full h-32 object-contain rounded-lg bg-white dark:bg-white p-2">` :
                    `<div class="w-full h-32 bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>`
                }
                <div class="space-y-1">
                    <h4 class="font-semibold text-sm lg:text-base text-gray-800 dark:text-gray-100 line-clamp-2 min-h-[2.5rem]">${product.product_name}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">${product.product_code}</p>
                    <div class="flex items-center justify-between">
                        ${priceHtml}
                        <span class="stock-badge px-2 py-1 text-xs rounded-full font-medium ${isOutOfStock ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : (isProductLowStock ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')}">
                            Stock: ${currentStock}
                        </span>
                    </div>
                    <div class="status-container">
                        ${statusBadgeHtml}
                    </div>
                </div>
            </div>
        `;

        resultsDiv.appendChild(productCard);
    });
}

// ============================================
// FORM SUBMISSION & PAYMENT PROCESSING
// ============================================
document.getElementById('saleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (cart.length === 0) {
        showNotification('Please add items to cart before completing sale', 'error');
        return false;
    }

    if (!selectedCustomer) {
        showNotification('Please select a customer before completing sale', 'error');
        document.getElementById('customerSearch').focus();
        return false;
    }

    if (!selectedPaymentMethod) {
        showNotification('Please select a payment method', 'error');
        return false;
    }

    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const loyaltyPointsUsed = parseFloat(document.getElementById('loyaltyPoints').value) || 0;
    const payment = parseFloat(document.getElementById('paymentAmount').value) || 0;

    if (selectedPaymentMethod === 'loyalty') {
        if (!selectedCustomer || !selectedCustomer.id) {
            showNotification('Customer is required for loyalty payment', 'error');
            return false;
        }

        const totalBeforeLoyalty = Math.max(0, subtotal - discount + tax);

        if (selectedCustomer.loyaltyPoints < totalBeforeLoyalty) {
            showNotification(`Insufficient loyalty points. Available: ${selectedCustomer.loyaltyPoints}, Required: ${Math.ceil(totalBeforeLoyalty)}`, 'error');
            return false;
        }

        document.getElementById('paymentAmount').value = totalBeforeLoyalty;
        document.getElementById('loyaltyPoints').value = Math.floor(totalBeforeLoyalty);
    } else {
        const totalAfterLoyalty = Math.max(0, subtotal - discount - loyaltyPointsUsed + tax);

        if (loyaltyPointsUsed > 0) {
            if (!selectedCustomer || !selectedCustomer.id) {
                showNotification('Customer is required when using loyalty points', 'error');
                return false;
            }

            if (selectedCustomer.loyaltyPoints < loyaltyPointsUsed) {
                showNotification(`Insufficient loyalty points. Available: ${selectedCustomer.loyaltyPoints}`, 'error');
                return false;
            }
        }

        if (selectedPaymentMethod === 'cash' && payment < totalAfterLoyalty) {
            showNotification('Payment amount is insufficient for cash payment', 'error');
            document.getElementById('paymentAmount').focus();
            return false;
        }
    }

    showNotification('Processing sale...', 'info');

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Transaction failed');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Transaction response:', data);

        if (!data.success) {
            throw new Error(data.message || 'Transaction failed');
        }

        if (data.snap_token && (data.payment_method === 'card' || data.payment_method === 'qris' || data.payment_method === 'transfer' || data.payment_method === 'ewallet')) {
            console.log('Opening Midtrans Snap with token:', data.snap_token);

            if (typeof window.snap === 'undefined') {
                throw new Error('Midtrans Snap library not loaded');
            }

            let autoCheckInterval = null;
            let checkCount = 0;
            const maxChecks = 60;

            function startAutoCheck() {
                console.log('🔄 Starting auto payment check...');
                
                autoCheckInterval = setInterval(() => {
                    checkCount++;
                    console.log(`🔍 Auto-checking payment status... (${checkCount}/${maxChecks})`);
                    
                    fetch(`/api/sales/${data.sale_id}/check-payment`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(statusData => {
                        console.log('📊 Auto-check result:', statusData);
                        
                        if (statusData.success && statusData.status === 'completed') {
                            console.log('✅ Payment detected as completed!');
                            clearInterval(autoCheckInterval);
                            
                            showNotification('Payment successful!', 'success');
                            cart = [];
                            updateCartDisplay();
                            
                            window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
                        }
                        else if (statusData.status === 'failed' || statusData.status === 'expired' || statusData.status === 'cancelled') {
                            console.log('❌ Payment failed/expired');
                            clearInterval(autoCheckInterval);
                            
                            setTimeout(() => {
                                openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                            }, 500);
                        }
                        else if (checkCount >= maxChecks) {
                            console.log('⏱️ Auto-check timeout');
                            clearInterval(autoCheckInterval);
                            
                            setTimeout(() => {
                                openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                            }, 500);
                        }
                    })
                    .catch(error => {
                        console.error('Error in auto-check:', error);
                        if (checkCount >= maxChecks) {
                            clearInterval(autoCheckInterval);
                        }
                    });
                }, 5000);
            }

            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('✅ Snap onSuccess callback:', result);
                    
                    if (autoCheckInterval) {
                        clearInterval(autoCheckInterval);
                    }
                    
                    showNotification('Payment successful! Verifying...', 'success');
                    cart = [];
                    updateCartDisplay();
                    
                    window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
                },
                onPending: function(result) {
                    console.log('⏳ Snap onPending callback:', result);
                    
                    if (autoCheckInterval) {
                        clearInterval(autoCheckInterval);
                    }
                    
                    setTimeout(() => {
                        openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                    }, 500);
                },
                onError: function(result) {
                    console.error('❌ Snap onError callback:', result);
                    
                    if (autoCheckInterval) {
                        clearInterval(autoCheckInterval);
                    }
                    
                    showNotification('Payment failed. Please try again.', 'error');
                    
                    setTimeout(() => {
                        openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                    }, 500);
                },
                onClose: function() {
                    console.log('🚪 Snap popup closed by user');
                    
                    console.log('⏳ Waiting 30 seconds before showing cancel modal...');
                    
                    setTimeout(() => {
                        fetch(`/api/sales/${data.sale_id}/check-payment`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(statusData => {
                            if (statusData.success && statusData.status === 'completed') {
                                if (autoCheckInterval) {
                                    clearInterval(autoCheckInterval);
                                }
                                
                                cart = [];
                                updateCartDisplay();
                                window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
                            } else {
                                if (autoCheckInterval) {
                                    clearInterval(autoCheckInterval);
                                }
                                
                                openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                            }
                        })
                        .catch(error => {
                            console.error('Final check error:', error);
                            if (autoCheckInterval) {
                                clearInterval(autoCheckInterval);
                            }
                            openCancelPaymentModal(data.sale_id, data.transaction_number, data.total_amount);
                        });
                    }, 30000);
                }
            });

            setTimeout(() => {
                startAutoCheck();
            }, 5000);
        } else {
            console.log('Cash/Loyalty payment - completed immediately');
            showNotification('Sale completed successfully!', 'success');

            cart = [];
            updateCartDisplay();

            setTimeout(() => {
                window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Transaction error:', error);
        showNotification(error.message || 'Failed to process transaction', 'error');
    });

    return false;
});

// ============================================
// SUCCESS MODAL
// ============================================
function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function closeModalOnOutsideClick(event) {
    if (event.target === event.currentTarget) {
        closeSuccessModal();
    }
}

function checkPaymentStatusFromModal(saleId, isAutoCheck = false) {
    const loadingState = document.getElementById('loadingState');
    const successState = document.getElementById('successState');
    const pendingState = document.getElementById('pendingState');
    const checkBtn = document.getElementById('checkStatusBtnModal');
    const checkBtnText = document.getElementById('checkButtonTextModal');
    const checkSpinner = document.getElementById('checkSpinnerModal');

    if (!isAutoCheck && checkBtn) {
        checkBtnText.textContent = 'Checking...';
        checkSpinner.classList.remove('hidden');
    }

    fetch(`/api/sales/${saleId}/check-payment`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Payment status response:', data);

        if (checkSpinner) {
            checkSpinner.classList.add('hidden');
        }

        if (data.success && data.status === 'completed') {
            console.log('💚 Payment completed!');
            
            if (loadingState) loadingState.classList.add('hidden');
            if (pendingState) pendingState.classList.add('hidden');
            if (successState) successState.classList.remove('hidden');
            
            if (!isAutoCheck) {
                showNotification('✅ Payment confirmed successfully!', 'success');
            }
        } 
        else if (data.status === 'pending') {
            console.log('⏳ Payment still pending');
            
            if (loadingState) loadingState.classList.add('hidden');
            if (successState) successState.classList.add('hidden');
            if (pendingState) pendingState.classList.remove('hidden');
            
            if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
            
            if (!isAutoCheck) {
                showNotification('⏳ Payment is still pending', 'warning');
            }
        }
        else if (data.status === 'failed' || data.status === 'expired' || data.status === 'cancelled') {
            console.log('❌ Payment failed/expired');
            
            if (loadingState) loadingState.classList.add('hidden');
            if (successState) successState.classList.add('hidden');
            if (pendingState) pendingState.classList.remove('hidden');
            
            if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
            
            if (!isAutoCheck) {
                showNotification('❌ Payment ' + data.status, 'error');
            }
        }
        else {
            console.log('❓ Unknown status:', data.status);
            
            if (loadingState) loadingState.classList.add('hidden');
            if (successState) successState.classList.add('hidden');
            if (pendingState) pendingState.classList.remove('hidden');
            
            if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
        }
    })
    .catch(error => {
        console.error('❌ Error checking payment status:', error);
        
        if (checkSpinner) checkSpinner.classList.add('hidden');
        if (checkBtnText) checkBtnText.textContent = 'Check Payment Status';
        
        if (loadingState) loadingState.classList.add('hidden');
        if (successState) successState.classList.add('hidden');
        if (pendingState) pendingState.classList.remove('hidden');
        
        if (!isAutoCheck) {
            showNotification('Failed to check payment status', 'error');
        }
    });
}

// ============================================
// CANCEL/CHANGE PAYMENT MODALS
// ============================================
function openCancelPaymentModal(saleId, transactionNumber, totalAmount) {
    currentPendingSaleId = saleId;
    currentPendingTransactionNumber = transactionNumber;
    currentPendingTotalAmount = totalAmount;

    if (typeof window.snap !== 'undefined') {
        try {
            document.body.style.overflow = 'auto';
        } catch (e) {
            console.log('Snap popup already closed');
        }
    }

    const modal = document.getElementById('cancelPaymentModal');
    modal.classList.remove('hidden');

    setTimeout(() => {
        document.body.style.overflow = 'hidden';
    }, 100);

    console.log('Cancel Payment Modal opened successfully');
}

function closeCancelPaymentModal() {
    document.getElementById('cancelPaymentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openChangePaymentMethodModal() {
    closeCancelPaymentModal();
    document.getElementById('changePaymentTransactionNumber').textContent = currentPendingTransactionNumber;
    document.getElementById('changePaymentTotalAmount').textContent = 'Rp ' + number_format(currentPendingTotalAmount);

    newSelectedPaymentMethod = null;
    newSelectedPaymentChannel = null;
    
    document.querySelectorAll('.new-payment-method-btn').forEach(btn => {
        btn.classList.remove('selected', 'border-blue-500', 'bg-blue-50');
    });
    
    document.getElementById('confirmChangePaymentBtn').disabled = true;

    const modal = document.getElementById('changePaymentMethodModal');
    modal.classList.remove('hidden');

    setTimeout(() => {
        document.body.style.overflow = 'hidden';
    }, 100);

    console.log('Change Payment Method Modal opened');
}

function closeChangePaymentMethodModal() {
    document.getElementById('changePaymentMethodModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function selectNewPaymentMethod(method) {
    newSelectedPaymentMethod = method;
    newSelectedPaymentChannel = null;

    document.querySelectorAll('.new-payment-method-btn').forEach(btn => {
        btn.classList.remove('selected', 'border-blue-500', 'bg-blue-50');
    });

    document.querySelector(`.new-payment-method-btn[data-method="${method}"]`)?.classList.add('selected', 'border-blue-500', 'bg-blue-50');

    document.getElementById('confirmChangePaymentBtn').disabled = false;
    
    const methodName = {
        'cash': 'Cash',
        'card': 'Credit/Debit Card',
        'qris': 'QRIS',
        'transfer': 'Bank Transfer',
        'ewallet': 'E-Wallet (GoPay, ShopeePay, DANA)'
    }[method];
    
    showNotification(`${methodName} selected`, 'info');
}

function confirmChangePaymentMethod() {
    if (!newSelectedPaymentMethod) {
        showNotification('Please select a payment method', 'error');
        return;
    }

    const confirmBtn = document.getElementById('confirmChangePaymentBtn');
    const confirmText = document.getElementById('confirmChangePaymentText');
    const confirmSpinner = document.getElementById('confirmChangeSpinner');

    confirmBtn.disabled = true;
    confirmText.textContent = 'Processing...';
    confirmSpinner.classList.remove('hidden');

    fetch(`/api/sales/${currentPendingSaleId}/change-payment-method`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            new_payment_method: newSelectedPaymentMethod,
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Change payment method response:', data);

        if (data.success) {
            showNotification('Payment method changed successfully!', 'success');

            closeChangePaymentMethodModal();

            if (data.snap_token) {
                setTimeout(() => {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            cart = [];
                            updateCartDisplay();
                            window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            setTimeout(() => {
                                openCancelPaymentModal(data.sale_id, data.transaction_number, currentPendingTotalAmount);
                            }, 500);
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            setTimeout(() => {
                                openCancelPaymentModal(data.sale_id, data.transaction_number, currentPendingTotalAmount);
                            }, 500);
                        },
                        onClose: function() {
                            console.log('Snap popup closed');
                            setTimeout(() => {
                                openCancelPaymentModal(data.sale_id, data.transaction_number, currentPendingTotalAmount);
                            }, 500);
                        }
                    });
                }, 500);
            } else {
                setTimeout(() => {
                    window.location.href = `/sales/create?payment_success=true&sale_id=${data.sale_id}&transaction_number=${data.transaction_number}`;
                }, 1000);
            }
        } else {
            throw new Error(data.message || 'Failed to change payment method');
        }
    })
    .catch(error => {
        console.error('Error changing payment method:', error);
        showNotification('Error: ' + error.message, 'error');

        confirmBtn.disabled = false;
        confirmText.textContent = 'Confirm Payment Method';
        confirmSpinner.classList.add('hidden');
    });
}

function confirmCancelSale() {
    if (!confirm('Are you sure you want to cancel this sale? This action cannot be undone.')) {
        return;
    }

    showNotification('Cancelling sale...', 'info');

    fetch(`/api/sales/${currentPendingSaleId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cancel sale response:', data);

        if (data.success) {
            showNotification('Sale cancelled successfully', 'success');

            closeCancelPaymentModal();

            cart = [];
            updateCartDisplay();

            setTimeout(() => {
                window.location.href = '/sales/create';
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to cancel sale');
        }
    })
    .catch(error => {
        console.error('Error cancelling sale:', error);
        showNotification('Error: ' + error.message, 'error');
    });
}

// ============================================
// UTILITY FUNCTIONS
// ============================================
function printReceiptFromModal(saleId) {
    const printUrl = `/sales/${saleId}/receipt?autoprint=1`;

    const existingIframe = document.getElementById('printIframe');
    if (existingIframe) {
        existingIframe.remove();
    }

    let iframe = document.createElement('iframe');
    iframe.id = 'printIframe';
    iframe.style.display = 'none';
    iframe.src = printUrl;
    document.body.appendChild(iframe);

    iframe.onload = function() {
        setTimeout(function() {
            try {
                iframe.contentWindow.print();
                showNotification('Receipt opened in print dialog', 'success');

                setTimeout(() => {
                    if (iframe.parentNode) {
                        iframe.remove();
                    }
                }, 5000);
            } catch (error) {
                console.error('Print error:', error);
                showNotification('Failed to open print dialog', 'error');
            }
        }, 500);
    };

    iframe.onerror = function() {
        showNotification('Failed to load receipt', 'error');
        iframe.remove();
    };
}

function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    const dateTimeStr = now.toLocaleDateString('id-ID', options);
    document.getElementById('currentDateTime').textContent = dateTimeStr;
}

// ============================================
// SEARCH & RESET FUNCTIONALITY
// ============================================
let isSearchActive = false;

function handleSearch() {
    if (isSearchActive) {
        resetSearch();
    } else {
        searchProducts();
        setSearchMode(true);
    }
}

function setSearchMode(active) {
    isSearchActive = active;
    const button = document.getElementById('searchButton');
    const buttonText = document.getElementById('searchButtonText');
    const searchIcon = document.getElementById('searchIcon');
    const resetIcon = document.getElementById('resetIcon');

    if (active) {
        button.classList.remove('from-blue-500', 'to-blue-600', 'hover:from-blue-600', 'hover:to-blue-700');
        button.classList.add('from-orange-500', 'to-orange-600', 'hover:from-orange-600', 'hover:to-orange-700');
        buttonText.textContent = 'Reset';
        searchIcon.classList.add('hidden');
        resetIcon.classList.remove('hidden');
    } else {
        button.classList.remove('from-orange-500', 'to-orange-600', 'hover:from-orange-600', 'hover:to-orange-700');
        button.classList.add('from-blue-500', 'to-blue-600', 'hover:from-blue-600', 'hover:to-blue-700');
        buttonText.textContent = 'Search';
        searchIcon.classList.remove('hidden');
        resetIcon.classList.add('hidden');
    }
}

// ✅ FIXED: Reset Search - Use originalProductsData
function resetSearch() {
    document.getElementById('productSearch').value = '';
    document.getElementById('categoryFilter').value = '';
    
    // ✅ Display all products with promotion data
    displayProducts(originalProductsData);
    
    setSearchMode(false);
    
    showNotification('Search reset - showing all products', 'info');
}

// ============================================
// INITIALIZATION & EVENT LISTENERS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // ✅ CRITICAL: Extract promotion data on page load
    extractPromotionDataFromDOM();
    
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('customerDropdown');
        const search = document.getElementById('customerSearch');

        if (!dropdown.contains(event.target) && !search.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.getElementById('productSearch').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        if (this.value) {
            searchProducts();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSuccessModal();
        }
    });

    setInterval(updateDateTime, 1000);
    updateDateTime();

    window.addEventListener('load', function() {
        const loader = document.getElementById('pageLoader');
        setTimeout(() => {
            loader.classList.add('hide');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }, 500);
    });

    setTimeout(() => {
        const loader = document.getElementById('pageLoader');
        if (loader && !loader.classList.contains('hide')) {
            loader.classList.add('hide');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }
    }, 3000);

    displayAllCustomers();
    selectPaymentMethod('cash');
    updateCartDisplay();
    calculateTotal();

    setTimeout(() => {
        if (!selectedCustomer) {
            showNotification('Please select a customer to start shopping (Member or Walk-in Customer)', 'info', 8000);
        }
    }, 1000);
});

// ============================================
// BARCODE SCANNER FUNCTIONALITY - FIXED VERSION
// ============================================
let currentScannerMode = 'hardware';
let cameraStream = null;
let isScanning = false;

function toggleBarcodeScanner() {
    if (!selectedCustomer) {
        showNotification('Please select a customer first', 'warning');
        return;
    }

    const modal = document.getElementById('barcodeScannerModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on hardware input by default
    setTimeout(() => {
        document.getElementById('hardwareBarcodeInput').focus();
    }, 300);
}

function closeBarcodeScanner() {
    const modal = document.getElementById('barcodeScannerModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Stop camera if active
    stopCamera();
    
    // Clear input
    document.getElementById('hardwareBarcodeInput').value = '';
}

function switchScannerMode(mode) {
    currentScannerMode = mode;
    
    // Update tabs
    const hardwareTab = document.getElementById('hardwareTab');
    const cameraTab = document.getElementById('cameraTab');
    const hardwareMode = document.getElementById('hardwareMode');
    const cameraMode = document.getElementById('cameraMode');
    
    if (mode === 'hardware') {
        hardwareTab.classList.add('bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300');
        hardwareTab.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        cameraTab.classList.remove('bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300');
        cameraTab.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        
        hardwareMode.classList.remove('hidden');
        cameraMode.classList.add('hidden');
        
        stopCamera();
        document.getElementById('hardwareBarcodeInput').focus();
    } else {
        cameraTab.classList.add('bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300');
        cameraTab.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        hardwareTab.classList.remove('bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300');
        hardwareTab.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        
        cameraMode.classList.remove('hidden');
        hardwareMode.classList.add('hidden');
    }
}

// Hardware Scanner Handler
document.addEventListener('DOMContentLoaded', function() {
    const hardwareInput = document.getElementById('hardwareBarcodeInput');
    
    if (hardwareInput) {
        let barcodeBuffer = '';
        let lastKeyTime = Date.now();
        
        hardwareInput.addEventListener('keypress', function(e) {
            const currentTime = Date.now();
            
            // If more than 100ms between keys, reset buffer (human typing)
            if (currentTime - lastKeyTime > 100) {
                barcodeBuffer = '';
            }
            
            lastKeyTime = currentTime;
            
            if (e.key === 'Enter') {
                e.preventDefault();
                if (barcodeBuffer.length > 0) {
                    processBarcodeInput(barcodeBuffer);
                    barcodeBuffer = '';
                    hardwareInput.value = '';
                }
            } else {
                barcodeBuffer += e.key;
            }
        });
        
        // Also handle direct input change (for manual typing + Enter)
        hardwareInput.addEventListener('change', function() {
            const barcode = this.value.trim();
            if (barcode) {
                processBarcodeInput(barcode);
                this.value = '';
                barcodeBuffer = '';
            }
        });
    }
});

async function startCamera() {
    const startBtn = document.getElementById('startCameraBtn');
    const overlay = document.getElementById('cameraOverlay');
    const status = document.getElementById('cameraStatus');
    
    try {
        status.textContent = 'Starting camera...';
        startBtn.disabled = true;
        
        if (typeof Quagga === 'undefined') {
            throw new Error('Quagga library not loaded. Please refresh the page.');
        }
        
        // ✅ RESPONSIVE: Detect device type
        const isMobile = window.innerWidth <= 640;
        const isTablet = window.innerWidth > 640 && window.innerWidth <= 1024;
        
        // ✅ Adjust constraints based on device
        const constraints = {
            width: { 
                min: isMobile ? 320 : 640, 
                ideal: isMobile ? 720 : (isTablet ? 1024 : 1280), 
                max: isMobile ? 1080 : 1920 
            },
            height: { 
                min: isMobile ? 240 : 480, 
                ideal: isMobile ? 540 : (isTablet ? 768 : 720), 
                max: isMobile ? 720 : 1080 
            },
            facingMode: "environment",
            aspectRatio: { ideal: 16/9 }
        };
        
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive'),
                constraints: constraints,
                area: {
                    top: isMobile ? "25%" : "20%",
                    right: isMobile ? "5%" : "10%",
                    left: isMobile ? "5%" : "10%",
                    bottom: isMobile ? "25%" : "20%"
                }
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "upc_reader",
                    "upc_e_reader"
                ],
                debug: {
                    drawBoundingBox: true,
                    showFrequency: false,
                    drawScanline: true,
                    showPattern: false
                },
                multiple: false
            },
            locate: true,
            locator: {
                halfSample: true,
                patchSize: isMobile ? "small" : "medium"
            },
            numOfWorkers: navigator.hardwareConcurrency || 2,
            frequency: isMobile ? 5 : 10
        }, function(err) {
            if (err) {
                console.error('❌ Quagga initialization error:', err);
                status.textContent = 'Error: ' + err.message;
                startBtn.disabled = false;
                return;
            }
            
            console.log("✅ Quagga initialized successfully");
            Quagga.start();
            
            isScanning = true;
            overlay.classList.add('hidden');
            status.textContent = ' Camera active - Point at barcode';
            
            // ✅ RESPONSIVE: Add scanning guide overlay
            const interactive = document.querySelector('#interactive');
            let guide = interactive.querySelector('.scan-guide');
            if (!guide) {
                guide = document.createElement('div');
                guide.className = 'scan-guide';
                const guideWidth = isMobile ? '85%' : '80%';
                const guideHeight = isMobile ? '45%' : '40%';
                guide.style.cssText = `position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: ${guideWidth}; height: ${guideHeight}; border: 3px solid #00ff00; border-radius: 10px; pointer-events: none; z-index: 5; box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);`;
                interactive.appendChild(guide);
            }
            
            let lastDetectedCode = '';
            let lastDetectionTime = 0;
            
            Quagga.onDetected(function(result) {
                if (!isScanning) return;
                
                const code = result.codeResult.code;
                const currentTime = Date.now();
                
                if (code !== lastDetectedCode || currentTime - lastDetectionTime > 2000) {
                    lastDetectedCode = code;
                    lastDetectionTime = currentTime;
                    
                    guide.style.borderColor = '#00ff00';
                    guide.style.boxShadow = '0 0 20px #00ff00, 0 0 0 9999px rgba(0,0,0,0.5)';
                    
                    setTimeout(() => {
                        guide.style.borderColor = '#00ff00';
                        guide.style.boxShadow = '0 0 0 9999px rgba(0,0,0,0.5)';
                    }, 300);
                    
                    status.textContent = 'Barcode detected: ' + code;
                    playBeep();
                    processBarcodeInput(code);
                }
            });
        });
        
    } catch (error) {
        console.error('❌ Camera error:', error);
        status.textContent = 'Error: ' + error.message;
        startBtn.disabled = false;
        showNotification('Camera error: ' + error.message, 'error');
    }
}

function stopCamera() {
    if (isScanning) {
        try {
            Quagga.stop();
            isScanning = false;
            console.log("Camera stopped successfully");
        } catch (error) {
            console.error('Error stopping camera:', error);
        }
    }
    
    const video = document.getElementById('cameraPreview');
    const overlay = document.getElementById('cameraOverlay');
    const startBtn = document.getElementById('startCameraBtn');
    const status = document.getElementById('cameraStatus');
    
    if (overlay) overlay.classList.remove('hidden');
    if (startBtn) startBtn.disabled = false;
    if (status) status.textContent = 'Camera stopped';
}

function playBeep() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (error) {
        console.error('Beep sound error:', error);
    }
}

function processBarcodeInput(barcode) {
    if (!barcode) return;
    
    console.log('Processing barcode:', barcode);
    
    const statusElement = currentScannerMode === 'hardware' 
        ? document.getElementById('hardwareStatus') 
        : document.getElementById('cameraStatus');
    
    statusElement.textContent = 'Searching product...';
    
    // Add to recent scans
    addToRecentScans(barcode);
    
    // Search product by barcode
    fetch('/sales/search-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ barcode: barcode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.product) {
            const product = data.product;
            
            statusElement.textContent = 'Found: ' + product.product_name;
            
            // Add to cart using existing function
            addProduct(
                product.id,
                product.product_name,
                product.final_price,
                product.stock,
                product.price_promo_id,
                product.price_discount,
                product.price_promo_type,
                product.qty_promo_id,
                product.buy_qty,
                product.get_qty,
                product.qty_promo_type,
                product.original_price
            );
            
            // Reset status after 2 seconds
            setTimeout(() => {
                statusElement.textContent = currentScannerMode === 'hardware' 
                    ? 'Ready to scan...' 
                    : 'Camera active - Point at barcode';
            }, 2000);
            
        } else {
            statusElement.textContent = '❌ ' + (data.message || 'Product not found');
            showNotification(data.message || 'Product not found with barcode: ' + barcode, 'error');
            
            setTimeout(() => {
                statusElement.textContent = currentScannerMode === 'hardware' 
                    ? 'Ready to scan...' 
                    : 'Camera active - Point at barcode';
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Barcode search error:', error);
        statusElement.textContent = '❌ Error searching product';
        showNotification('Error searching product: ' + error.message, 'error');
        
        setTimeout(() => {
            statusElement.textContent = currentScannerMode === 'hardware' 
                ? 'Ready to scan...' 
                : 'Camera active - Point at barcode';
        }, 3000);
    });
}

function addToRecentScans(barcode) {
    const recentScansDiv = document.getElementById('recentScans');
    const recentScansList = document.getElementById('recentScansList');
    
    recentScansDiv.classList.remove('hidden');
    
    const scanItem = document.createElement('div');
    scanItem.className = 'flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-700 rounded text-xs sm:text-sm';
    scanItem.innerHTML = `
        <span class="font-mono truncate flex-1 mr-2">${barcode}</span>
        <span class="text-[10px] sm:text-xs text-gray-500 whitespace-nowrap">${new Date().toLocaleTimeString()}</span>
    `;
    
    recentScansList.insertBefore(scanItem, recentScansList.firstChild);
    
    // Keep only last 5 scans
    while (recentScansList.children.length > 5) {
        recentScansList.removeChild(recentScansList.lastChild);
    }
}

// ============================================
// QUICK CREATE CUSTOMER FUNCTIONALITY
// ============================================
function openQuickCreateCustomerModal() {
    const modal = document.getElementById('quickCreateCustomerModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset form
    document.getElementById('quickCreateCustomerForm').reset();
    
    // Clear all error messages
    document.querySelectorAll('[id^="error_"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    // Focus on name input
    setTimeout(() => {
        document.getElementById('quick_customer_name').focus();
    }, 300);
}

function closeQuickCreateCustomerModal() {
    const modal = document.getElementById('quickCreateCustomerModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quickCreateCustomerForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('quickCreateCustomerBtn');
            const btnText = document.getElementById('quickCreateBtnText');
            const spinner = document.getElementById('quickCreateSpinner');
            
            // Disable button
            btn.disabled = true;
            btnText.textContent = 'Creating...';
            spinner.classList.remove('hidden');
            
            // Clear previous errors
            document.querySelectorAll('[id^="error_"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            
            // Get form data
            const formData = new FormData(this);
            
            // Send request
            fetch('/sales/customers/quick-create', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Customer created successfully!', 'success');
                    
                    // Add to customers list
                    const newCustomer = data.customer;
                    allCustomers.push(newCustomer);
                    
                    // Close modal
                    closeQuickCreateCustomerModal();
                    
                    // Select the new customer
                    setTimeout(() => {
                        selectCustomer(
                            newCustomer.id,
                            newCustomer.customer_name,
                            newCustomer.phone_number || '',
                            newCustomer.photo_profile || '',
                            newCustomer.loyalty_points || 0
                        );
                    }, 300);
                    
                    // Refresh customer list display
                    displayAllCustomers();
                } else {
                    throw new Error(data.message || 'Failed to create customer');
                }
            })
            .catch(error => {
                console.error('Create customer error:', error);
                
                // Try to parse validation errors
                if (error.response) {
                    error.response.json().then(errData => {
                        if (errData.errors) {
                            // Show validation errors
                            Object.keys(errData.errors).forEach(field => {
                                const errorEl = document.getElementById('error_' + field);
                                if (errorEl) {
                                    errorEl.textContent = errData.errors[field][0];
                                    errorEl.classList.remove('hidden');
                                }
                            });
                        }
                    });
                }
                
                showNotification(error.message || 'Failed to create customer', 'error');
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btnText.textContent = 'Create Customer';
                spinner.classList.add('hidden');
            });
        });
    }
});
</script>
</x-app-layout>