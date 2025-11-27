<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Edit Purchases Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">
                    {{ __('Edit Purchase') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update purchase information and notes</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('purchases.show', $purchase) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Details
                </a>
                <a href="{{ route('purchases.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Purchase Information Card (Read-only) -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-750 rounded-xl shadow-lg p-6 sm:p-8 mb-6 border border-blue-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Purchase Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Purchase Number</div>
                        <div id="purchase-number" class="text-lg font-bold text-gray-900 dark:text-gray-100">-</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Supplier</div>
                        <div id="supplier-name" class="text-lg font-semibold text-gray-900 dark:text-gray-100">-</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Total Amount</div>
                        <div id="total-price" class="text-lg font-bold text-green-600 dark:text-green-400">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    
                    <form id="edit-purchase-form">
                        
                        <!-- Editable Fields Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editable Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Purchase Date -->
                                <div>
                                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Purchase Date <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="date" 
                                               id="purchase_date" 
                                               name="purchase_date" 
                                               required
                                               class="pl-10 w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    </div>
                                </div>

                                <!-- Status (Hidden - always received) -->
                                <input type="hidden" name="status" id="status" value="received">
                                
                                <!-- Status Display (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status
                                    </label>
                                    <div class="flex items-center px-4 py-2.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">Received</span>
                                    </div>
                                </div>

                            </div>

                            <!-- Notes -->
                            <div class="mt-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Notes (Optional)
                                </label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="4"
                                          placeholder="Add or update notes about this purchase..."
                                          class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Purchase Details (Read-only) -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                Purchase Details
                                <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">(Read-only)</span>
                            </h3>
                            
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-750">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Product
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Quantity
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                    Purchase Price
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
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
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" 
                                    onclick="window.location.href='/purchases/{{ $purchase->id }}'"
                                    class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" 
                                    id="submit-btn"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Purchase
                            </button>
                        </div>

                    </form>

                    <!-- Loading indicator -->
                    <div id="loading" class="text-center py-12 hidden">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-500 dark:border-gray-600 dark:border-t-blue-400"></div>
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Loading purchase data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const purchaseId = parseInt('{{ $purchase->id }}');
        let currentPurchase = null;
        
        // Load purchase details
        async function loadPurchase() {
            const loading = document.getElementById('loading');
            const form = document.getElementById('edit-purchase-form');
            
            loading.classList.remove('hidden');
            form.style.display = 'none';
            
            try {
                const response = await fetch(`/api/purchases/${purchaseId}`);
                if (!response.ok) throw new Error('Failed to load purchase');
                
                currentPurchase = await response.json();
                populateForm(currentPurchase);
                
                form.style.display = 'block';
            } catch (error) {
                console.error('Error loading purchase:', error);
                alert('Error loading purchase details. Please refresh the page.');
            } finally {
                loading.classList.add('hidden');
            }
        }

        // Populate form with purchase data
        function populateForm(purchase) {
            // Header information (read-only)
            document.getElementById('purchase-number').textContent = purchase.purchase_number || '-';
            document.getElementById('supplier-name').textContent = purchase.supplier?.supplier_name || '-';
            
            const totalPrice = parseFloat(purchase.total_price || 0);
            document.getElementById('total-price').textContent = `Rp ${totalPrice.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            
            // Form fields
            document.getElementById('purchase_date').value = purchase.purchase_date || '';
            document.getElementById('notes').value = purchase.notes || '';
            
            // Purchase details table
            displayPurchaseDetails(purchase.purchase_details || []);
        }

        // Display purchase details table
        function displayPurchaseDetails(details) {
            const tbody = document.getElementById('purchase-details-table');
            tbody.innerHTML = '';

            if (details.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            No purchase details available
                        </td>
                    </tr>
                `;
                return;
            }

            details.forEach((detail, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? '' : 'bg-gray-50 dark:bg-gray-750';
                
                const purchasePrice = parseFloat(detail.purchase_price || 0);
                const subtotal = parseFloat(detail.subtotal || 0);
                
                row.innerHTML = `
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                        ${detail.product?.product_name || '-'}
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            ${detail.product?.product_code || ''}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                            ${detail.quantity} pcs
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        Rp ${purchasePrice.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Rp ${subtotal.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Handle form submission
        document.getElementById('edit-purchase-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const originalHTML = submitBtn.innerHTML;
            
            // Validation
            const purchaseDate = document.getElementById('purchase_date').value;
            if (!purchaseDate) {
                alert('Please select a purchase date');
                return;
            }
            
            // Disable button and show loading
            submitBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            `;
            submitBtn.disabled = true;
            
            const formData = {
                purchase_date: purchaseDate,
                status: 'received',
                notes: document.getElementById('notes').value
            };
            
            try {
                const response = await fetch(`/api/purchases/${purchaseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (response.ok) {
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    successMsg.textContent = 'Purchase updated successfully!';
                    document.body.appendChild(successMsg);
                    
                    setTimeout(() => {
                        window.location.href = `/purchases/${purchaseId}`;
                    }, 1000);
                } else {
                    alert(result.message || 'Error updating purchase');
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating purchase. Please try again.');
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        });

        // Load purchase when page loads
        document.addEventListener('DOMContentLoaded', loadPurchase);

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