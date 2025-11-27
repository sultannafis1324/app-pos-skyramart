<?php

namespace App\Http\Controllers;

use App\Models\CustomerMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerMessageTemplateController extends Controller
{
    public function index()
    {
        $templates = CustomerMessageTemplate::orderBy('type')->get();
        return view('templates.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = CustomerMessageTemplate::findOrFail($id);
        $variables = CustomerMessageTemplate::getAvailableVariables();
        
        return view('customer-message-templates.edit', compact('template', 'variables'));
    }

    public function update(Request $request, $id)
    {
        $template = CustomerMessageTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string',
            'greeting_text' => 'nullable|string',
            'account_details_title' => 'nullable|string',
            'benefits_title' => 'nullable|string',
            'benefits_list' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'store_branding' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template updated successfully!');
    }

    public function preview($id)
    {
        $template = CustomerMessageTemplate::findOrFail($id);

        // Sample customer data
        $sampleCustomer = (object)[
            'customer_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '08123456789',
            'loyalty_points' => 150,
        ];

        $preview = [
            'greeting' => $template->replaceVariables($template->greeting_text ?? '', $sampleCustomer),
            'account_details_title' => $template->replaceVariables($template->account_details_title ?? '', $sampleCustomer),
            'benefits_title' => $template->replaceVariables($template->benefits_title ?? '', $sampleCustomer),
            'benefits_list' => $template->replaceVariables($template->benefits_list ?? '', $sampleCustomer),
            'contact_info' => $template->replaceVariables($template->contact_info ?? '', $sampleCustomer),
            'footer' => $template->replaceVariables($template->footer_text ?? '', $sampleCustomer),
            'store_branding' => $template->replaceVariables($template->store_branding ?? '', $sampleCustomer),
        ];

        return view('customer-message-templates.preview', compact('template', 'preview', 'sampleCustomer'));
    }

    public function reset($id)
    {
        $template = CustomerMessageTemplate::findOrFail($id);

        if ($template->type === 'whatsapp') {
            $template->update([
                'greeting_text' => "🎉 *Welcome to {store_name}!* 🎉\n\nHello *{customer_name}*! 👋\n\nThank you for joining the SkyraMart family! 💚",
                'account_details_title' => "📋 *Your Account Details*",
                'benefits_title' => "🌟 *Member Benefits*",
                'benefits_list' => "✅ *Loyalty Rewards Program*\n   Earn 1 point per Rp 100 spent!\n\n✅ *Exclusive Promotions*\n   Get special deals & discounts\n\n✅ *Digital Receipts*\n   Instant receipts via WhatsApp & Email\n\n✅ *Easy Shopping*\n   Fast checkout & order tracking",
                'contact_info' => "🏪 {store_address}\n📱 WhatsApp: {store_whatsapp}\n📧 Email: {store_email}\n⏰ Hours: {store_hours}",
                'footer_text' => "Start shopping now and enjoy amazing deals! 🛒",
                'store_branding' => "💚 *{store_name}* - Your Trusted Store\n_Thank you for choosing us!_",
            ]);
        } else {
            $template->update([
                'subject' => 'Welcome to {store_name}! 🎉',
                'greeting_text' => "Dear {customer_name},\n\nWe are thrilled to have you as our valued customer!",
                'account_details_title' => "Your Account Information:",
                'benefits_title' => "Member Benefits:",
                'contact_info' => "Visit us: {store_address}\nWhatsApp: {store_whatsapp}\nEmail: {store_email}",
                'footer_text' => "We look forward to serving you!\n\nHappy Shopping! 🛒",
                'store_branding' => "{store_name} - Your Trusted Store",
            ]);
        }

        return redirect()->back()->with('success', 'Template reset to default successfully!');
    }
}