<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <?php echo e(__('Edit Template: ')); ?> <?php echo e($template->name); ?>

                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Customize your <?php echo e($template->type); ?> welcome message template
                </p>
            </div>
            <a href="<?php echo e(route('templates.index')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT: Form Edit -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Info Alert -->
                            <div class="mb-6 bg-purple-50 dark:bg-purple-900 border-l-4 border-purple-400 p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-purple-700 dark:text-purple-200">
                                        <p class="font-medium mb-1">How to use variables:</p>
                                        <p>Use variables like <code class="bg-purple-100 dark:bg-purple-800 px-1 rounded">{customer_name}</code> in your text. They will be replaced with actual customer data.</p>
                                        <p class="mt-2">See available variables on the right sidebar →</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="<?php echo e(route('customer-message-templates.update', $template->id)); ?>" id="templateForm">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <!-- Template Name -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Template Name
                                    </label>
                                    <input type="text" name="name" value="<?php echo e(old('name', $template->name)); ?>" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <?php if($template->type === 'email'): ?>
                                <!-- Subject (Email only) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Subject
                                    </label>
                                    <input type="text" name="subject" value="<?php echo e(old('subject', $template->subject)); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>
                                <?php endif; ?>

                                <!-- Greeting Text -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Greeting Text
                                        <span class="text-xs text-gray-500">(Opening welcome message)</span>
                                    </label>
                                    <textarea name="greeting_text" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm"><?php echo e(old('greeting_text', $template->greeting_text)); ?></textarea>
                                    <?php $__errorArgs = ['greeting_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Account Details Title -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Account Details Section Title
                                    </label>
                                    <input type="text" name="account_details_title" 
                                           value="<?php echo e(old('account_details_title', $template->account_details_title)); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Benefits Title -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Benefits Section Title
                                    </label>
                                    <input type="text" name="benefits_title" 
                                           value="<?php echo e(old('benefits_title', $template->benefits_title)); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Benefits List -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Benefits List
                                        <span class="text-xs text-gray-500">(List of member benefits)</span>
                                    </label>
                                    <textarea name="benefits_list" rows="6" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm"><?php echo e(old('benefits_list', $template->benefits_list)); ?></textarea>
                                    <p class="mt-1 text-xs text-gray-500">
                                        For WhatsApp: Use ✅ or emojis. For Email: Will be parsed as JSON array.
                                    </p>
                                </div>

                                <!-- Contact Info -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Contact Information
                                        <span class="text-xs text-gray-500">(Store address, phone, email, hours)</span>
                                    </label>
                                    <textarea name="contact_info" rows="4" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm"><?php echo e(old('contact_info', $template->contact_info)); ?></textarea>
                                </div>

                                <!-- Footer Text -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Footer Text
                                        <span class="text-xs text-gray-500">(Closing message)</span>
                                    </label>
                                    <textarea name="footer_text" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm"><?php echo e(old('footer_text', $template->footer_text)); ?></textarea>
                                </div>

                                <!-- Store Branding -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Branding
                                        <span class="text-xs text-gray-500">(Brand text at the end)</span>
                                    </label>
                                    <input type="text" name="store_branding" 
                                           value="<?php echo e(old('store_branding', $template->store_branding)); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-mono text-sm">
                                </div>

                                <!-- Active Status -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" 
                                               <?php echo e(old('is_active', $template->is_active) ? 'checked' : ''); ?>

                                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Template is active
                                        </span>
                                    </label>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
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
                                <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var => $description): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition" 
                                     onclick="copyVariable('<?php echo e($var); ?>')">
                                    <div class="flex items-center justify-between">
                                        <code class="text-xs font-mono text-purple-600 dark:text-purple-400"><?php echo e($var); ?></code>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($description); ?></p>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Quick Tips:</h4>
                                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Variables are case-sensitive
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Use \n for new line in WhatsApp
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Use * for bold in WhatsApp
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-purple-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
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
    <div id="toast" class="fixed bottom-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300">
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
                form.action = '<?php echo e(route('customer-message-templates.reset', $template->id)); ?>';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '<?php echo e(csrf_token()); ?>';
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\app-pos-skyramart\resources\views/customer-message-templates/edit.blade.php ENDPATH**/ ?>