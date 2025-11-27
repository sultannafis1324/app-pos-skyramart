<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin default
        User::create([
            'name' => 'Sultan',
            'email' => 'sultannafis1324@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => User::ROLE_ADMIN,
            'phone' => '089654557984',
            'photo_profile' => null,
        ]);

        // Cashier default
        User::create([
            'name' => 'Tantan',
            'email' => 'sultan.nafis65@smk.belajar.id',
            'password' => Hash::make('12345678'),
            'role' => User::ROLE_CASHIER,
            'phone' => '089876543210',
            'photo_profile' => null,
        ]);
    }
}
