<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Promotions') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your promotional campaigns and discounts</p>
            </div>
            <a href="{{ route('promotions.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Promotion
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-start animate-fade-in">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filter & Search Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
                <form method="GET" action="{{ route('promotions.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by name, code, or description..." 
                                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" 
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select name="type" 
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-200 transition-all">
                                <option value="">All Types</option>
                                <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="buy_x_get_y" {{ request('type') == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y</option>
                                <option value="bundle" {{ request('type') == 'bundle' ? 'selected' : '' }}>Bundle</option>
                                <option value="cashback" {{ request('type') == 'cashback' ? 'selected' : '' }}>Cashback</option>
                                <option value="free_shipping" {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                <option value="seasonal" {{ request('type') == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['search', 'status', 'type']))
                            <a href="{{ route('promotions.index') }}" 
                               class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Promotions Grid -->
            @if($promotions->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No promotions found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first promotional campaign</p>
                    <a href="{{ route('promotions.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Your First Promotion
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($promotions as $promotion)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <!-- Image/Badge Section -->
                            <div class="relative h-40 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                                @if($promotion->image)
                                    <img src="{{ Storage::url($promotion->image) }}" 
                                         alt="{{ $promotion->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="inline-block px-6 py-3 bg-white/20 backdrop-blur-sm rounded-2xl border border-white/30">
                                                <p class="text-2xl font-bold text-white">{{ $promotion->badge_text }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($promotion->isActive())
                                        <span class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                            <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">
                                            Inactive
                                        </span>
                                    @endif
                                </div>

                                <!-- Priority Badge -->
                                @if($promotion->priority > 0)
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Priority
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Section -->
                            <div class="p-5">
                                <!-- Title & Code -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1 line-clamp-1">
                                        {{ $promotion->name }}
                                    </h3>
                                    @if($promotion->code)
                                        <div class="inline-flex items-center px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-mono font-semibold rounded-lg">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            {{ $promotion->code }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Type Badge -->
                                <div class="mb-4">
                                    @php
                                        $typeColors = [
                                            'percentage' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
                                            'fixed' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
                                            'buy_x_get_y' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300',
                                            'bundle' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300',
                                            'cashback' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300',
                                            'free_shipping' => 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300',
                                            'seasonal' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-lg {{ $typeColors[$promotion->type] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $promotion->type)) }}
                                    </span>
                                </div>

                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Usage</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            {{ $promotion->current_usage }}
                                            @if($promotion->usage_limit)
                                                <span class="text-sm text-gray-500">/ {{ $promotion->usage_limit }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Period</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $promotion->start_date->format('M d') }}
                                            @if($promotion->end_date)
                                                - {{ $promotion->end_date->format('M d') }}
                                            @else
                                                - ∞
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($promotion->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $promotion->description }}
                                    </p>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('promotions.edit', $promotion) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-amber-50 dark:bg-amber-900/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 rounded-xl text-sm font-medium transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('promotions.destroy', $promotion) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this promotion?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center px-4 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($promotions->hasPages())
                    <div class="mt-8">
                        {{ $promotions->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>