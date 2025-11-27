<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori berdasarkan nama
        $makananId = Category::where('name', 'Makanan')->first()?->id;
        $minumanId = Category::where('name', 'Minuman')->first()?->id;
        $alatTulisId = Category::where('name', 'Alat Tulis')->first()?->id;
        $atributId = Category::where('name', 'Atribut & Perlengkapan Lainnya')->first()?->id;

        // Validasi kategori
        if (!$makananId || !$minumanId || !$alatTulisId || !$atributId) {
            $this->command->error('Kategori tidak lengkap. Jalankan CategorySeeder terlebih dahulu.');
            return;
        }

        $products = [
            // ===== Makanan =====
            [
                'product_name' => 'Indomie Goreng',
                'description' => "Mi goreng instan dengan rasa original yang gurih dan nikmat. 
Mudah dimasak hanya dalam beberapa menit sehingga praktis untuk siapa saja. 
Cocok disantap kapan saja, baik siang maupun malam, sebagai teman santai ataupun pengganjal lapar.",
                'selling_price' => 3500,
                'purchase_price' => 2500,
                'stock' => 100,
                'minimum_stock' => 10,
                'category_id' => $makananId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2026-05-15',
            ],
            [
                'product_name' => 'Chitato Sapi Panggang',
                'description' => "Keripik kentang dengan rasa sapi panggang yang lezat dan menggoda.
                                  Renyah di setiap gigitan dengan bumbu khas Chitato yang bikin nagih.
                                  Pas untuk camilan saat santai atau kumpul bersama teman-teman.",
                'selling_price' => 8000,
                'purchase_price' => 6000,
                'stock' => 80,
                'minimum_stock' => 5,
                'category_id' => $makananId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2026-07-20',
            ],
            [
                'product_name' => 'Oreo Original',
                'description' => "Biskuit sandwich legendaris dengan krim vanilla manis dan lembut. 
                                  Bisa dinikmati dengan cara diputar, dijilat, lalu dicelup ke susu. 
                                  Cemilan klasik yang disukai oleh anak-anak hingga orang dewasa.",
                'selling_price' => 6000,
                'purchase_price' => 4000,
                'stock' => 120,
                'minimum_stock' => 10,
                'category_id' => $makananId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2026-09-10',
            ],
            [
                'product_name' => 'SilverQueen Chunky Bar',
                'description' => "Cokelat kacang mete khas Indonesia yang terkenal enak dan berkualitas.
                                  Potongan kacang mete yang besar berpadu dengan cokelat lembut. 
                                  Cocok untuk dinikmati sendiri atau dibagikan bersama orang terdekat.",
                'selling_price' => 15000,
                'purchase_price' => 11000,
                'stock' => 60,
                'minimum_stock' => 10,
                'category_id' => $makananId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2025-11-15',
            ],

            // ===== Minuman =====
            [
                'product_name' => 'Teh Botol Sosro',
                'description' => "Minuman teh jasmine asli Indonesia yang menyegarkan.
                                  Rasanya manis pas, cocok diminum dingin maupun suhu ruang. 
                                  Teman setia saat makan siang, bepergian, atau kumpul santai bersama keluarga.",
                'selling_price' => 5000,
                'purchase_price' => 3500,
                'stock' => 50,
                'minimum_stock' => 15,
                'category_id' => $minumanId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2026-08-10',
            ],
            [
                'product_name' => 'Aqua Botol 600ml',
                'description' => "Air mineral murni dalam kemasan botol 600ml yang praktis.
                                  Menjaga tubuh tetap segar dan terhidrasi sepanjang hari. 
                                  Mudah dibawa ke sekolah, kantor, atau aktivitas luar ruangan.",
                'selling_price' => 4000,
                'purchase_price' => 2000,
                'stock' => 100,
                'minimum_stock' => 15,
                'category_id' => $minumanId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2027-11-04',
            ],
            [
                'product_name' => 'Pocari Sweat',
                'description' => "Minuman isotonik yang membantu mengembalikan ion tubuh dengan cepat. 
                                  Mencegah dehidrasi dan menjaga kondisi tubuh tetap segar. 
                                  Cocok dikonsumsi setelah olahraga atau aktivitas fisik berat.",
                'selling_price' => 8000,
                'purchase_price' => 5000,
                'stock' => 100,
                'minimum_stock' => 5,
                'category_id' => $minumanId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2025-11-30',
            ],
            [
                'product_name' => 'Good Day Cappuccino',
                'description' => "Minuman kopi instan rasa cappuccino yang creamy dan nikmat. 
                                  Hadir dalam kemasan praktis untuk menemani waktu istirahat singkat. 
                                  Cocok diminum saat butuh semangat tambahan di pagi atau sore hari.",
                'selling_price' => 7000,
                'purchase_price' => 5000,
                'stock' => 90,
                'minimum_stock' => 5,
                'category_id' => $minumanId,
                'unit' => 'pcs',
                'supplier_id' => 1,
                'has_expiry' => true,
                'expiry_date' => '2026-07-15',
            ],

            // ===== Alat Tulis =====
            [
                'product_name' => 'Pulpen Pilot Hitam',
                'description' => "Pulpen gel hitam yang nyaman digunakan untuk menulis. 
                                  Tinta mengalir lancar sehingga tulisan terlihat lebih jelas. 
                                  Pilihan tepat untuk keperluan sekolah, kantor, maupun catatan harian.",
                'selling_price' => 7000,
                'purchase_price' => 4000,
                'stock' => 100,
                'minimum_stock' => 10,
                'category_id' => $alatTulisId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Pensil 2B Faber Castell',
                'description' => "Pensil 2B berkualitas yang pas untuk ujian maupun menggambar. 
                                  Tajam dan tidak mudah patah saat digunakan menulis. 
                                  Menjadi pilihan utama bagi siswa dan mahasiswa.",
                'selling_price' => 3000,
                'purchase_price' => 1500,
                'stock' => 100,
                'minimum_stock' => 10,
                'category_id' => $alatTulisId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Penghapus Staedtler',
                'description' => "Penghapus karet putih dengan kualitas tinggi. 
                                  Mampu menghapus tulisan pensil dengan bersih tanpa merusak kertas. 
                                  Ideal untuk kegiatan belajar dan pekerjaan kantor.",
                'selling_price' => 5000,
                'purchase_price' => 2500,
                'stock' => 150,
                'minimum_stock' => 30,
                'category_id' => $alatTulisId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Buku Tulis University 58 Lembar',
                'description' => "Buku tulis bergaris dengan isi 58 lembar. 
                                  Kertas tebal yang nyaman digunakan menulis dengan pulpen maupun pensil. 
                                  Cocok untuk pelajar, mahasiswa, ataupun catatan sehari-hari.",
                'selling_price' => 4000,
                'purchase_price' => 2000,
                'stock' => 40,
                'minimum_stock' => 10,
                'category_id' => $alatTulisId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],

            // ===== Atribut & Perlengkapan =====
            [
                'product_name' => 'Topi Sekolah',
                'description' => "Topi sekolah SMK dengan warna abu-abu standar.
                                  Bahan kuat dan nyaman digunakan untuk kegiatan sehari-hari.
                                  Wajib dipakai sesuai aturan seragam sekolah.",
                'selling_price' => 12000,
                'purchase_price' => 8000,
                'stock' => 70,
                'minimum_stock' => 15,
                'category_id' => $atributId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Dasi Sekolah',
                'description' => "Dasi sekolah warna abu-abu untuk pelajar SMK.
                                  Desain formal dan sesuai aturan seragam resmi.
                                  Cocok dipakai pada upacara maupun kegiatan belajar mengajar.",
                'selling_price' => 8000,
                'purchase_price' => 5000,
                'stock' => 100,
                'minimum_stock' => 10,
                'category_id' => $atributId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Sabuk Sekolah',
                'description' => "Sabuk seragam sekolah warna hitam dengan kualitas baik.
                                  Bahan kuat dan awet untuk penggunaan jangka panjang.
                                  Membuat penampilan lebih rapi sesuai aturan sekolah.",
                'selling_price' => 15000,
                'purchase_price' => 10000,
                'stock' => 80,
                'minimum_stock' => 5,
                'category_id' => $atributId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
            [
                'product_name' => 'Kaos Kaki Sekolah',
                'description' => "Kaos kaki putih untuk pelengkap seragam sekolah.
                                  Bahan lembut, elastis, dan nyaman dipakai sepanjang hari.
                                  Wajib dipakai agar penampilan lebih rapi dan sesuai aturan sekolah.",
                'selling_price' => 10000,
                'purchase_price' => 7000,
                'stock' => 150,
                'minimum_stock' => 10,
                'category_id' => $atributId,
                'unit' => 'pcs',
                'supplier_id' => 2,
                'has_expiry' => false,
            ],
        ];

        foreach ($products as $productData) {
            // Generate product code otomatis menggunakan method dari model
            $productCode = Product::generateUniqueCode($productData['product_name']);
            
            // Generate barcode otomatis
            $barcode = Product::generateBarcode();

            // Buat produk tanpa expiry_date
            $product = Product::create([
                'product_code' => $productCode,
                'product_name' => $productData['product_name'],
                'description' => $productData['description'],
                'category_id' => $productData['category_id'],
                'supplier_id' => $productData['supplier_id'],
                'purchase_price' => $productData['purchase_price'],
                'selling_price' => $productData['selling_price'],
                'stock' => $productData['stock'],
                'minimum_stock' => $productData['minimum_stock'],
                'unit' => $productData['unit'],
                'barcode' => $barcode,
                'has_expiry' => $productData['has_expiry'],
                'image' => 'products/' . Str::slug($productData['product_name']) . '.jpeg',
                'is_active' => true,
            ]);

            // Jika produk memiliki expiry date, buat batch nya
            if ($productData['has_expiry'] && isset($productData['expiry_date'])) {
                ProductBatch::create([
                    'product_id' => $product->id,
                    'purchase_detail_id' => null, // Null karena initial stock, bukan dari purchase
                    'expiry_date' => $productData['expiry_date'],
                    'quantity_received' => $productData['stock'],
                    'quantity_remaining' => $productData['stock'],
                    'batch_number' => 'INIT-' . $product->product_code . '-' . date('Ymd'),
                    'notes' => 'Initial stock batch'
                ]);
            }
        }

        $this->command->info('Produk berhasil di-seed dengan product batches!');
    }
}