<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Store Variables Preview') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    See how your store variables will appear in templates
                </p>
            </div>
            <a href="{{ route('store-settings.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Settings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Store Information Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Current Store Information
                        </h3>

                        @if($settings)
                        <div class="space-y-4">
                            <!-- Logo -->
                            @if($settings->store_logo)
                            <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <img src="{{ asset('storage/' . $settings->store_logo) }}" 
                                     alt="Store Logo" 
                                     class="h-24 w-auto">
                            </div>
                            @endif

                            <!-- Store Name -->
                            <div class="bg-indigo-50 dark:bg-indigo-900 rounded-lg p-4">
                                <p class="text-sm font-medium text-indigo-900 dark:text-indigo-100 mb-1">Store Name</p>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">{{ $settings->store_name }}</p>
                            </div>

                            <!-- Contact Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Address</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $settings->store_address }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $settings->store_email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $settings->store_phone }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">WhatsApp</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $settings->store_whatsapp }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Operating Hours</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $settings->store_hours }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Regional Settings -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Regional Settings</p>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Currency</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $settings->currency_symbol }} ({{ $settings->currency_code }})</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Timezone</p>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $settings->timezone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500 dark:text-gray-400">No store settings found</p>
                        @endif
                    </div>
                </div>

                <!-- Variables Reference Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Available Variables
                        </h3>

                        @if($variables)
                        <div class="space-y-3">
                            @foreach($variables as $key => $value)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-gray-600 transition cursor-pointer"
                                 onclick="copyToClipboard('{{ $key }}')">
                                <div class="flex items-center justify-between mb-2">
                                    <code class="text-sm font-mono text-purple-600 dark:text-purple-400">{{ $key }}</code>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 break-all">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-200">
                                    <p class="font-medium mb-1">How to use:</p>
                                    <p>Click on any variable to copy it, then paste it into your message templates. The variables will be automatically replaced with your store information.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Example Usage -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Example Usage in Templates
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- WhatsApp Example -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">WhatsApp Template Example:</p>
                            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4 border-2 border-green-200 dark:border-green-700">
                                <pre class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-wrap">🎉 Welcome to {store_name}!

📍 Visit us at:
{store_address}

📱 Contact:
WhatsApp: {store_whatsapp}
Email: {store_email}

⏰ {store_hours}</pre>
                            </div>
                        </div>

                        <!-- Email Example -->
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Email Template Example:</p>
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 border-2 border-blue-200 dark:border-blue-700">
                                <pre class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-wrap">Dear Customer,

Thank you for shopping at {store_name}!

Store Location:
{store_address}

Contact Us:
Email: {store_email}
Phone: {store_phone}

Best regards,
{store_name} Team</pre>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Variable copied: ' + text);
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
    </script>
</x-app-layout>