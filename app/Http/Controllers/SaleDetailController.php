<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function index()
    {
        $saleDetails = SaleDetail::with(['sale', 'product'])->get();
        return response()->json($saleDetails);
    }

    public function create()
    {
        // Return form view for creating sale detail
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'item_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $saleDetail = SaleDetail::create($validated);

        return response()->json([
            'message' => 'Sale detail created successfully',
            'data' => $saleDetail->load(['sale', 'product'])
        ], 201);
    }

    public function show(SaleDetail $saleDetail)
    {
        return response()->json($saleDetail->load(['sale', 'product']));
    }

    public function edit(SaleDetail $saleDetail)
    {
        // Return edit form view
    }

    public function update(Request $request, SaleDetail $saleDetail)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'item_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $saleDetail->update($validated);

        return response()->json([
            'message' => 'Sale detail updated successfully',
            'data' => $saleDetail->load(['sale', 'product'])
        ]);
    }

    public function destroy(SaleDetail $saleDetail)
    {
        $saleDetail->delete();

        return response()->json([
            'message' => 'Sale detail deleted successfully'
        ]);
    }

    // Additional methods
    public function getBySale($saleId)
    {
        $saleDetails = SaleDetail::where('sale_id', $saleId)
            ->with(['product'])
            ->get();
        
        return response()->json($saleDetails);
    }
}