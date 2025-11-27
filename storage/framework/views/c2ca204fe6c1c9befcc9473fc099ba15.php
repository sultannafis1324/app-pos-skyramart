<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <meta name="user-role" content="<?php echo e(auth()->user()->role ?? 'cashier'); ?>">

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span id="current-time"></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today's Revenue -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-white/80">Today's Revenue</p>
                                <p class="text-2xl font-bold text-white" id="today-revenue">Loading...</p>
                                <p class="text-xs text-white/60 mt-1" id="revenue-growth">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Sales -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-white/80">Today's Sales</p>
                                <p class="text-2xl font-bold text-white" id="today-sales">Loading...</p>
                                <p class="text-xs text-white/60 mt-1" id="sales-growth">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-white/80">Low Stock</p>
                                <p class="text-2xl font-bold text-white" id="low-stock">Loading...</p>
                                <p class="text-xs text-white/60 mt-1">Items need restock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-white/80">Total Customers</p>
                                <p class="text-2xl font-bold text-white" id="total-customers">Loading...</p>
                                <p class="text-xs text-white/60 mt-1" id="active-customers">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Overview</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg chart-filter active" data-days="7">7 Days</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-lg chart-filter" data-days="30">30 Days</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-lg chart-filter" data-days="90">90 Days</button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="p-6 max-h-80 overflow-y-auto">
                        <div class="space-y-4" id="recent-activity">
                            <div class="flex items-center space-x-3 animate-pulse">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="h-3 bg-gray-300 rounded w-3/4"></div>
                                    <div class="h-2 bg-gray-200 rounded w-1/2 mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Products</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4" id="top-products">
                            <div class="animate-pulse">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-lg"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2 mt-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions - Role Based -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <!-- Cashier Actions -->
                        <div class="grid grid-cols-2 gap-4" id="cashier-actions" style="display: none;">
                            <button onclick="showScanPopup()" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors cursor-pointer">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Scan</p>
                                    <p class="text-xs text-gray-500">Scan barcode product</p>
                                </div>
                            </button>

                            <a href="<?php echo e(route('sales.create')); ?>" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Create Sales</p>
                                    <p class="text-xs text-gray-500">New transaction</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('products.index')); ?>" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Products</p>
                                    <p class="text-xs text-gray-500">View inventory</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('customers.create')); ?>" class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Create Customer</p>
                                    <p class="text-xs text-gray-500">Add new customer</p>
                                </div>
                            </a>
                        </div>

                        <!-- Admin Actions -->
                        <div class="grid grid-cols-2 gap-4" id="admin-actions" style="display: none;">
                            <a href="<?php echo e(route('categories.create')); ?>" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Add Category</p>
                                    <p class="text-xs text-gray-500">Manage categories</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('products.create')); ?>" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Add Product</p>
                                    <p class="text-xs text-gray-500">Manage inventory</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('suppliers.create')); ?>" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Add Supplier</p>
                                    <p class="text-xs text-gray-500">Supplier database</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('purchases.create')); ?>" class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m.6 0L6 15m0 0L5 19h14m-9-4h9m-9 0V9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Purchase</p>
                                    <p class="text-xs text-gray-500">Stock purchase</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scan Popup Modal -->
    <div id="scanPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">Scan Feature</h3>
                <p class="text-center text-gray-600 dark:text-gray-400 mb-6">System in progress</p>
                <div class="flex items-center justify-center space-x-2 mb-6">
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
                <button onclick="closeScanPopup()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Initialize variables
    let salesChart;
    const API_BASE = window.location.origin;

    // Format currency helper
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Update current time
    function updateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-time').textContent = 
            now.toLocaleDateString('id-ID', options);
    }

    // Show/Hide Quick Actions based on role
    function initQuickActions() {
        const userRole = document.querySelector('meta[name="user-role"]')?.content || 'cashier';
        const cashierActions = document.getElementById('cashier-actions');
        const adminActions = document.getElementById('admin-actions');

        if (userRole === 'admin' || userRole === 'administrator') {
            if (cashierActions) cashierActions.style.display = 'none';
            if (adminActions) adminActions.style.display = 'grid';
        } else {
            if (cashierActions) cashierActions.style.display = 'grid';
            if (adminActions) adminActions.style.display = 'none';
        }
    }

    // Fetch and update dashboard stats
    async function loadDashboardStats() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/stats`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to fetch stats');
            }

            updateStatsUI(data);
            updateRecentActivity(data.recent_activity);
            updateTopProducts(data.top_products);

        } catch (error) {
            console.error('Failed to load dashboard stats:', error);
            showFallbackData();
        }
    }

    // Update stats UI
    function updateStatsUI(data) {
        const { stats, period, is_admin, comparison_label } = data;

        // Update revenue
        const revenueEl = document.getElementById('today-revenue');
        if (revenueEl) revenueEl.textContent = formatCurrency(stats.revenue);

        // Update revenue label
        const revenueLabelEl = document.querySelector('.text-sm.font-medium.text-white\\/80');
        if (revenueLabelEl) {
            revenueLabelEl.textContent = is_admin ? "This Month's Revenue" : "Today's Revenue";
        }

        // Update revenue growth
        const revenueGrowthEl = document.getElementById('revenue-growth');
        if (revenueGrowthEl) {
            const sign = stats.revenue_growth >= 0 ? '+' : '';
            revenueGrowthEl.textContent = `${sign}${stats.revenue_growth}% from ${comparison_label}`;
        }

        // Update sales count
        const salesEl = document.getElementById('today-sales');
        if (salesEl) salesEl.textContent = stats.sales_count;

        // Update sales label
        const salesLabelEls = document.querySelectorAll('.text-sm.font-medium.text-white\\/80');
        if (salesLabelEls[1]) {
            salesLabelEls[1].textContent = is_admin ? "This Month's Sales" : "Today's Sales";
        }

        // Update sales growth
        const salesGrowthEl = document.getElementById('sales-growth');
        if (salesGrowthEl) {
            const sign = stats.sales_growth >= 0 ? '+' : '';
            salesGrowthEl.textContent = `${sign}${stats.sales_growth}% from ${comparison_label}`;
        }

        // Update low stock
        const lowStockEl = document.getElementById('low-stock');
        if (lowStockEl) lowStockEl.textContent = stats.low_stock;

        // Update customers
        const totalCustomersEl = document.getElementById('total-customers');
        if (totalCustomersEl) totalCustomersEl.textContent = stats.total_customers;

        const activeCustomersEl = document.getElementById('active-customers');
        if (activeCustomersEl) {
            activeCustomersEl.textContent = `${stats.active_customers} active`;
        }
    }

    // Update recent activity
    function updateRecentActivity(activities) {
        const container = document.getElementById('recent-activity');
        if (!container) return;

        if (!activities || activities.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No recent activity</p>';
            return;
        }

        const html = activities.map(activity => `
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-${activity.status_color}-500 rounded-full flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900 dark:text-white truncate">
                        ${activity.transaction_number} - ${activity.customer_name}
                    </p>
                    <p class="text-xs text-gray-500">
                        ${formatCurrency(activity.total_price)} • ${activity.time}
                    </p>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    // Update top products (Max 3 products)
    function updateTopProducts(products) {
        const container = document.getElementById('top-products');
        if (!container) return;

        if (!products || products.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No sales data available</p>
                </div>
            `;
            return;
        }

        // Badge colors for top 3
        const badgeColors = [
            'from-yellow-400 to-yellow-500', // Gold for #1
            'from-gray-300 to-gray-400',     // Silver for #2
            'from-orange-400 to-orange-500'  // Bronze for #3
        ];

        const html = products.slice(0, 3).map((product, index) => `
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-all">
                <div class="w-12 h-12 bg-gradient-to-br ${badgeColors[index]} rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="text-white font-bold text-lg">${product.rank}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate mb-1">
                        ${product.product_name}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        <span class="font-medium">${product.total_quantity} sold</span> • ${formatCurrency(product.total_revenue)}
                    </p>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    // Show fallback data on error
    function showFallbackData() {
        console.warn('Showing fallback data due to API errors');

        const elements = {
            'today-revenue': formatCurrency(0),
            'today-sales': '0',
            'low-stock': '0',
            'total-customers': '0',
            'active-customers': '0 active',
            'revenue-growth': 'No data',
            'sales-growth': 'No data'
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        });

        const recentActivityEl = document.getElementById('recent-activity');
        if (recentActivityEl) {
            recentActivityEl.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Unable to load recent activity</p>';
        }

        const topProductsEl = document.getElementById('top-products');
        if (topProductsEl) {
            topProductsEl.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Unable to load top products</p>';
        }
    }

    // Initialize sales chart
    async function initSalesChart(days = 7) {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/chart?days=${days}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error('Failed to fetch chart data');
            }

            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            // Destroy existing chart if exists
            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.revenues,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Failed to load chart:', error);
        }
    }

    // Chart filter functionality
    function initChartFilters() {
        document.querySelectorAll('.chart-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.chart-filter').forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                });

                // Add active class to clicked button
                this.classList.add('active', 'bg-blue-100', 'text-blue-700');
                this.classList.remove('bg-gray-100', 'text-gray-700');

                // Update chart
                const days = parseInt(this.dataset.days);
                initSalesChart(days);
            });
        });
    }

    // Scan popup functions
    function showScanPopup() {
        document.getElementById('scanPopup').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeScanPopup() {
        document.getElementById('scanPopup').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close popup on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeScanPopup();
        }
    });

    // Initialize everything on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard initializing...');

        // Initialize UI
        updateTime();
        setInterval(updateTime, 60000); // Update time every minute

        initQuickActions();
        initChartFilters();

        // Load data
        loadDashboardStats();
        initSalesChart(7);

        // Auto-refresh every 5 minutes
        setInterval(loadDashboardStats, 300000);

        console.log('Dashboard initialization complete');
    });
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\app-pos-skyramart\resources\views/dashboard.blade.php ENDPATH**/ ?>