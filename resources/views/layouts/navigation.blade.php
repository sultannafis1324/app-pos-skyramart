<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm sticky top-0 z-50 backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side: Logo & Navigation -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group transition-all duration-300">
                        <div class="relative">
                            <img src="{{ store_logo() }}" alt="Logo" 
                                class="h-10 w-auto transform group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-indigo-500 opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-300"></div>
                        </div>
                        <!-- Tampilkan nama di semua ukuran layar -->
                        <span class="ml-3 text-xl font-bold bg-gradient-to-r from-sky-400 to-sky-600 bg-clip-text text-transparent">
                            <h1>{{ store_name() }}</h1>
                        </span>
                    </a>
                </div>

                <!-- Menu Bagian Admin -->
                @if(Auth::user()->role === 'admin')
                        <!-- Desktop Navigation Links -->
                        <div class="hidden lg:flex lg:items-center lg:space-x-1">
                            <!-- Dashboard -->
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                class="nav-item group relative overflow-hidden">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    {{ __('Dashboard') }}
                                </span>
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                            </x-nav-link>
                            
                            <!-- Products Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button @click="open = !open" class="nav-item group relative overflow-hidden flex items-center">
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Products
                                        <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </button>
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 translate-y-1" 
                                    x-transition:enter-end="opacity-100 translate-y-0" 
                                    x-transition:leave="transition ease-in duration-150" 
                                    x-transition:leave-start="opacity-100 translate-y-0" 
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="absolute left-0 mt-3 w-56 origin-top-left rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                                    style="display: none;">
                                    <div class="py-2">
                                        <a href="{{ route('categories.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Categories') }}</span>
                                        </a>
                                        <a href="{{ route('products.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Products') }}</span>
                                        </a>
                                        <a href="{{ route('promotions.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Promotions') }}</span>
                                        </a>
                                        <a href="{{ route('stock-histories.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Stock History') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Transactions Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button @click="open = !open" class="nav-item group relative overflow-hidden flex items-center">
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Transactions
                                        <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </button>
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 translate-y-1" 
                                    x-transition:enter-end="opacity-100 translate-y-0" 
                                    x-transition:leave="transition ease-in duration-150" 
                                    x-transition:leave-start="opacity-100 translate-y-0" 
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="absolute left-0 mt-3 w-56 origin-top-left rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                                    style="display: none;">
                                    <div class="py-2">
                                        <a href="{{ route('purchases.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Purchases') }}</span>
                                        </a>
                                        <a href="{{ route('sales.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Sales') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Partners Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button @click="open = !open" class="nav-item group relative overflow-hidden flex items-center">
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Partners
                                        <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </button>
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 translate-y-1" 
                                    x-transition:enter-end="opacity-100 translate-y-0" 
                                    x-transition:leave="transition ease-in duration-150" 
                                    x-transition:leave-start="opacity-100 translate-y-0" 
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="absolute left-0 mt-3 w-56 origin-top-left rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                                    style="display: none;">
                                    <div class="py-2">
                                        <a href="{{ route('suppliers.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Suppliers') }}</span>
                                        </a>
                                        <a href="{{ route('customers.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ __('Customers') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Report Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button @click="open = !open" class="nav-item group relative overflow-hidden flex items-center">
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Report
                                        <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </button>
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 translate-y-1" 
                                    x-transition:enter-end="opacity-100 translate-y-0" 
                                    x-transition:leave="transition ease-in duration-150" 
                                    x-transition:leave-start="opacity-100 translate-y-0" 
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="absolute left-0 mt-3 w-56 origin-top-left rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                                    style="display: none;">
                                    <div class="py-2">
                                        <a href="{{ route('report.sales') }}" class="dropdown-item group flex items-center px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 dark:hover:from-violet-900/30 dark:hover:to-purple-900/30 transition-all duration-300">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/10 to-violet-600/10 dark:from-violet-400/20 dark:to-violet-500/20 flex items-center justify-center mr-3.5 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                                <span class="font-medium block">{{ __('Report Sales') }}</span>
                                        </a>
                                        <a href="{{ route('report.purchases') }}" class="dropdown-item group flex items-center px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-fuchsia-50 hover:to-pink-50 dark:hover:from-fuchsia-900/30 dark:hover:to-pink-900/30 transition-all duration-300">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-fuchsia-500/10 to-fuchsia-600/10 dark:from-fuchsia-400/20 dark:to-fuchsia-500/20 flex items-center justify-center mr-3.5 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                                <span class="font-medium block">{{ __('Report Purchases') }}</span>
                                        </a>
                                        <a href="{{ route('report.profit') }}" class="dropdown-item group flex items-center px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 dark:hover:from-emerald-900/30 dark:hover:to-teal-900/30 transition-all duration-300">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 dark:from-emerald-400/20 dark:to-emerald-500/20 flex items-center justify-center mr-3.5 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                            </div>
                                                <span class="font-medium block">{{ __('Report Profit') }}</span>
                                        </a>
                                        <a href="{{ route('report.loss') }}" class="dropdown-item group flex items-center px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 dark:hover:from-red-900/30 dark:hover:to-red-800/30 transition-all duration-300">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500/10 to-red-600/10 dark:from-red-400/20 dark:to-red-500/20 flex items-center justify-center mr-3.5 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400"fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M3 6l6 6 4-4 5 5m0 0h-4m4 0v-4"></path>
                                                </svg>
                                            </div>
                                            <span class="font-medium block">{{ __('Report Loss') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Users -->
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                                class="nav-item group relative overflow-hidden">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    {{ __('Users') }}
                                </span>
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                            </x-nav-link>

                            <x-nav-link :href="route('templates.index')" :active="request()->routeIs('receipt-templates.*')" class="nav-item group relative overflow-hidden">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ __('Receipt Templates') }}
                                </span>

                                <!-- Hover background effect -->
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 
                                            transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                            </x-nav-link>

                        </div>
                    </div>

                    <!-- Right side: User Profile -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-4">
                        <x-dropdown align="right" width="72">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 group">
                                    <!-- Profile Photo or Avatar -->
                                    @if(Auth::user()->photo_profile)
                                        <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                            alt="{{ Auth::user()->name }}" 
                                            class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/20 group-hover:ring-indigo-500/40 transition-all duration-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm ring-2 ring-indigo-500/20 group-hover:ring-indigo-500/40 transition-all duration-200">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <div class="hidden xl:block text-left min-w-0 max-w-[140px]">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 capitalize truncate">{{ Auth::user()->role }}</div>
                                    </div>
                                    
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                                    <div class="flex items-center space-x-3">
                                        @if(Auth::user()->photo_profile)
                                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                                alt="{{ Auth::user()->name }}" 
                                                class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500/20 flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg ring-2 ring-indigo-500/20 flex-shrink-0">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ Auth::user()->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</div>
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 capitalize font-medium mt-1">{{ Auth::user()->role }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dropdown Menu Items -->
                                <div class="py-2 bg-white dark:bg-gray-800">
                                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200 group">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-indigo-500 transition-colors duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="font-medium">{{ __('Profile') }}</span>
                                    </x-dropdown-link>
                                    
                                    <x-dropdown-link :href="route('store-settings.index')" 
    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 
           hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 
           dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 
           transition-all duration-200 group">

    <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-indigo-500 
                transition-colors duration-200 flex-shrink-0"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 
                 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 
                 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 
                 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 
                 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 
                 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 
                 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 
                 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>

    <span class="font-medium">{{ __('Store Settings') }}</span>
</x-dropdown-link>


                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                                class="flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                                            <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="font-medium">{{ __('Log Out') }}</span>
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Menu Bagian Chasier -->
                @if(Auth::user()->role === 'cashier')
                    <!-- Desktop Navigation Links -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-1">
                        <!-- Dashboard -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                            class="nav-item group relative overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                {{ __('Dashboard') }}
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </x-nav-link>
                        
                        <!-- Products Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click="open = !open" class="nav-item group relative overflow-hidden flex items-center">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Products
                                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                            </button>
                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200" 
                                x-transition:enter-start="opacity-0 translate-y-1" 
                                x-transition:enter-end="opacity-100 translate-y-0" 
                                x-transition:leave="transition ease-in duration-150" 
                                x-transition:leave-start="opacity-100 translate-y-0" 
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 mt-3 w-56 origin-top-left rounded-xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                                style="display: none;">
                                <div class="py-2">
                                    <a href="{{ route('products.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ __('Products') }}</span>
                                    </a>
                                    <a href="{{ route('stock-histories.index') }}" class="dropdown-item group flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ __('Stock History') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Transactions - Jika hanya 1 menu, ubah menjadi link biasa -->
                        <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')" 
                            class="nav-item group relative overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                {{ __('Sales') }}
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </x-nav-link>
                        
                        <!-- Partners - Jika hanya 1 menu, ubah menjadi link biasa -->
                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')" 
                            class="nav-item group relative overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ __('Customers') }}
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </x-nav-link>
                    </div>
                    </div>

                    <!-- Right side: User Profile -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-4">
                        <x-dropdown align="right" width="72">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 group">
                                    <!-- Profile Photo or Avatar -->
                                    @if(Auth::user()->photo_profile)
                                        <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                            alt="{{ Auth::user()->name }}" 
                                            class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/20 group-hover:ring-indigo-500/40 transition-all duration-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm ring-2 ring-indigo-500/20 group-hover:ring-indigo-500/40 transition-all duration-200">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <div class="hidden xl:block text-left min-w-0 max-w-[140px]">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 capitalize truncate">{{ Auth::user()->role }}</div>
                                    </div>
                                    
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                                    <div class="flex items-center space-x-3">
                                        @if(Auth::user()->photo_profile)
                                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                                alt="{{ Auth::user()->name }}" 
                                                class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500/20 flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg ring-2 ring-indigo-500/20 flex-shrink-0">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ Auth::user()->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</div>
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 capitalize font-medium mt-1">{{ Auth::user()->role }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dropdown Menu Items -->
                                <div class="py-2 bg-white dark:bg-gray-800">
                                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-indigo-900/20 dark:hover:to-purple-900/20 transition-all duration-200 group">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-indigo-500 transition-colors duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="font-medium">{{ __('Profile') }}</span>
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                                class="flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                                            <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="font-medium">{{ __('Log Out') }}</span>
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

<!-- Menu Bagian Admin -->
@if(Auth::user()->role === 'admin')
    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-gray-200 dark:border-gray-700">
        <!-- User Info Section -->
        <div class="px-4 pt-4 pb-3 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
            <div class="flex items-center space-x-3">
                @if(Auth::user()->photo_profile)
                    <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500/30">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg ring-2 ring-indigo-500/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-indigo-600 dark:text-indigo-400 capitalize font-medium mt-0.5">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1 bg-white dark:bg-gray-900">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center px-4 py-3 border-l-4 transition-all duration-200"
                :class="request()->routeIs('dashboard') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            
            <!-- Products Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </div>
            </div>
            <x-responsive-nav-link :href="route('promotions.index')" :active="request()->routeIs('promotions.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('promotions.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                {{ __('Promotions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('categories.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('products.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock-histories.index')" :active="request()->routeIs('stock-histories.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('stock-histories.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                {{ __('Stock History') }}
            </x-responsive-nav-link>
            
            <!-- Transactions Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Transactions
                </div>
            </div>
            <x-responsive-nav-link :href="route('purchases.index')" :active="request()->routeIs('purchases.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('purchases.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                {{ __('Purchases') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('sales.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                {{ __('Sales') }}
            </x-responsive-nav-link>
            
            <!-- Reports Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reports
                </div>
            </div>
            <x-responsive-nav-link :href="route('report.sales')" :active="request()->routeIs('report.sales')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('report.sales') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                {{ __('Report Sales') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('report.purchases')" :active="request()->routeIs('report.purchases')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('report.purchases') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-fuchsia-100 dark:bg-fuchsia-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                {{ __('Report Purchases') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('report.profit')" :active="request()->routeIs('report.profit')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('report.profit') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                {{ __('Report Profit') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('report.loss')" :active="request()->routeIs('report.loss')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('report.loss') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-red-100 dark:bg-red-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l6 6 4-4 5 5m0 0h-4m4 0v-4"></path>
                    </svg>
                </div>
                {{ __('Report Loss') }}
            </x-responsive-nav-link>
            
            <!-- Partners Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Partners
                </div>
            </div>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('suppliers.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                {{ __('Suppliers') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('customers.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-pink-100 dark:bg-pink-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                {{ __('Customers') }}
            </x-responsive-nav-link>
            
            <!-- Users -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Management
                </div>
            </div>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('users.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                {{ __('Users') }}
            </x-responsive-nav-link>
        </div>

        <!-- Bottom Actions -->
        <div class="pt-3 pb-4 border-t border-gray-200 dark:border-gray-700 space-y-1 bg-white dark:bg-gray-900">
            <x-responsive-nav-link :href="route('profile.edit')" 
                class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">{{ __('Profile') }}</span>
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" 
                        class="flex items-center px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="font-medium">{{ __('Log Out') }}</span>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
@endif

<!-- Menu Bagian Cashier -->
@if(Auth::user()->role === 'cashier')
    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-gray-200 dark:border-gray-700">
        <!-- User Info Section -->
        <div class="px-4 pt-4 pb-3 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
            <div class="flex items-center space-x-3">
                @if(Auth::user()->photo_profile)
                    <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500/30">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg ring-2 ring-indigo-500/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-indigo-600 dark:text-indigo-400 capitalize font-medium mt-0.5">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1 bg-white dark:bg-gray-900">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center px-4 py-3 border-l-4 transition-all duration-200"
                :class="request()->routeIs('dashboard') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            
            <!-- Products Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </div>
            </div>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('products.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock-histories.index')" :active="request()->routeIs('stock-histories.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('stock-histories.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                {{ __('Stock History') }}
            </x-responsive-nav-link>
            
            <!-- Transactions Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Transactions
                </div>
            </div>
            <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('sales.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                {{ __('Sales') }}
            </x-responsive-nav-link>
            
            <!-- Partners Section -->
            <div class="px-4 pt-4 pb-2">
                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Partners
                </div>
            </div>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" 
                class="flex items-center px-4 py-3 pl-10 border-l-4 transition-all duration-200"
                :class="request()->routeIs('customers.*') ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'border-transparent hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                <div class="w-6 h-6 rounded-lg bg-pink-100 dark:bg-pink-900/50 flex items-center justify-center mr-3">
                    <svg class="w-3.5 h-3.5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                {{ __('Customers') }}
            </x-responsive-nav-link>
        </div>

        <!-- Bottom Actions -->
        <div class="pt-3 pb-4 border-t border-gray-200 dark:border-gray-700 space-y-1 bg-white dark:bg-gray-900">
            <x-responsive-nav-link :href="route('profile.edit')" 
                class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">{{ __('Profile') }}</span>
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" 
                        class="flex items-center px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="font-medium">{{ __('Log Out') }}</span>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
@endif
</nav>

<style>
/* Navigation Item Styles */
.nav-item {
    @apply px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out;
    @apply text-gray-600 dark:text-gray-400;
    @apply hover:text-gray-900 dark:hover:text-white;
    @apply hover:bg-gray-50 dark:hover:bg-gray-800;
}

.nav-item:focus {
    @apply outline-none ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900;
}

/* Active State */
x-nav-link[active="true"] .nav-item,
.nav-item.active {
    @apply bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30;
    @apply text-indigo-700 dark:text-indigo-300;
    @apply font-semibold;
}

/* Dropdown Items */
.dropdown-item {
    @apply transition-all duration-200 ease-in-out;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom Scrollbar for Mobile Menu */
@media (max-width: 1024px) {
    .lg\:hidden > div:last-child {
        max-height: calc(100vh - 64px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .lg\:hidden > div:last-child::-webkit-scrollbar {
        width: 4px;
    }
    
    .lg\:hidden > div:last-child::-webkit-scrollbar-track {
        @apply bg-gray-100 dark:bg-gray-800;
    }
    
    .lg\:hidden > div:last-child::-webkit-scrollbar-thumb {
        @apply bg-gray-300 dark:bg-gray-600 rounded-full;
    }
}

/* Animations */
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

.dropdown-enter {
    animation: slideDown 0.2s ease-out;
}
</style>