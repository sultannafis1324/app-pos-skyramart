<x-app-layout>

    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4">
            </div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Edit Customers Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                    {{ __('Edit Customer') }}
                </h2>
            </div>
            <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('customers.index') }}" class="hover:text-indigo-600">Customers</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Edit</span>
            </nav>
        </div>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-600 rounded-full">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Update Customer Information</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="px-6 py-8 sm:p-10">
                    <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Profile Photo Section -->
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Profile Photo</h3>
                            <div class="flex flex-col items-center space-y-4">
                                <div id="photo-preview" class="relative">
                                    <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                                        @if($customer->photo_profile)
                                            <img id="preview-image" src="{{ asset('storage/' . $customer->photo_profile) }}" class="w-full h-full object-cover" alt="Profile photo">
                                        @else
                                            <img id="preview-image" class="w-full h-full object-cover hidden" alt="Profile preview">
                                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <label for="photo_profile" class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $customer->photo_profile ? 'Change Photo' : 'Choose Photo' }}
                                    </label>
                                    <input type="file" name="photo_profile" id="photo_profile" accept="image/*" class="hidden">
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">JPG, PNG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('photo_profile')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Name -->
                                <div class="md:col-span-2">
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Customer Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" required
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200"
                                           placeholder="Enter full name">
                                    @error('customer_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200"
                                           placeholder="+62 812-3456-7890">
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200"
                                           placeholder="john@example.com">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gender
                                    </label>
                                    <select name="gender" id="gender" 
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                        <option value="">Select Gender</option>
                                        <option value="M" {{ old('gender', $customer->gender) === 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('gender', $customer->gender) === 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Birth Date -->
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Birth Date
                                    </label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $customer->birth_date?->format('Y-m-d')) }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                    @error('birth_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bank Name -->
                                <div class="md:col-span-2">
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Bank Name
                                    </label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $customer->bank_name) }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200"
                                           placeholder="Enter bank name (e.g., BCA, BNI, Mandiri)">
                                    @error('bank_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Address Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Province -->
                                <div>
                                    <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Province
                                    </label>
                                    <select name="province_id" id="province_id" 
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                        <option value="">Select Province</option>
                                        @foreach(\Laravolt\Indonesia\Models\Province::all() as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id', $customer->address->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        City/Regency
                                    </label>
                                    <select name="city_id" id="city_id" 
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                        <option value="">Select City/Regency</option>
                                    </select>
                                    @error('city_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- District -->
                                <div>
                                    <label for="district_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        District
                                    </label>
                                    <select name="district_id" id="district_id" 
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                        <option value="">Select District</option>
                                    </select>
                                    @error('district_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Village -->
                                <div>
                                    <label for="village_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Village
                                    </label>
                                    <select name="village_id" id="village_id" 
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200">
                                        <option value="">Select Village</option>
                                    </select>
                                    @error('village_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Detail Address -->
                                <div class="md:col-span-2">
                                    <label for="detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Detail Address
                                    </label>
                                    <textarea name="detail_address" id="detail_address" rows="3"
                                              class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 transition duration-200"
                                              placeholder="Street name, building number, RT/RW, etc...">{{ old('detail_address', $customer->address->detail_address ?? '') }}</textarea>
                                    @error('detail_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map Section -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pin Location on Map
                                </label>
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden">
                                    <div id="map" style="height: 400px; width: 100%;"></div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Click on the map to set customer location or drag the marker
                                </p>
                                
                                <!-- Hidden inputs for coordinates -->
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $customer->address->latitude ?? '') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $customer->address->longitude ?? '') }}">
                                
                                <!-- Coordinate display -->
                                <div class="mt-3 grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Latitude:</span>
                                        <div id="lat-display" class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->address->latitude ?? 'Not set' }}</div>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Longitude:</span>
                                        <div id="lng-display" class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->address->longitude ?? 'Not set' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Account Status
                            </h3>
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                                       class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_active" class="ml-3 flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Active Customer</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Customer can make purchases and earn loyalty points</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('customers.index') }}" 
                               class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
// Image preview functionality
document.getElementById('photo_profile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview-image');
    const photoPreview = document.getElementById('photo-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            photoPreview.querySelector('svg')?.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Initialize Map
let map;
let marker;
const defaultLat = {{ $customer->address->latitude ?? -6.2088 }};
const defaultLng = {{ $customer->address->longitude ?? 106.8456 }};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Add click event to map
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // Initialize marker if coordinates exist
    const oldLat = document.getElementById('latitude').value;
    const oldLng = document.getElementById('longitude').value;
    if (oldLat && oldLng) {
        setMarker(parseFloat(oldLat), parseFloat(oldLng));
        map.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
    }

    // Get select elements
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');
    const provinceSelect = document.getElementById('province_id');
    
    // Saved values for edit mode
    const savedCityId = "{{ old('city_id', $customer->address->city_id ?? '') }}";
    const savedDistrictId = "{{ old('district_id', $customer->address->district_id ?? '') }}";
    const savedVillageId = "{{ old('village_id', $customer->address->village_id ?? '') }}";

    // Check and disable dropdowns
    function checkAndDisableDropdowns() {
        if (!provinceSelect.value) {
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Pilih Provinsi Dulu</option>';
        }
        
        if (!citySelect.value || citySelect.disabled) {
            districtSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Pilih Kota Dulu</option>';
        }
        
        if (!districtSelect.value || districtSelect.disabled) {
            villageSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
        }
    }
    
    // Load existing data on page load
    if (provinceSelect.value) {
        loadCities(provinceSelect.value, savedCityId);
    } else {
        checkAndDisableDropdowns();
    }

    // Province change event
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        
        citySelect.innerHTML = '<option value="">Select City/Regency</option>';
        districtSelect.innerHTML = '<option value="">Pilih Kota Dulu</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
        
        citySelect.disabled = true;
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        if (provinceId) {
            loadCities(provinceId);
            tryAutoGeocode();
        }
    });

    // City change event
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        
        districtSelect.innerHTML = '<option value="">Select District</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
        
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        if (cityId) {
            loadDistricts(cityId, savedDistrictId);
            tryAutoGeocode();
        }
    });

    // District change event
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        villageSelect.disabled = true;
        
        if (districtId) {
            loadVillages(districtId, savedVillageId);
            tryAutoGeocode();
        }
    });

    // Village change event
    villageSelect.addEventListener('change', function() {
        if (this.value) {
            tryAutoGeocode();
        }
    });

    // Auto Geocoding untuk detail address
    const detailAddressInput = document.getElementById('detail_address');
    let geocodeTimeout;

    detailAddressInput.addEventListener('input', function() {
        clearTimeout(geocodeTimeout);
        const address = this.value.trim();
        
        if (address.length > 10) {
            geocodeTimeout = setTimeout(() => {
                tryAutoGeocode();
            }, 1500);
        }
    });

    // Functions to load dropdown data
    function loadCities(provinceId, selectedCityId = null) {
        fetch(`/api/cities/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City/Regency</option>';
                data.forEach(city => {
                    const option = new Option(city.name, city.id);
                    if (selectedCityId && city.id == selectedCityId) {
                        option.selected = true;
                    }
                    citySelect.add(option);
                });
                citySelect.disabled = false;
                
                // If we have a selected city, load its districts
                if (selectedCityId) {
                    loadDistricts(selectedCityId, savedDistrictId);
                }
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                citySelect.disabled = true;
            });
    }

    function loadDistricts(cityId, selectedDistrictId = null) {
        fetch(`/api/districts/${cityId}`)
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                data.forEach(district => {
                    const option = new Option(district.name, district.id);
                    if (selectedDistrictId && district.id == selectedDistrictId) {
                        option.selected = true;
                    }
                    districtSelect.add(option);
                });
                districtSelect.disabled = false;
                
                // If we have a selected district, load its villages
                if (selectedDistrictId) {
                    loadVillages(selectedDistrictId, savedVillageId);
                }
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                districtSelect.disabled = true;
            });
    }

    function loadVillages(districtId, selectedVillageId = null) {
        fetch(`/api/villages/${districtId}`)
            .then(response => response.json())
            .then(data => {
                villageSelect.innerHTML = '<option value="">Select Village</option>';
                data.forEach(village => {
                    const option = new Option(village.name, village.id);
                    if (selectedVillageId && village.id == selectedVillageId) {
                        option.selected = true;
                    }
                    villageSelect.add(option);
                });
                villageSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading villages:', error);
                villageSelect.disabled = true;
            });
    }

    function tryAutoGeocode() {
        const detailAddress = detailAddressInput.value.trim();
        
        if (detailAddress.length > 5 && provinceSelect.value) {
            geocodeAddress(detailAddress);
        }
    }

    // Add floating label effect
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        if (input.value) {
            input.classList.add('has-value');
        }
        
        input.addEventListener('blur', function() {
            if (this.value) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
});

function setMarker(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    updateCoordinates(lat, lng);
    
    marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
    document.getElementById('lat-display').textContent = lat.toFixed(6);
    document.getElementById('lng-display').textContent = lng.toFixed(6);
}

function geocodeAddress(address) {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');
    
    let addressVariations = [];
    
    // Versi 1: Alamat lengkap dengan detail
    let fullAddress = address;
    if (villageSelect.value && villageSelect.selectedIndex > 0) {
        fullAddress += ', ' + villageSelect.options[villageSelect.selectedIndex].text;
    }
    if (districtSelect.value && districtSelect.selectedIndex > 0) {
        fullAddress += ', ' + districtSelect.options[districtSelect.selectedIndex].text;
    }
    if (citySelect.value && citySelect.selectedIndex > 0) {
        fullAddress += ', ' + citySelect.options[citySelect.selectedIndex].text;
    }
    if (provinceSelect.value && provinceSelect.selectedIndex > 0) {
        fullAddress += ', ' + provinceSelect.options[provinceSelect.selectedIndex].text;
    }
    fullAddress += ', Indonesia';
    addressVariations.push(fullAddress);
    
    // Versi 2: Tanpa detail address
    let generalAddress = '';
    if (villageSelect.value && villageSelect.selectedIndex > 0) {
        generalAddress = villageSelect.options[villageSelect.selectedIndex].text;
    }
    if (districtSelect.value && districtSelect.selectedIndex > 0) {
        generalAddress += (generalAddress ? ', ' : '') + districtSelect.options[districtSelect.selectedIndex].text;
    }
    if (citySelect.value && citySelect.selectedIndex > 0) {
        generalAddress += (generalAddress ? ', ' : '') + citySelect.options[citySelect.selectedIndex].text;
    }
    if (provinceSelect.value && provinceSelect.selectedIndex > 0) {
        generalAddress += (generalAddress ? ', ' : '') + provinceSelect.options[provinceSelect.selectedIndex].text;
    }
    generalAddress += ', Indonesia';
    if (generalAddress !== fullAddress) {
        addressVariations.push(generalAddress);
    }
    
    // Versi 3: Hanya kecamatan dan kota
    if (districtSelect.value && districtSelect.selectedIndex > 0 && citySelect.value && citySelect.selectedIndex > 0) {
        let districtCity = districtSelect.options[districtSelect.selectedIndex].text + ', ' + 
                          citySelect.options[citySelect.selectedIndex].text + ', Indonesia';
        addressVariations.push(districtCity);
    }
    
    // Versi 4: Hanya kota dan provinsi
    if (citySelect.value && citySelect.selectedIndex > 0) {
        let cityProvince = citySelect.options[citySelect.selectedIndex].text;
        if (provinceSelect.value && provinceSelect.selectedIndex > 0) {
            cityProvince += ', ' + provinceSelect.options[provinceSelect.selectedIndex].text;
        }
        cityProvince += ', Indonesia';
        addressVariations.push(cityProvince);
    }
    
    console.log('Trying address variations:', addressVariations);
    tryGeocodeWithFallback(addressVariations, 0);
}

function tryGeocodeWithFallback(addressVariations, index) {
    if (index >= addressVariations.length) {
        console.log('All address variations failed');
        showNotification('⚠️ Lokasi tidak ditemukan. Silakan pin manual di peta.', 'warning');
        return;
    }
    
    const currentAddress = addressVariations[index];
    console.log(`Trying geocoding attempt ${index + 1}: ${currentAddress}`);
    
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(currentAddress)}&limit=1&countrycodes=id`;
    
    fetch(url, {
        headers: {
            'User-Agent': 'CustomerManagementApp/1.0'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                console.log(`Location found on attempt ${index + 1}:`, lat, lng);
                
                setMarker(lat, lng);
                map.flyTo([lat, lng], index === 0 ? 17 : (index === 1 ? 16 : 15), {
                    duration: 1.5
                });
                
                if (index === 0) {
                    showNotification('📍 Lokasi detail ditemukan!', 'success');
                } else {
                    showNotification('📍 Lokasi area ditemukan. Geser marker untuk lokasi tepat. Pastikan Nama Jalan nya benar!', 'info');
                }
            } else {
                console.log(`Attempt ${index + 1} failed, trying next variation...`);
                setTimeout(() => {
                    tryGeocodeWithFallback(addressVariations, index + 1);
                }, 500);
            }
        })
        .catch(error => {
            console.error('Geocoding error:', error);
            setTimeout(() => {
                tryGeocodeWithFallback(addressVariations, index + 1);
            }, 500);
        });
}

function showNotification(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        warning: 'bg-yellow-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const bgColor = colors[type] || colors.success;
    
    const oldNotification = document.querySelector('.custom-notification');
    if (oldNotification) {
        oldNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transition-all duration-300 transform translate-x-0`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1000); // Show loader for at least 500ms
        });

        // Fallback: Hide loader after 3 seconds max
        setTimeout(() => {
            const loader = document.getElementById('pageLoader');
            if (loader && !loader.classList.contains('hide')) {
                loader.classList.add('hide');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }
        }, 5000);
    </script>

    <style>
        .has-value {
            @apply border-indigo-300 ring-1 ring-indigo-300;
        }
        
        .leaflet-container {
            z-index: 1;
        }
    </style>
</x-app-layout>