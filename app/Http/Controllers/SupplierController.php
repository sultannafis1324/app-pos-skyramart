<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('store_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Get suppliers with pagination
        $suppliers = $query->orderBy('supplier_name', 'asc')
                          ->paginate(10)
                          ->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }


    public function apiIndex()
    {
        $suppliers = Supplier::with('address.province', 'address.city', 'address.district', 'address.village')
            ->get()
            ->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->supplier_name,
                    'supplier_name' => $supplier->supplier_name,
                    'address' => $supplier->address ? [
                        'province' => $supplier->address->province?->name,
                        'city' => $supplier->address->city?->name,
                        'district' => $supplier->address->district?->name,
                        'village' => $supplier->address->village?->name,
                        'detail_address' => $supplier->address->detail_address,
                    ] : null,
                    'phone_number' => $supplier->phone_number,
                    'email' => $supplier->email,
                    'store_name' => $supplier->store_name,
                    'is_active' => $supplier->is_active,
                ];
            });

        return response()->json($suppliers);
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'village_id' => 'nullable|integer',
            'detail_address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'store_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = $request->file('photo_profile')->store('suppliers', 'public');
        }

        // Save address if any location data is provided
        if ($request->filled(['province_id', 'city_id', 'district_id', 'village_id', 'detail_address'])) {
            $address = Address::create([
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,
                'detail_address' => $request->detail_address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            $validated['address_id'] = $address->id;
        }

        $supplier = Supplier::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Supplier created successfully',
                'data' => $supplier
            ], 201);
        }

        return redirect()->route('suppliers.index')
                        ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
{
    // Load all necessary relationships
    $supplier->load([
        'address.province', 
        'address.city', 
        'address.district', 
        'address.village',
        'products' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        },
        'purchases' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        },
        'purchases.purchaseDetails'
    ]);

    if (request()->expectsJson()) {
        return response()->json($supplier);
    }

    return view('suppliers.show', compact('supplier'));
}

    public function edit(Supplier $supplier)
    {
        $supplier->load('address.province', 'address.city', 'address.district', 'address.village');
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'detail_address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'store_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            // Delete old photo if exists
            if ($supplier->photo_profile) {
                \Storage::disk('public')->delete($supplier->photo_profile);
            }
            $validated['photo_profile'] = $request->file('photo_profile')->store('suppliers', 'public');
        }

        // Update or create address
        if (
            $request->filled('province_id') ||
            $request->filled('city_id') ||
            $request->filled('district_id') ||
            $request->filled('village_id') ||
            $request->filled('detail_address')
        ) {
            if ($supplier->address_id) {
                // Update existing address
                $supplier->address->update([
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'detail_address' => $request->detail_address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            } else {
                // Create new address
                $address = Address::create([
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'detail_address' => $request->detail_address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $validated['address_id'] = $address->id;
            }
        }
        
        $supplier->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Supplier updated successfully',
                'data' => $supplier
            ]);
        }

        return redirect()->route('suppliers.index')
                        ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Cannot delete supplier with associated products'
                ], 422);
            }

            return redirect()->route('suppliers.index')
                           ->with('error', 'Cannot delete supplier with associated products.');
        }

        $supplierName = $supplier->supplier_name;
        
        // Delete photo if exists
        if ($supplier->photo_profile) {
            \Storage::disk('public')->delete($supplier->photo_profile);
        }
        
        $supplier->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Supplier deleted successfully'
            ]);
        }

        return redirect()->route('suppliers.index')
                        ->with('success', "Supplier '{$supplierName}' deleted successfully.");
    }
}