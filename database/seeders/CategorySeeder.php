<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan'],
            ['name' => 'Minuman'],
            ['name' => 'Alat Tulis'],
            ['name' => 'Atribut & Perlengkapan Lainnya'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'is_active' => true,
            ]);
        }
    }
}
