<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Preview: ') }} {{ $template->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    See how your template will look with sample data
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('receipt-templates.edit', $template->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Template
                </a>
                <a href="{{ route('templates.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- LEFT: Preview -->
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Template Preview
                                </h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($template->type) }}
                                </span>
                            </div>

                            @if($template->type === 'whatsapp')
                            <!-- WhatsApp Style Preview -->
                            <div class="bg-gradient-to-b from-green-50 to-white dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border-2 border-green-200 dark:border-green-700">
                                <div class="bg-white dark:bg-gray-900 rounded-lg p-4 shadow-md">
                                    <div class="space-y-3 text-sm font-mono whitespace-pre-wrap">
                                        @if($preview['header'])
                                        <div class="text-gray-900 dark:text-gray-100 font-semibold">
                                            {!! nl2br(e($preview['header'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['greeting'])
                                        <div class="text-gray-800 dark:text-gray-200">
                                            {!! nl2br(e($preview['greeting'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['transaction_title'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {!! nl2br(e($preview['transaction_title'])) !!}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            🧾 Receipt No: TXN-20251122-1234
                                            📅 Date: 22 Nov 2025, 14:30
                                            👤 Cashier: Admin User
                                        </div>

                                        @if($preview['items_title'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {!! nl2br(e($preview['items_title'])) !!}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            1. Sample Product A
                                               2 x Rp 50.000 = Rp 100.000
                                            
                                            2. Sample Product B
                                               1 x Rp 50.000 = Rp 50.000
                                        </div>

                                        @if($preview['payment_title'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {!! nl2br(e($preview['payment_title'])) !!}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            Subtotal: Rp 150.000
                                            Discount (3.3%): -Rp 5.000
                                            TOTAL: Rp 145.000
                                            
                                            Payment Method: CASH
                                        </div>

                                        @if($preview['notes'])
                                        <div class="bg-blue-50 dark:bg-blue-900 rounded p-2 text-blue-800 dark:text-blue-200 text-xs">
                                            {!! nl2br(e($preview['notes'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['contact'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2 text-gray-600 dark:text-gray-400 text-xs">
                                            {!! nl2br(e($preview['contact'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['footer'])
                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            {!! nl2br(e($preview['footer'])) !!}
                                        </div>
                                        @endif

                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2 text-center font-semibold text-gray-900 dark:text-gray-100 text-xs">
                                            {{ $template->store_branding ?? '💚 SkyraMart - Your Trusted Store' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Print Style Preview -->
                            <div class="bg-white rounded-lg p-6 shadow-md border-2 border-gray-300" style="font-family: 'Courier New', monospace; font-size: 12px;">
                                <div class="text-center border-b-2 border-black pb-4 mb-4">
                                    @if($preview['header'])
                                    <div class="font-bold" style="white-space: pre-wrap;">{{ $preview['header'] }}</div>
                                    @endif
                                </div>

                                @if($preview['transaction_title'])
                                <div class="font-bold mb-2">{{ $preview['transaction_title'] }}</div>
                                @endif

                                <div class="mb-4 space-y-1">
                                    <div>Receipt No: TXN-20251122-1234</div>
                                    <div>Date: 22/11/2025 14:30</div>
                                    <div>Cashier: Admin User</div>
                                    <div>Customer: John Doe</div>
                                </div>

                                @if($preview['items_title'])
                                <div class="font-bold border-t-2 border-black pt-2 mt-2 mb-2">{{ $preview['items_title'] }}</div>
                                @endif

                                <table class="w-full text-xs mb-4">
                                    <thead class="border-y-2 border-black">
                                        <tr>
                                            <th class="text-left py-1">Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Sample Product A</td>
                                            <td class="text-center">2</td>
                                            <td class="text-right">50.000</td>
                                            <td class="text-right">100.000</td>
                                        </tr>
                                        <tr>
                                            <td>Sample Product B</td>
                                            <td class="text-center">1</td>
                                            <td class="text-right">50.000</td>
                                            <td class="text-right">50.000</td>
                                        </tr>
                                    </tbody>
                                </table>

                                @if($preview['payment_title'])
                                <div class="font-bold border-t-2 border-black pt-2 mt-2 mb-2">{{ $preview['payment_title'] }}</div>
                                @endif

                                <div class="space-y-1 mb-4">
                                    <div class="flex justify-between">
                                        <span>Subtotal:</span>
                                        <span>Rp 150.000</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Discount (3.3%):</span>
                                        <span>-Rp 5.000</span>
                                    </div>
                                    <div class="flex justify-between font-bold border-t-2 border-black pt-1 mt-1">
                                        <span>TOTAL:</span>
                                        <span>Rp 145.000</span>
                                    </div>
                                </div>

                                @if($preview['footer'])
                                <div class="text-center border-t-2 border-black pt-4 mt-4" style="white-space: pre-wrap;">
                                    {{ $preview['footer'] }}
                                </div>
                                @endif

                                @if($preview['contact'])
                                <div class="text-center text-gray-600 mt-2" style="white-space: pre-wrap; font-size: 10px;">
                                    {{ $preview['contact'] }}
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Sample Data Info -->
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Sample Data Used
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                This preview shows how your template looks with sample data
                            </p>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <table class="w-full text-sm">
                                    <tbody class="space-y-2">
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Customer:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleData['customer_name'] }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Transaction No:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100 font-mono text-xs">{{ $sampleData['transaction_number'] }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Date:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleData['date'] }} {{ $sampleData['time'] }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Cashier:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleData['cashier_name'] }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Subtotal:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">Rp {{ number_format($sampleData['subtotal'], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Discount:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">Rp {{ number_format($sampleData['discount'], 0, ',', '.') }} ({{ $sampleData['discount_percentage'] }}%)</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Total:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100 font-bold">Rp {{ number_format($sampleData['total'], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Payment:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleData['payment_method'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-200">
                                        <p class="font-medium mb-1">Note:</p>
                                        <p>Real receipts will show actual transaction data instead of this sample data.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('receipt-templates.edit', $template->id) }}" 
                                   class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit This Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>