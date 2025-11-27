<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StoreSettingController extends Controller
{
    /**
     * Display store settings form
     */
    public function index()
    {
        $settings = StoreSetting::getActive();
        
        // If no settings exist, create default
        if (!$settings) {
            $settings = StoreSetting::create([
                'store_name' => 'SkyraMart',
                'store_email' => 'support@skyramart.com',
                'store_phone' => '0889-2114-416',
                'store_whatsapp' => '0889-2114-416',
                'store_address' => 'Jl. Masjid Daruttaqwa No. 123, Depok',
                'store_hours' => 'Monday - Sunday, 08:00 - 22:00',
                'currency_symbol' => 'Rp',
                'currency_code' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]);
        }

        return view('store-settings.index', compact('settings'));
    }

    /**
     * Update store settings
     */
    public function update(Request $request)
    {
        $settings = StoreSetting::getActive();

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'required|string|max:20',
            'store_whatsapp' => 'required|string|max:20',
            'store_address' => 'required|string',
            'store_hours' => 'required|string|max:255',
            'store_website' => 'nullable|url|max:255',
            'store_instagram' => 'nullable|string|max:255',
            'store_facebook' => 'nullable|string|max:255',
            'store_description' => 'nullable|string',
            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:3',
            'timezone' => 'required|string|max:50',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo
            if ($settings->store_logo && Storage::disk('public')->exists($settings->store_logo)) {
                Storage::disk('public')->delete($settings->store_logo);
            }

            $file = $request->file('store_logo');
            $filename = 'store_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('store/logos', $filename, 'public');
            $validated['store_logo'] = $path;
        }

        $settings->update($validated);

        return redirect()->route('store-settings.index')
            ->with('success', 'Store settings updated successfully!');
    }

    /**
     * Delete store logo
     */
    public function deleteLogo()
    {
        $settings = StoreSetting::getActive();

        if ($settings && $settings->store_logo) {
            Storage::disk('public')->delete($settings->store_logo);
            $settings->update(['store_logo' => null]);
        }

        return redirect()->back()->with('success', 'Store logo deleted successfully!');
    }

    /**
     * Preview store information (for testing)
     */
    public function preview()
    {
        $settings = StoreSetting::getActive();
        $variables = $settings ? $settings->getVariables() : [];
        
        return view('store-settings.preview', compact('settings', 'variables'));
    }
}