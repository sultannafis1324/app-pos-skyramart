<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Customers Details Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                        {{ __('Customer Details') }}
                    </h2>
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('customers.index') }}" class="hover:text-indigo-600">Customers</a>
                        <span>/</span>
                        <span class="text-gray-900 dark:text-white">{{ $customer->customer_name }}</span>
                    </nav>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('customers.edit', $customer) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('customers.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Customer Profile Header -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0">
                        <div class="flex-shrink-0">
                            @if($customer->photo_profile)
                                <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg" 
                                     src="{{ asset('storage/' . $customer->photo_profile) }}" 
                                     alt="{{ $customer->customer_name }}">
                            @else
                                <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 flex items-center justify-center border-4 border-white shadow-lg">
                                    <svg class="h-12 w-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="sm:ml-6 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold text-white">{{ $customer->customer_name }}</h1>
                                    <div class="mt-2 flex items-center space-x-4 text-indigo-100">
                                        @if($customer->email)
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $customer->email }}
                                            </div>
                                        @endif
                                        @if($customer->phone_number)
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $customer->phone_number }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <div class="w-2 h-2 rounded-full {{ $customer->is_active ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $customer->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Name</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $customer->bank_name ?? 'No Bank' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $customer->gender_label ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</dt>
                                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                                        @if($customer->birth_date)
                                            {{ $customer->birth_date->format('d M Y') }} 
                                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $customer->birth_date->age }} years old)</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400 italic">Not specified</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $customer->created_at->format('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Contact Information -->
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Contact Information
        </h3>
    </div>
    <div class="p-6">
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                    @if($customer->email)
                        <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-800 transition duration-200">
                            {{ $customer->email }}
                        </a>
                    @else
                        <span class="text-gray-500 dark:text-gray-400 italic">Not provided</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</dt>
                <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                    @if($customer->phone_number)
                        <a href="tel:{{ $customer->phone_number }}" class="text-indigo-600 hover:text-indigo-800 transition duration-200">
                            {{ $customer->phone_number }}
                        </a>
                    @else
                        <span class="text-gray-500 dark:text-gray-400 italic">Not provided</span>
                    @endif
                </dd>
            </div>
            
            @if($customer->address)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                            @if($customer->address->detail_address)
                                <p class="font-medium">{{ $customer->address->detail_address }}</p>
                            @endif
                            
                            <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                @if($customer->address->village)
                                    <p>{{ $customer->address->village->name }}</p>
                                @endif
                                
                                @if($customer->address->district)
                                    <p>{{ $customer->address->district->name }}</p>
                                @endif
                                
                                @if($customer->address->city)
                                    <p>{{ $customer->address->city->name }}</p>
                                @endif
                                
                                @if($customer->address->province)
                                    <p>{{ $customer->address->province->name }}</p>
                                @endif
                            </div>

                            @if($customer->address->latitude && $customer->address->longitude)
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <a href="https://www.google.com/maps?q={{ $customer->address->latitude }},{{ $customer->address->longitude }}" 
                                       target="_blank"
                                       class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        View on Google Maps
                                    </a>
                                </div>
                            @endif
                        </div>
                    </dd>
                </div>
            @else
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">
                        <span class="text-gray-500 dark:text-gray-400 italic">No address recorded</span>
                    </dd>
                </div>
            @endif
        </dl>
    </div>
</div>

                    <!-- Sales History -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Recent Transactions
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($customer->sales && $customer->sales->count() > 0)
                                <div class="overflow-hidden">
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($customer->sales->take(5) as $sale)
                                            <li class="py-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                                Transaction #{{ $sale->id }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $sale->created_at->format('d M Y, H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            Rp {{ number_format($sale->total_price ?? 0, 0, ',', '.') }}
                                                        </p>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ ucfirst($sale->status ?? 'completed') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if($customer->sales->count() > 5)
                                        <div class="mt-4 text-center">
                                            <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                View All Transactions ({{ $customer->sales->count() }})
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No transactions yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Customer Stats
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-full mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-green-800">Rp {{ number_format($customer->total_purchase ?? 0, 0, ',', '.') }}</h4>
                                <p class="text-sm text-green-600">Total Purchase</p>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                                <div class="flex items-center justify-center w-12 h-12 bg-purple-500 rounded-full mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-purple-800">{{ number_format($customer->loyalty_points ?? 0, 0, ',', '.') }}</h4>
                                <p class="text-sm text-purple-600">Loyalty Points</p>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-full mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-blue-800">{{ $customer->sales()->count() }}</h4>
                                <p class="text-sm text-blue-600">Total Transactions</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('customers.edit', $customer) }}" 
                               class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Customer
                            </a>
                            
                            @if($customer->email)
                                <a href="mailto:{{ $customer->email }}" 
                                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Send Email
                                </a>
                            @endif

                            @if($customer->phone_number)
                                <a href="tel:{{ $customer->phone_number }}" 
                                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Call Customer
                                </a>
                            @endif

                            <button onclick="syncCustomerData()" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Sync Data
                            </button>
                        </div>
                    </div>

                    <!-- Account Information 
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Account Info
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Customer ID</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">#{{ $customer->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Created</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Last Updated</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>

    <script>
        function syncCustomerData() {
            if (confirm('Are you sure you want to sync customer data? This will recalculate total purchases and loyalty points.')) {
                fetch(`{{ route('customers.syncCustomerData', $customer) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while syncing data.');
                });
            }
        }
        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1000); // Show loader for at least 500ms
        });

        // Fallback: Hide loader after 3 seconds max
        setTimeout(() => {
            const loader = document.getElementById('pageLoader');
            if (loader && !loader.classList.contains('hide')) {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }
        }, 5000);
    </script>
</x-app-layout>