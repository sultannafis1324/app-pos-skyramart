<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class IndonesiaTableSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('laravolt:indonesia:seed');
    }
}
