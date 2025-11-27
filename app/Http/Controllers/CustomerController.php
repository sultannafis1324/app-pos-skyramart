<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $customers = Customer::query()
            ->when($request->search, function($query, $search) {
                $query->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->when($request->status !== null, function($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->withCount('sales')
            ->paginate($perPage)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function apiIndex()
    {
        $customers = Customer::withCount('sales')->get();
        return response()->json($customers);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'village_id' => 'nullable|integer',
            'detail_address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone_number' => 'nullable|string|max:15',
            'bank_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date',
            'is_active' => 'boolean',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $filename = 'customer_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customers/photos', $filename, 'public');
            $validated['photo_profile'] = $path;
        }

        $address = \App\Models\Address::create([
        'province_id' => $request->province_id,
        'city_id' => $request->city_id,
        'district_id' => $request->district_id,
        'village_id' => $request->village_id,
        'detail_address' => $request->detail_address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    $validated['address_id'] = $address->id;

    $customer = Customer::create($validated);

    // ✅ TAMBAHKAN INI: Send Welcome Notifications
    try {
        // Send Email if customer has email
        if ($customer->email) {
            \Illuminate\Support\Facades\Mail::to($customer->email)
                ->send(new \App\Mail\CustomerWelcomeMail($customer));
            
            Log::info('✅ Welcome email sent to new customer', [
                'customer_id' => $customer->id,
                'email' => $customer->email
            ]);
        }

        // Send WhatsApp if customer has phone number
        if ($customer->phone_number) {
            $this->sendWelcomeWhatsApp($customer);
        }

    } catch (\Exception $e) {
        Log::error('❌ Failed to send welcome notifications', [
            'customer_id' => $customer->id,
            'error' => $e->getMessage()
        ]);
        // Don't throw - customer creation should still succeed
    }

    return redirect()->route('customers.index')->with('success', 'Customer created successfully');
}

    public function show(Customer $customer)
    {
        $customer->load([
            'sales',
            'address.province',
            'address.city', 
            'address.district',
            'address.village'
        ]);
        
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'detail_address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'bank_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date',
            'is_active' => 'boolean',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if (
            $request->filled('province_id') ||
            $request->filled('city_id') ||
            $request->filled('district_id') ||
            $request->filled('village_id') ||
            $request->filled('detail_address')
        ) {
            if ($customer->address_id) {
                $customer->address->update([
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'detail_address' => $request->detail_address,
                ]);
            } else {
                $address = Address::create([
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'detail_address' => $request->detail_address,
                ]);
                $validated['address_id'] = $address->id;
            }
        }

        if ($request->hasFile('photo_profile')) {
            if ($customer->photo_profile && Storage::disk('public')->exists($customer->photo_profile)) {
                Storage::disk('public')->delete($customer->photo_profile);
            }

            $file = $request->file('photo_profile');
            $filename = 'customer_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customers/photos', $filename, 'public');
            $validated['photo_profile'] = $path;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists()) {
            return redirect()->route('customers.index')->with('error', 'Cannot delete customer with associated sales');
        }

        if ($customer->photo_profile) {
            Storage::disk('public')->delete($customer->photo_profile);
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }
    
    public function syncCustomerData(Customer $customer)
    {
        $totalPurchase = $customer->sales()
            ->where('status', 'completed')
            ->sum('total_price');
            
        $customer->update(['total_purchase' => $totalPurchase]);
        $customer->syncLoyaltyPoints();
        
        return redirect()->back()->with('success', 'Customer data synchronized successfully');
    }

    public function deletePhoto(Customer $customer)
    {
        if ($customer->photo_profile) {
            Storage::disk('public')->delete($customer->photo_profile);
            $customer->update(['photo_profile' => null]);
        }

        return redirect()->back()->with('success', 'Profile photo deleted successfully');
    }

    public function getCustomerForMidtrans($customerId)
    {
        if (!$customerId || $customerId === 'null' || $customerId === '') {
            return (object)[
                'id' => null,
                'customer_name' => 'Walk-in Customer',
                'phone_number' => '082100000000',
                'email' => 'customer@skyramart.com',
                'address' => (object)[
                    'detail_address' => 'No address for walk-in customer',
                    'city' => (object)['name' => 'Jakarta'],
                    'province' => (object)['name' => 'DKI Jakarta'],
                    'district' => null,
                    'village' => null,
                ],
            ];
        }

        $customer = Customer::with([
            'address.province',
            'address.city',
            'address.district',
            'address.village'
        ])->find($customerId);

        if (!$customer) {
            return (object)[
                'id' => null,
                'customer_name' => 'Walk-in Customer',
                'phone_number' => '082100000000',
                'email' => 'customer@skyramart.com',
                'address' => (object)[
                    'detail_address' => 'No address for walk-in customer',
                    'city' => (object)['name' => 'Jakarta'],
                    'province' => (object)['name' => 'DKI Jakarta'],
                    'district' => null,
                    'village' => null,
                ],
            ];
        }

        return $customer;
    }

/**
 * Send Welcome WhatsApp Message using Template
 */
public function sendWelcomeWhatsApp($customer)
{
    try {
        Log::info('🚀 Starting Welcome WhatsApp', [
            'customer_id' => $customer->id,
            'phone' => $customer->phone_number
        ]);

        $template = \App\Models\CustomerMessageTemplate::getActive('whatsapp');
        
        if (!$template) {
            Log::warning('No active WhatsApp template found');
            return;
        }

        $message = $this->buildWelcomeMessageFromTemplate($customer, $template);

        $whatsappService = new \App\Services\WhatsAppService();
        $result = $whatsappService->sendMessage($customer->phone_number, $message);

        if ($result) {
            Log::info('✅ Welcome WhatsApp sent', ['customer_id' => $customer->id]);
        }

    } catch (\Exception $e) {
        Log::error('❌ Failed to send Welcome WhatsApp', [
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Build Welcome Message from Template
 */
protected function buildWelcomeMessageFromTemplate($customer, $template)
{
    $message = "";

    // Greeting
    if ($template->greeting_text) {
        $message .= $template->replaceVariables($template->greeting_text, $customer) . "\n\n";
    }

    // Account Details
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    if ($template->account_details_title) {
        $message .= $template->replaceVariables($template->account_details_title, $customer) . "\n";
    }
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "👤 Name: {$customer->customer_name}\n";
    
    if ($customer->phone_number) {
        $message .= "📞 Phone: {$customer->phone_number}\n";
    }
    
    if ($customer->email) {
        $message .= "📧 Email: {$customer->email}\n";
    }
    
    $message .= "🎁 Loyalty Points: {$customer->loyalty_points} points\n\n";

    // Benefits
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    if ($template->benefits_title) {
        $message .= $template->replaceVariables($template->benefits_title, $customer) . "\n";
    }
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if ($template->benefits_list) {
        $message .= $template->replaceVariables($template->benefits_list, $customer) . "\n\n";
    }

    // Contact Info
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "📍 *Visit Us*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if ($template->contact_info) {
        $message .= $template->replaceVariables($template->contact_info, $customer) . "\n\n";
    }

    // Footer
    if ($template->footer_text) {
        $message .= $template->replaceVariables($template->footer_text, $customer) . "\n\n";
    }

    // Store Branding
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    if ($template->store_branding) {
        $message .= $template->replaceVariables($template->store_branding, $customer);
    }

    return $message;
}
}