<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage your account settings and preferences.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 md:p-8 text-white shadow-lg shadow-purple-500/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative flex-shrink-0">
                        @if(auth()->user()->photo_profile)
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-white/30" 
                                src="{{ asset('storage/' . auth()->user()->photo_profile) }}" 
                                alt="{{ auth()->user()->name }}">
                        @else
                            <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl font-bold border-4 border-white/30">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-400 rounded-full border-4 border-purple-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-2xl font-bold">{{ auth()->user()->name ?? 'User Name' }}</h3>
                        <p class="text-white/80 mt-1">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                                        Active Account
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                                        Verified
                                    </span>
                                </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ __('Profile Information') }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __("Update your account's profile information and contact details.") }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ __('Security Settings') }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-3">
                            {{ __('Account Summary') }}
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Member since') }}</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Last updated') }}</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email verified') }}</span>
                                @if(auth()->user()->email_verified_at)
                                    <span class="flex items-center gap-1.5 px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        {{ __('Not Verified') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-l-4 border-red-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('Danger Zone') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Once you delete your account, there is no going back.') }}
                            </p>
                            <div class="mt-4">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>