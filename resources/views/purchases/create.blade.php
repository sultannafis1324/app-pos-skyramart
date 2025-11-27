<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 sm:h-16 sm:w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Purchases Create Page...</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 dark:text-gray-100">
                    {{ __('Add New Purchase') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Create and manage your purchase orders</p>
            </div>
            <a href="{{ route('purchases.index') }}" 
               class="inline-flex items-center px-3 sm:px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm w-full sm:w-auto justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Purchases
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 sm:p-6 lg:p-8">
                    
                    <form id="purchase-form">
                        @csrf
                        
                        <!-- Purchase Header Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-750 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 border border-blue-100 dark:border-gray-600">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Purchase Information
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                                
                                <!-- Purchase Number -->
                                <div>
                                    <label for="purchase_number" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                                        Purchase Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                        </div>
                                        <input type="text" name="purchase_number" id="purchase_number" required
                                               class="pl-8 sm:pl-10 w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    </div>
                                    <div id="purchase_number_error" class="text-red-500 text-xs mt-1 sm:mt-1.5 hidden"></div>
                                </div>

                                <!-- Purchase Date -->
                                <div>
                                    <label for="purchase_date" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                                        Purchase Date <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="date" name="purchase_date" id="purchase_date" required
                                               class="pl-8 sm:pl-10 w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    </div>
                                    <div id="purchase_date_error" class="text-red-500 text-xs mt-1 sm:mt-1.5 hidden"></div>
                                </div>

                                <!-- Supplier -->
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label for="supplier_id" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                                        Supplier <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <select name="supplier_id" id="supplier_id" required
                                                class="pl-8 sm:pl-10 w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none">
                                            <option value="">Select Supplier</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-2 sm:pr-3 flex items-center pointer-events-none">
                                        </div>
                                    </div>
                                    <div id="supplier_id_error" class="text-red-500 text-xs mt-1 sm:mt-1.5 hidden"></div>
                                </div>

                                <input type="hidden" name="user_id" id="user_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="status" value="received">
                            </div>

                            <!-- Notes -->
                            <div class="mt-4 sm:mt-6">
                                <label for="notes" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                          placeholder="Add any additional notes here..."></textarea>
                                <div id="notes_error" class="text-red-500 text-xs mt-1 sm:mt-1.5 hidden"></div>
                            </div>
                        </div>

                        <!-- Purchase Items Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-750 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                        Purchase Items
                                    </h3>
                                    <button type="button" id="add-item" class="w-full sm:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Item
                                    </button>
                                </div>
                            </div>

                            <!-- Mobile View: Card Layout -->
                            <div id="mobile-items" class="block lg:hidden divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Items will be added here for mobile -->
                            </div>

                            <!-- Desktop/Tablet View: Table Layout -->
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-750">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Product
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchase-items" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <!-- Items will be added here for desktop -->
                                    </tbody>
                                </table>
                            </div>

                            <div id="empty-state" class="text-center py-8 sm:py-12 hidden">
                                <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">No items added yet. Click "Add Item" to get started.</p>
                            </div>

                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-750 dark:to-gray-700 px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300">Total Amount</span>
                                    <div class="text-right">
                                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                            Rp <span id="total-amount">0</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1">Indonesian Rupiah</div>
                                    </div>
                                </div>
                                <input type="hidden" name="total_price" id="total_price" value="0">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 mt-4 sm:mt-6">
                            <button type="button" onclick="window.history.back()" 
                                    class="px-4 sm:px-6 py-2 sm:py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" id="submit-btn"
                                    class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Purchase
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    let products = [];
let allProducts = [];
let itemCounter = 0;

async function loadFormData() {
    try {
        const suppliersResponse = await fetch('/api/suppliers');
        if (suppliersResponse.ok) {
            const suppliers = await suppliersResponse.json();
            const supplierSelect = document.getElementById('supplier_id');
            suppliers.forEach(supplier => {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.textContent = supplier.supplier_name;
                supplierSelect.appendChild(option);
            });
        }

        const productsResponse = await fetch('/api/products');
        if (productsResponse.ok) {
            allProducts = await productsResponse.json();
            console.log('All products loaded:', allProducts);
        }
    } catch (error) {
        console.error('Error loading form data:', error);
    }
}

async function loadProductsBySupplier(supplierId) {
    try {
        if (!supplierId) {
            products = [];
            return;
        }

        const response = await fetch(`/api/products/by-supplier?supplier_id=${supplierId}`);
        if (response.ok) {
            products = await response.json();
            console.log('Products for supplier:', products);
            
            updateAllProductDropdowns();
            
            if (products.length === 0) {
                alert('No products found for this supplier. Please add products for this supplier first.');
            }
        }
    } catch (error) {
        console.error('Error loading products by supplier:', error);
        alert('Failed to load products for selected supplier');
    }
}

function updateAllProductDropdowns() {
    const productSelects = document.querySelectorAll('.product-select');
    productSelects.forEach(select => {
        const currentValue = select.value;
        
        select.innerHTML = '<option value="">Select Product</option>';
        
        products.forEach(product => {
            const price = product.purchase_price || product.selling_price || 0;
            const option = document.createElement('option');
            option.value = product.id;
            option.dataset.price = price;
            option.dataset.hasExpiry = product.has_expiry ? 'true' : 'false';
            option.textContent = `${product.product_name} (${product.product_code})`;
            select.appendChild(option);
        });
        
        if (currentValue && products.find(p => p.id == currentValue)) {
            select.value = currentValue;
            // Trigger change to show/hide expiry field
            select.dispatchEvent(new Event('change'));
        } else {
            select.value = '';
            const row = select.closest('tr') || select.closest('.item-card');
            if (row) {
                const priceInput = row.querySelector('.price-input');
                const subtotalInput = row.querySelector('.subtotal-input');
                if (priceInput) priceInput.value = '';
                if (subtotalInput) subtotalInput.value = '';
                hideExpiryField(row);
                calculateTotal();
            }
        }
    });
}

function setDefaults() {
    const today = new Date();
    document.getElementById('purchase_date').value = today.toISOString().split('T')[0];
    
    const purchaseNumber = 'PO-' + today.getFullYear() + (today.getMonth() + 1).toString().padStart(2, '0') + today.getDate().toString().padStart(2, '0') + '-' + Date.now().toString().slice(-4);
    document.getElementById('purchase_number').value = purchaseNumber;
}

function addItemRow() {
    itemCounter++;
    const desktopTbody = document.getElementById('purchase-items');
    const mobileContainer = document.getElementById('mobile-items');
    const emptyState = document.getElementById('empty-state');
    if (emptyState) emptyState.classList.add('hidden');
    
    let productOptions = '<option value="">Select Product</option>';
    products.forEach(product => {
        const price = product.purchase_price || product.selling_price || 0;
        productOptions += `<option value="${product.id}" data-price="${price}" data-has-expiry="${product.has_expiry ? 'true' : 'false'}">${product.product_name} (${product.product_code})</option>`;
    });
    
    // Desktop Row
    const row = document.createElement('tr');
    row.id = `item-row-${itemCounter}`;
    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150';
    
    row.innerHTML = `
        <td class="px-4 py-3">
            <select name="purchase_details[${itemCounter}][product_id]" class="product-select w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                ${productOptions}
            </select>
            <!-- ✅ Expiry field container (hidden by default) -->
            <div class="expiry-field-container hidden mt-2"></div>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="purchase_details[${itemCounter}][quantity]" class="quantity-input w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" min="1" value="1" required>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="purchase_details[${itemCounter}][purchase_price]" class="price-input w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" step="0.01" min="0" required>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="purchase_details[${itemCounter}][subtotal]" class="subtotal-input w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 font-medium" step="0.01" readonly>
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" onclick="removeItemRow(${itemCounter})" class="inline-flex items-center px-2 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg text-xs font-medium transition-all duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Remove
            </button>
        </td>
    `;
    desktopTbody.appendChild(row);

    // Mobile Card
    const mobileCard = document.createElement('div');
    mobileCard.id = `item-card-${itemCounter}`;
    mobileCard.className = 'item-card p-4 bg-white dark:bg-gray-800';
    
    mobileCard.innerHTML = `
        <div class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product</label>
                <select name="purchase_details_mobile[${itemCounter}][product_id]" class="product-select w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    ${productOptions}
                </select>
                <!-- ✅ Expiry field container (hidden by default) -->
                <div class="expiry-field-container hidden mt-2"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Quantity</label>
                    <input type="number" name="purchase_details_mobile[${itemCounter}][quantity]" class="quantity-input w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg" min="1" value="1" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Price</label>
                    <input type="number" name="purchase_details_mobile[${itemCounter}][purchase_price]" class="price-input w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg" step="0.01" min="0" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subtotal</label>
                <input type="number" name="purchase_details_mobile[${itemCounter}][subtotal]" class="subtotal-input w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg font-medium" step="0.01" readonly>
            </div>
            <button type="button" onclick="removeItemRow(${itemCounter})" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg text-sm font-medium transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Remove Item
            </button>
        </div>
    `;
    mobileContainer.appendChild(mobileCard);

    // Add event listeners for both desktop and mobile
    setupItemEventListeners(row, itemCounter);
    setupItemEventListeners(mobileCard, itemCounter);
}

function setupItemEventListeners(container, counter) {
    const productSelect = container.querySelector('.product-select');
    const quantityInput = container.querySelector('.quantity-input');
    const priceInput = container.querySelector('.price-input');
    const subtotalInput = container.querySelector('.subtotal-input');

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const productId = this.value;
        
        if (selectedOption.dataset.price) {
            priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
            
            // ✅ CHECK if product has expiry
            const hasExpiry = selectedOption.dataset.hasExpiry === 'true';
            
            if (hasExpiry) {
                const product = products.find(p => p.id == productId);
                showExpiryField(container, counter, product);
            } else {
                hideExpiryField(container);
            }
            
            // Sync with counterpart (desktop/mobile)
            const counterpartContainer = container.id.includes('row') 
                ? document.getElementById(`item-card-${counter}`)
                : document.getElementById(`item-row-${counter}`);
            
            if (counterpartContainer) {
                const counterpartSelect = counterpartContainer.querySelector('.product-select');
                const counterpartPrice = counterpartContainer.querySelector('.price-input');
                counterpartSelect.value = this.value;
                counterpartPrice.value = priceInput.value;
                
                // Sync expiry field visibility
                if (hasExpiry) {
                    const counterpartProduct = products.find(p => p.id == productId);
                    showExpiryField(counterpartContainer, counter, counterpartProduct);
                } else {
                    hideExpiryField(counterpartContainer);
                }
            }
            
            calculateSubtotal();
        } else {
            // Reset jika tidak ada product dipilih
            priceInput.value = '';
            hideExpiryField(container);
        }
    });

    quantityInput.addEventListener('input', function() {
        syncWithCounterpart(counter, 'quantity', this.value);
        calculateSubtotal();
    });
    
    priceInput.addEventListener('input', function() {
        syncWithCounterpart(counter, 'price', this.value);
        calculateSubtotal();
    });

    function syncWithCounterpart(counter, type, value) {
        const counterpartContainer = container.id.includes('row') 
            ? document.getElementById(`item-card-${counter}`)
            : document.getElementById(`item-row-${counter}`);
        
        if (counterpartContainer) {
            const counterpartInput = counterpartContainer.querySelector(
                type === 'quantity' ? '.quantity-input' : '.price-input'
            );
            if (counterpartInput) {
                counterpartInput.value = value;
            }
        }
    }

    function calculateSubtotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const subtotal = quantity * price;
        subtotalInput.value = subtotal.toFixed(2);
        
        // Sync subtotal with counterpart
        const counterpartContainer = container.id.includes('row') 
            ? document.getElementById(`item-card-${counter}`)
            : document.getElementById(`item-row-${counter}`);
        
        if (counterpartContainer) {
            const counterpartSubtotal = counterpartContainer.querySelector('.subtotal-input');
            if (counterpartSubtotal) {
                counterpartSubtotal.value = subtotal.toFixed(2);
            }
        }
        
        calculateTotal();
    }
}

// ✅ FUNCTION BARU: Show expiry field
function showExpiryField(container, counter, product) {
    const expiryContainer = container.querySelector('.expiry-field-container');
    
    if (!expiryContainer) return;
    
    // ✅ Show container
    expiryContainer.classList.remove('hidden');
    
    // ✅ Build expiry field HTML with current batches info
    let batchesInfo = '';
    if (product && product.batches && product.batches.length > 0) {
        batchesInfo = '<div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded text-xs">';
        batchesInfo += '<p class="font-semibold text-blue-800 dark:text-blue-300 mb-1">📦 Current Batches:</p>';
        
        product.batches
            .filter(b => b.quantity_remaining > 0)
            .sort((a, b) => new Date(a.expiry_date) - new Date(b.expiry_date))
            .forEach((batch, idx) => {
                const expDate = new Date(batch.expiry_date);
                const daysLeft = Math.ceil((expDate - new Date()) / (1000 * 60 * 60 * 24));
                const expColor = daysLeft <= 7 ? 'text-red-600 dark:text-red-400' : daysLeft <= 30 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400';
                
                batchesInfo += `
                    <div class="flex justify-between items-center py-1 ${idx > 0 ? 'border-t border-blue-200 dark:border-blue-700' : ''}">
                        <span class="text-gray-700 dark:text-gray-300">
                            Batch #${batch.id}: <strong>${batch.quantity_remaining}</strong> pcs
                        </span>
                        <span class="${expColor} font-semibold">
                            Exp: ${expDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                            ${daysLeft > 0 ? `(${daysLeft}d left)` : '(EXPIRED)'}
                        </span>
                    </div>
                `;
            });
        
        batchesInfo += '</div>';
    }
    
    expiryContainer.innerHTML = `
        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                <svg class="w-3 h-3 inline mr-1 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Expiry Date for New Batch <span class="text-red-500">*</span>
            </label>
            <input type="date" 
                   name="purchase_details[${counter}][expiry_date]" 
                   class="expiry-date-input w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" 
                   min="${new Date().toISOString().split('T')[0]}"
                   required>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                ⚠️ This will create a new batch. Old batches remain unchanged.
            </p>
            ${batchesInfo}
        </div>
    `;
    
    // ✅ Add event listener to sync expiry date
    const expiryInput = expiryContainer.querySelector('.expiry-date-input');
    if (expiryInput) {
        expiryInput.addEventListener('change', function() {
            syncExpiryDate(counter, this.value);
        });
    }
}

// ✅ FUNCTION BARU: Hide expiry field
function hideExpiryField(container) {
    const expiryContainer = container.querySelector('.expiry-field-container');
    
    if (expiryContainer) {
        expiryContainer.classList.add('hidden');
        expiryContainer.innerHTML = '';
    }
}

// ✅ FUNCTION BARU: Sync expiry date between desktop and mobile
function syncExpiryDate(counter, value) {
    const desktopRow = document.getElementById(`item-row-${counter}`);
    const mobileCard = document.getElementById(`item-card-${counter}`);
    
    if (desktopRow) {
        const desktopExpiryInput = desktopRow.querySelector('.expiry-date-input');
        if (desktopExpiryInput) {
            desktopExpiryInput.value = value;
        }
    }
    
    if (mobileCard) {
        const mobileExpiryInput = mobileCard.querySelector('.expiry-date-input');
        if (mobileExpiryInput) {
            mobileExpiryInput.value = value;
        }
    }
}

function removeItemRow(counter) {
    const row = document.getElementById(`item-row-${counter}`);
    const card = document.getElementById(`item-card-${counter}`);
    
    if (row) row.remove();
    if (card) card.remove();
    
    calculateTotal();
    
    const desktopTbody = document.getElementById('purchase-items');
    const mobileContainer = document.getElementById('mobile-items');
    const emptyState = document.getElementById('empty-state');
    
    if (desktopTbody.children.length === 0 && mobileContainer.children.length === 0 && emptyState) {
        emptyState.classList.remove('hidden');
    }
}

function calculateTotal() {
    const subtotalInputs = document.querySelectorAll('#purchase-items .subtotal-input');
    let total = 0;
    subtotalInputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-amount').textContent = total.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    document.getElementById('total_price').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', async function() {
    console.log('DOM loaded, initializing...');
    
    await loadFormData();
    setDefaults();
    
    const supplierSelect = document.getElementById('supplier_id');
    supplierSelect.addEventListener('change', async function() {
        const supplierId = this.value;
        
        if (supplierId) {
            const desktopTbody = document.getElementById('purchase-items');
            const mobileContainer = document.getElementById('mobile-items');
            desktopTbody.innerHTML = '';
            mobileContainer.innerHTML = '';
            itemCounter = 0;
            
            await loadProductsBySupplier(supplierId);
            
            if (products.length > 0) {
                setTimeout(() => {
                    addItemRow();
                }, 100);
            } else {
                document.getElementById('empty-state')?.classList.remove('hidden');
            }
        } else {
            const desktopTbody = document.getElementById('purchase-items');
            const mobileContainer = document.getElementById('mobile-items');
            desktopTbody.innerHTML = '';
            mobileContainer.innerHTML = '';
            itemCounter = 0;
            products = [];
            document.getElementById('empty-state')?.classList.remove('hidden');
        }
        
        calculateTotal();
    });
    
    console.log('Initialization complete');
});

document.getElementById('add-item').addEventListener('click', function() {
    const supplierId = document.getElementById('supplier_id').value;
    
    if (!supplierId) {
        alert('Please select a supplier first');
        return;
    }
    
    if (products.length === 0) {
        alert('No products available for this supplier');
        return;
    }
    
    addItemRow();
});

document.getElementById('purchase-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const desktopItems = document.querySelectorAll('#purchase-items tr');
    if (desktopItems.length === 0) {
        alert('Please add at least one item to the purchase');
        return;
    }
    
    // ✅ VALIDASI: Cek apakah produk dengan expiry sudah diisi expiry_date nya
    let hasError = false;
    const items = document.querySelectorAll('#purchase-items tr');
    
    items.forEach((item, index) => {
        const productSelect = item.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const hasExpiry = selectedOption.dataset.hasExpiry === 'true';
        const expiryInput = item.querySelector('.expiry-date-input');
        
        if (hasExpiry && (!expiryInput || !expiryInput.value)) {
            alert(`Product "${selectedOption.textContent}" requires an expiry date. Please fill in the expiry date.`);
            hasError = true;
            return false;
        }
    });
    
    if (hasError) return;
    
    const submitBtn = document.getElementById('submit-btn');
    const originalHTML = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
    `;
    submitBtn.disabled = true;

    document.querySelectorAll('[id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });

    const formData = new FormData(this);

    try {
        const response = await fetch('/api/purchases', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            alert('Purchase created successfully!');
            window.location.href = '/purchases';
        } else {
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const errorEl = document.getElementById(field + '_error');
                    if (errorEl) {
                        errorEl.textContent = result.errors[field][0];
                        errorEl.classList.remove('hidden');
                    }
                });
            } else {
                alert(result.message || 'Error creating purchase');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error creating purchase');
    } finally {
        submitBtn.innerHTML = originalHTML;
        submitBtn.disabled = false;
    }
});

window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    setTimeout(() => {
        loader.classList.add('hide');
        setTimeout(() => {
            loader.style.display = 'none';
        }, 800);
    }, 1000);
});

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

    <style>
        /* Custom fade animation for loader */
        #pageLoader.hide {
            opacity: 0;
            transition: opacity 0.8s ease-out;
        }

        /* Smooth scrolling for mobile */
        @media (max-width: 1023px) {
            #mobile-items {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Better touch targets for mobile */
        @media (max-width: 640px) {
            button, select, input {
                min-height: 44px;
            }
        }
    </style>

</x-app-layout>