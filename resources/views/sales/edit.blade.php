<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Sale Status') }}
            </h2>
            <a href="{{ route('sales.show', $sale) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Sale Details
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Sale Information -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Sale Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Transaction Number:</span>
                                <p class="font-medium">{{ $sale->transaction_number }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                <p class="font-medium">{{ $sale->sale_date->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Customer:</span>
                                <p class="font-medium">{{ $sale->customer ? $sale->customer->customer_name : 'Walk-in Customer' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Total:</span>
                                <p class="font-medium">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Current Status:</span>
                                <p class="font-medium">
                                    @switch($sale->status)
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                            @break
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                                <p class="font-medium">
                                    @if($sale->payments->count() > 0)
                                        @php $latestPayment = $sale->payments->first(); @endphp
                                        @switch($latestPayment->status)
                                            @case('completed')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Failed</span>
                                                @break
                                        @endswitch
                                    @else
                                        <span class="text-gray-400">No payment</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <form action="{{ route('sales.updateStatus', $sale) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Status Dropdown -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="status" 
                                name="status" 
                                required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">-- Select Status --</option>
                                @foreach($availableStatuses as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}">{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Change Warning -->
                        <div id="status-warning" class="hidden p-4 rounded-lg border">
                            <!-- Will be populated by JavaScript -->
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="3"
                                placeholder="Add a reason for status change..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('notes', $sale->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Error Messages -->
                        @if(session('error'))
                            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->has('general'))
                            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ $errors->first('general') }}
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a 
                                href="{{ route('sales.show', $sale) }}" 
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition"
                            >
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition"
                            >
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const warningDiv = document.getElementById('status-warning');
            
            const warnings = {
                'completed': {
                    from_pending: {
                        class: 'bg-blue-50 border-blue-200 text-blue-800',
                        message: '✓ This will mark the sale as completed. Stock will be reduced and customer purchase will be recorded.'
                    },
                    from_cancelled: {
                        class: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                        message: '⚠ This will reactivate the sale and reduce stock again.'
                    }
                },
                'pending': {
                    from_completed: {
                        class: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                        message: '⚠ This will revert the sale to pending. Stock will be restored and customer purchase will be removed.'
                    },
                    from_cancelled: {
                        class: 'bg-blue-50 border-blue-200 text-blue-800',
                        message: '✓ This will reactivate the sale as pending.'
                    }
                },
                'cancelled': {
                    from_pending: {
                        class: 'bg-red-50 border-red-200 text-red-800',
                        message: '⚠ This will cancel the sale. The sale cannot be completed unless reactivated.'
                    },
                    from_completed: {
                        class: 'bg-red-50 border-red-200 text-red-800',
                        message: '⚠ This will cancel the sale. Stock will be restored and customer purchase will be removed.'
                    }
                }
            };
            
            const currentStatus = '{{ $sale->status }}';
            
            statusSelect.addEventListener('change', function() {
                const newStatus = this.value;
                
                if (!newStatus || newStatus === currentStatus) {
                    warningDiv.classList.add('hidden');
                    return;
                }
                
                const warningKey = `from_${currentStatus}`;
                const warning = warnings[newStatus]?.[warningKey];
                
                if (warning) {
                    warningDiv.className = `p-4 rounded-lg border ${warning.class}`;
                    warningDiv.innerHTML = `
                        <p class="text-sm font-medium">${warning.message}</p>
                    `;
                    warningDiv.classList.remove('hidden');
                } else {
                    warningDiv.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>