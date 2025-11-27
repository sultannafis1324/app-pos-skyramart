<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\models\PurchaseDetail;
use App\models\StockHistory;
use App\Models\Product;
use App\Models\ProductBatch;  // ✅ TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PurchaseController extends Controller
{
    // Web methods (return views)
    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        // Handle form submission from web - delegate to API method
        return $this->apiStore($request);
    }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        // Handle form submission from web - delegate to API method
        return $this->apiUpdate($request, $purchase);
    }

    public function destroy(Purchase $purchase)
    {
        // Handle deletion from web - delegate to API method
        return $this->apiDestroy($purchase);
    }

    // API methods (return JSON)
    public function apiIndex()
    {
        $purchases = Purchase::with(['supplier', 'user', 'purchaseDetails.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($purchases);
    }

    public function apiStore(Request $request)
{
    $validated = $request->validate([
        'purchase_number' => 'required|string|max:255|unique:purchases',
        'purchase_date' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'user_id' => 'required|exists:users,id',
        'total_price' => 'required|numeric|min:0',
        'status' => 'nullable|string',
        'notes' => 'nullable|string',
        'purchase_details' => 'required|array|min:1',
        'purchase_details.*.product_id' => 'required|exists:products,id',
        'purchase_details.*.quantity' => 'required|integer|min:1',
        'purchase_details.*.purchase_price' => 'required|numeric|min:0',
        'purchase_details.*.expiry_date' => 'nullable|date|after:today',
        'purchase_details.*.subtotal' => 'required|numeric|min:0'
    ]);

    DB::beginTransaction();
    try {
        // ✅ VALIDASI: Jika produk has_expiry=true, expiry_date wajib diisi
        foreach ($validated['purchase_details'] as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            
            if ($product->has_expiry && empty($detail['expiry_date'])) {
                throw new \Exception("Expiry date is required for product: {$product->product_name}");
            }
        }

        // Create purchase
        $purchaseData = collect($validated)->except('purchase_details')->toArray();
        $purchaseData['status'] = 'received';
        $purchase = Purchase::create($purchaseData);

        // Create purchase details and batches
        foreach ($validated['purchase_details'] as $detail) {
            $product = Product::findOrFail($detail['product_id']);

            // Create purchase detail
            $purchaseDetail = PurchaseDetail::create([
                'purchase_id' => $purchase->id,
                'product_id' => $detail['product_id'],
                'purchase_price' => $detail['purchase_price'],
                'quantity' => $detail['quantity'],
                'expiry_date' => $detail['expiry_date'] ?? null,
                'subtotal' => $detail['subtotal']
            ]);

            $oldStock = $product->stock;
            $newStock = $oldStock + $detail['quantity'];

            // ✅ JIKA PRODUK HAS_EXPIRY, BUAT BATCH BARU
            if ($product->has_expiry && !empty($detail['expiry_date'])) {
                ProductBatch::create([
                    'product_id' => $product->id,
                    'purchase_detail_id' => $purchaseDetail->id,
                    'expiry_date' => $detail['expiry_date'],
                    'quantity_received' => $detail['quantity'],
                    'quantity_remaining' => $detail['quantity'],
                    'batch_number' => $purchase->purchase_number . '-' . $detail['product_id'],
                    'notes' => "From purchase: {$purchase->purchase_number}"
                ]);
            }

            // ✅ PERBAIKAN: Update HANYA stock dan purchase_price, TANPA expiry_date
            $product->update([
                'stock' => $newStock,
                'purchase_price' => $detail['purchase_price']
            ]);

            // Create stock history
            StockHistory::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $detail['quantity'],
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'reference_type' => 'purchase',
                'reference_id' => $purchase->id,
                'description' => "Purchase: {$purchase->purchase_number}" . 
                                ($detail['expiry_date'] ?? false ? " | Exp: " . date('d M Y', strtotime($detail['expiry_date'])) : ""),
                'user_id' => $validated['user_id']
            ]);
        }

        DB::commit();
        
        return response()->json([
            'message' => 'Purchase created successfully',
            'data' => $purchase->load(['supplier', 'user', 'purchaseDetails.product', 'purchaseDetails.batch'])
        ], 201);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Failed to create purchase: ' . $e->getMessage()
        ], 422);
    }
}

    public function apiShow(Purchase $purchase)
    {
        return response()->json($purchase->load(['supplier', 'user', 'purchaseDetails.product']));
    }

    public function apiUpdate(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Force status to 'received' - no status changes allowed
            $validated['status'] = 'received';
            
            $purchase->update($validated);
            DB::commit();
            
            return response()->json([
                'message' => 'Purchase updated successfully',
                'data' => $purchase->load(['supplier', 'user', 'purchaseDetails.product'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to update purchase: ' . $e->getMessage()
            ], 422);
        }
    }

    public function apiDestroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            // Before deleting, we need to reverse the stock changes
            foreach ($purchase->purchaseDetails as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $oldStock = $product->stock;
                    $newStock = $oldStock - $detail->quantity;
                    
                    // Prevent negative stock
                    if ($newStock < 0) {
                        DB::rollback();
                        return response()->json([
                            'message' => "Cannot delete purchase: Product '{$product->product_name}' would have negative stock ({$newStock}). Please adjust stock first."
                        ], 422);
                    }
                    
                    $product->update(['stock' => $newStock]);
                    
                    // Create stock history for reversal
                    StockHistory::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $detail->quantity,
                        'stock_before' => $oldStock,
                        'stock_after' => $newStock,
                        'reference_type' => 'purchase_deleted',
                        'reference_id' => $purchase->id,
                        'description' => "Purchase deleted: {$purchase->purchase_number}",
                        'user_id' => auth()->id() ?? $purchase->user_id
                    ]);
                }
            }
            
            // Delete purchase details first (foreign key constraint)
            $purchase->purchaseDetails()->delete();
            
            // Delete the purchase
            $purchase->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Purchase deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to delete purchase: ' . $e->getMessage()
            ], 422);
        }
    }
}