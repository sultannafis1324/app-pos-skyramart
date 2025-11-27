<x-app-layout>
    <x-slot name="header">
        <meta name="user-role" content="{{ Auth::user()->role }}">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-box-open mr-2"></i>{{ __('Product Management') }}
            </h2>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('products.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Add Product
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-sky-100 dark:bg-sky-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filter Section --}}
            <div class="glass-effect rounded-2xl p-4 sm:p-6 mb-4 sm:mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div class="lg:col-span-2">
                        <input type="text" id="search" placeholder="Search Products..." class="input-field">
                    </div>
                    <div>
                        <select id="category-filter" class="input-field">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                    <div>
                        <select id="stock-filter" class="input-field">
                            <option value="">All Status</option>
                            <option value="low">Low Stock</option>
                            <option value="out">Out Of Stock</option>
                            <option value="expired">Expired</option>
                            <option value="near-expiry">Near Expiry</option>
                        </select>
                    </div>
                    <div>
                        <button type="button" id="filter-button" onclick="toggleFilter()" class="w-full btn-primary justify-center">
                            <i class="fas fa-search mr-2"></i><span id="button-text">Search</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4 sm:mb-8">
    <div class="glass-effect rounded-xl p-4 text-center hover:bg-blue-50 transition-colors cursor-pointer" onclick="filterByStatus('all')">
        <div class="text-2xl sm:text-3xl font-bold text-blue-600 mb-1" id="total-products">0</div>
        <div class="text-gray-600 text-sm">Total Products</div>
    </div>
    <div class="glass-effect rounded-xl p-4 text-center hover:bg-orange-50 transition-colors cursor-pointer" onclick="filterByStatus('low')">
        <div class="text-2xl sm:text-3xl font-bold text-orange-600 mb-1" id="low-stock-products">0</div>
        <div class="text-gray-600 text-sm">Low Stock</div>
    </div>
    <div class="glass-effect rounded-xl p-4 text-center hover:bg-red-50 transition-colors cursor-pointer" onclick="filterByStatus('out')">
        <div class="text-2xl sm:text-3xl font-bold text-red-600 mb-1" id="out-stock-products">0</div>
        <div class="text-gray-600 text-sm">Out Of Stock</div>
    </div>
    <!-- ✅ TAMBAHKAN: Expired card -->
    <div class="glass-effect rounded-xl p-4 text-center hover:bg-red-100 transition-colors cursor-pointer animate-pulse" onclick="filterByStatus('expired')">
        <div class="text-2xl sm:text-3xl font-bold text-red-700 mb-1" id="expired-products">0</div>
        <div class="text-gray-600 text-sm">Expired</div>
    </div>
    <!-- ✅ TAMBAHKAN: Near expiry card -->
    <div class="glass-effect rounded-xl p-4 text-center hover:bg-amber-50 transition-colors cursor-pointer" onclick="filterByStatus('near-expiry')">
        <div class="text-2xl sm:text-3xl font-bold text-amber-600 mb-1" id="near-expiry-products">0</div>
        <div class="text-gray-600 text-sm">Near Expiry</div>
    </div>
</div>

            {{-- Products Grid --}}
            <div class="glass-effect rounded-2xl p-4 sm:p-8">
                <div class="mb-4 sm:mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2" id="products-title">
                        Product List
                    </h3>
                    <p class="text-gray-600 text-sm sm:text-base" id="products-subtitle">
                        Manage all products in your store.
                    </p>
                </div>

                {{-- Loading Indicator --}}
                <div id="loading" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    <p class="mt-2 text-gray-600">Loading products...</p>
                </div>

                {{-- Products Container --}}
                <div id="products-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6" style="display: none;">
                    {{-- Products will be loaded here via JavaScript --}}
                </div>

                {{-- Empty State --}}
                <div id="empty-state" class="text-center py-8 sm:py-16" style="display: none;">
                    <i class="fas fa-box-open text-gray-300 text-5xl sm:text-6xl mb-4"></i>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600 text-sm sm:text-base mb-4 sm:mb-6">There are no products available yet.</p>
                    <a href="{{ route('products.create') }}" class="btn-primary inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add Your First Product
                    </a>
                </div>

                {{-- No Low Stock Message --}}
                <div id="no-low-stock-message" class="text-center py-8 sm:py-16" style="display: none;">
                    <i class="fas fa-check-circle text-green-400 text-5xl sm:text-6xl mb-4"></i>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No Low Stock Products</h3>
                    <p class="text-gray-600 text-sm sm:text-base">All products have sufficient stock levels.</p>
                </div>

                {{-- No Out of Stock Message --}}
                <div id="no-out-stock-message" class="text-center py-8 sm:py-16" style="display: none;">
                    <i class="fas fa-check-circle text-green-400 text-5xl sm:text-6xl mb-4"></i>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No Out of Stock Products</h3>
                    <p class="text-gray-600 text-sm sm:text-base">All products are currently in stock.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
    .glass-effect { 
        background: rgba(255, 255, 255, 0.85); 
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }
    
    .btn-primary, .btn-secondary, .btn-danger { 
        border: none; 
        color: white; 
        padding: 0.625rem 1.25rem; 
        border-radius: 0.5rem; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        font-weight: 600; 
        font-size: 0.875rem; 
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-primary { 
        background: linear-gradient(135deg, #667eea 0%, #4400ff 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary { 
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }
    
    .btn-danger { 
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .btn-primary:hover, .btn-secondary:hover, .btn-danger:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
        color: white; 
        text-decoration: none; 
    }
    
    .btn-primary:active, .btn-secondary:active, .btn-danger:active {
        transform: translateY(0);
    }
    
    .btn-primary:disabled, .btn-secondary:disabled, .btn-danger:disabled { 
        opacity: 0.5; 
        cursor: not-allowed; 
        transform: none; 
        box-shadow: none; 
    }
    
    .input-field { 
        width: 100%; 
        padding: 0.75rem 1rem; 
        border: 2px solid #e5e7eb; 
        border-radius: 0.625rem; 
        background: rgba(255, 255, 255, 0.95); 
        transition: all 0.3s ease; 
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
    }
    
    .input-field:focus { 
        outline: none; 
        border-color: #667eea; 
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1); 
    }
    
    .input-field::placeholder {
        color: #9ca3af;
        font-weight: 400;
    }
    
    /* Reset Button Styling - Enhanced Version */
.btn-reset { 
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
    border: none;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    font-size: 0.875rem;
    text-align: center;
    cursor: pointer;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}

.btn-reset::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}

.btn-reset:hover::before {
    left: 0;
}

.btn-reset:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(249, 115, 22, 0.5);
    color: white;
    text-decoration: none;
}

.btn-reset:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
}

.btn-reset i,
.btn-reset span {
    position: relative;
    z-index: 1;
}

.btn-reset:hover i {
    animation: rotate-reset 0.6s ease-in-out;
}

@keyframes rotate-reset {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments for reset button */
@media (max-width: 640px) {
    .btn-reset {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }

    .btn-reset i {
        margin-right: 0.25rem;
        font-size: 0.7rem;
    }
}

/* Alternative Red Version (jika ingin warna merah seperti button delete) */
.btn-reset-red { 
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    border: none;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    font-size: 0.875rem;
    text-align: center;
    cursor: pointer;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}

.btn-reset-red::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}

.btn-reset-red:hover::before {
    left: 0;
}

.btn-reset-red:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.5);
    color: white;
    text-decoration: none;
}

.btn-reset-red:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.btn-reset-red i,
.btn-reset-red span {
    position: relative;
    z-index: 1;
}

.btn-reset-red:hover i {
    animation: rotate-reset 0.6s ease-in-out;
}

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Filter Section Improvements */
    .filter-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    /* Statistics Card Hover Effects */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .stat-card:active {
        transform: translateY(-2px) scale(1.01);
    }

    /* Product Card Improvements */
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    /* Clickable card area */
    .card-clickable {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .card-clickable:hover {
        background-color: rgba(102, 126, 234, 0.02);
    }

    .card-clickable:active {
        background-color: rgba(102, 126, 234, 0.05);
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        .glass-effect {
            padding: 0.875rem !important;
            margin-bottom: 1rem !important;
        }

        .filter-container {
            gap: 0.5rem;
        }
        
        #products-container {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 0.625rem !important;
        }
        
        .btn-primary, .btn-secondary, .btn-danger, .btn-reset {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-primary i, .btn-secondary i, .btn-danger i, .btn-reset i {
            margin-right: 0.25rem;
            font-size: 0.7rem;
        }

        .input-field {
            padding: 0.625rem 0.875rem;
            font-size: 0.8125rem;
        }

        .stat-card {
            padding: 0.875rem !important;
        }

        .stat-card .text-2xl,
        .stat-card .text-3xl {
            font-size: 1.5rem !important;
        }

        .product-card {
            border-radius: 0.75rem;
        }

        .product-card h4 {
            font-size: 0.8125rem !important;
            line-height: 1.3;
            min-height: 2.6rem;
        }

        .product-card .text-sm {
            font-size: 0.7rem !important;
        }

        .product-card .text-xs {
            font-size: 0.65rem !important;
        }

        /* Action buttons di mobile */
        .product-card .btn-secondary,
        .product-card .btn-danger {
            padding: 0.5rem 0.5rem !important;
            font-size: 0.7rem !important;
        }

        .product-card .btn-secondary span,
        .product-card .btn-danger span {
            display: inline !important;
        }
    }

    @media (min-width: 641px) and (max-width: 768px) {
        .filter-container {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .filter-search {
            grid-column: 1 / -1;
        }

        #products-container {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1rem !important;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .filter-container {
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 0.875rem;
        }

        #products-container {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 1.25rem !important;
        }
    }

    @media (min-width: 1025px) {
        .filter-container {
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
        }

        #products-container {
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 1.5rem !important;
        }
    }

    /* Line clamp utility */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Icon size adjustments */
    .fas {
        font-size: inherit;
    }

    /* =======================================
   PROMO BADGE STYLING - Tambahkan ke <style>
   ======================================= */

/* Promo Badge Container Animation */
.promo-badge-wrapper {
    animation: slideInFromLeft 0.6s ease-out;
}

@keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Promo Badge with Shine Effect */
.promo-badge {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(8px);
}

.promo-badge:hover {
    transform: scale(1.05) translateY(-2px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
}

/* Shine Animation Effect */
.promo-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255, 255, 255, 0.4) 50%,
        transparent 70%
    );
    animation: shine 3s infinite;
    pointer-events: none;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
    }
}

/* Pulse Animation untuk Badge Icons */
.promo-badge svg {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

/* Badge Text Styling */
.promo-badge span {
    position: relative;
    z-index: 1;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.3px;
}

/* Responsive Badge Sizing */
@media (max-width: 640px) {
    .promo-badge {
        padding: 0.375rem 0.625rem !important;
        font-size: 0.65rem !important;
        gap: 0.25rem !important;
    }
    
    .promo-badge svg {
        width: 0.625rem !important;
        height: 0.625rem !important;
    }
    
    .promo-badge span {
        max-width: 80px !important;
    }
}

/* Enhanced Product Card Hover Effect */
.product-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 0.75rem;
    padding: 2px;
    background: linear-gradient(135deg, transparent, rgba(102, 126, 234, 0.3), transparent);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.product-card:hover::before {
    opacity: 1;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Price Display Enhancement */
.product-card .bg-red-100 {
    animation: fadeInScale 0.3s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Stock Badge Enhancement */
.product-card .bg-green-100,
.product-card .bg-orange-100,
.product-card .bg-red-100 {
    transition: all 0.3s ease;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Category Badge Improvement */
.product-card .bg-gradient-to-r {
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-weight: 700;
}

/* Enhanced Card Image Container */
.product-card .aspect-square {
    overflow: hidden;
    position: relative;
}

.product-card .aspect-square img {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover .aspect-square img {
    transform: scale(1.08);
}

/* Promo Info Badge Styling */
.bg-green-50,
.bg-gradient-to-r.from-purple-500 {
    animation: bounceIn 0.5s ease-out;
    transition: all 0.3s ease;
}

.bg-green-50:hover,
.bg-gradient-to-r.from-purple-500:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Gift Icon Animation */
@keyframes gift-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.bg-gradient-to-r.from-purple-500::before {
    display: inline-block;
    animation: gift-bounce 1s ease-in-out infinite;
}

/* Loading Shimmer Effect untuk gambar */
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.product-card img[src*="placeholder"] {
    background: linear-gradient(
        90deg,
        #f0f0f0 0%,
        #e0e0e0 50%,
        #f0f0f0 100%
    );
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}

/* Smooth color transitions */
* {
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Enhanced button hover states */
.btn-secondary:hover,
.btn-danger:hover {
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
}

/* Z-index management for overlapping elements */
.product-card .absolute.top-2.left-2,
.product-card .absolute.top-2.right-2 {
    z-index: 10;
}

.promo-badge-wrapper {
    z-index: 20;
}

/* FIXED: Category badge positioning to avoid overlap with Click to View */
.product-card .category-badge {
    position: absolute;
    bottom: 12px;
    left: 8px;
    z-index: 15;
    max-width: calc(100% - 16px);
}

.product-card .click-to-view-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 40%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 45px; /* Memberi ruang untuk category badge */
    z-index: 5;
}

.product-card:hover .click-to-view-overlay {
    opacity: 1;
}

.product-card .click-to-view-button {
    transform: translateY(10px);
    transition: transform 0.3s ease;
}

.product-card:hover .click-to-view-button {
    transform: translateY(0);
}

/* Expiry badge animations */
@keyframes pulse-red {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.animate-pulse-red {
    animation: pulse-red 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Expired product styling */
.product-card.expired {
    border: 2px solid #ef4444;
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
}

/* Near expiry warning glow */
.near-expiry-glow {
    box-shadow: 0 0 15px rgba(251, 146, 60, 0.4);
}

/* Expiry countdown animation */
@keyframes countdown {
    0% {
        transform: scale(1);
        color: #f97316;
    }
    50% {
        transform: scale(1.1);
        color: #ea580c;
    }
    100% {
        transform: scale(1);
        color: #f97316;
    }
}

.expiry-countdown {
    animation: countdown 1.5s ease-in-out infinite;
}
</style>

<script>
    let products = [];
    let filteredProducts = [];
    let currentFilter = 'all';
    let searchTimeout = null;

    // Load products 
    async function loadProducts() {
        const loadingElement = document.getElementById('loading');
        const productsContainer = document.getElementById('products-container');
        const emptyState = document.getElementById('empty-state');
        const noLowStockMessage = document.getElementById('no-low-stock-message');
        const noOutStockMessage = document.getElementById('no-out-stock-message');
        
        loadingElement.style.display = 'block';
        productsContainer.style.display = 'none';
        emptyState.style.display = 'none';
        noLowStockMessage.style.display = 'none';
        noOutStockMessage.style.display = 'none';
        
        try {
            const response = await fetch('/api/products', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            products = data;
            filteredProducts = data;
            
            updateStatistics();
            displayProducts(products);
            loadCategories();
            
        } catch (error) {
            console.error('Error loading products:', error);
            productsContainer.innerHTML = `
                <div class="col-span-full text-center text-red-600 py-8">
                    Error loading products: ${error.message}
                    <br>
                    <button onclick="loadProducts()" class="mt-4 btn-primary">
                        Retry
                    </button>
                </div>
            `;
            productsContainer.style.display = 'block';
        } finally {
            loadingElement.style.display = 'none';
        }
    }

    function updateStatistics() {
    const total = products.length;
    const lowStock = products.filter(p => p.stock > 0 && p.stock <= (p.minimum_stock || 10)).length;
    const outStock = products.filter(p => p.stock <= 0).length;
    
    // ✅ UPDATED: Calculate expired using effective expiry date
    const expired = products.filter(p => {
        // Check if product is marked as expired (already checked in backend)
        return p.is_expired === true;
    }).length;
    
    // ✅ UPDATED: Calculate near expiry using effective expiry date
    const nearExpiry = products.filter(p => {
        // Check if product is marked as near expiry (already checked in backend)
        return p.is_near_expiry === true && !p.is_expired;
    }).length;
    
    document.getElementById('total-products').textContent = total;
    document.getElementById('low-stock-products').textContent = lowStock;
    document.getElementById('out-stock-products').textContent = outStock;
    document.getElementById('expired-products').textContent = expired;
    document.getElementById('near-expiry-products').textContent = nearExpiry;
}

function applyFilters() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const categoryId = document.getElementById('category-filter').value;
    const stockFilter = document.getElementById('stock-filter').value;
    
    let filtered = products;
    
    // Search filter
    if (searchTerm) {
        filtered = filtered.filter(product => 
            (product.product_name || product.name || '').toLowerCase().includes(searchTerm) ||
            (product.product_code || '').toLowerCase().includes(searchTerm) ||
            (product.description || '').toLowerCase().includes(searchTerm)
        );
    }
    
    // Category filter
    if (categoryId) {
        filtered = filtered.filter(product => product.category_id == categoryId);
    }
    
    // ✅ UPDATED: Stock filter with updated expiry checks
    if (stockFilter === 'low') {
        filtered = filtered.filter(product => product.stock > 0 && product.stock <= (product.minimum_stock || 10));
    } else if (stockFilter === 'out') {
        filtered = filtered.filter(product => product.stock <= 0);
    } else if (stockFilter === 'expired') {
        // Filter using is_expired flag (already calculated in backend)
        filtered = filtered.filter(product => product.is_expired === true);
    } else if (stockFilter === 'near-expiry') {
        // Filter using is_near_expiry flag (already calculated in backend)
        filtered = filtered.filter(product => product.is_near_expiry === true && !product.is_expired);
    }
    
    filteredProducts = filtered;
    displayProducts(filtered);
    updateTitle(stockFilter);
}

    // Load categories for filter
    async function loadCategories() {
        try {
            const response = await fetch('/api/categories', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const categories = await response.json();
                const select = document.getElementById('category-filter');
                
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id || category.category_id;
                    option.textContent = category.name;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

function displayProducts(productsToShow) {
    const container = document.getElementById('products-container');
    const emptyState = document.getElementById('empty-state');
    const noLowStockMessage = document.getElementById('no-low-stock-message');
    const noOutStockMessage = document.getElementById('no-out-stock-message');
    const stockFilter = document.getElementById('stock-filter').value;
    
    // Hide all states first
    container.style.display = 'none';
    emptyState.style.display = 'none';
    noLowStockMessage.style.display = 'none';
    noOutStockMessage.style.display = 'none';
    
    if (productsToShow.length === 0) {
        if (stockFilter === 'low') {
            noLowStockMessage.style.display = 'block';
        } else if (stockFilter === 'out') {
            noOutStockMessage.style.display = 'block';
        } else {
            emptyState.style.display = 'block';
        }
        return;
    }
    
    container.style.display = 'grid';
    container.innerHTML = '';

    productsToShow.forEach(product => {
        const stockStatus = product.stock <= 0 ? 'out' : (product.stock <= (product.minimum_stock || 10) ? 'low' : 'good');
        const isOutOfStock = product.stock <= 0;
        const isAdmin = document.querySelector('meta[name="user-role"]')?.content === 'admin';
        
        // ✅ TAMBAHKAN: Check expiry status
        const isExpired = product.has_expiry && product.expiry_date && new Date(product.expiry_date) < new Date();
        const isNearExpiry = product.has_expiry && product.expiry_date && !isExpired && 
                           Math.ceil((new Date(product.expiry_date) - new Date()) / (1000 * 60 * 60 * 24)) <= 30;
        
        const card = document.createElement('div');
        card.className = `product-card bg-white rounded-xl shadow-md overflow-hidden flex flex-col ${isOutOfStock || isExpired ? 'opacity-75' : ''} cursor-pointer relative`;
        
        // Promo badges - posisi atas, tidak tumpang tindih dengan category
        let promoBadgeHtml = '';
        if (product.has_active_promotion) {
            promoBadgeHtml = '<div class="absolute top-2 left-2 right-2 z-20 flex flex-col gap-1.5">';
            
            // Price promotion badge
            if (product.price_promotion) {
                const badgeColor = product.price_promotion.badge_color || '#FF6B6B';
                const badgeText = product.price_promotion.badge_text || 'DISKON';
                
                const r = parseInt(badgeColor.slice(1, 3), 16);
                const g = parseInt(badgeColor.slice(3, 5), 16);
                const b = parseInt(badgeColor.slice(5, 7), 16);
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                const textColor = brightness > 155 ? '#000000' : '#FFFFFF';
                
                promoBadgeHtml += `
                    <div class="inline-block self-start max-w-[85%]">
                        <div class="promo-badge relative inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-bold text-xs shadow-lg"
                             style="background: linear-gradient(135deg, ${badgeColor} 0%, ${adjustColor(badgeColor, -20)} 100%); color: ${textColor};"
                             title="${badgeText}">
                            <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">${badgeText}</span>
                            <div class="promo-shine"></div>
                        </div>
                    </div>
                `;
            }
            
            // Quantity promotion badge
            if (product.quantity_promotion) {
                const badgeColor = product.quantity_promotion.badge_color || '#10B981';
                const badgeText = product.quantity_promotion.badge_text || 'PROMO';
                
                const r = parseInt(badgeColor.slice(1, 3), 16);
                const g = parseInt(badgeColor.slice(3, 5), 16);
                const b = parseInt(badgeColor.slice(5, 7), 16);
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                const textColor = brightness > 155 ? '#000000' : '#FFFFFF';
                
                promoBadgeHtml += `
                    <div class="inline-block self-start max-w-[85%]">
                        <div class="promo-badge relative inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-bold text-xs shadow-lg"
                             style="background: linear-gradient(135deg, ${badgeColor} 0%, ${adjustColor(badgeColor, -20)} 100%); color: ${textColor};"
                             title="${badgeText}">
                            <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                            <span class="truncate">${badgeText}</span>
                            <div class="promo-shine"></div>
                        </div>
                    </div>
                `;
            }
            
            promoBadgeHtml += '</div>';
        }
        
        // ✅ TAMBAHKAN: Expiry badge (hanya jika ada promo, taruh setelah promo badges)
        let expiryBadgeHtml = '';
        if (!product.has_active_promotion && product.has_expiry && product.expiry_date) {
            expiryBadgeHtml = '<div class="absolute top-2 left-2 z-20">';
            
            if (isExpired) {
                expiryBadgeHtml += `
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-bold text-xs shadow-lg bg-gradient-to-r from-red-600 to-red-700 text-white animate-pulse">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>EXPIRED</span>
                    </span>
                `;
            } else if (isNearExpiry) {
                const daysLeft = Math.ceil((new Date(product.expiry_date) - new Date()) / (1000 * 60 * 60 * 24));
                expiryBadgeHtml += `
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-bold text-xs shadow-lg bg-gradient-to-r from-orange-500 to-orange-600 text-white animate-pulse">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>${daysLeft}d left</span>
                    </span>
                `;
            }
            
            expiryBadgeHtml += '</div>';
        }
        
        // Price display dengan icon, tanpa emoji
        let priceHtml = '';
        if (product.has_active_promotion) {
            const discountPercent = Math.round(((parseFloat(product.original_price_for_display || product.selling_price) - parseFloat(product.discounted_price)) / parseFloat(product.original_price_for_display || product.selling_price)) * 100);
            
            priceHtml = `
                <div class="flex flex-col space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 line-through">
                            Rp ${parseFloat(product.original_price_for_display || product.selling_price).toLocaleString('id-ID')}
                        </span>
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded">
                            -${discountPercent}%
                        </span>
                    </div>
                    <span class="text-base sm:text-lg font-bold text-red-600">
                        Rp ${parseFloat(product.discounted_price).toLocaleString('id-ID')}
                    </span>
                    <span class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded inline-flex items-center gap-1 self-start">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Hemat Rp ${parseFloat(product.discount_amount).toLocaleString('id-ID')}</span>
                    </span>
            `;
            
            // Quantity promo info dengan icon
            if (product.quantity_promotion) {
                priceHtml += `
                    <span class="text-xs font-bold bg-gradient-to-r from-purple-500 to-pink-500 text-white px-2 py-1 rounded inline-flex items-center gap-1 self-start">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                        </svg>
                        <span>Beli ${product.quantity_promotion.buy_quantity} Gratis ${product.quantity_promotion.get_quantity}</span>
                    </span>
                `;
            }
            
            priceHtml += '</div>';
        } else {
            priceHtml = `
                <span class="text-base sm:text-lg font-bold text-blue-600">
                    Rp ${parseFloat(product.selling_price).toLocaleString('id-ID')}
                </span>
            `;
        }
        
        // ✅ TAMBAHKAN: Expiry info dalam card content (jika tidak ada promo badge)
        let expiryInfoHtml = '';
        if (product.has_expiry && product.expiry_date && !product.has_active_promotion) {
            if (isExpired) {
                expiryInfoHtml = `
                    <div class="mt-2 text-xs text-red-600 font-semibold bg-red-50 px-2 py-1 rounded inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Expired: ${new Date(product.expiry_date).toLocaleDateString('id-ID')}</span>
                    </div>
                `;
            } else if (isNearExpiry) {
                const daysLeft = Math.ceil((new Date(product.expiry_date) - new Date()) / (1000 * 60 * 60 * 24));
                expiryInfoHtml = `
                    <div class="mt-2 text-xs text-orange-600 font-semibold bg-orange-50 px-2 py-1 rounded inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Expires in ${daysLeft} days</span>
                    </div>
                `;
            }
        }
        
        // Card content - FIXED: Menggunakan struktur yang diperbaiki untuk menghindari tabrakan
        const cardContent = `
            ${promoBadgeHtml}
            ${expiryBadgeHtml}
            <div class="card-clickable relative" onclick="window.location.href='/products/${product.id}/show'">
                <div class="relative aspect-square bg-gray-200">
                    <img src="${product.image ? '/storage/' + product.image : '/images/placeholder.jpg'}" 
                         alt="${product.product_name || product.name}" 
                         class="w-full h-full object-contain ${isOutOfStock || isExpired ? 'grayscale' : ''}"
                         loading="lazy">
                    
                    <!-- Category badge - posisi bawah kiri -->
                    ${product.category ? `
                        <div class="category-badge">
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full text-white bg-gradient-to-r from-blue-500 to-blue-600 shadow-lg line-clamp-1 max-w-full">
                                ${product.category.name}
                            </span>
                        </div>
                    ` : ''}
                    
                    <!-- Stock status - posisi kanan atas -->
                    <div class="absolute top-2 right-2 z-10">
                        ${isExpired ? '<span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full text-white bg-gradient-to-r from-red-700 to-red-800 shadow-lg">Expired</span>' : ''}
                        ${!isExpired && stockStatus === 'out' ? '<span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full text-white bg-gradient-to-r from-red-600 to-red-700 shadow-lg animate-pulse">Out</span>' : ''}
                        ${!isExpired && stockStatus === 'low' ? '<span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full text-white bg-gradient-to-r from-orange-500 to-orange-600 shadow-lg animate-pulse">Low</span>' : ''}
                    </div>
                    
                    <!-- Click to view overlay -->
                    <div class="click-to-view-overlay">
                        <span class="click-to-view-button text-white text-xs sm:text-sm font-semibold flex items-center gap-1 bg-blue-600 px-3 py-1.5 rounded-full shadow-lg">
                            <i class="fas fa-eye"></i>
                            <span>Click to view</span>
                        </span>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-2 line-clamp-2 leading-tight">${product.product_name || product.name}</h4>
                    
                    <p class="text-gray-600 text-xs mb-2 hidden sm:block line-clamp-2" style="white-space: pre-line;">${product.description || 'No description available'}</p>
                    
                    <div class="flex justify-between items-center gap-2">
                        ${priceHtml}
                        <span class="text-xs sm:text-sm font-semibold ${isExpired ? 'text-red-600' : stockStatus === 'out' ? 'text-red-600' : stockStatus === 'low' ? 'text-orange-600' : 'text-green-600'}">
                            Stock: ${product.stock || 0}
                        </span>
                    </div>
                    ${expiryInfoHtml}
                </div>
            </div>
        `;
        
        // Action buttons (hanya untuk admin)
        const actionButtons = isAdmin ? `
            <div class="border-t border-gray-200 p-2 sm:p-3 flex gap-2">
                <a href="/products/${product.id}/edit" 
                   class="flex-1 btn-secondary text-xs sm:text-sm py-2"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-pencil-alt mr-1"></i>
                    <span>Edit</span>
                </a>
                <button onclick="event.stopPropagation(); deleteProduct(${product.id})" 
                        class="flex-1 btn-danger text-xs sm:text-sm py-2">
                    <i class="fas fa-trash-alt mr-1"></i>
                    <span>Delete</span>
                </button>
            </div>
        ` : '';
        
        card.innerHTML = cardContent + actionButtons;
        container.appendChild(card);
    });
}

// Helper function untuk menggelapkan/mencerahkan warna
function adjustColor(color, amount) {
    const clamp = (val) => Math.min(Math.max(val, 0), 255);
    const num = parseInt(color.slice(1), 16);
    const r = clamp((num >> 16) + amount);
    const g = clamp(((num >> 8) & 0x00FF) + amount);
    const b = clamp((num & 0x0000FF) + amount);
    return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
}

    function applyFilters() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const categoryId = document.getElementById('category-filter').value;
    const stockFilter = document.getElementById('stock-filter').value;
    
    let filtered = products;
    
    // Search filter
    if (searchTerm) {
        filtered = filtered.filter(product => 
            (product.product_name || product.name || '').toLowerCase().includes(searchTerm) ||
            (product.product_code || '').toLowerCase().includes(searchTerm) ||
            (product.description || '').toLowerCase().includes(searchTerm)
        );
    }
    
    // Category filter
    if (categoryId) {
        filtered = filtered.filter(product => product.category_id == categoryId);
    }
    
    // Stock filter
    if (stockFilter === 'low') {
        filtered = filtered.filter(product => product.stock > 0 && product.stock <= (product.minimum_stock || 10));
    } else if (stockFilter === 'out') {
        filtered = filtered.filter(product => product.stock <= 0);
    } else if (stockFilter === 'expired') {
        // ✅ TAMBAHKAN: Filter expired
        filtered = filtered.filter(product => {
            if (!product.has_expiry || !product.expiry_date) return false;
            return new Date(product.expiry_date) < new Date();
        });
    } else if (stockFilter === 'near-expiry') {
        // ✅ TAMBAHKAN: Filter near expiry (30 days)
        filtered = filtered.filter(product => {
            if (!product.has_expiry || !product.expiry_date) return false;
            const expiryDate = new Date(product.expiry_date);
            const today = new Date();
            const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
            return daysUntilExpiry >= 0 && daysUntilExpiry <= 30;
        });
    }
    
    filteredProducts = filtered;
    displayProducts(filtered);
    updateTitle(stockFilter);
}

    // Reset filters
    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('stock-filter').value = '';
        currentFilter = 'all';
        
        filteredProducts = products;
        displayProducts(products);
        updateTitle('');
    }

    function filterByStatus(status) {
    currentFilter = status;
    const stockFilter = document.getElementById('stock-filter');
    
    // Reset other filters
    document.getElementById('search').value = '';
    document.getElementById('category-filter').value = '';
    
    if (status === 'all') {
        stockFilter.value = '';
    } else if (status === 'low') {
        stockFilter.value = 'low';
    } else if (status === 'out') {
        stockFilter.value = 'out';
    } else if (status === 'expired') {
        stockFilter.value = 'expired';        // ✅ TAMBAHKAN
    } else if (status === 'near-expiry') {
        stockFilter.value = 'near-expiry';    // ✅ TAMBAHKAN
    }
    
    applyFilters();
}

    function updateTitle(filter) {
    const title = document.getElementById('products-title');
    const subtitle = document.getElementById('products-subtitle');
    
    if (filter === 'low') {
        title.textContent = 'Low Stock Products';
        subtitle.textContent = 'Filter: Low Stock Items';
    } else if (filter === 'out') {
        title.textContent = 'Out of Stock Products';
        subtitle.textContent = 'Filter: Out of Stock Items';
    } else if (filter === 'expired') {
        // ✅ TAMBAHKAN
        title.textContent = 'Expired Products';
        subtitle.textContent = 'Filter: Expired Products - Remove from inventory';
    } else if (filter === 'near-expiry') {
        // ✅ TAMBAHKAN
        title.textContent = 'Near Expiry Products';
        subtitle.textContent = 'Filter: Products expiring within 30 days';
    } else {
        title.textContent = 'Product List';
        subtitle.textContent = 'Manage all products in your store.';
    }
}

    // Delete product
    async function deleteProduct(id) {
        if (!confirm('Are you sure you want to delete this product?')) return;

        try {
            const response = await fetch(`/api/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const result = await response.json();
            
            if (response.ok) {
                alert('Product successfully deleted');
                loadProducts();
            } else {
                alert(result.message || 'Error deleting product');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error deleting product');
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Auto search with debounce (300ms delay)
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 300);
        });
        
        // Category filter - langsung apply
        document.getElementById('category-filter').addEventListener('change', function() {
            applyFilters();
        });
        
        // Stock filter - langsung apply
        document.getElementById('stock-filter').addEventListener('change', function() {
            applyFilters();
        });
        
        // Remove filter button, ganti jadi reset button
        const filterButton = document.getElementById('filter-button');
        if (filterButton) {
            filterButton.innerHTML = '<i class="fas fa-redo mr-2"></i><span>Reset Filters</span>';
            filterButton.className = 'w-full btn-reset justify-center';
            filterButton.onclick = resetFilters;
        }
        
        // Load products when page loads
        loadProducts();
    });
</script>
</x-app-layout>