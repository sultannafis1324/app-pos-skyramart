<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDetail;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    public function index()
    {
        $purchaseDetails = PurchaseDetail::with(['purchase', 'product'])->get();
        return response()->json($purchaseDetails);
    }

    public function create()
    {
        // Return form view for creating purchase detail
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'purchase_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $purchaseDetail = PurchaseDetail::create($validated);

        return response()->json([
            'message' => 'Purchase detail created successfully',
            'data' => $purchaseDetail->load(['purchase', 'product'])
        ], 201);
    }

    public function show(PurchaseDetail $purchaseDetail)
    {
        return response()->json($purchaseDetail->load(['purchase', 'product']));
    }

    public function edit(PurchaseDetail $purchaseDetail)
    {
        // Return edit form view
    }

    public function update(Request $request, PurchaseDetail $purchaseDetail)
    {
        $validated = $request->validate([
            'purchase_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $purchaseDetail->update($validated);

        return response()->json([
            'message' => 'Purchase detail updated successfully',
            'data' => $purchaseDetail->load(['purchase', 'product'])
        ]);
    }

    public function destroy(PurchaseDetail $purchaseDetail)
    {
        $purchaseDetail->delete();

        return response()->json([
            'message' => 'Purchase detail deleted successfully'
        ]);
    }

    // Additional methods
    public function getByPurchase($purchaseId)
    {
        $purchaseDetails = PurchaseDetail::where('purchase_id', $purchaseId)
            ->with(['product'])
            ->get();
        
        return response()->json($purchaseDetails);
    }
}