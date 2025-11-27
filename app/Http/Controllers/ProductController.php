<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\Facades\DNS1DFacade;


class ProductController extends Controller
{
public function index()
{
    try {
        $products = Product::with(['category', 'supplier', 'batches' => function($query) {
                $query->available()->notExpired()->FEFO();
            }])
            ->get()
            ->map(function($product) {
                $promotions = $product->getActivePromotions();
                $pricePromo = $promotions['price_promotion'];
                $qtyPromo = $promotions['quantity_promotion'];
                
                $priceData = $product->getPriceWithMultiPromotions(1);
                
                $productArray = $product->toArray();
                
                // ✅ FIXED: Get effective expiry info from AVAILABLE batches only
                $nearestAvailableBatch = $product->batches()
                    ->available()
                    ->notExpired()
                    ->FEFO()
                    ->first();
                
                if ($nearestAvailableBatch) {
                    // Use nearest AVAILABLE batch with stock
                    $productArray['expiry_date'] = $nearestAvailableBatch->expiry_date->format('Y-m-d');
                    $productArray['has_expiry'] = true;
                    $productArray['is_expired'] = false;
                    $productArray['is_near_expiry'] = $nearestAvailableBatch->is_near_expiry;
                    $productArray['days_until_expiry'] = $nearestAvailableBatch->days_until_expiry;
                    $productArray['batch_number'] = $nearestAvailableBatch->batch_number; // ✅ TAMBAHAN
                } else {
                    // Check if there are expired batches
                    $hasExpiredBatches = $product->batches()
                        ->where('quantity_remaining', '>', 0)
                        ->where('expiry_date', '<', now())
                        ->exists();
                    
                    if ($hasExpiredBatches) {
                        $expiredBatch = $product->batches()
                            ->where('quantity_remaining', '>', 0)
                            ->where('expiry_date', '<', now())
                            ->orderBy('expiry_date', 'asc')
                            ->first();
                        
                        $productArray['expiry_date'] = $expiredBatch->expiry_date->format('Y-m-d');
                        $productArray['has_expiry'] = true;
                        $productArray['is_expired'] = true;
                        $productArray['is_near_expiry'] = false;
                        $productArray['days_until_expiry'] = now()->diffInDays($expiredBatch->expiry_date, false);
                        $productArray['batch_number'] = $expiredBatch->batch_number; // ✅ TAMBAHAN
                    } else {
                        // No expiry info
                        $productArray['expiry_date'] = null;
                        $productArray['has_expiry'] = false;
                        $productArray['is_expired'] = false;
                        $productArray['is_near_expiry'] = false;
                        $productArray['days_until_expiry'] = null;
                        $productArray['batch_number'] = null; // ✅ TAMBAHAN
                    }
                }
                
                // Add promotion flags (existing code...)
                $productArray['has_active_promotion'] = $pricePromo !== null || $qtyPromo !== null;
                $productArray['has_multiple_promotions'] = $pricePromo !== null && $qtyPromo !== null;
                
                $productArray['discounted_price'] = $priceData['final_price_per_unit'];
                $productArray['original_price_for_display'] = $priceData['original_price'];
                $productArray['discount_amount'] = $priceData['total_savings'];
                
                if ($pricePromo) {
                    $productArray['price_promotion'] = [
                        'id' => $pricePromo->id,
                        'name' => $pricePromo->name,
                        'type' => $pricePromo->type,
                        'discount_value' => $pricePromo->discount_value,
                        'badge_text' => $pricePromo->badge_text,
                        'badge_color' => $pricePromo->badge_color,
                    ];
                } else {
                    $productArray['price_promotion'] = null;
                }
                
                if ($qtyPromo) {
                    $productArray['quantity_promotion'] = [
                        'id' => $qtyPromo->id,
                        'name' => $qtyPromo->name,
                        'type' => $qtyPromo->type,
                        'buy_quantity' => $qtyPromo->buy_quantity,
                        'get_quantity' => $qtyPromo->get_quantity,
                        'badge_text' => $qtyPromo->badge_text,
                        'badge_color' => $qtyPromo->badge_color,
                    ];
                } else {
                    $productArray['quantity_promotion'] = null;
                }
                
                $productArray['batches'] = $product->batches->map(function($batch) {
                    return [
                        'id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'expiry_date' => $batch->expiry_date->format('Y-m-d'),
                        'quantity_remaining' => $batch->quantity_remaining,
                        'is_expired' => $batch->is_expired,
                        'is_near_expiry' => $batch->is_near_expiry,
                        'days_until_expiry' => $batch->days_until_expiry,
                    ];
                })->toArray();
                
                return $productArray;
            });
            
        return response()->json($products);
    } catch (\Exception $e) {
        Log::error('Error in ProductController@index: ' . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'product_code' => 'required|string|max:255|unique:products',
        'product_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'purchase_price' => 'nullable|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'stock' => 'nullable|integer|min:0',
        'minimum_stock' => 'nullable|integer|min:0',
        'unit' => 'nullable|string|max:10',
        'barcode' => 'nullable|string|max:255',
        'has_expiry' => 'boolean',
        'expiry_date' => 'nullable|date|after:today',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean'
    ]);

    // ✅ UPDATED: Validasi expiry untuk product dengan expiry
    if (!empty($validated['has_expiry']) && $validated['has_expiry']) {
        $request->validate([
            'expiry_date' => 'required|date|after:today',
        ], [
            'expiry_date.required' => 'Expiry date is required when product has expiry',
            'expiry_date.after' => 'Expiry date must be a future date',
        ]);
    }

    // ✅ PENTING: Simpan expiry_date sementara, lalu hapus dari validated
    $expiryDate = $validated['expiry_date'] ?? null;
    unset($validated['expiry_date']); // Hapus dari data yang akan disimpan ke products

    // Handle description
    if (!empty($validated['description'])) {
        $validated['description'] = $validated['description'];
    }
    
    // Handle image upload
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    // Generate barcode if empty
    if (empty($validated['barcode'])) {
        $validated['barcode'] = Product::generateBarcode();
    }

    // Handle duplicate product_code
    $productCode = $validated['product_code'];
    $counter = 1;
    while (Product::where('product_code', $productCode)->exists()) {
        $basecode = explode('-', $validated['product_code'])[0];
        $randomNum = rand(100, 999);
        $productCode = "{$basecode}-{$randomNum}";
        $counter++;
        
        if ($counter > 10) {
            return response()->json([
                'message' => 'Failed to generate unique product code. Please try again.',
                'errors' => ['product_code' => ['Product code generation failed']]
            ], 422);
        }
    }
    
    $validated['product_code'] = $productCode;

    // Create product
    $product = Product::create($validated);
    
    // ✅ BARU: Buat batch jika product has_expiry dan ada stock awal
    if ($product->has_expiry && $expiryDate && $product->stock > 0) {
        \App\Models\ProductBatch::create([
            'product_id' => $product->id,
            'purchase_detail_id' => null,
            'expiry_date' => $expiryDate,
            'quantity_received' => $product->stock,
            'quantity_remaining' => $product->stock,
            'batch_number' => 'INIT-' . date('Ymd') . '-' . $product->id,
            'notes' => 'Initial batch created with product'
        ]);
    }
    
    return response()->json([
        'message' => 'Product created successfully',
        'data' => $product->load(['category', 'supplier'])
    ], 201);
}


    public function getBySupplier(Request $request)
    {
        try {
            $supplierId = $request->get('supplier_id');
            
            if (!$supplierId) {
                return response()->json([
                    'error' => 'Supplier ID is required'
                ], 400);
            }

            $products = Product::with('category')
                ->where('supplier_id', $supplierId)
                ->where('is_active', true)
                ->orderBy('product_name', 'asc')
                ->get();

            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Get products by supplier error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Product $product)
{
    // Load relationships with recent data + batches
    $product->load([
        'category', 
        'supplier',
        'batches' => function($query) {
            $query->orderBy('expiry_date', 'asc');
        },
        'stockHistories' => function($query) {
            $query->with('user')->latest()->limit(10);
        },
        'saleDetails' => function($query) {
            $query->with(['sale.customer', 'sale.user'])
                  ->whereHas('sale', function($q) {
                      $q->where('status', 'completed');
                  })
                  ->latest()
                  ->limit(10);
        },
        'purchaseDetails' => function($query) {
            $query->with(['purchase.supplier', 'purchase.user', 'batch'])
                  ->whereHas('purchase', function($q) {
                      $q->where('status', 'received');
                  })
                  ->latest()
                  ->limit(10);
        }
    ]);

    // Calculate statistics
    $stats = [
        'total_sold' => $product->saleDetails()
            ->whereHas('sale', function($q) {
                $q->where('status', 'completed');
            })
            ->sum('quantity'),
        'total_purchased' => $product->purchaseDetails()
            ->whereHas('purchase', function($q) {
                $q->where('status', 'received');
            })
            ->sum('quantity'),
        'total_revenue' => $product->saleDetails()
            ->whereHas('sale', function($q) {
                $q->where('status', 'completed');
            })
            ->sum('subtotal'),
    ];

    // ✅ Get promotions
    $promotions = $product->getActivePromotions();
    $productArray = $product->toArray();
    
    // ✅ TAMBAHAN: Add effective expiry date dari batch
    $nearestAvailableBatch = $product->batches()
        ->available()
        ->notExpired()
        ->FEFO()
        ->first();
    
    if ($nearestAvailableBatch) {
        $productArray['effective_expiry_date'] = $nearestAvailableBatch->expiry_date->format('Y-m-d');
        $productArray['effective_batch_number'] = $nearestAvailableBatch->batch_number;
        $productArray['effective_batch_id'] = $nearestAvailableBatch->id;
        $productArray['effective_batch_stock'] = $nearestAvailableBatch->quantity_remaining;
    } else {
        // Check if there are expired batches with stock
        $expiredBatch = $product->batches()
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '<', now())
            ->orderBy('expiry_date', 'asc')
            ->first();
        
        if ($expiredBatch) {
            $productArray['effective_expiry_date'] = $expiredBatch->expiry_date->format('Y-m-d');
            $productArray['effective_batch_number'] = $expiredBatch->batch_number;
            $productArray['effective_batch_id'] = $expiredBatch->id;
            $productArray['effective_batch_stock'] = $expiredBatch->quantity_remaining;
        } else {
            $productArray['effective_expiry_date'] = null;
            $productArray['effective_batch_number'] = null;
            $productArray['effective_batch_id'] = null;
            $productArray['effective_batch_stock'] = null;
        }
    }
    
    // Add promotion flags
    $productArray['has_active_promotion'] = $promotions['price_promotion'] !== null || $promotions['quantity_promotion'] !== null;
    $productArray['has_multiple_promotions'] = $promotions['price_promotion'] !== null && $promotions['quantity_promotion'] !== null;
    
    // Add price data with promotions
    $priceData = $product->getPriceWithMultiPromotions(1);
    $productArray['discounted_price'] = $priceData['final_price_per_unit'];
    $productArray['original_price_for_display'] = $priceData['original_price'];
    $productArray['discount_amount'] = $priceData['total_savings'];
    
    // Add promotion details
    if ($promotions['price_promotion']) {
        $productArray['price_promotion'] = [
            'id' => $promotions['price_promotion']->id,
            'name' => $promotions['price_promotion']->name,
            'type' => $promotions['price_promotion']->type,
            'discount_value' => $promotions['price_promotion']->discount_value,
            'badge_text' => $promotions['price_promotion']->badge_text,
            'badge_color' => $promotions['price_promotion']->badge_color,
        ];
    } else {
        $productArray['price_promotion'] = null;
    }
    
    if ($promotions['quantity_promotion']) {
        $productArray['quantity_promotion'] = [
            'id' => $promotions['quantity_promotion']->id,
            'name' => $promotions['quantity_promotion']->name,
            'type' => $promotions['quantity_promotion']->type,
            'buy_quantity' => $promotions['quantity_promotion']->buy_quantity,
            'get_quantity' => $promotions['quantity_promotion']->get_quantity,
            'badge_text' => $promotions['quantity_promotion']->badge_text,
            'badge_color' => $promotions['quantity_promotion']->badge_color,
        ];
    } else {
        $productArray['quantity_promotion'] = null;
    }
    
    // ✅ Add batches information with detailed data
    $productArray['batches'] = $product->batches->map(function($batch) {
        return [
            'id' => $batch->id,
            'batch_number' => $batch->batch_number,
            'expiry_date' => $batch->expiry_date->format('Y-m-d'),
            'quantity_received' => $batch->quantity_received,
            'quantity_remaining' => $batch->quantity_remaining,
            'is_expired' => $batch->is_expired,
            'is_near_expiry' => $batch->is_near_expiry,
            'days_until_expiry' => $batch->days_until_expiry,
            'notes' => $batch->notes,
            'created_at' => $batch->created_at->format('Y-m-d H:i:s'),
        ];
    })->toArray();

    if (request()->expectsJson() || request()->is('api/*')) {
        return response()->json([
            'product' => $productArray,
            'stats' => $stats
        ]);
    }

    return response()->json([
        'product' => $productArray,
        'stats' => $stats
    ]);
}

public function getPromotions(Product $product)
{
    try {
        $promotions = \App\Models\Promotion::forProduct($product->id)
            ->orderBy('priority', 'desc')
            ->get()
            ->map(function($promo) {
                return [
                    'id' => $promo->id,
                    'name' => $promo->name,
                    'description' => $promo->description,
                    'type' => $promo->type,
                    'discount_value' => $promo->discount_value,
                    'max_discount' => $promo->max_discount,
                    'buy_quantity' => $promo->buy_quantity,
                    'get_quantity' => $promo->get_quantity,
                    'min_purchase' => $promo->min_purchase,
                    'usage_limit' => $promo->usage_limit,
                    'is_combinable' => $promo->is_combinable,
                    'start_date' => $promo->start_date,
                    'end_date' => $promo->end_date,
                    'badge_text' => $promo->badge_text,
                    'badge_color' => $promo->badge_color,
                ];
            });

        return response()->json([
            'promotions' => $promotions
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching promotions: ' . $e->getMessage());
        return response()->json([
            'promotions' => []
        ], 500);
    }
}

    public function update(Request $request, Product $product)
{
    $rules = [
        'product_code' => 'required|string|max:255|unique:products,product_code,' . $product->id,
        'product_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'purchase_price' => 'nullable|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'stock' => 'nullable|integer|min:0',
        'minimum_stock' => 'nullable|integer|min:0',
        'unit' => 'nullable|string|max:10',
        'barcode' => 'nullable|string|max:255',
        'has_expiry' => 'boolean',
        'expiry_date' => 'nullable|date|after:today',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean'
    ];

    // ✅ Validasi stock adjustment
    if ($request->has('stock') && $request->stock != $product->stock) {
        $rules['stock_adjustment_reason'] = 'required|string|max:500';
    }

    // ✅ Validasi expiry
    if (!empty($request->has_expiry) && $request->has_expiry) {
        $rules['expiry_date'] = 'required|date|after:today';
    }

    $validated = $request->validate($rules, [
        'stock_adjustment_reason.required' => 'Stock adjustment reason is required when stock changes',
        'expiry_date.required' => 'Expiry date is required when product has expiry',
        'expiry_date.after' => 'Expiry date must be a future date',
    ]);

    // ✅ PENTING: Simpan expiry_date sementara, lalu hapus dari validated
    $expiryDate = $validated['expiry_date'] ?? null;
    $oldHasExpiry = $product->has_expiry;
    unset($validated['expiry_date']); // Hapus dari data yang akan disimpan ke products

    // ✅ Clear expiry_date jika has_expiry false (tidak dipakai lagi di products table)
    // Tidak perlu action karena expiry_date tidak lagi di products table

    if (!empty($validated['description'])) {
        $validated['description'] = $validated['description'];
    }
    
    // Handle image upload
    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    // ✅ Handle stock changes
    if ($request->has('stock') && $request->stock != $product->stock) {
        $oldStock = $product->stock;
        $newStock = $validated['stock'];
        $difference = $newStock - $oldStock;

        // Update product stock dulu
        $product->update($validated);

        // ✅ BARU: Update atau create batch jika product has expiry
        if ($product->has_expiry && $expiryDate) {
            // Cari batch dengan expiry date yang sama
            $batch = \App\Models\ProductBatch::where('product_id', $product->id)
                ->where('expiry_date', $expiryDate)
                ->first();
            
            if ($batch) {
                // Update existing batch
                $batch->update([
                    'quantity_received' => $batch->quantity_received + $difference,
                    'quantity_remaining' => $batch->quantity_remaining + $difference,
                ]);
            } else {
                // Create new batch
                \App\Models\ProductBatch::create([
                    'product_id' => $product->id,
                    'purchase_detail_id' => null,
                    'expiry_date' => $expiryDate,
                    'quantity_received' => max(0, $difference),
                    'quantity_remaining' => max(0, $difference),
                    'batch_number' => 'ADJ-' . date('Ymd') . '-' . $product->id,
                    'notes' => 'Batch created from stock adjustment'
                ]);
            }
        }

        // Buat stock history
        StockHistory::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => abs($difference),
            'stock_before' => $oldStock,
            'stock_after' => $newStock,
            'reference_type' => 'product edit',
            'reference_id' => null,
            'description' => $validated['stock_adjustment_reason'] ?? 'Manual stock adjustment from product edit',
            'user_id' => Auth::id() ?? 1
        ]);
    } else {
        // ✅ BARU: Update batch expiry date jika berubah (tanpa stock change)
        if ($product->has_expiry && $expiryDate) {
            // Update batch yang ada atau create new
            $latestBatch = \App\Models\ProductBatch::where('product_id', $product->id)
                ->where('quantity_remaining', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->first();
            
            if ($latestBatch && $latestBatch->expiry_date != $expiryDate) {
                // Update expiry date di batch
                $latestBatch->update(['expiry_date' => $expiryDate]);
            } elseif (!$latestBatch && $product->stock > 0) {
                // Create batch baru jika belum ada
                \App\Models\ProductBatch::create([
                    'product_id' => $product->id,
                    'purchase_detail_id' => null,
                    'expiry_date' => $expiryDate,
                    'quantity_received' => $product->stock,
                    'quantity_remaining' => $product->stock,
                    'batch_number' => 'UPD-' . date('Ymd') . '-' . $product->id,
                    'notes' => 'Batch created from product update'
                ]);
            }
        }
        
        // Update product tanpa stock history
        $product->update($validated);
    }

    return response()->json([
        'message' => 'Product updated successfully',
        'data' => $product->load(['category', 'supplier'])
    ]);
}

    public function destroy(Product $product)
    {
        if ($product->saleDetails()->count() > 0 || $product->purchaseDetails()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete product with associated sales or purchases'
            ], 422);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    public function lowStock()
    {
        $products = Product::lowStock()->with(['category', 'supplier'])->get();
        return response()->json($products);
    }

    public function searchForPOS(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $category = $request->get('category', '');

            $query = Product::with('category')
                ->where('is_active', true);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%");
                });
            }

            if ($category) {
                $query->where('category_id', $category);
            }

            $products = $query->orderByRaw('stock > 0 DESC, stock DESC, product_name ASC')
                ->limit(24)
                ->get();

            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Product search error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Tambahkan di akhir class ProductController sebelum closing bracket

public function expiredProducts()
{
    $products = Product::expired()
        ->with(['category', 'supplier'])
        ->orderBy('expiry_date', 'asc')
        ->get();
        
    return response()->json($products);
}

public function nearExpiryProducts()
{
    $products = Product::nearExpiry()
        ->with(['category', 'supplier'])
        ->orderBy('expiry_date', 'asc')
        ->get();
        
    return response()->json($products);
}

/**
 * Generate barcode labels for printing
 */
public function printBarcodes(Product $product)
{
    try {
        // Get quantity based on stock
        $quantity = $product->stock > 0 ? $product->stock : 1;
        
        // Generate barcode SVG
        $barcodeSvg = DNS1DFacade::getBarcodeHTML($product->barcode, 'C128', 2, 60);
        
        return view('products.barcode-print', [
            'product' => $product,
            'quantity' => $quantity,
            'barcodeSvg' => $barcodeSvg
        ]);
    } catch (\Exception $e) {
        Log::error('Error generating barcode: ' . $e->getMessage());
        return back()->with('error', 'Failed to generate barcode');
    }
}

/**
 * Get barcode image (for display in show page)
 */
public function getBarcodeImage(Product $product)
{
    try {
        $barcodeSvg = DNS1DFacade::getBarcodeHTML($product->barcode, 'C128', 2, 60);
        
        return response()->json([
            'barcode' => $barcodeSvg,
            'barcode_number' => $product->barcode
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to generate barcode'], 500);
    }
}
}