<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Sales Detail Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sale Detail') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sale #{{ $sale->transaction_number }}</h1>
                            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $sale->sale_date->format('d F Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 no-print">
                            <!-- Add this button next to existing print button -->
<button onclick="printThermal({{ $sale->id }})" 
    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
    </svg>
    Print Thermal
</button>
                            <button onclick="printReceipt()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print Receipt
                            </button>
                            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center mt-2">
                        @switch($sale->status)
                        @case('completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Completed
                        </span>
                        @break
                        @case('pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                        @break
                        @case('cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Cancelled
                        </span>
                        @break
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Customer & Transaction Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer & Transaction Info Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Transaction Details -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Transaction Details</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Transaction Number:</span>
                                            <span class="text-gray-900 dark:text-white font-medium">{{ $sale->transaction_number }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Date:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $sale->sale_date->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Cashier:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $sale->user->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Information -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Customer Information</h3>
                                    <div class="space-y-3">
                                        @if($sale->customer)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Name:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $sale->customer->customer_name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Phone:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $sale->customer->phone_number ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Email:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $sale->customer->email ?? '-' }}</span>
                                        </div>
                                        @else
                                        <div class="text-center py-4">
                                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <p class="mt-2 text-gray-500 dark:text-gray-400">Walk-in Customer</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($sale->notes)
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Notes</h4>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $sale->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- ✅ IMPROVED: Items Purchased Card - Professional & Modern Design --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Items Purchased
                                </h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $sale->saleDetails->count() }} {{ Str::plural('item', $sale->saleDetails->count()) }}
                                </span>
                            </div>

                            {{-- Desktop Table View --}}
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Discount</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Promotion</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach($sale->saleDetails as $detail)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">

                                            {{-- 🛒 Product Column --}}
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($detail->product && $detail->product->image)
                                                    <img src="{{ Storage::url($detail->product->image) }}"
                                                        alt="{{ $detail->product_name }}"
                                                        class="h-14 w-14 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                                    @else
                                                    <div class="h-14 w-14 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                    @endif
                                                    <div class="min-w-0 flex-1">
                                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                                            {{ $detail->product_name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                            {{ $detail->product->product_code ?? '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- 🔢 Quantity Column --}}
                                            <td class="px-4 py-4">
                                                <div class="text-center">
                                                    <div class="inline-flex items-center justify-center min-w-[60px] px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                            {{ $detail->quantity }}
                                                        </span>
                                                    </div>
                                                    @if($detail->free_quantity > 0)
                                                    <div class="mt-1.5 inline-flex items-center px-2 py-0.5 bg-green-50 dark:bg-green-900/30 rounded-md">
                                                        <svg class="w-3 h-3 text-green-600 dark:text-green-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                                                        </svg>
                                                        <span class="text-xs font-semibold text-green-700 dark:text-green-400">
                                                            +{{ $detail->free_quantity }} FREE
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- 💰 Price Column --}}
                                            <td class="px-4 py-4 text-right">
                                                @if($detail->original_price > $detail->unit_price)
                                                <div class="text-xs text-gray-400 line-through mb-0.5 whitespace-nowrap">
                                                    Rp {{ number_format($detail->original_price, 0, ',', '.') }}
                                                </div>
                                                @endif
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                    Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                                </div>
                                            </td>

                                            {{-- 🎁 Discount Column --}}
                                            <td class="px-4 py-4 text-right">
                                                @php
                                                $hasPriceDiscount = $detail->price_discount_amount > 0;
                                                $hasQtyDiscount = $detail->quantity_discount_amount > 0;
                                                $hasManualDiscount = $detail->item_discount > 0;
                                                $totalDiscount = $detail->price_discount_amount + $detail->quantity_discount_amount + $detail->item_discount;
                                                @endphp

                                                @if($totalDiscount > 0)
                                                <div class="space-y-1">
                                                    @if($hasPriceDiscount)
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Price:</span>
                                                        <span class="text-sm font-medium text-green-600 dark:text-green-400 whitespace-nowrap">
                                                            -Rp {{ number_format($detail->price_discount_amount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    @endif

                                                    @if($hasQtyDiscount)
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Free:</span>
                                                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400 whitespace-nowrap">
                                                            -Rp {{ number_format($detail->quantity_discount_amount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    @endif

                                                    @if($hasManualDiscount)
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Manual:</span>
                                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400 whitespace-nowrap">
                                                            -Rp {{ number_format($detail->item_discount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    @endif

                                                    @if(($hasPriceDiscount && $hasQtyDiscount) || ($hasManualDiscount && ($hasPriceDiscount || $hasQtyDiscount)))
                                                    <div class="pt-1 mt-1 border-t border-gray-200 dark:border-gray-600">
                                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                            -Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @else
                                                <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- 🏷️ Promotion Column --}}
                                            <td class="px-4 py-4">
                                                @php
                                                $hasPricePromo = $detail->price_promotion_id && $detail->pricePromotion;
                                                $hasQtyPromo = $detail->quantity_promotion_id && $detail->quantityPromotion;
                                                @endphp

                                                @if($hasPricePromo || $hasQtyPromo)
                                                <div class="flex flex-col gap-1.5 items-center">
                                                    @if($hasPricePromo)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ $detail->pricePromotion->type === 'percentage' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                                        {{ strtoupper($detail->pricePromotion->badge_text ?? $detail->pricePromotion->name) }}
                                                    </span>
                                                    @endif

                                                    @if($hasQtyPromo)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                                        {{ strtoupper($detail->quantityPromotion->badge_text ?? 'BELI ' . $detail->quantityPromotion->buy_quantity . ' GRATIS ' . $detail->quantityPromotion->get_quantity) }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @else
                                                <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- 💵 Subtotal Column --}}
                                            <td class="px-4 py-4 text-right">
                                                <span class="text-base font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile Card View --}}
                            <div class="md:hidden space-y-3">
                                @foreach($sale->saleDetails as $detail)
                                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-750 dark:to-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">

                                    {{-- Product Header --}}
                                    <div class="flex items-start gap-3 mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                                        @if($detail->product && $detail->product->image)
                                        <img src="{{ Storage::url($detail->product->image) }}"
                                            alt="{{ $detail->product_name }}"
                                            class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                        @else
                                        <div class="h-16 w-16 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 truncate">
                                                {{ $detail->product_name }}
                                            </h4>
                                            @if($detail->product)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->product->product_code }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Promotion Badges --}}
                                    @if($detail->price_promotion_id || $detail->quantity_promotion_id)
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if($detail->price_promotion_id && $detail->pricePromotion)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold {{ $detail->pricePromotion->type === 'percentage' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ strtoupper($detail->pricePromotion->badge_text ?? $detail->pricePromotion->name) }}
                                        </span>
                                        @endif

                                        @if($detail->quantity_promotion_id && $detail->quantityPromotion)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700">
                                            {{ strtoupper($detail->quantityPromotion->badge_text) }}
                                        </span>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- Details Grid --}}
                                    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                        {{-- Quantity --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-2.5 border border-gray-200 dark:border-gray-700">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Quantity</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                {{ $detail->quantity }}
                                            </span>
                                            @if($detail->free_quantity > 0)
                                            <span class="text-green-600 font-semibold ml-1">
                                                +{{ $detail->free_quantity }}
                                            </span>
                                            @endif
                                        </div>

                                        {{-- Unit Price --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-2.5 border border-gray-200 dark:border-gray-700">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Unit Price</span>
                                            @if($detail->original_price > $detail->unit_price)
                                            <div class="text-xs text-gray-400 line-through">
                                                Rp {{ number_format($detail->original_price, 0, ',', '.') }}
                                            </div>
                                            @endif
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Discounts --}}
                                    @php
                                    $totalDiscount = $detail->price_discount_amount + $detail->quantity_discount_amount + $detail->item_discount;
                                    @endphp
                                    @if($totalDiscount > 0)
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2.5 mb-3">
                                        <span class="text-xs text-gray-600 dark:text-gray-400 block mb-1.5">Discounts Applied</span>
                                        <div class="space-y-1">
                                            @if($detail->price_discount_amount > 0)
                                            <div class="flex justify-between text-xs">
                                                <span class="text-gray-600 dark:text-gray-400">Price Discount:</span>
                                                <span class="font-semibold text-green-600 dark:text-green-400">
                                                    -Rp {{ number_format($detail->price_discount_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @endif
                                            @if($detail->quantity_discount_amount > 0)
                                            <div class="flex justify-between text-xs">
                                                <span class="text-gray-600 dark:text-gray-400">Free Items Value:</span>
                                                <span class="font-semibold text-purple-600 dark:text-purple-400">
                                                    -Rp {{ number_format($detail->quantity_discount_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @endif
                                            @if($detail->item_discount > 0)
                                            <div class="flex justify-between text-xs">
                                                <span class="text-gray-600 dark:text-gray-400">Manual Discount:</span>
                                                <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                    -Rp {{ number_format($detail->item_discount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Subtotal --}}
                                    <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-3 flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment & Summary -->
                <div class="space-y-6">
                    <!-- Payment Information Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h3>

                            @if($sale->payments && $sale->payments->count() > 0)
                            @foreach($sale->payments as $payment)
                            <div class="mb-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $payment->payment_method_label }}</span>
                                        @if($payment->reference_number)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ref: {{ $payment->reference_number }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full 
                                                        {{ $payment->status === 'completed' 
                                                            ? 'bg-green-100 text-green-800' 
                                                            : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $payment->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Payment Summary -->
                            <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Total Price:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Total Paid:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($amountPaid, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-300 dark:border-gray-600">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Change:</span>
                                    <span class="font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($change, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-6">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">No payment information available</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @if($sale->discount > 0)
                                <div class="flex justify-between text-red-600 dark:text-red-400">
                                    <span>Discount:</span>
                                    <span>- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($sale->tax > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($sale->tax, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 12px;
                background: white !important;
                color: black !important;
            }

            .bg-white,
            .dark\:bg-gray-800 {
                background: white !important;
                color: black !important;
            }

            .shadow-sm,
            .rounded-xl {
                box-shadow: none !important;
                border-radius: 0 !important;
                border: 1px solid #ddd !important;
            }

            .print-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .print-section {
                margin-bottom: 15px;
            }

            .grid {
                display: block !important;
            }

            .lg\:col-span-2,
            .lg\:col-span-1 {
                width: 100% !important;
            }

            .space-y-6>*+* {
                margin-top: 20px !important;
            }
        }
    </style>
    <script>
        async function printThermal(saleId) {
    try {
        showNotification('Connecting to printer...', 'info');
        
        const response = await fetch(`/sales/${saleId}/print-thermal`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // ✅ CRITICAL: Force JSON response
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest' // ✅ Tell Laravel this is AJAX
            }
        });

        // ✅ Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response. Check if printer server is running.');
        }

        const result = await response.json();

        if (result.success) {
            showNotification('✅ Receipt printed successfully!', 'success');
        } else {
            showNotification('❌ ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Print error:', error);
        
        // ✅ Better error messages
        if (error.message.includes('non-JSON')) {
            showNotification('❌ Printer server not responding. Is it running on port 3001?', 'error');
        } else if (error.message.includes('Failed to fetch')) {
            showNotification('❌ Cannot connect to printer server', 'error');
        } else {
            showNotification('❌ Print failed: ' + error.message, 'error');
        }
    }
}

function showNotification(message, type) {
    // Reuse existing notification function
    alert(message);
}

        function printReceipt() {
            // Buka receipt di iframe tersembunyi untuk print tanpa pindah halaman
            const printUrl = '{{ route("sales.receipt", $sale) }}?autoprint=1';

            // Buat iframe tersembunyi
            let iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = printUrl;
            document.body.appendChild(iframe);

            // Bersihkan iframe setelah print selesai
            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.print();
                }, 500);
            };
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