<section>
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <!-- Password Strength Indicator -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200">
                        {{ __('Password Requirements') }}
                    </h3>
                    <div class="mt-2 text-sm text-blue-800 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ __('Minimum 8 characters long') }}</li>
                            <li>{{ __('Mix of uppercase and lowercase letters') }}</li>
                            <li>{{ __('Include numbers and special characters') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Password -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative mt-1">
                <x-text-input 
                    id="update_password_current_password" 
                    name="current_password" 
                    type="password" 
                    class="block w-full pr-10" 
                    autocomplete="current-password" 
                />
                <button type="button" 
                        onclick="togglePassword('update_password_current_password')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    class="block w-full pr-10" 
                    autocomplete="new-password"
                    oninput="checkPasswordStrength(this.value)" 
                />
                <button type="button" 
                        onclick="togglePassword('update_password_password')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon-new">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Password Strength Meter -->
            <div id="password-strength" class="mt-2 hidden">
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div id="strength-bar" class="h-full transition-all duration-300 rounded-full" style="width: 0%"></div>
                    </div>
                    <span id="strength-text" class="text-xs font-medium"></span>
                </div>
            </div>
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="block w-full pr-10" 
                    autocomplete="new-password" 
                />
                <button type="button" 
                        onclick="togglePassword('update_password_password_confirmation')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon-confirm">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <x-primary-button class="px-6 py-2.5">
                    {{ __('Update Password') }}
                </x-primary-button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" 
                       x-show="show" 
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)"
                       class="inline-flex items-center text-sm font-medium text-green-600 dark:text-green-400">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Password updated!') }}
                    </p>
                @endif
            </div>
        </div>
    </form>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        function checkPasswordStrength(password) {
            const strengthMeter = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            if (password.length === 0) {
                strengthMeter.classList.add('hidden');
                return;
            }
            
            strengthMeter.classList.remove('hidden');
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 25;
            
            // Character variety checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 15;
            
            // Update bar
            strengthBar.style.width = strength + '%';
            
            // Update color and text
            if (strength < 40) {
                strengthBar.className = 'h-full transition-all duration-300 rounded-full bg-red-500';
                strengthText.className = 'text-xs font-medium text-red-600 dark:text-red-400';
                strengthText.textContent = 'Weak';
            } else if (strength < 70) {
                strengthBar.className = 'h-full transition-all duration-300 rounded-full bg-yellow-500';
                strengthText.className = 'text-xs font-medium text-yellow-600 dark:text-yellow-400';
                strengthText.textContent = 'Medium';
            } else {
                strengthBar.className = 'h-full transition-all duration-300 rounded-full bg-green-500';
                strengthText.className = 'text-xs font-medium text-green-600 dark:text-green-400';
                strengthText.textContent = 'Strong';
            }
        }
    </script>
</section>