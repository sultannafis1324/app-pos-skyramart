<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Supplier Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('suppliers.edit', $supplier) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('suppliers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .supplier-detail-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .profile-header-card {
            position: relative;
            overflow: hidden;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .dark .profile-header-card {
            background: #1f2937;
        }
        
        .profile-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 120px;
        }
        
        .profile-content {
            padding: 80px 32px 24px 32px;
        }
        
        .profile-avatar {
            position: absolute;
            top: 60px;
            left: 32px;
            z-index: 10;
        }
        
        .profile-avatar img,
        .profile-avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .dark .profile-avatar img,
        .dark .profile-avatar-placeholder {
            border-color: #1f2937;
        }
        
        .profile-avatar-placeholder {
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dark .profile-avatar-placeholder {
            background: #4b5563;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .dark .stat-card {
            background: #1f2937;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        
        .dark .section-card {
            background: #1f2937;
        }
        
        .section-header {
            background: #f9fafb;
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .dark .section-header {
            background: #374151;
            border-bottom-color: #4b5563;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: #f9fafb;
        }
        
        .dark .data-table thead {
            background: #374151;
        }
        
        .data-table th {
            padding: 12px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            white-space: nowrap;
        }
        
        .dark .data-table th {
            color: #d1d5db;
        }
        
        .data-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .dark .data-table td {
            border-bottom-color: #374151;
        }
        
        .data-table tbody tr:hover {
            background: #f9fafb;
        }
        
        .dark .data-table tbody tr:hover {
            background: #374151;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .dark .badge-success {
            background: #064e3b;
            color: #6ee7b7;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .dark .badge-warning {
            background: #78350f;
            color: #fcd34d;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .dark .badge-danger {
            background: #7f1d1d;
            color: #fca5a5;
        }
        
        .badge-gray {
            background: #f3f4f6;
            color: #1f2937;
        }
        
        .dark .badge-gray {
            background: #374151;
            color: #e5e7eb;
        }
        
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }
        
        .empty-state-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 16px;
            color: #9ca3af;
        }
        
        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 12px 0;
        }
        
        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
        }
        
        .contact-icon {
            width: 20px;
            height: 20px;
            color: #9ca3af;
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .product-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .dark .product-placeholder {
            background: #4b5563;
        }
        
        @media (max-width: 768px) {
            .profile-content {
                padding: 70px 16px 24px 16px;
            }
            
            .profile-avatar {
                left: 16px;
                top: 50px;
            }
            
            .profile-avatar img,
            .profile-avatar-placeholder {
                width: 90px;
                height: 90px;
            }
            
            .profile-gradient {
                height: 100px;
            }
        }
    </style>

    <div class="py-8">
        <div class="supplier-detail-container px-4 sm:px-6 lg:px-8">
            <!-- Profile Header Card -->
            <div class="profile-header-card mb-6">
                <div class="profile-gradient"></div>
                <div class="profile-avatar">
                    @if($supplier->photo_profile)
                        <img src="{{ Storage::url($supplier->photo_profile) }}" 
                             alt="{{ $supplier->supplier_name }}"
                             class="object-cover">
                    @else
                        <div class="profile-avatar-placeholder">
                            <svg class="w-14 h-14 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="profile-content">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $supplier->supplier_name }}</h1>
                            @if($supplier->store_name)
                                <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">{{ $supplier->store_name }}</p>
                            @endif
                            <div class="flex flex-wrap items-center mt-3 gap-3">
                                <span class="badge {{ $supplier->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Partner since {{ $supplier->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="stat-icon bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $supplier->products()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="stat-icon bg-green-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Purchases</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $supplier->purchases()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="stat-icon bg-purple-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Value</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-gray-100 truncate">
                                Rp {{ number_format($supplier->purchases()->sum('total_price') ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="stat-icon bg-orange-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Purchase</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate">
                                {{ $supplier->purchases()->latest()->first()?->created_at->diffForHumans() ?? 'No purchases' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Information -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Contact Information</h3>
                        </div>
                        <div class="p-6 space-y-2">
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $supplier->email ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $supplier->phone_number ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            @if($supplier->address)
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 break-words">
                                        @if($supplier->address->detail_address)
                                            {{ $supplier->address->detail_address }},
                                        @endif
                                        @if($supplier->address->village)
                                            {{ $supplier->address->village->name }},
                                        @endif
                                        @if($supplier->address->district)
                                            {{ $supplier->address->district->name }},
                                        @endif
                                        @if($supplier->address->city)
                                            {{ $supplier->address->city->name }},
                                        @endif
                                        @if($supplier->address->province)
                                            {{ $supplier->address->province->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Timeline</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2">
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-blue-500"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Last Updated</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $supplier->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-green-500"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Partnership Started</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $supplier->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Products Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Products</h3>
                        </div>
                        <div class="table-responsive">
                            @if($supplier->products()->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplier->products()->take(10)->get() as $product)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    @if($product->image)
                                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                                    @else
                                                        <div class="product-placeholder">
                                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($product->description ?? '', 30) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $product->product_code ?? '-' }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $product->stock > 10 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $product->stock ?? 0 }} units
                                                </span>
                                            </td>
                                            <td class="text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-gray' }}">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($supplier->products()->count() > 10)
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-center border-t border-gray-200 dark:border-gray-600">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Showing 10 of {{ $supplier->products()->count() }} products
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No products found for this supplier</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Purchase History Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Purchase History</h3>
                        </div>
                        <div class="table-responsive">
                            @if($supplier->purchases()->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Purchase ID</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplier->purchases()->latest()->take(10)->get() as $purchase)
                                        <tr>
                                            <td class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                #{{ $purchase->id }}
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                {{ $purchase->created_at->format('d M Y') }}
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $purchase->purchaseDetails->count() ?? 0 }} items
                                            </td>
                                            <td class="text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                Rp {{ number_format($purchase->total_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($purchase->status == 'received') badge-success
                                                    @elseif($purchase->status == 'pending') badge-warning
                                                    @else badge-gray
                                                    @endif">
                                                    {{ ucfirst($purchase->status ?? 'Unknown') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($supplier->purchases()->count() > 10)
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-center border-t border-gray-200 dark:border-gray-600">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Showing 10 of {{ $supplier->purchases()->count() }} purchases
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No purchase history found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>