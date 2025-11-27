<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with(['usages']);

        // Filter status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        // Filter type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $promotions = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::active()->get();
        $categories = Category::all();
        
        return view('promotions.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:promotions,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,buy_x_get_y,bundle,cashback,free_shipping,seasonal',
            'discount_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'bundle_price' => 'nullable|numeric|min:0',
            'bundle_quantity' => 'nullable|integer|min:1',
            'cashback_percentage' => 'nullable|numeric|min:0|max:100',
            'max_cashback' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_customer' => 'nullable|integer|min:1',
            'target_type' => 'required|in:all,specific_products,category',
            'target_ids' => 'nullable|array',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer',
            'is_stackable' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion = Promotion::create($validated);

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion created successfully!');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::active()->get();
        $categories = Category::all();
        
        return view('promotions.edit', compact('promotion', 'products', 'categories'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,buy_x_get_y,bundle,cashback,free_shipping,seasonal',
            'discount_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'bundle_price' => 'nullable|numeric|min:0',
            'bundle_quantity' => 'nullable|integer|min:1',
            'cashback_percentage' => 'nullable|numeric|min:0|max:100',
            'max_cashback' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_customer' => 'nullable|integer|min:1',
            'target_type' => 'required|in:all,specific_products,category',
            'target_ids' => 'nullable|array',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer',
            'is_stackable' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($promotion->image) {
                Storage::disk('public')->delete($promotion->image);
            }
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($validated);

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion updated successfully!');
    }

    public function destroy(Promotion $promotion)
    {
        // Delete image
        if ($promotion->image) {
            Storage::disk('public')->delete($promotion->image);
        }

        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Promotion deleted successfully!');
    }

    /**
     * Check promotion code validity
     */
    public function checkCode(Request $request)
    {
        $code = $request->input('code');
        $customerId = $request->input('customer_id');
        $totalAmount = $request->input('total_amount', 0);

        $promotion = Promotion::where('code', $code)
            ->active()
            ->first();

        if (!$promotion) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired promotion code'
            ]);
        }

        if ($promotion->min_purchase && $totalAmount < $promotion->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimum purchase amount not met. Required: Rp ' . number_format($promotion->min_purchase, 0, ',', '.')
            ]);
        }

        if ($customerId && !$promotion->canBeUsedBy($customerId)) {
            return response()->json([
                'valid' => false,
                'message' => 'You have reached the usage limit for this promotion'
            ]);
        }

        return response()->json([
            'valid' => true,
            'promotion' => $promotion,
            'message' => 'Promotion code applied successfully!'
        ]);
    }

    /**
     * API: Get active promotions
     */
    public function apiActive(Request $request)
    {
        $promotions = Promotion::active()
            ->orderBy('priority', 'desc')
            ->get();

        return response()->json($promotions);
    }

    /**
     * API: Get promotions for specific product
     */
    public function apiForProduct(Request $request, $productId)
    {
        $promotions = Promotion::forProduct($productId)
            ->orderBy('priority', 'desc')
            ->get();

        return response()->json($promotions);
    }
}