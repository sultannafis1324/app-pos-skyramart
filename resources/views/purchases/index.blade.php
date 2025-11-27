<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Purchases Management Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">
                    Purchases Management
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your purchase orders and inventory</p>
            </div>
            <a href="{{ route('purchases.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Purchase
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Total Purchases Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Purchases</p>
                            <p id="total-purchases" class="text-3xl sm:text-4xl font-bold text-blue-900 dark:text-blue-100 mt-2">0</p>
                            <p class="text-xs text-blue-600/70 dark:text-blue-400/70 mt-1">All time orders</p>
                        </div>
                        <div class="p-3 bg-blue-600 dark:bg-blue-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Value Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Value</p>
                            <p id="total-value" class="text-3xl sm:text-4xl font-bold text-green-900 dark:text-green-100 mt-2">Rp 0</p>
                            <p class="text-xs text-green-600/70 dark:text-green-400/70 mt-1">Total investment</p>
                        </div>
                        <div class="p-3 bg-green-600 dark:bg-green-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Order Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl shadow-sm border border-purple-200 dark:border-purple-800 p-6 hover:shadow-md transition-shadow duration-200 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Average Order</p>
                            <p id="avg-order" class="text-3xl sm:text-4xl font-bold text-purple-900 dark:text-purple-100 mt-2">Rp 0</p>
                            <p class="text-xs text-purple-600/70 dark:text-purple-400/70 mt-1">Per purchase</p>
                        </div>
                        <div class="p-3 bg-purple-600 dark:bg-purple-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2 xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" id="search" placeholder="Search purchases..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Supplier Filter -->
                    <div class="xl:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                        <select id="supplier-filter" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all appearance-none bg-no-repeat pr-10" style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23666%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 0.75rem center; background-size: 1.25rem;">
                            <option value="">All Suppliers</option>
                        </select>
                    </div>
                    
                    <!-- Date From -->
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" id="date-from" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                    </div>
                    
                    <!-- Date To -->
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" id="date-to" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                    </div>

                    <!-- Clear Filters Button -->
                    <div class="flex items-end xl:col-span-1">
                        <button id="clear-filters" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Table Header with Items Per Page -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Show</label>
                        <select id="items-per-page" class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm appearance-none bg-no-repeat bg-right pr-4" style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-size: 1rem;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="text-sm text-gray-600 dark:text-gray-400">entries</label>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing <span id="showing-from">0</span> to <span id="showing-to">0</span> of <span id="total-entries">0</span> entries
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Purchase Number
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Supplier
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Total Price
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="purchases-table" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading indicator -->
                <div id="loading" class="text-center py-12" style="display: none;">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-blue-600"></div>
                    <p class="text-gray-500 dark:text-gray-400 mt-4">Loading purchases...</p>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="text-center py-12" style="display: none;">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No purchases found</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new purchase.</p>
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                    </div>
                    <div class="flex gap-2">
                        <button id="prev-page" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Previous
                        </button>
                        <div id="page-numbers" class="flex gap-2">
                            <!-- Page numbers will be inserted here -->
                        </div>
                        <button id="next-page" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let purchases = [];
        let filteredPurchases = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        
        // Load purchases
        async function loadPurchases() {
            const loading = document.getElementById('loading');
            const emptyState = document.getElementById('empty-state');
            const table = document.getElementById('purchases-table');
            
            loading.style.display = 'block';
            emptyState.style.display = 'none';
            table.innerHTML = '';
            
            try {
                const response = await fetch('/api/purchases');
                purchases = await response.json();
                
                // Sort by date (newest first)
                purchases.sort((a, b) => new Date(b.purchase_date) - new Date(a.purchase_date));
                
                filteredPurchases = [...purchases];
                loadSuppliers();
                updateStatistics(purchases);
                displayPurchases();
            } catch (error) {
                console.error('Error loading purchases:', error);
                emptyState.style.display = 'block';
            } finally {
                loading.style.display = 'none';
            }
        }

        // Load suppliers for filter
        async function loadSuppliers() {
            try {
                const response = await fetch('/api/suppliers');
                const suppliers = await response.json();
                const supplierFilter = document.getElementById('supplier-filter');
                
                // Clear existing options except the first one
                supplierFilter.innerHTML = '<option value="">All Suppliers</option>';
                
                suppliers.forEach(supplier => {
                    const option = document.createElement('option');
                    option.value = supplier.id;
                    option.textContent = supplier.supplier_name || supplier.name;
                    supplierFilter.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading suppliers:', error);
            }
        }

        // Display purchases with pagination
        function displayPurchases() {
            const tbody = document.getElementById('purchases-table');
            const emptyState = document.getElementById('empty-state');
            const paginationContainer = document.getElementById('pagination-container');
            
            tbody.innerHTML = '';
            
            if (filteredPurchases.length === 0) {
                emptyState.style.display = 'block';
                paginationContainer.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            paginationContainer.style.display = 'flex';
            
            // Calculate pagination
            const totalPages = Math.ceil(filteredPurchases.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredPurchases.length);
            const currentPurchases = filteredPurchases.slice(startIndex, endIndex);
            
            // Update showing text
            document.getElementById('showing-from').textContent = filteredPurchases.length > 0 ? startIndex + 1 : 0;
            document.getElementById('showing-to').textContent = endIndex;
            document.getElementById('total-entries').textContent = filteredPurchases.length;
            
            // Display purchases
            currentPurchases.forEach(purchase => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            ${purchase.purchase_number}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            ${new Date(purchase.purchase_date).toLocaleDateString('id-ID', { 
                                day: '2-digit', 
                                month: 'short', 
                                year: 'numeric' 
                            })}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            ${purchase.supplier?.supplier_name || purchase.supplier?.name || '-'}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            Rp ${Number(purchase.total_price).toLocaleString('id-ID')}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(purchase.status)}">
                            ${purchase.status.charAt(0).toUpperCase() + purchase.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-3">
                            <a href="/purchases/${purchase.id}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                View
                            </a>
                            <a href="/purchases/${purchase.id}/edit" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                Edit
                            </a>
                            ${purchase.status !== 'received' ? 
                                `<button onclick="deletePurchase(${purchase.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    Delete
                                </button>` : 
                                ''
                            }
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            updatePagination(totalPages);
        }

        // Update pagination controls
        function updatePagination(totalPages) {
            document.getElementById('current-page').textContent = currentPage;
            document.getElementById('total-pages').textContent = totalPages;
            
            const prevBtn = document.getElementById('prev-page');
            const nextBtn = document.getElementById('next-page');
            const pageNumbers = document.getElementById('page-numbers');
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            // Generate page numbers
            pageNumbers.innerHTML = '';
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-4 py-2 border rounded-lg transition-colors ${
                    i === currentPage 
                        ? 'bg-blue-600 text-white border-blue-600' 
                        : 'border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'
                }`;
                btn.onclick = () => goToPage(i);
                pageNumbers.appendChild(btn);
            }
        }

        // Go to specific page
        function goToPage(page) {
            currentPage = page;
            displayPurchases();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Get status color class
        function getStatusColor(status) {
            switch(status) {
                case 'pending': 
                    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                case 'received': 
                    return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                case 'cancelled': 
                    return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                default: 
                    return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            }
        }

        // Update statistics
        function updateStatistics(purchasesToShow) {
            const totalPurchases = purchasesToShow.length;
            const totalValue = purchasesToShow.reduce((sum, p) => sum + Number(p.total_price), 0);
            const avgOrder = totalPurchases > 0 ? totalValue / totalPurchases : 0;
            
            document.getElementById('total-purchases').textContent = totalPurchases;
            document.getElementById('total-value').textContent = `Rp ${totalValue.toLocaleString('id-ID')}`;
            document.getElementById('avg-order').textContent = `Rp ${Math.round(avgOrder).toLocaleString('id-ID')}`;
        }

        // Filter purchases
        function filterPurchases() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const supplierFilter = document.getElementById('supplier-filter').value;
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;

            filteredPurchases = purchases.filter(purchase => {
                // Search filter
                const matchesSearch = !searchTerm || 
                    purchase.purchase_number.toLowerCase().includes(searchTerm) ||
                    (purchase.supplier?.supplier_name && purchase.supplier.supplier_name.toLowerCase().includes(searchTerm)) ||
                    (purchase.supplier?.name && purchase.supplier.name.toLowerCase().includes(searchTerm)) ||
                    (purchase.notes && purchase.notes.toLowerCase().includes(searchTerm));

                // Supplier filter
                const matchesSupplier = !supplierFilter || purchase.supplier_id == supplierFilter;

                // Date range filter
                const purchaseDate = new Date(purchase.purchase_date);
                const matchesDateFrom = !dateFrom || purchaseDate >= new Date(dateFrom);
                const matchesDateTo = !dateTo || purchaseDate <= new Date(dateTo);

                return matchesSearch && matchesSupplier && matchesDateFrom && matchesDateTo;
            });

            currentPage = 1;
            updateStatistics(filteredPurchases);
            displayPurchases();
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('search').value = '';
            document.getElementById('supplier-filter').value = '';
            document.getElementById('date-from').value = '';
            document.getElementById('date-to').value = '';
            filterPurchases();
        }

        // Event listeners for filters
        document.getElementById('search').addEventListener('input', filterPurchases);
        document.getElementById('supplier-filter').addEventListener('change', filterPurchases);
        document.getElementById('date-from').addEventListener('change', filterPurchases);
        document.getElementById('date-to').addEventListener('change', filterPurchases);
        document.getElementById('clear-filters').addEventListener('click', clearFilters);

        // Items per page change
        document.getElementById('items-per-page').addEventListener('change', (e) => {
            itemsPerPage = parseInt(e.target.value);
            currentPage = 1;
            displayPurchases();
        });

        // Pagination buttons
        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage > 1) goToPage(currentPage - 1);
        });

        document.getElementById('next-page').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredPurchases.length / itemsPerPage);
            if (currentPage < totalPages) goToPage(currentPage + 1);
        });

        // Delete purchase
        async function deletePurchase(id) {
            if (!confirm('Are you sure you want to delete this purchase?')) return;

            try {
                const response = await fetch(`/api/purchases/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();
                
                if (response.ok) {
                    // Show success message with modern notification
                    showNotification('Purchase deleted successfully', 'success');
                    loadPurchases();
                } else {
                    showNotification(result.message || 'Error deleting purchase', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error deleting purchase', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-green-500 text-white' 
                    : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Load purchases when page loads
        document.addEventListener('DOMContentLoaded', loadPurchases);

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