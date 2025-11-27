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
                    <?php echo e(__('Store Settings')); ?>

                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Manage your store information, logo, and contact details
                </p>
            </div>
            <a href="<?php echo e(route('store-settings.preview')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Preview Variables
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT: Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <form method="POST" action="<?php echo e(route('store-settings.update')); ?>" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <!-- Store Logo -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Logo
                                    </label>
                                    
                                    <?php if($settings->store_logo): ?>
                                    <div class="mb-4">
                                        <img src="<?php echo e(asset('storage/' . $settings->store_logo)); ?>" 
                                             alt="Store Logo" 
                                             class="h-32 w-auto rounded-lg border-2 border-gray-200 dark:border-gray-600">
                                        <button type="button" 
                                                onclick="deleteLogo()"
                                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            Remove Logo
                                        </button>
                                    </div>
                                    <?php endif; ?>

                                    <input type="file" name="store_logo" accept="image/*"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, SVG up to 2MB</p>
                                    <?php $__errorArgs = ['store_logo'];
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

                                <!-- Store Name -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Name *
                                    </label>
                                    <input type="text" name="store_name" value="<?php echo e(old('store_name', $settings->store_name)); ?>" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                    <?php $__errorArgs = ['store_name'];
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

                                <!-- Contact Information -->
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                        📞 Contact Information
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Email *
                                            </label>
                                            <input type="email" name="store_email" value="<?php echo e(old('store_email', $settings->store_email)); ?>" required
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                            <?php $__errorArgs = ['store_email'];
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

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Phone *
                                            </label>
                                            <input type="text" name="store_phone" value="<?php echo e(old('store_phone', $settings->store_phone)); ?>" required
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                            <?php $__errorArgs = ['store_phone'];
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

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                WhatsApp *
                                            </label>
                                            <input type="text" name="store_whatsapp" value="<?php echo e(old('store_whatsapp', $settings->store_whatsapp)); ?>" required
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                            <?php $__errorArgs = ['store_whatsapp'];
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

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Website
                                            </label>
                                            <input type="url" name="store_website" value="<?php echo e(old('store_website', $settings->store_website)); ?>"
                                                   placeholder="https://www.example.com"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                            <?php $__errorArgs = ['store_website'];
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
                                    </div>
                                </div>

                                <!-- Address & Hours -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Address *
                                    </label>
                                    <textarea name="store_address" rows="3" required
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"><?php echo e(old('store_address', $settings->store_address)); ?></textarea>
                                    <?php $__errorArgs = ['store_address'];
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

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Operating Hours *
                                    </label>
                                    <input type="text" name="store_hours" value="<?php echo e(old('store_hours', $settings->store_hours)); ?>" required
                                           placeholder="Monday - Sunday, 08:00 - 22:00"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                    <?php $__errorArgs = ['store_hours'];
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

                                <!-- Social Media -->
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                        📱 Social Media
                                    </h3>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Instagram
                                            </label>
                                            <input type="text" name="store_instagram" value="<?php echo e(old('store_instagram', $settings->store_instagram)); ?>"
                                                   placeholder="@skyramart"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Facebook
                                            </label>
                                            <input type="text" name="store_facebook" value="<?php echo e(old('store_facebook', $settings->store_facebook)); ?>"
                                                   placeholder="facebook.com/skyramart"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                        </div>
                                    </div>
                                </div>

                                <!-- Store Description -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Description
                                    </label>
                                    <textarea name="store_description" rows="4"
                                              placeholder="Brief description about your store..."
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"><?php echo e(old('store_description', $settings->store_description)); ?></textarea>
                                </div>

                                <!-- Currency & Timezone -->
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                        ⚙️ Regional Settings
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Currency Symbol *
                                            </label>
                                            <input type="text" name="currency_symbol" value="<?php echo e(old('currency_symbol', $settings->currency_symbol)); ?>" required
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Currency Code *
                                            </label>
                                            <input type="text" name="currency_code" value="<?php echo e(old('currency_code', $settings->currency_code)); ?>" required
                                                   maxlength="3"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Timezone *
                                            </label>
                                            <select name="timezone" required
                                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                                <option value="Asia/Jakarta" <?php echo e(old('timezone', $settings->timezone) == 'Asia/Jakarta' ? 'selected' : ''); ?>>Asia/Jakarta</option>
                                                <option value="Asia/Singapore" <?php echo e(old('timezone', $settings->timezone) == 'Asia/Singapore' ? 'selected' : ''); ?>>Asia/Singapore</option>
                                                <option value="Asia/Kuala_Lumpur" <?php echo e(old('timezone', $settings->timezone) == 'Asia/Kuala_Lumpur' ? 'selected' : ''); ?>>Asia/Kuala_Lumpur</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Store Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Info Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                💡 How It Works
                            </h3>
                            
                            <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-3">
                                    <p class="text-blue-800 dark:text-blue-200">
                                        <strong>Store Settings</strong> will automatically replace variables in all templates (receipts, emails, WhatsApp messages).
                                    </p>
                                </div>

                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100 mb-2">Available Variables:</p>
                                    <ul class="space-y-1">
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_name}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_email}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_phone}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_whatsapp}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_address}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{store_hours}</code></li>
                                        <li>• <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded text-xs">{currency_symbol}</code></li>
                                    </ul>
                                </div>

                                <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-3">
                                    <p class="text-purple-800 dark:text-purple-200">
                                        <strong>Tip:</strong> Update these settings once, and all your templates will automatically use the new information!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Logo Form -->
    <form id="deleteLogoForm" method="POST" action="<?php echo e(route('store-settings.delete-logo')); ?>" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <script>
        function deleteLogo() {
            if (confirm('Are you sure you want to delete the store logo?')) {
                document.getElementById('deleteLogoForm').submit();
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
<?php endif; ?><?php /**PATH C:\laragon\www\app-pos-skyramart\resources\views/store-settings/index.blade.php ENDPATH**/ ?>