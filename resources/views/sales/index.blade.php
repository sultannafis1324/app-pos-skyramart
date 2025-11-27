<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Sales Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">
                    Sales Management
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your sales transactions and orders</p>
            </div>
            @if(Auth::user()->role === 'cashier')
            <a href="{{ route('sales.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Sale
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Total Sales Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Sales</p>
                            <p id="total-sales" class="text-3xl sm:text-4xl font-bold text-blue-900 dark:text-blue-100 mt-2">0</p>
                            <p class="text-xs text-blue-600/70 dark:text-blue-400/70 mt-1">All time transactions</p>
                        </div>
                        <div class="p-3 bg-blue-600 dark:bg-blue-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Revenue</p>
                            <p id="total-revenue" class="text-3xl sm:text-4xl font-bold text-green-900 dark:text-green-100 mt-2">Rp 0</p>
                            <p class="text-xs text-green-600/70 dark:text-green-400/70 mt-1">Total earnings</p>
                        </div>
                        <div class="p-3 bg-green-600 dark:bg-green-500 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Sale Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl shadow-sm border border-purple-200 dark:border-purple-800 p-6 hover:shadow-md transition-shadow duration-200 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Average Sale</p>
                            <p id="avg-sale" class="text-3xl sm:text-4xl font-bold text-purple-900 dark:text-purple-100 mt-2">Rp 0</p>
                            <p class="text-xs text-purple-600/70 dark:text-purple-400/70 mt-1">Per transaction</p>
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
                            <input type="text" id="search" placeholder="Search sales..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="xl:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status-filter" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all appearance-none bg-no-repeat pr-10" style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23666%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 0.75rem center; background-size: 1.25rem;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
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
                        <select id="items-per-page" class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm appearance-none bg-no-repeat bg-right pr-8" style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-size: 1rem; background-position: right 0.5rem center;">
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
                                    No
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Transaction Number
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="sales-table" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading indicator -->
                <div id="loading" class="text-center py-12" style="display: none;">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-blue-600"></div>
                    <p class="text-gray-500 dark:text-gray-400 mt-4">Loading sales...</p>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="text-center py-12" style="display: none;">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No sales found</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new sale.</p>
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

    <!-- MODAL EDIT STATUS dengan Tombol Bayar -->
    <div id="editStatusModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Edit Sale Status
                        </h3>
                        <button onclick="closeEditStatusModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Payment Link (Jika Ada) -->
                    <div id="paymentLinkContainer" class="mb-5 hidden">
                        <a id="paymentLink" href="#" target="_blank" 
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Complete Payment
                        </a>
                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                            Click to open payment page
                        </p>
                    </div>
                    
                    <form id="editStatusForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Status
                            </label>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span id="currentStatus" class="text-base font-semibold text-gray-900 dark:text-gray-100"></span>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Status
                            </label>
                            <select name="status" id="statusSelect" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Select Status</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3" placeholder="Add any additional notes..."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="closeEditStatusModal()" 
                                    class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let sales = @json($sales->items() ?? []);
        let filteredSales = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkExpiredPayments();
            loadSales();
        });

        // Check expired payments
        async function checkExpiredPayments() {
            try {
                const response = await fetch('/sales/check-expired', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.updated_count > 0) {
                        console.log(`Auto-cancelled ${result.updated_count} expired payments`);
                        setTimeout(() => window.location.reload(), 1000);
                    }
                }
            } catch (error) {
                console.error('Failed to check expired payments:', error);
            }
        }

        // Load sales data
        function loadSales() {
            filteredSales = [...sales];
            filteredSales.sort((a, b) => new Date(b.sale_date) - new Date(a.sale_date));
            updateStatistics(sales);
            displaySales();
        }

        // Display sales with pagination
        function displaySales() {
            const tbody = document.getElementById('sales-table');
            const emptyState = document.getElementById('empty-state');
            const paginationContainer = document.getElementById('pagination-container');
            
            tbody.innerHTML = '';
            
            if (filteredSales.length === 0) {
                emptyState.style.display = 'block';
                paginationContainer.style.display = 'none';
                document.getElementById('showing-from').textContent = '0';
                document.getElementById('showing-to').textContent = '0';
                document.getElementById('total-entries').textContent = '0';
                return;
            }
            
            emptyState.style.display = 'none';
            paginationContainer.style.display = 'flex';
            
            const totalPages = Math.ceil(filteredSales.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredSales.length);
            const currentSales = filteredSales.slice(startIndex, endIndex);
            
            document.getElementById('showing-from').textContent = startIndex + 1;
            document.getElementById('showing-to').textContent = endIndex;
            document.getElementById('total-entries').textContent = filteredSales.length;
            
            currentSales.forEach((sale, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                
                const rowNumber = startIndex + index + 1;
                
                // Determine if actions should be shown
                const showActions = !['cancelled', 'completed'].includes(sale.status);
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${rowNumber}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            ${sale.transaction_number}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            ${new Date(sale.sale_date).toLocaleDateString('id-ID', { 
                                day: '2-digit', 
                                month: 'short', 
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            ${sale.customer ? sale.customer.customer_name : 'Walk-in Customer'}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            Rp ${Number(sale.total_price).toLocaleString('id-ID')}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(sale.status)}">
                            ${sale.status.charAt(0).toUpperCase() + sale.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View Button -->
                            <a href="/sales/${sale.id}" 
                               class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200"
                               title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            
                            ${showActions ? `
                                <!-- Edit Status Button -->
                                <button onclick="openEditStatusModal(${sale.id}, '${sale.status}')" 
                                        class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-200"
                                        title="Edit Status">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Delete Button -->
                                <button onclick="deleteSale(${sale.id})" 
                                        class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                        title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            ` : ''}
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
                        : 'border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300'
                }`;
                btn.onclick = () => goToPage(i);
                pageNumbers.appendChild(btn);
            }
        }

        // Go to specific page
        function goToPage(page) {
            currentPage = page;
            displaySales();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Get status color class
        function getStatusColor(status) {
            switch(status) {
                case 'pending': 
                    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                case 'completed': 
                    return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                case 'cancelled': 
                    return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                default: 
                    return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            }
        }

        // Update statistics
        function updateStatistics(salesToShow) {
            const totalSales = salesToShow.length;
            const totalRevenue = salesToShow.reduce((sum, s) => sum + Number(s.total_price), 0);
            const avgSale = totalSales > 0 ? totalRevenue / totalSales : 0;
            
            document.getElementById('total-sales').textContent = totalSales;
            document.getElementById('total-revenue').textContent = `Rp ${totalRevenue.toLocaleString('id-ID')}`;
            document.getElementById('avg-sale').textContent = `Rp ${Math.round(avgSale).toLocaleString('id-ID')}`;
        }

        // Filter sales
        function filterSales() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const statusFilter = document.getElementById('status-filter').value;
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;

            filteredSales = sales.filter(sale => {
                const matchesSearch = !searchTerm || 
                    sale.transaction_number.toLowerCase().includes(searchTerm) ||
                    (sale.customer?.customer_name && sale.customer.customer_name.toLowerCase().includes(searchTerm)) ||
                    (sale.notes && sale.notes.toLowerCase().includes(searchTerm));

                const matchesStatus = !statusFilter || sale.status === statusFilter;

                const saleDate = new Date(sale.sale_date);
                const matchesDateFrom = !dateFrom || saleDate >= new Date(dateFrom);
                const matchesDateTo = !dateTo || saleDate <= new Date(dateTo);

                return matchesSearch && matchesStatus && matchesDateFrom && matchesDateTo;
            });

            currentPage = 1;
            updateStatistics(filteredSales);
            displaySales();
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('search').value = '';
            document.getElementById('status-filter').value = '';
            document.getElementById('date-from').value = '';
            document.getElementById('date-to').value = '';
            filterSales();
        }

        // Event listeners for filters
        document.getElementById('search').addEventListener('input', filterSales);
        document.getElementById('status-filter').addEventListener('change', filterSales);
        document.getElementById('date-from').addEventListener('change', filterSales);
        document.getElementById('date-to').addEventListener('change', filterSales);
        document.getElementById('clear-filters').addEventListener('click', clearFilters);

        // Items per page change
        document.getElementById('items-per-page').addEventListener('change', (e) => {
            itemsPerPage = parseInt(e.target.value);
            currentPage = 1;
            displaySales();
        });

        // Pagination buttons
        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage > 1) goToPage(currentPage - 1);
        });

        document.getElementById('next-page').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredSales.length / itemsPerPage);
            if (currentPage < totalPages) goToPage(currentPage + 1);
        });

        // Modal functions
        let currentSaleId = null;
        
        async function openEditStatusModal(saleId, currentStatus) {
            currentSaleId = saleId;
            
            document.getElementById('currentStatus').textContent = currentStatus.toUpperCase();
            
            try {
                // Get sale with payment info
                const paymentResponse = await fetch(`/sales/${saleId}/payment-info`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (paymentResponse.ok) {
                    const paymentData = await paymentResponse.json();
                    
                    // Show payment link if available and status is pending
                    const paymentLinkContainer = document.getElementById('paymentLinkContainer');
                    const paymentLink = document.getElementById('paymentLink');
                    
                    if (paymentData.payment && 
                        paymentData.payment.midtrans_payment_url && 
                        paymentData.payment.status === 'pending' &&
                        paymentData.sale.status === 'pending') {
                        
                        paymentLink.href = paymentData.payment.midtrans_payment_url;
                        paymentLinkContainer.classList.remove('hidden');
                    } else {
                        paymentLinkContainer.classList.add('hidden');
                    }
                }
                
                // Get available status options
                const response = await fetch(`/api/sales/${saleId}/edit-status`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch status options');
                }
                
                const data = await response.json();
                const select = document.getElementById('statusSelect');
                select.innerHTML = '<option value="">Select Status</option>';
                
                Object.entries(data.available_statuses).forEach(([value, label]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    select.appendChild(option);
                });
                
            } catch (error) {
                console.error('Failed to fetch status options:', error);
                showNotification('Failed to load status options', 'error');
                return;
            }
            
            document.getElementById('editStatusForm').action = `/sales/${saleId}/status`;
            document.getElementById('editStatusModal').classList.remove('hidden');
        }
        
        function closeEditStatusModal() {
            document.getElementById('editStatusModal').classList.add('hidden');
            document.getElementById('editStatusForm').reset();
            document.getElementById('paymentLinkContainer').classList.add('hidden');
            currentSaleId = null;
        }
        
        // Handle form submission
        document.getElementById('editStatusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            if (!data.status) {
                showNotification('Please select a status', 'error');
                return;
            }
            
            try {
                const response = await fetch(this.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeEditStatusModal();
                    showNotification(result.message || 'Status updated successfully', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update status', 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred while updating status', 'error');
            }
        });
        
        // Close modal when clicking outside
        document.getElementById('editStatusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditStatusModal();
            }
        });

        // Delete sale
        async function deleteSale(id) {
            if (!confirm('Are you sure you want to delete this sale?')) return;

            try {
                const response = await fetch(`/sales/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();
                
                if (response.ok) {
                    showNotification('Sale deleted successfully', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Error deleting sale', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error deleting sale', 'error');
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
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1000);
        });

        // Fallback: Hide loader after 5 seconds max
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
        #pageLoader.hide {
            opacity: 0;
            transition: opacity 0.8s ease-out;
        }
    </style>
</x-app-layout>