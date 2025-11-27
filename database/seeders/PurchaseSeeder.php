<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $supplierId = Supplier::first()->id ?? 1;
            $userId = User::first()->id ?? 1;

            // ambil beberapa produk
            $products = Product::take(2)->get();

            // sementara total_price kosong
            $purchase = Purchase::create([
                'purchase_number' => 'PO-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'purchase_date' => now(),
                'supplier_id' => $supplierId,
                'user_id' => $userId,
                'total_price' => 0,
                'status' => 'received',
                'notes' => 'Seeder auto purchase',
            ]);

            $total = 0;

            foreach ($products as $product) {
                $qty = rand(5, 20);
                $price = $product->purchase_price;

                $subtotal = $qty * $price;
                $total += $subtotal;

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'purchase_price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                // update stok produk juga
                $product->increment('stock', $qty);
            }

            // update total_price
            $purchase->update(['total_price' => $total]);
        });
    }
}
