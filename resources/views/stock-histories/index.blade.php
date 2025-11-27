<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg transform transition-transform hover:scale-105">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl md:text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Stock History') }}
                    </h2>
                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mt-1">Monitor and track all inventory movements</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('stock-histories.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Stock In Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 border border-green-100 dark:border-green-800/30 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs md:text-sm font-medium text-green-600 dark:text-green-400 uppercase tracking-wider mb-2">Stock In</p>
                            <h3 class="text-2xl md:text-3xl font-bold text-green-700 dark:text-green-300 mb-1">{{ $stockHistories->where('type', 'in')->sum('quantity') }}</h3>
                            <p class="text-xs text-green-600/80 dark:text-green-500/80">Total items received</p>
                        </div>
                        <div class="p-3 bg-green-500/10 rounded-2xl flex-shrink-0">
                            <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stock Out Card -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 border border-red-100 dark:border-red-800/30 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs md:text-sm font-medium text-red-600 dark:text-red-400 uppercase tracking-wider mb-2">Stock Out</p>
                            <h3 class="text-2xl md:text-3xl font-bold text-red-700 dark:text-red-300 mb-1">{{ $stockHistories->where('type', 'out')->sum('quantity') }}</h3>
                            <p class="text-xs text-red-600/80 dark:text-red-500/80">Total items sold</p>
                        </div>
                        <div class="p-3 bg-red-500/10 rounded-2xl flex-shrink-0">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Adjustments Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 border border-blue-100 dark:border-blue-800/30 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs md:text-sm font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Adjustments</p>
                            <h3 class="text-2xl md:text-3xl font-bold text-blue-700 dark:text-blue-300 mb-1">{{ $stockHistories->where('type', 'adjustment')->count() }}</h3>
                            <p class="text-xs text-blue-600/80 dark:text-blue-500/80">Manual corrections</p>
                        </div>
                        <div class="p-3 bg-blue-500/10 rounded-2xl flex-shrink-0">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md mb-6 p-5 border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('stock-histories.index') }}" class="space-y-4" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date-from" value="{{ request('date_from') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition-all duration-200">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                            <input type="date" name="date_to" id="date-to" value="{{ request('date_to') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition-all duration-200">
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select name="type" id="type-filter"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition-all duration-200">
                                <option value="">All Types</option>
                                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                        </div>

                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" id="search" placeholder="Product, user..." 
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition-all duration-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Items Per Page -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Per Page</label>
                            <select id="per-page"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white text-sm transition-all duration-200">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filter
                        </button>
                        <a href="{{ route('stock-histories.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-800 dark:text-white rounded-xl text-sm font-medium transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                        <div class="ml-auto flex flex-wrap gap-2">
                            <a href="{{ route('stock-histories.export-pdf', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'type' => request('type')]) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export PDF
                            </a>
                            <a href="{{ route('stock-histories.export-excel', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'type' => request('type')]) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Container -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                                <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Qty</th>
                                <th class="hidden md:table-cell px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Before</th>
                                <th class="hidden md:table-cell px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">After</th>
                                <th class="hidden lg:table-cell px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Reference</th>
                                <th class="hidden xl:table-cell px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="stock-history-table">
                            @forelse($stockHistories as $history)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 text-sm data-row" 
                                data-type="{{ $history->type }}" 
                                data-product="{{ strtolower($history->product->product_name ?? '') }}" 
                                data-user="{{ strtolower($history->user->name ?? '') }}" 
                                data-description="{{ strtolower($history->description ?? '') }}"
                                data-date="{{ $history->created_at->format('Y-m-d') }}">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-2 h-2 rounded-full mr-3 {{ $history->type == 'in' ? 'bg-green-500' : ($history->type == 'out' ? 'bg-red-500' : 'bg-blue-500') }}"></div>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $history->created_at->format('M d, Y') }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $history->created_at->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $history->product->product_name ?? 'N/A' }}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $history->product->product_code ?? 'N/A' }}</div>
                                        
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    @if($history->type == 'in')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Stock In</span>
                                            <span class="sm:hidden">In</span>
                                        </span>
                                    @elseif($history->type == 'out')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Stock Out</span>
                                            <span class="sm:hidden">Out</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Adjustment</span>
                                            <span class="sm:hidden">Adj</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold {{ $history->type == 'in' ? 'text-green-600 dark:text-green-400' : ($history->type == 'out' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }}">
                                        @if($history->type == 'out')
                                            -{{ $history->quantity }}
                                        @elseif($history->type == 'in')
                                            +{{ $history->quantity }}
                                        @else
                                            {{ ($history->stock_after - $history->stock_before >= 0 ? '+' : '') }}{{ $history->stock_after - $history->stock_before }}
                                        @endif
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">
                                        {{ $history->stock_before }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">
                                        {{ $history->stock_after }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $history->reference_type ?? 'Manual' }}</div>
                                        @if($history->reference_id)
                                            <div class="text-gray-500 dark:text-gray-400 text-xs">#{{ $history->reference_id }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="hidden xl:table-cell px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-semibold text-xs shadow-sm">
                                            {{ strtoupper(substr($history->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $history->user->name ?? 'System' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 break-words whitespace-normal max-w-xs">
                                        {{ $history->description ?: '-' }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="initial-empty-state">
                                <td colspan="9" class="px-4 md:px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No stock history found</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Stock movements will appear here when products are received or dispatched.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                            <!-- Empty state for filtered results -->
                            <tr id="empty-state" style="display: none;">
                                <td colspan="9" class="px-4 md:px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No stock history found</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">No results match your current filters. Try adjusting your search criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 md:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing <span id="showing-start" class="font-semibold text-gray-900 dark:text-white">0</span> to 
                            <span id="showing-end" class="font-semibold text-gray-900 dark:text-white">0</span> of 
                            <span id="total-entries" class="font-semibold text-gray-900 dark:text-white">0</span> entries
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button id="prev-page" class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            
                            <div id="page-numbers" class="flex items-center space-x-1 text-sm">
                                <!-- Page numbers will be inserted here -->
                            </div>
                            
                            <button id="next-page" class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let perPage = 25;
        let allRows = [];
        let filteredRows = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            allRows = Array.from(document.querySelectorAll('.data-row'));
            filteredRows = [...allRows];
            
            // Hide initial empty state if there are rows
            const initialEmptyState = document.getElementById('initial-empty-state');
            if (initialEmptyState && allRows.length > 0) {
                initialEmptyState.style.display = 'none';
            }
            
            updatePagination();
        });

        // Search functionality
        document.getElementById('search').addEventListener('input', function() {
            currentPage = 1;
            applyFilters();
        });

        // Type filter functionality
        document.getElementById('type-filter').addEventListener('change', function() {
            currentPage = 1;
            applyClientSideFilters();
        });

        // Date filter functionality
        document.getElementById('date-from').addEventListener('change', function() {
            currentPage = 1;
            applyClientSideFilters();
        });

        document.getElementById('date-to').addEventListener('change', function() {
            currentPage = 1;
            applyClientSideFilters();
        });

        // Per page selector
        document.getElementById('per-page').addEventListener('change', function() {
            perPage = parseInt(this.value);
            currentPage = 1;
            updatePagination();
        });

        // Previous page button
        document.getElementById('prev-page').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });

        // Next page button
        document.getElementById('next-page').addEventListener('click', function() {
            const totalPages = Math.ceil(filteredRows.length / perPage);
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });

        function applyClientSideFilters() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const typeFilter = document.getElementById('type-filter').value;
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;

            filteredRows = allRows.filter(row => {
                const productName = row.getAttribute('data-product');
                const type = row.getAttribute('data-type');
                const user = row.getAttribute('data-user');
                const description = row.getAttribute('data-description');
                const date = row.getAttribute('data-date');

                // Search filter
                const matchesSearch = !searchTerm || 
                                    productName.includes(searchTerm) || 
                                    user.includes(searchTerm) || 
                                    description.includes(searchTerm);

                // Type filter
                const matchesType = !typeFilter || type === typeFilter;

                // Date filter
                let matchesDate = true;
                if (dateFrom && date < dateFrom) {
                    matchesDate = false;
                }
                if (dateTo && date > dateTo) {
                    matchesDate = false;
                }

                return matchesSearch && matchesType && matchesDate;
            });

            currentPage = 1;
            updatePagination();
        }

        function applyFilters() {
            applyClientSideFilters();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredRows.length / perPage);
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, filteredRows.length);

            // Hide all data rows
            allRows.forEach(row => row.style.display = 'none');

            // Show current page rows
            for (let i = start; i < end; i++) {
                filteredRows[i].style.display = '';
            }

            // Update showing info
            document.getElementById('showing-start').textContent = filteredRows.length > 0 ? start + 1 : 0;
            document.getElementById('showing-end').textContent = end;
            document.getElementById('total-entries').textContent = filteredRows.length;

            // Update page numbers
            renderPageNumbers(totalPages);

            // Update button states
            document.getElementById('prev-page').disabled = currentPage === 1;
            document.getElementById('next-page').disabled = currentPage === totalPages || totalPages === 0;

            // Show/hide empty states
            const emptyState = document.getElementById('empty-state');
            const initialEmptyState = document.getElementById('initial-empty-state');
            
            if (filteredRows.length === 0) {
                // Show filtered empty state
                if (emptyState) {
                    emptyState.style.display = '';
                }
                // Hide initial empty state
                if (initialEmptyState) {
                    initialEmptyState.style.display = 'none';
                }
            } else {
                // Hide both empty states when there are results
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
                if (initialEmptyState) {
                    initialEmptyState.style.display = 'none';
                }
            }
        }

        function renderPageNumbers(totalPages) {
            const pageNumbersDiv = document.getElementById('page-numbers');
            pageNumbersDiv.innerHTML = '';

            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) {
                    pageNumbersDiv.appendChild(createPageButton(i));
                }
            } else {
                pageNumbersDiv.appendChild(createPageButton(1));

                if (currentPage > 3) {
                    pageNumbersDiv.appendChild(createEllipsis());
                }

                const start = Math.max(2, currentPage - 1);
                const end = Math.min(totalPages - 1, currentPage + 1);

                for (let i = start; i <= end; i++) {
                    pageNumbersDiv.appendChild(createPageButton(i));
                }

                if (currentPage < totalPages - 2) {
                    pageNumbersDiv.appendChild(createEllipsis());
                }

                pageNumbersDiv.appendChild(createPageButton(totalPages));
            }
        }

        function createPageButton(pageNum) {
            const button = document.createElement('button');
            button.textContent = pageNum;
            button.type = 'button';
            button.className = currentPage === pageNum
                ? 'px-4 py-2 rounded-xl text-sm font-semibold bg-green-500 text-white shadow-md transition-all duration-200'
                : 'px-4 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 transition-all duration-200';
            
            button.addEventListener('click', function() {
                currentPage = pageNum;
                updatePagination();
            });

            return button;
        }

        function createEllipsis() {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'px-2 text-gray-500 dark:text-gray-400 text-sm';
            return span;
        }
    </script>
</x-app-layout>