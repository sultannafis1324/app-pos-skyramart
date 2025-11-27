<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Edit Promotion') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update promotional campaign details</p>
            </div>
            <a href="{{ route('promotions.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Promotions
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data" id="promotionForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Basic Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Update the basic details of your promotion</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Promotion Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $promotion->name) }}"
                                   placeholder="e.g., Summer Sale 2024"
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Promotion Code
                                <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   value="{{ old('code', $promotion->code) }}"
                                   placeholder="e.g., SUMMER50"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all uppercase">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Promotion Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" 
                                    id="promotionType"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('type', $promotion->type) == 'percentage' ? 'selected' : '' }}>Percentage Discount</option>
                                <option value="fixed" {{ old('type', $promotion->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="buy_x_get_y" {{ old('type', $promotion->type) == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y Free</option>
                                <option value="bundle" {{ old('type', $promotion->type) == 'bundle' ? 'selected' : '' }}>Bundle Deal</option>
                                <option value="cashback" {{ old('type', $promotion->type) == 'cashback' ? 'selected' : '' }}>Cashback</option>
                                <option value="free_shipping" {{ old('type', $promotion->type) == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                <option value="seasonal" {{ old('type', $promotion->type) == 'seasonal' ? 'selected' : '' }}>Seasonal Event</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" 
                                      rows="3" 
                                      placeholder="Describe your promotion campaign..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">{{ old('description', $promotion->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Discount Configuration -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Discount Configuration</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Configure discount values based on promotion type</p>
                        </div>
                    </div>

                    <!-- Percentage & Fixed -->
                    <div id="percentageFixed" class="discount-config hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span id="discountLabel">Discount Value</span>
                                </label>
                                <input type="number" 
                                       name="discount_value" 
                                       value="{{ old('discount_value', $promotion->discount_value) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Discount (Rp)</label>
                                <input type="number" 
                                       name="max_discount" 
                                       value="{{ old('max_discount', $promotion->max_discount) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Purchase (Rp)</label>
                                <input type="number" 
                                       name="min_purchase" 
                                       value="{{ old('min_purchase', $promotion->min_purchase) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Buy X Get Y -->
                    <div id="buyXGetY" class="discount-config hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buy Quantity</label>
                                <input type="number" 
                                       name="buy_quantity" 
                                       value="{{ old('buy_quantity', $promotion->buy_quantity) }}"
                                       min="1"
                                       placeholder="e.g., 2"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Get Quantity (Free)</label>
                                <input type="number" 
                                       name="get_quantity" 
                                       value="{{ old('get_quantity', $promotion->get_quantity) }}"
                                       min="1"
                                       placeholder="e.g., 1"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Bundle -->
                    <div id="bundle" class="discount-config hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bundle Quantity</label>
                                <input type="number" 
                                       name="bundle_quantity" 
                                       value="{{ old('bundle_quantity', $promotion->bundle_quantity) }}"
                                       min="1"
                                       placeholder="e.g., 3"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bundle Price (Rp)</label>
                                <input type="number" 
                                       name="bundle_price" 
                                       value="{{ old('bundle_price', $promotion->bundle_price) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Cashback -->
                    <div id="cashback" class="discount-config hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cashback Percentage (%)</label>
                                <input type="number" 
                                       name="cashback_percentage" 
                                       value="{{ old('cashback_percentage', $promotion->cashback_percentage) }}"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       placeholder="e.g., 10"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Cashback (Rp)</label>
                                <input type="number" 
                                       name="max_cashback" 
                                       value="{{ old('max_cashback', $promotion->max_cashback) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target & Schedule Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Target & Schedule</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Set target products and promotion period</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Type -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Target Type <span class="text-red-500">*</span>
                            </label>
                            <select name="target_type" 
                                    id="targetType"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                                <option value="all" {{ old('target_type', $promotion->target_type) == 'all' ? 'selected' : '' }}>All Products</option>
                                <option value="specific_products" {{ old('target_type', $promotion->target_type) == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                                <option value="category" {{ old('target_type', $promotion->target_type) == 'category' ? 'selected' : '' }}>Category</option>
                            </select>
                        </div>

                        <!-- Target Products -->
                        <div id="specificProducts" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Products</label>
                            <select name="target_ids[]" 
                                    id="productSelect"
                                    multiple
                                    class="w-full">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ in_array($product->id, old('target_ids', $promotion->target_ids ?? [])) ? 'selected' : '' }}
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}">
                                        {{ $product->product_name }} - Rp {{ number_format($product->selling_price, 0, ',', '.') }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Search and select multiple products</p>
                        </div>

                        <!-- Target Categories -->
                        <div id="targetCategories" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Categories</label>
                            <select name="target_ids[]" 
                                    id="categorySelect"
                                    multiple
                                    class="w-full">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ in_array($category->id, old('target_ids', $promotion->target_ids ?? [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Search and select multiple categories</p>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="start_date" 
                                   value="{{ old('start_date', $promotion->start_date ? date('Y-m-d\TH:i', strtotime($promotion->start_date)) : '') }}"
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Date
                                <span class="text-gray-400 text-xs">(Optional - leave empty for no end date)</span>
                            </label>
                            <input type="datetime-local" 
                                   name="end_date" 
                                   value="{{ old('end_date', $promotion->end_date ? date('Y-m-d\TH:i', strtotime($promotion->end_date)) : '') }}"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Usage Limits & Display -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Usage Limits & Display</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Configure usage restrictions and display settings</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Usage Limit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Usage Limit
                                <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input type="number" 
                                   name="usage_limit" 
                                   value="{{ old('usage_limit', $promotion->usage_limit) }}"
                                   min="1"
                                   placeholder="Unlimited"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                        </div>

                        <!-- Usage Per Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Usage Per Customer
                                <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input type="number" 
                                   name="usage_per_customer" 
                                   value="{{ old('usage_per_customer', $promotion->usage_per_customer) }}"
                                   min="1"
                                   placeholder="Unlimited"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                            <input type="number" 
                                   name="priority" 
                                   value="{{ old('priority', $promotion->priority ?? 0) }}"
                                   min="0"
                                   placeholder="0"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Higher priority promotions are applied first</p>
                        </div>

                        <!-- Badge Text -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Badge Text</label>
                            <input type="text" 
                                   name="badge_text" 
                                   value="{{ old('badge_text', $promotion->badge_text) }}"
                                   maxlength="50"
                                   placeholder="e.g., SAVE 50%"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for auto-generated badge</p>
                        </div>

                        <!-- Badge Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Badge Color</label>
                            <div class="flex gap-2">
                                <input type="color" 
                                       name="badge_color" 
                                       value="{{ old('badge_color', $promotion->badge_color ?? '#FF0000') }}"
                                       class="h-11 w-16 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer">
                                <input type="text" 
                                       name="badge_color_text" 
                                       value="{{ old('badge_color', $promotion->badge_color ?? '#FF0000') }}"
                                       placeholder="#FF0000"
                                       class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                            </div>
                        </div>

                        <!-- Current Image -->
                        @if($promotion->image)
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Image</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . $promotion->image) }}" 
                                     alt="Promotion Image" 
                                     class="w-32 h-32 object-cover rounded-xl border-2 border-gray-200 dark:border-gray-600">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Upload a new image to replace the current one</p>
                                    <label class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Choose New Image
                                        <input type="file" 
                                               name="image" 
                                               accept="image/jpeg,image/png,image/jpg"
                                               class="hidden"
                                               onchange="previewImage(event)">
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 2MB (JPEG, PNG, JPG)</p>
                                </div>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Image Preview:</p>
                                <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border-2 border-blue-500">
                            </div>
                        </div>
                        @else
                        <!-- Image Upload -->
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Promotion Image</label>
                            <input type="file" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300"
                                   onchange="previewImage(event)">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 2MB (JPEG, PNG, JPG)</p>
                            <div id="imagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image Preview:</p>
                                <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border-2 border-blue-500">
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Toggles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <!-- Is Active -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Active Status</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enable this promotion immediately</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Is Stackable -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stackable</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Can be combined with other promotions</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_stackable" 
                                       value="1" 
                                       {{ old('is_stackable', $promotion->is_stackable) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Promotion
                    </button>
                    <a href="{{ route('promotions.index') }}" 
                       class="flex-1 sm:flex-initial inline-flex items-center justify-center px-6 py-3.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Modern Select2 Custom Styling -->
<style>
/* ===== SELECT2 CONTAINER ===== */
.select2-container--default .select2-selection--multiple {
    background: linear-gradient(to bottom, rgb(249 250 251), rgb(243 244 246)) !important;
    border: 2px solid rgb(229 231 235) !important;
    border-radius: 0.875rem !important;
    min-height: 52px !important;
    padding: 6px 12px !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
}

/* Dark mode container */
@media (prefers-color-scheme: dark) {
    .select2-container--default .select2-selection--multiple {
        background: linear-gradient(to bottom, rgb(31 41 55), rgb(17 24 39)) !important;
        border: 2px solid rgb(55 65 81) !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3) !important;
    }
}

/* Focus state */
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: rgb(59 130 246) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    background: white !important;
}

@media (prefers-color-scheme: dark) {
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        background: rgb(31 41 55) !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 1px 3px 0 rgba(0, 0, 0, 0.3) !important;
    }
}

/* ===== SELECTED ITEMS (TAGS) ===== */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, rgb(59 130 246), rgb(37 99 235)) !important;
    border: none !important;
    color: white !important;
    border-radius: 0.625rem !important;
    padding: 6px 12px 6px 10px !important;
    margin: 3px 4px 3px 0 !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    display: inline-flex !important;
    align-items: center !important;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important;
    transition: all 0.2s ease !important;
    outline: none !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
    background: linear-gradient(135deg, rgb(37 99 235), rgb(29 78 216)) !important;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4) !important;
    transform: translateY(-1px) !important;
    outline: none !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice:focus {
    outline: none !important;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important;
}

/* Remove button */
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255, 255, 255, 0.8) !important;
    margin-right: 6px !important;
    font-weight: bold !important;
    transition: color 0.2s ease !important;
    order: -1 !important;
    border: none !important;
    outline: none !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: rgb(254 202 202) !important;
    background: transparent !important;
}

/* ===== HILANGKAN SEMUA BORDER YANG TIDAK DIINGINKAN ===== */
.select2-selection__rendered {
    border: none !important;
    outline: none !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    border: none !important;
    outline: none !important;
}

/* Hilangkan border saat focus */
.select2-container--default.select2-container--focus .select2-selection--multiple .select2-selection__rendered {
    border: none !important;
    outline: none !important;
}

/* ===== DROPDOWN ===== */
.select2-dropdown {
    background: white !important;
    border: 2px solid rgb(229 231 235) !important;
    border-radius: 0.875rem !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    margin-top: 4px !important;
    overflow: hidden !important;
}

@media (prefers-color-scheme: dark) {
    .select2-dropdown {
        background: rgb(31 41 55) !important;
        border: 2px solid rgb(55 65 81) !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5) !important;
    }
}

/* ===== SEARCH BOX ===== */
.select2-search--dropdown {
    padding: 12px !important;
    background: rgb(249 250 251) !important;
    border-bottom: 2px solid rgb(229 231 235) !important;
}

@media (prefers-color-scheme: dark) {
    .select2-search--dropdown {
        background: rgb(17 24 39) !important;
        border-bottom: 2px solid rgb(55 65 81) !important;
    }
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    background: white !important;
    border: 2px solid rgb(229 231 235) !important;
    border-radius: 0.625rem !important;
    color: rgb(17 24 39) !important;
    padding: 10px 14px !important;
    font-size: 0.875rem !important;
    transition: all 0.2s ease !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: rgb(59 130 246) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    outline: none !important;
}

@media (prefers-color-scheme: dark) {
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background: rgb(31 41 55) !important;
        border: 2px solid rgb(55 65 81) !important;
        color: rgb(229 231 235) !important;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: rgb(59 130 246) !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    }
}

/* Placeholder */
.select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
    color: rgb(156 163 175) !important;
}

/* ===== RESULTS/OPTIONS ===== */
.select2-results {
    padding: 4px !important;
}

.select2-container--default .select2-results__option {
    color: rgb(31 41 55) !important;
    padding: 12px 14px !important;
    border-radius: 0.5rem !important;
    margin: 2px 0 !important;
    font-size: 0.875rem !important;
    transition: all 0.15s ease !important;
    cursor: pointer !important;
}

@media (prefers-color-scheme: dark) {
    .select2-container--default .select2-results__option {
        color: rgb(229 231 235) !important;
    }
}

/* Hover state */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: linear-gradient(to right, rgb(59 130 246), rgb(37 99 235)) !important;
    color: white !important;
}

/* Selected state */
.select2-container--default .select2-results__option[aria-selected=true] {
    background: rgb(219 234 254) !important;
    color: rgb(29 78 216) !important;
    font-weight: 500 !important;
}

@media (prefers-color-scheme: dark) {
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: rgb(30 58 138) !important;
        color: rgb(191 219 254) !important;
    }
}

/* Selected + hover */
.select2-container--default .select2-results__option[aria-selected=true].select2-results__option--highlighted {
    background: linear-gradient(to right, rgb(37 99 235), rgb(29 78 216)) !important;
    color: white !important;
}

/* ===== PLACEHOLDER TEXT ===== */
.select2-selection__placeholder {
    color: rgb(156 163 175) !important;
    font-size: 0.875rem !important;
}

/* ===== CUSTOM SCROLLBAR ===== */
.select2-results__options {
    max-height: 300px !important;
    scrollbar-width: thin !important;
    scrollbar-color: rgb(203 213 225) transparent !important;
}

.select2-results__options::-webkit-scrollbar {
    width: 8px !important;
}

.select2-results__options::-webkit-scrollbar-track {
    background: transparent !important;
}

.select2-results__options::-webkit-scrollbar-thumb {
    background-color: rgb(203 213 225) !important;
    border-radius: 4px !important;
}

.select2-results__options::-webkit-scrollbar-thumb:hover {
    background-color: rgb(148 163 184) !important;
}

@media (prefers-color-scheme: dark) {
    .select2-results__options {
        scrollbar-color: rgb(75 85 99) transparent !important;
    }
    
    .select2-results__options::-webkit-scrollbar-thumb {
        background-color: rgb(75 85 99) !important;
    }
    
    .select2-results__options::-webkit-scrollbar-thumb:hover {
        background-color: rgb(107 114 128) !important;
    }
}

/* ===== ANIMATION ===== */
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

.select2-dropdown {
    animation: slideDown 0.2s ease-out !important;
}
</style>

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promotionType = document.getElementById('promotionType');
    const targetType = document.getElementById('targetType');
    const discountLabel = document.getElementById('discountLabel');
    
    // Discount configuration sections
    const percentageFixed = document.getElementById('percentageFixed');
    const buyXGetY = document.getElementById('buyXGetY');
    const bundle = document.getElementById('bundle');
    const cashback = document.getElementById('cashback');
    
    // Target sections
    const specificProducts = document.getElementById('specificProducts');
    const targetCategories = document.getElementById('targetCategories');

    // Initialize Select2
    function initSelect2() {
        // Product Select2
        $('#productSelect').select2({
            placeholder: 'Search and select products...',
            allowClear: true,
            width: '100%',
            templateResult: formatProduct,
            templateSelection: formatProductSelection
        });

        // Category Select2
        $('#categorySelect').select2({
            placeholder: 'Search and select categories...',
            allowClear: true,
            width: '100%'
        });
    }

    // Format product display in dropdown
    function formatProduct(product) {
        if (!product.id) {
            return product.text;
        }
        
        var $product = $(
            '<div class="py-1">' +
                '<div class="font-medium">' + product.text.split(' - ')[0] + '</div>' +
                '<div class="text-xs text-gray-500">' + product.text.split(' - ').slice(1).join(' - ') + '</div>' +
            '</div>'
        );
        
        return $product;
    }

    // Format selected product
    function formatProductSelection(product) {
        return product.text.split(' - ')[0];
    }

    // Initialize Select2
    initSelect2();

    // Handle promotion type change
    promotionType.addEventListener('change', function() {
        // Hide all discount configs
        document.querySelectorAll('.discount-config').forEach(el => {
            el.classList.add('hidden');
        });

        const type = this.value;
        
        switch(type) {
            case 'percentage':
                percentageFixed.classList.remove('hidden');
                discountLabel.textContent = 'Discount Percentage (%)';
                break;
            case 'fixed':
                percentageFixed.classList.remove('hidden');
                discountLabel.textContent = 'Discount Amount (Rp)';
                break;
            case 'buy_x_get_y':
                buyXGetY.classList.remove('hidden');
                break;
            case 'bundle':
                bundle.classList.remove('hidden');
                break;
            case 'cashback':
                cashback.classList.remove('hidden');
                break;
        }
    });

    // Handle target type change
    targetType.addEventListener('change', function() {
        specificProducts.classList.add('hidden');
        targetCategories.classList.add('hidden');

        if (this.value === 'specific_products') {
            specificProducts.classList.remove('hidden');
        } else if (this.value === 'category') {
            targetCategories.classList.remove('hidden');
        } else {
            // Clear selections for 'all' type
            $('#productSelect').val(null).trigger('change');
            $('#categorySelect').val(null).trigger('change');
        }
    });

    // Sync color picker with text input
    const colorPicker = document.querySelector('input[type="color"]');
    const colorText = document.querySelector('input[name="badge_color_text"]');
    
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value.toUpperCase();
        });
        
        colorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }

    // Auto-uppercase promotion code
    const codeInput = document.querySelector('input[name="code"]');
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    }

    // Image preview function
    window.previewImage = function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Trigger initial state
    if (promotionType.value) {
        promotionType.dispatchEvent(new Event('change'));
    }
    if (targetType.value) {
        targetType.dispatchEvent(new Event('change'));
    }

    // Form validation
    document.getElementById('promotionForm').addEventListener('submit', function(e) {
        const type = promotionType.value;
        const targetTypeValue = targetType.value;
        let isValid = true;
        let errorMessage = '';

        // Validate target selection
        if (targetTypeValue === 'specific_products') {
            const selectedProducts = $('#productSelect').val();
            if (!selectedProducts || selectedProducts.length === 0) {
                isValid = false;
                errorMessage = 'Please select at least one product';
            }
        } else if (targetTypeValue === 'category') {
            const selectedCategories = $('#categorySelect').val();
            if (!selectedCategories || selectedCategories.length === 0) {
                isValid = false;
                errorMessage = 'Please select at least one category';
            }
        }

        // Validate based on promotion type
        if (isValid) {
            if (type === 'percentage' || type === 'fixed') {
                const discountValue = document.querySelector('input[name="discount_value"]').value;
                if (!discountValue || parseFloat(discountValue) <= 0) {
                    isValid = false;
                    errorMessage = 'Please enter a valid discount value';
                }
            } else if (type === 'buy_x_get_y') {
                const buyQty = document.querySelector('input[name="buy_quantity"]').value;
                const getQty = document.querySelector('input[name="get_quantity"]').value;
                if (!buyQty || !getQty || parseInt(buyQty) <= 0 || parseInt(getQty) <= 0) {
                    isValid = false;
                    errorMessage = 'Please enter valid buy and get quantities';
                }
            } else if (type === 'bundle') {
                const bundleQty = document.querySelector('input[name="bundle_quantity"]').value;
                const bundlePrice = document.querySelector('input[name="bundle_price"]').value;
                if (!bundleQty || !bundlePrice || parseInt(bundleQty) <= 0 || parseFloat(bundlePrice) <= 0) {
                    isValid = false;
                    errorMessage = 'Please enter valid bundle quantity and price';
                }
            } else if (type === 'cashback') {
                const cashbackPct = document.querySelector('input[name="cashback_percentage"]').value;
                if (!cashbackPct || parseFloat(cashbackPct) <= 0 || parseFloat(cashbackPct) > 100) {
                    isValid = false;
                    errorMessage = 'Please enter a valid cashback percentage (0-100)';
                }
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
});
</script>
</x-app-layout>