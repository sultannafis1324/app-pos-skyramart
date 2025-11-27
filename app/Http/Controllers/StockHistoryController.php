<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockHistoryExport;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StockHistory::with(['product', 'user']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $stockHistories = $query->orderBy('created_at', 'desc')->get();
        
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($stockHistories);
        }
        
        // Return view for web requests
        return view('stock-histories.index', compact('stockHistories'));
    }

    public function exportPdf(Request $request)
    {
        $query = StockHistory::with(['product', 'user']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $stockHistories = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('stock-histories.pdf', [
            'stockHistories' => $stockHistories,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to
        ])->setPaper('a4', 'landscape');

        return $pdf->download('stock-history-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new StockHistoryExport($request->date_from, $request->date_to, $request->type), 
            'stock-history-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function create()
    {
        return view('stock-histories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $product = Product::find($validated['product_id']);
        $oldStock = $product->stock;

        // Calculate new stock based on type
        switch ($validated['type']) {
            case 'in':
                $newStock = $oldStock + $validated['quantity'];
                break;
            case 'out':
                if ($oldStock < $validated['quantity']) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Insufficient stock for this operation'
                        ], 422);
                    }
                    return back()->withErrors(['quantity' => 'Insufficient stock for this operation']);
                }
                $newStock = $oldStock - $validated['quantity'];
                break;
            case 'adjustment':
                // For adjustment, quantity can be positive or negative
                $newStock = $oldStock + $validated['quantity'];
                if ($newStock < 0) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Stock cannot be negative'
                        ], 422);
                    }
                    return back()->withErrors(['quantity' => 'Stock cannot be negative']);
                }
                break;
        }

        // Update product stock
        $product->update(['stock' => $newStock]);

        // Create stock history
        $stockHistory = StockHistory::create([
            'product_id' => $validated['product_id'],
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'stock_before' => $oldStock,
            'stock_after' => $newStock,
            'reference_type' => 'adjustment',
            'reference_id' => null,
            'description' => $validated['description'] ?? 'Manual stock adjustment',
            'user_id' => $validated['user_id']
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Stock history created successfully',
                'data' => $stockHistory->load(['product', 'user'])
            ], 201);
        }

        return redirect()->route('stock-histories.index')->with('success', 'Stock history created successfully');
    }

    public function show(StockHistory $stockHistory, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($stockHistory->load(['product', 'user']));
        }
        
        return view('stock-histories.show', compact('stockHistory'));
    }

    public function edit(StockHistory $stockHistory)
    {
        return view('stock-histories.edit', compact('stockHistory'));
    }

    public function update(Request $request, StockHistory $stockHistory)
    {
        $validated = $request->validate([
            'description' => 'nullable|string'
        ]);

        $stockHistory->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Stock history updated successfully',
                'data' => $stockHistory->load(['product', 'user'])
            ]);
        }

        return redirect()->route('stock-histories.index')->with('success', 'Stock history updated successfully');
    }

    public function destroy(StockHistory $stockHistory, Request $request)
    {
        if ($stockHistory->reference_type !== 'adjustment') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cannot delete system generated stock history'
                ], 422);
            }
            return back()->withErrors(['error' => 'Cannot delete system generated stock history']);
        }

        $stockHistory->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Stock history deleted successfully'
            ]);
        }

        return redirect()->route('stock-histories.index')->with('success', 'Stock history deleted successfully');
    }

    public function getByProduct($productId, Request $request)
    {
        $stockHistories = StockHistory::where('product_id', $productId)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($stockHistories);
        }
        
        $product = Product::findOrFail($productId);
        return view('stock-histories.by-product', compact('stockHistories', 'product'));
    }
}