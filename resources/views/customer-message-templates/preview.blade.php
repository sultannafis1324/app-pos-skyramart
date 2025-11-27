<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Preview: ') }} {{ $template->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    See how your welcome message will look with sample data
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('customer-message-templates.edit', $template->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">
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
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($template->type) }} Message
                                </span>
                            </div>

                            @if($template->type === 'whatsapp')
                            <!-- WhatsApp Style Preview -->
                            <div class="bg-gradient-to-b from-green-50 to-white dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border-2 border-green-200 dark:border-green-700">
                                <div class="bg-white dark:bg-gray-900 rounded-lg p-4 shadow-md">
                                    <div class="space-y-3 text-sm font-mono whitespace-pre-wrap">
                                        @if($preview['greeting'])
                                        <div class="text-gray-900 dark:text-gray-100">
                                            {!! nl2br(e($preview['greeting'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['account_details_title'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-3 mt-3">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {!! nl2br(e($preview['account_details_title'])) !!}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="text-gray-700 dark:text-gray-300 text-xs space-y-1">
                                            <div>👤 Name: {{ $sampleCustomer->customer_name }}</div>
                                            <div>📧 Email: {{ $sampleCustomer->email }}</div>
                                            <div>📞 Phone: {{ $sampleCustomer->phone_number }}</div>
                                            <div>🎁 Loyalty Points: {{ number_format($sampleCustomer->loyalty_points, 0, ',', '.') }} points</div>
                                        </div>

                                        @if($preview['benefits_title'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-3 mt-3">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {!! nl2br(e($preview['benefits_title'])) !!}
                                            </div>
                                        </div>
                                        @endif

                                        @if($preview['benefits_list'])
                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            {!! nl2br(e($preview['benefits_list'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['contact_info'])
                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-3 mt-3 text-gray-600 dark:text-gray-400 text-xs">
                                            <div class="font-semibold mb-2">📍 Visit Us</div>
                                            {!! nl2br(e($preview['contact_info'])) !!}
                                        </div>
                                        @endif

                                        @if($preview['footer'])
                                        <div class="text-gray-700 dark:text-gray-300 text-xs">
                                            {!! nl2br(e($preview['footer'])) !!}
                                        </div>
                                        @endif

                                        <div class="border-t border-gray-300 dark:border-gray-600 pt-3 mt-3 text-center font-semibold text-gray-900 dark:text-gray-100 text-xs">
                                            {!! nl2br(e($preview['store_branding'])) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Email Style Preview -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                                <!-- Email Header -->
                                <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 text-center">
                                    <h1 class="text-2xl font-bold">🛒 SkyraMart</h1>
                                    <p class="text-sm mt-1">Your Trusted Shopping Partner</p>
                                </div>

                                <!-- Email Body -->
                                <div class="p-6">
                                    @if($preview['greeting'])
                                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                                        {!! nl2br(e($preview['greeting'])) !!}
                                    </div>
                                    @endif

                                    @if($preview['account_details_title'])
                                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                        <p class="font-semibold mb-3">{{ $preview['account_details_title'] }}</p>
                                        <p class="text-sm">📧 Email: {{ $sampleCustomer->email }}</p>
                                        <p class="text-sm">📞 Phone: {{ $sampleCustomer->phone_number }}</p>
                                        <p class="text-sm">🎁 Loyalty Points: {{ number_format($sampleCustomer->loyalty_points, 0, ',', '.') }} points</p>
                                    </div>
                                    @endif

                                    @if($preview['benefits_title'])
                                    <h3 class="text-lg font-semibold text-purple-600 mb-4">{{ $preview['benefits_title'] }}</h3>
                                    @endif

                                    @if($preview['benefits_list'])
                                    <div class="space-y-3 mb-6">
                                        @php
                                            $benefitsList = json_decode($template->benefits_list, true);
                                        @endphp

                                        @if(is_array($benefitsList))
                                            @foreach($benefitsList as $benefit)
                                            <div class="flex items-start bg-gray-50 rounded-lg p-3">
                                                <div class="text-2xl mr-3">{{ $benefit['icon'] }}</div>
                                                <div>
                                                    <strong>{{ $benefit['title'] }}</strong>
                                                    <p class="text-sm text-gray-600">{{ $benefit['description'] }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div>{!! nl2br(e($preview['benefits_list'])) !!}</div>
                                        @endif
                                    </div>
                                    @endif

                                    @if($preview['contact_info'])
                                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                        <p class="font-semibold mb-2">📞 Need Help?</p>
                                        {!! nl2br(e($preview['contact_info'])) !!}
                                    </div>
                                    @endif

                                    @if($preview['footer'])
                                    <p class="mb-4">{!! nl2br(e($preview['footer'])) !!}</p>
                                    @endif
                                </div>

                                <!-- Email Footer -->
                                <div class="bg-gray-100 text-center p-6 text-sm text-gray-600">
                                    @if($preview['store_branding'])
                                    <p class="font-semibold mb-2">{{ $preview['store_branding'] }}</p>
                                    @endif
                                    <p class="text-xs">This is an automated message. Please do not reply to this email.</p>
                                    <p class="text-xs mt-2">© {{ date('Y') }} SkyraMart. All rights reserved.</p>
                                </div>
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
                                Sample Customer Data
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                This preview shows how your template looks with sample customer data
                            </p>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <table class="w-full text-sm">
                                    <tbody class="space-y-2">
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Name:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleCustomer->customer_name }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Email:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleCustomer->email }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Phone:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100">{{ $sampleCustomer->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium text-gray-700 dark:text-gray-300">Loyalty Points:</td>
                                            <td class="py-2 text-gray-900 dark:text-gray-100 font-bold">{{ number_format($sampleCustomer->loyalty_points, 0, ',', '.') }} points</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-purple-700 dark:text-purple-200">
                                        <p class="font-medium mb-1">Note:</p>
                                        <p>Real welcome messages will show actual customer data instead of this sample data.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-200">
                                        <p class="font-medium mb-1">When is this sent?</p>
                                        <p>This welcome message is automatically sent to customers when they are first created in the system.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('customer-message-templates.edit', $template->id) }}" 
                                   class="w-full inline-flex justify-center items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
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