<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Customer 1: Muhammad Raafi Rudhi
        $this->createCustomer([
            'name' => 'Muhammad Raafi Rudhi',
            'phone' => '085311956497',
            'email' => 'prommaucf566@gmail.com',
            'birth_date' => '2007-03-05',
            'province_name' => 'JAWA BARAT',
            'city_name' => 'KOTA DEPOK',
            'district_name' => 'Cinere',
            'village_name' => 'Cinere',
            'detail_address' => 'Jalan Markisa Ujung',
        ]);

        // Customer 2: Nilam Nur Hasbullah
        $this->createCustomer([
            'name' => 'Nilam Nur Hasbullah',
            'phone' => '08950582221',
            'email' => 'nilamnurhasbullah3@gmail.com',
            'birth_date' => '2007-06-28',
            'province_name' => 'JAWA BARAT',
            'city_name' => 'KOTA DEPOK',
            'district_name' => 'Cinere',
            'village_name' => 'Cinere',
            'detail_address' => 'Jalan SD Inpres',
        ]);

        // Customer 3: Muhammad Faris Saleh
        $this->createCustomer([
            'name' => 'Sultan Nafis',
            'phone' => '089654557984',
            'email' => 'sultannafis1324@gmail.com',
            'birth_date' => '2006-01-24',
            'province_name' => 'JAWA BARAT',
            'city_name' => 'KOTA DEPOK',
            'district_name' => 'Limo',
            'village_name' => 'Limo',
            'detail_address' => 'Jalan Pinang Dua',
        ]);
    }

    private function createCustomer(array $data): void
    {
        // Cari province
        $province = Province::where('name', 'LIKE', '%' . $data['province_name'] . '%')->first();
        
        if (!$province) {
            $this->command->error("Province tidak ditemukan: {$data['province_name']}");
            return;
        }

        // Cari city
        $city = City::where('province_code', $province->code)
            ->where('name', 'LIKE', '%' . $data['city_name'] . '%')
            ->first();
        
        if (!$city) {
            $this->command->error("City tidak ditemukan: {$data['city_name']}");
            return;
        }

        // Cari district
        $district = District::where('city_code', $city->code)
            ->where('name', 'LIKE', '%' . $data['district_name'] . '%')
            ->first();
        
        if (!$district) {
            $this->command->error("District tidak ditemukan: {$data['district_name']}");
            return;
        }

        // Cari village
        $village = Village::where('district_code', $district->code)
            ->where('name', 'LIKE', '%' . $data['village_name'] . '%')
            ->first();
        
        if (!$village) {
            $this->command->error("Village tidak ditemukan: {$data['village_name']}");
            return;
        }

        // Geocode alamat untuk mendapatkan koordinat
        $coordinates = $this->geocodeAddress(
            $data['detail_address'],
            $village->name,
            $district->name,
            $city->name,
            $province->name
        );

        // Buat address dengan koordinat
        $address = Address::create([
            'province_id' => $province->id,
            'city_id' => $city->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
            'detail_address' => $data['detail_address'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);

        // Buat customer
        Customer::create([
            'customer_name' => $data['name'],
            'address_id' => $address->id,
            'phone_number' => $data['phone'],
            'email' => $data['email'],
            'birth_date' => $data['birth_date'],
            'is_active' => true,
            'total_purchase' => 0,
            'loyalty_points' => 0,
        ]);

        if ($coordinates['latitude'] && $coordinates['longitude']) {
            $this->command->info("✓ Customer berhasil dibuat: {$data['name']} [Lat: {$coordinates['latitude']}, Lng: {$coordinates['longitude']}]");
        } else {
            $this->command->warn("✓ Customer berhasil dibuat: {$data['name']} [Koordinat tidak ditemukan]");
        }
    }

    /**
     * Geocode alamat menggunakan Nominatim OpenStreetMap
     * Dengan fallback dari alamat lengkap ke alamat umum
     */
    private function geocodeAddress(
        string $detailAddress,
        string $village,
        string $district,
        string $city,
        string $province
    ): array {
        // Siapkan variasi alamat dari spesifik ke umum
        $addressVariations = [
            // Versi 1: Alamat lengkap
            "{$detailAddress}, {$village}, {$district}, {$city}, {$province}, Indonesia",
            // Versi 2: Tanpa detail address
            "{$village}, {$district}, {$city}, {$province}, Indonesia",
            // Versi 3: Kecamatan dan kota
            "{$district}, {$city}, Indonesia",
            // Versi 4: Kota dan provinsi
            "{$city}, {$province}, Indonesia",
        ];

        foreach ($addressVariations as $index => $fullAddress) {
            $this->command->info("Geocoding attempt " . ($index + 1) . ": {$fullAddress}");
            
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'LaravelCustomerSeeder/1.0'
                    ])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'format' => 'json',
                        'q' => $fullAddress,
                        'limit' => 1,
                        'countrycodes' => 'id'
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                        $latitude = round((float) $data[0]['lat'], 7);
                        $longitude = round((float) $data[0]['lon'], 7);
                        
                        $this->command->info("  → Lokasi ditemukan!");
                        
                        return [
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        ];
                    }
                }
                
                // Delay antar request untuk menghormati rate limit Nominatim
                sleep(1);
                
            } catch (\Exception $e) {
                $this->command->warn("  → Geocoding error: " . $e->getMessage());
                sleep(1);
                continue;
            }
        }

        $this->command->warn("  → Koordinat tidak ditemukan untuk semua variasi alamat");
        
        return [
            'latitude' => null,
            'longitude' => null,
        ];
    }
}