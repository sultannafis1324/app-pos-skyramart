<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Template: ') }} {{ $template->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Customize your {{ $template->type }} receipt template
                </p>
            </div>
            <a href="{{ route('templates.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT: Form Edit -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Info Alert -->
                            <div class="mb-6 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-200">
                                        <p class="font-medium mb-1">How to use variables:</p>
                                        <p>Use variables like <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{customer_name}</code> in your text. They will be replaced with actual data.</p>
                                        <p class="mt-2">See available variables on the right sidebar →</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('receipt-templates.update', $template->id) }}" id="templateForm">
                                @csrf
                                @method('PUT')

                                <!-- Template Name -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Template Name
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Header Text -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Header Text
                                        <span class="text-xs text-gray-500">(Store name, address)</span>
                                    </label>
                                    <textarea name="header_text" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('header_text', $template->header_text) }}</textarea>
                                    @error('header_text')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if($template->type === 'whatsapp')
                                <!-- Greeting Text (WhatsApp only) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Greeting Text
                                        <span class="text-xs text-gray-500">(Opening message)</span>
                                    </label>
                                    <textarea name="greeting_text" rows="2" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('greeting_text', $template->greeting_text) }}</textarea>
                                </div>
                                @endif

                                <!-- Transaction Section Title -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Transaction Section Title
                                    </label>
                                    <input type="text" name="transaction_section_title" 
                                           value="{{ old('transaction_section_title', $template->transaction_section_title) }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Items Section Title -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Items Section Title
                                    </label>
                                    <input type="text" name="items_section_title" 
                                           value="{{ old('items_section_title', $template->items_section_title) }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Payment Section Title -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Payment Section Title
                                    </label>
                                    <input type="text" name="payment_section_title" 
                                           value="{{ old('payment_section_title', $template->payment_section_title) }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                @if($template->type === 'whatsapp')
                                <!-- Notes Text (WhatsApp only) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Notes Text
                                        <span class="text-xs text-gray-500">(PDF info, etc)</span>
                                    </label>
                                    <textarea name="notes_text" rows="2" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('notes_text', $template->notes_text) }}</textarea>
                                </div>
                                @endif

                                <!-- Contact Info -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Contact Information
                                    </label>
                                    <textarea name="contact_info" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('contact_info', $template->contact_info) }}</textarea>
                                </div>

                                <!-- Footer Text -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Footer Text
                                        <span class="text-xs text-gray-500">(Thank you message)</span>
                                    </label>
                                    <textarea name="footer_text" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('footer_text', $template->footer_text) }}</textarea>
                                </div>

                                <!-- Store Branding -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Branding
                                        <span class="text-xs text-gray-500">(Brand text at the end, e.g., "💚 SkyraMart - Your Trusted Store")</span>
                                    </label>
                                    <input type="text" name="store_branding" 
                                           value="{{ old('store_branding', $template->store_branding) }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Active Status -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" 
                                               {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Template is active
                                        </span>
                                    </label>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save Changes
                                    </button>
                                    <button type="button" onclick="resetToDefault()" 
                                            class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Available Variables -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Available Variables
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Click to copy a variable, then paste it into your template
                            </p>

                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                @foreach($variables as $var => $description)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition" 
                                     onclick="copyVariable('{{ $var }}')">
                                    <div class="flex items-center justify-between">
                                        <code class="text-xs font-mono text-blue-600 dark:text-blue-400">{{ $var }}</code>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Quick Tips:</h4>
                                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Variables are case-sensitive
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Use \n for new line in WhatsApp
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Use * for bold in WhatsApp
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Test with Preview before saving
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="toastMessage">Copied!</span>
        </div>
    </div>

    <script>
        function copyVariable(variable) {
            navigator.clipboard.writeText(variable).then(() => {
                showToast('Variable copied: ' + variable);
            }).catch(err => {
                console.error('Copy failed:', err);
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 2000);
        }

        function resetToDefault() {
            if (confirm('Are you sure you want to reset this template to default? All your changes will be lost.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('receipt-templates.reset', $template->id) }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>