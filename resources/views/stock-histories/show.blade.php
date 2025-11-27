<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                        {{ __('Stock History Detail') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View stock movement details</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('stock-histories.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <!-- Stock History Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Product Name:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->product->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">SKU:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->product->sku ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Current Stock:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->product->stock }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Movement Type</h3>
                                <div class="flex items-center space-x-3">
                                    @if($stockHistory->type === 'in')
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                                Stock In
                                            </span>
                                        </div>
                                    @elseif($stockHistory->type === 'out')
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                            <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                                                Stock Out
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                            <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Adjustment
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Movement</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Quantity:</span>
                                        <span class="font-bold text-lg {{ $stockHistory->type === 'in' ? 'text-green-600' : ($stockHistory->type === 'out' ? 'text-red-600' : 'text-yellow-600') }}">
                                            {{ $stockHistory->type === 'out' ? '-' : '+' }}{{ $stockHistory->quantity }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Stock Before:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->stock_before }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Stock After:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->stock_after }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">User:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Role:</span>
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ ucfirst($stockHistory->user->role) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $stockHistory->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Information -->
                    @if($stockHistory->reference_type && $stockHistory->reference_id)
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Reference Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Reference Type:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($stockHistory->reference_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Reference ID:</span>
                                <span class="font-medium text-gray-900 dark:text-white">#{{ $stockHistory->reference_id }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Description -->
                    @if($stockHistory->description)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $stockHistory->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>