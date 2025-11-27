<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $province = Province::inRandomOrder()->first();
        $city = City::where('province_code', $province->code)->inRandomOrder()->first();
        $district = District::where('city_code', $city->code)->inRandomOrder()->first();
        $village = Village::where('district_code', $district->code)->inRandomOrder()->first();

        return [
            'province_id'    => $province->id,
            'city_id'        => $city->id,
            'district_id'    => $district->id,
            'village_id'     => $village->id,
            'detail_address' => $this->faker->streetAddress(),
            'latitude'       => $this->faker->latitude(-8, 6),
            'longitude'      => $this->faker->longitude(95, 141),
        ];
    }
}
