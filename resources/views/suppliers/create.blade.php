<x-app-layout>
    <div id="pageLoader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-4"></div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Loading Create Supplier Page...</h3>
            <p class="text-gray-500 dark:text-gray-400">Please wait while we prepare everything</p>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                    {{ __('Add New Supplier') }}
                </h2>
            </div>
            <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-600">Suppliers</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Create</span>
            </nav>
        </div>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                        <span class="text-sm font-medium text-white">1</span>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Supplier Information</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="px-6 py-8 sm:p-10">
                    <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- Profile Photo Section -->
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Profile Photo</h3>
                            <div class="flex flex-col items-center space-y-4">
                                <div id="photo-preview" class="relative">
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:bg-gradient-to-br dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg">
                                        <img id="preview-image" class="w-full h-full object-cover hidden" alt="Profile preview">
                                        <svg class="w-16 h-16 text-blue-400 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <label for="photo_profile" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-xl transition duration-200 ease-in-out transform hover:scale-105 shadow-md">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Choose Photo
                                    </label>
                                    <input type="file" name="photo_profile" id="photo_profile" accept="image/*" class="hidden">
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">JPG, PNG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('photo_profile')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Supplier Name -->
                                <div class="md:col-span-2">
                                    <label for="supplier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Supplier Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" required
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200"
                                           placeholder="Enter supplier name">
                                    @error('supplier_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Store Name -->
                                <div>
                                    <label for="store_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Store Name
                                    </label>
                                    <input type="text" name="store_name" id="store_name" value="{{ old('store_name') }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200"
                                           placeholder="Store or company name">
                                    @error('store_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200"
                                           placeholder="+62 812-3456-7890">
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200"
                                           placeholder="supplier@example.com">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200">
                                        <option value="">Select Province</option>
                                        @foreach(\Laravolt\Indonesia\Models\Province::all() as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
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
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200" disabled>
                                        <option value="">Select Province First</option>
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
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200" disabled>
                                        <option value="">Select City First</option>
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
                                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200" disabled>
                                        <option value="">Select District First</option>
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
                                              class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition duration-200"
                                              placeholder="Street name, building number, additional info...">{{ old('detail_address') }}</textarea>
                                    @error('detail_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map Section -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    📍 Pin Location on Map
                                </label>
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden shadow-inner">
                                    <div id="map" style="height: 400px; width: 100%;"></div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Click on the map or drag the marker to set exact location
                                </p>
                                
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                
                                <div class="mt-3 grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Latitude:</span>
                                        <div id="lat-display" class="text-sm font-medium text-gray-900 dark:text-white">Not set</div>
                                    </div>
                                    <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Longitude:</span>
                                        <div id="lng-display" class="text-sm font-medium text-gray-900 dark:text-white">Not set</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </h3>
                            <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-xl">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-3 flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Active Supplier</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Supplier can provide products and services</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('suppliers.index') }}" 
                               class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Supplier
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
// Image preview
document.getElementById('photo_profile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview-image');
    const photoPreview = document.getElementById('photo-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            photoPreview.querySelector('svg').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Initialize Map
let map, marker;
const defaultLat = -6.2088;
const defaultLng = 106.8456;

document.addEventListener('DOMContentLoaded', function() {
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    const oldLat = document.getElementById('latitude').value;
    const oldLng = document.getElementById('longitude').value;
    if (oldLat && oldLng) {
        setMarker(parseFloat(oldLat), parseFloat(oldLng));
        map.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
    }

    // Cascading Dropdowns
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');
    const detailAddressInput = document.getElementById('detail_address');
    
    let geocodeTimeout;

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        
        citySelect.innerHTML = '<option value="">Select City/Regency</option>';
        districtSelect.innerHTML = '<option value="">Select City First</option>';
        villageSelect.innerHTML = '<option value="">Select District First</option>';
        
        citySelect.disabled = true;
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        if (provinceId) {
            fetch(`/api/cities/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City/Regency</option>';
                    data.forEach(city => {
                        citySelect.add(new Option(city.name, city.id));
                    });
                    citySelect.disabled = false;
                })
                .catch(error => console.error('Error:', error));
            
            tryAutoGeocode();
        }
    });

    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        
        districtSelect.innerHTML = '<option value="">Select District</option>';
        villageSelect.innerHTML = '<option value="">Select District First</option>';
        
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        if (cityId) {
            fetch(`/api/districts/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    data.forEach(district => {
                        districtSelect.add(new Option(district.name, district.id));
                    });
                    districtSelect.disabled = false;
                })
                .catch(error => console.error('Error:', error));
            
            tryAutoGeocode();
        }
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        villageSelect.disabled = true;
        
        if (districtId) {
            fetch(`/api/villages/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">Select Village</option>';
                    data.forEach(village => {
                        villageSelect.add(new Option(village.name, village.id));
                    });
                    villageSelect.disabled = false;
                })
                .catch(error => console.error('Error:', error));
            
            tryAutoGeocode();
        }
    });

    villageSelect.addEventListener('change', function() {
        if (this.value) tryAutoGeocode();
    });

    detailAddressInput.addEventListener('input', function() {
        clearTimeout(geocodeTimeout);
        if (this.value.trim().length > 10) {
            geocodeTimeout = setTimeout(() => tryAutoGeocode(), 1500);
        }
    });

    function tryAutoGeocode() {
        const detailAddress = detailAddressInput.value.trim();
        if (detailAddress.length > 5 && provinceSelect.value) {
            geocodeAddress(detailAddress);
        }
    }
});

function setMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
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
    
    tryGeocodeWithFallback(addressVariations, 0);
}

function tryGeocodeWithFallback(addressVariations, index) {
    if (index >= addressVariations.length) {
        showNotification('⚠️ Location not found. Please pin manually.', 'warning');
        return;
    }
    
    const currentAddress = addressVariations[index];
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(currentAddress)}&limit=1&countrycodes=id`;
    
    fetch(url, { headers: { 'User-Agent': 'SupplierApp/1.0' } })
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                setMarker(lat, lng);
                map.flyTo([lat, lng], 17, { duration: 1.5 });
                showNotification('📍 Location found!', 'success');
            } else {
                setTimeout(() => tryGeocodeWithFallback(addressVariations, index + 1), 500);
            }
        })
        .catch(error => {
            setTimeout(() => tryGeocodeWithFallback(addressVariations, index + 1), 500);
        });
}

function showNotification(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        warning: 'bg-yellow-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const oldNotification = document.querySelector('.custom-notification');
    if (oldNotification) oldNotification.remove();
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transition-all duration-300`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    setTimeout(() => {
        loader.style.opacity = '0';
        setTimeout(() => loader.style.display = 'none', 300);
    }, 800);
});
    </script>

    <style>
        .leaflet-container { z-index: 1; }
    </style>
</x-app-layout>