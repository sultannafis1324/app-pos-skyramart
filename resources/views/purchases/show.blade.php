<x-app-layout>
    <style>
        /* Professional Animations */
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(15px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }

        .delay-100 { animation-delay: 0.1s; animation-fill-mode: both; }
        .delay-200 { animation-delay: 0.2s; animation-fill-mode: both; }
        .delay-300 { animation-delay: 0.3s; animation-fill-mode: both; }

        /* Professional Cards */
        .info-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s ease;
        }

        .info-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .dark .info-card {
            background: #1f2937;
            border-color: #374151;
        }

        .dark .info-card:hover {
            border-color: #4b5563;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Status Badge */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-pending::before {
            background: #f59e0b;
        }

        .status-received {
            background: #d1fae5;
            color: #065f46;
        }

        .status-received::before {
            background: #10b981;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-cancelled::before {
            background: #ef4444;
        }

        .dark .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .dark .status-received {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .dark .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Table Styles */
        .modern-table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .dark .modern-table {
            border-color: #374151;
        }

        .modern-table thead {
            background: #f9fafb;
        }

        .dark .modern-table thead {
            background: #1f2937;
        }

        .modern-table tbody tr {
            transition: background 0.15s ease;
        }

        .modern-table tbody tr:hover {
            background: #f9fafb;
        }

        .dark .modern-table tbody tr:hover {
            background: #1f2937;
        }

        /* Loader */
        .page-loader {
            background: rgba(255, 255, 255, 0.98);
        }

        .dark .page-loader {
            background: rgba(17, 24, 39, 0.98);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .dark .spinner {
            border-color: #374151;
            border-top-color: #60a5fa;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Summary Box */
        .summary-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .dark .summary-box {
            background: #1f2937;
            border-color: #374151;
        }

        /* Info Row */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid #f3f4f6;
        }

        .dark .info-row:not(:last-child) {
            border-bottom-color: #374151;
        }

        /* Buttons */
        .btn-modern {
            transition: all 0.2s ease;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        /* Notes Section */
        .notes-section {
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 1rem 1.25rem;
            border-radius: 8px;
        }

        .dark .notes-section {
            background: rgba(251, 191, 36, 0.1);
            border-left-color: #fbbf24;
        }

        /* Hide loader */
        .hide {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .info-card {
                padding: 1.25rem;
            }
            
            .summary-box {
                padding: 1.25rem;
            }
        }
    </style>

    <!-- Loader -->
    <div id="pageLoader" class="page-loader fixed inset-0 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="spinner mb-4 mx-auto"></div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Loading purchase details...</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Purchase Details
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('purchases.edit', $purchase) }}" 
                   class="btn-modern bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('purchases.index') }}" 
                   class="btn-modern bg-gray-600 hover:bg-gray-700 text-white text-sm py-2 px-4 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6 lg:p-8">
                    
                    <!-- Header Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                        <!-- Purchase Info -->
                        <div class="info-card animate-fadeInUp">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wide">
                                Purchase Information
                            </h3>
                            <div class="space-y-3">
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Purchase Number</span>
                                    <span id="purchase-number" class="font-semibold text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Purchase Date</span>
                                    <span id="purchase-date" class="font-semibold text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                                    <span id="purchase-status" class="status-badge"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Supplier Info -->
                        <div class="info-card animate-fadeInUp delay-100">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wide">
                                Supplier & User
                            </h3>
                            <div class="space-y-3">
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Supplier</span>
                                    <span id="supplier-name" class="font-semibold text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Created by</span>
                                    <span id="user-name" class="font-semibold text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="info-row border-b-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                                    <span id="updated-at" class="text-sm text-gray-900 dark:text-gray-100"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6 animate-fadeInUp delay-200">
                        <div class="notes-section">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-sm mb-1">Notes</h4>
                                    <p id="purchase-notes" class="text-sm text-gray-700 dark:text-gray-300"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Details Table -->
                    <div class="mb-6 animate-fadeInUp delay-300">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Items
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="modern-table min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            SKU
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="purchase-details-table" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Details will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="flex justify-end">
                        <div class="summary-box w-full sm:w-80">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Total Items</span>
                                    <span id="total-items" class="font-semibold text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between items-baseline pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-base font-semibold text-gray-900 dark:text-gray-100">Total</span>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                            Rp <span id="final-total"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loading" class="text-center py-8" style="display: none;">
                        <div class="spinner mx-auto"></div>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Loading data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const purchaseId = parseInt('{{ $purchase->id }}');
        
        // Load purchase details
        async function loadPurchase() {
            document.getElementById('loading').style.display = 'block';
            try {
                const response = await fetch(`/api/purchases/${purchaseId}`);
                const purchase = await response.json();
                displayPurchase(purchase);
            } catch (error) {
                console.error('Error loading purchase:', error);
                alert('Error loading purchase details');
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        // Display purchase information
        function displayPurchase(purchase) {
            // Header information
            document.getElementById('purchase-number').textContent = purchase.purchase_number;
            document.getElementById('purchase-date').textContent = new Date(purchase.purchase_date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const statusElement = document.getElementById('purchase-status');
            const statusText = purchase.status.charAt(0).toUpperCase() + purchase.status.slice(1);
            statusElement.textContent = statusText;
            statusElement.className = `status-badge status-${purchase.status}`;
            
            // Supplier and user information
            document.getElementById('supplier-name').textContent = purchase.supplier?.supplier_name || '-';
            document.getElementById('user-name').textContent = purchase.user?.name || '-';
            document.getElementById('updated-at').textContent = new Date(purchase.updated_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            // Notes
            document.getElementById('purchase-notes').textContent = purchase.notes || 'No notes available';
            
            // Purchase details
            displayPurchaseDetails(purchase.purchase_details || []);
            
            // Summary
            const totalItems = purchase.purchase_details?.reduce((sum, detail) => sum + detail.quantity, 0) || 0;
            document.getElementById('total-items').textContent = totalItems;
            document.getElementById('final-total').textContent = parseFloat(purchase.total_price).toLocaleString('id-ID');
        }

        // Display purchase details table
        function displayPurchaseDetails(details) {
            const tbody = document.getElementById('purchase-details-table');
            tbody.innerHTML = '';

            details.forEach(detail => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                        ${detail.product?.product_name || '-'}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                        ${detail.product?.product_code || '-'}
                    </td>
                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                        ${detail.quantity}
                    </td>
                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                        Rp ${parseFloat(detail.purchase_price).toLocaleString('id-ID')}
                    </td>
                    <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">
                        Rp ${parseFloat(detail.subtotal).toLocaleString('id-ID')}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Load purchase when page loads
        document.addEventListener('DOMContentLoaded', loadPurchase);

        // Hide loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }, 800);
        });

        // Fallback: Hide loader after max time
        setTimeout(() => {
            const loader = document.getElementById('pageLoader');
            if (loader && !loader.classList.contains('hide')) {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }
        }, 3000);
    </script>
</x-app-layout>