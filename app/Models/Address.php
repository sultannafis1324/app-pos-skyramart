<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{

    use HasFactory;
    protected $fillable = [
        'province_id', 'city_id', 'district_id', 'village_id',
        'detail_address', 'latitude', 'longitude',
    ];

    // PERBAIKAN: Gunakan code sebagai foreign key
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }
}