<section class="space-y-8">
    <header class="space-y-3">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-red-50 dark:bg-red-900/20 rounded-xl">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ __('Delete Account') }}
            </h2>
        </div>

        <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <!-- Delete Form (Initially Hidden) -->
    <div id="deleteForm" class="hidden mt-6 p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">
                    {{ __('Confirm Account Deletion') }}
                </h3>
                <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                    {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
                
                <label for="password" class="block text-sm font-medium text-red-700 dark:text-red-300 mb-2">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-red-300 dark:border-red-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                    placeholder="{{ __('Enter your password') }}"
                />
                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ $errors->userDeletion->get('password')[0] }}
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <button 
                    type="button"
                    onclick="hideDeleteForm()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-all duration-200"
                >
                    {{ __('Cancel') }}
                </button>

                <button 
                    type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg shadow-lg shadow-red-500/25 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300"
                >
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </div>

    <div class="pt-4">
        <button
            onclick="showDeleteForm()"
            class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl shadow-lg shadow-red-500/25 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300 transform hover:scale-105"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Delete Account') }}
        </button>
    </div>

    <script>
        function showDeleteForm() {
            document.getElementById('deleteForm').classList.remove('hidden');
            document.getElementById('password').focus();
        }

        function hideDeleteForm() {
            document.getElementById('deleteForm').classList.add('hidden');
            document.getElementById('password').value = '';
        }

        // Show form if there are validation errors
        @if($errors->userDeletion->isNotEmpty())
            document.addEventListener('DOMContentLoaded', function() {
                showDeleteForm();
            });
        @endif
    </script>
</section>