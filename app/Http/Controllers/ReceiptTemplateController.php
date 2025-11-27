<?php

namespace App\Http\Controllers;

use App\Models\ReceiptTemplate;
use App\Models\CustomerMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceiptTemplateController extends Controller
{
    /**
     * Unified template list - Receipt & Customer Message Templates
     */
    public function unifiedIndex()
    {
        $receiptTemplates = ReceiptTemplate::orderBy('type')->get();
        $customerMessageTemplates = CustomerMessageTemplate::orderBy('type')->get();
        
        return view('receipt-templates.index', compact('receiptTemplates', 'customerMessageTemplates'));
    }

    /**
     * Display receipt template list only
     */
    public function index()
    {
        $templates = ReceiptTemplate::orderBy('type')->get();
        return view('templates.index', compact('templates'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $template = ReceiptTemplate::findOrFail($id);
        $variables = ReceiptTemplate::getAvailableVariables($template->type);
        
        return view('receipt-templates.edit', compact('template', 'variables'));
    }

    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        $template = ReceiptTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'header_text' => 'nullable|string',
            'greeting_text' => 'nullable|string',
            'transaction_section_title' => 'nullable|string',
            'items_section_title' => 'nullable|string',
            'payment_section_title' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'notes_text' => 'nullable|string',
            'store_branding' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Receipt template updated successfully!');
    }

    /**
     * Preview template
     */
    public function preview($id)
    {
        $template = ReceiptTemplate::findOrFail($id);

        // Sample data untuk preview
        $sampleData = [
            'customer_name' => 'John Doe',
            'transaction_number' => 'TXN-20251122-1234',
            'date' => '22 Nov 2025',
            'time' => '14:30',
            'cashier_name' => 'Admin User',
            'subtotal' => 150000,
            'discount' => 5000,
            'discount_percentage' => 3.3,
            'tax' => 0,
            'total' => 145000,
            'payment_method' => 'CASH',
            'payment_channel' => 'Cash Payment',
            'pdf_link' => 'https://example.com/receipt.pdf',
        ];

        // Build preview
        $preview = [
            'header' => $template->replaceVariables($template->header_text ?? '', $sampleData),
            'greeting' => $template->replaceVariables($template->greeting_text ?? '', $sampleData),
            'transaction_title' => $template->replaceVariables($template->transaction_section_title ?? '', $sampleData),
            'items_title' => $template->replaceVariables($template->items_section_title ?? '', $sampleData),
            'payment_title' => $template->replaceVariables($template->payment_section_title ?? '', $sampleData),
            'footer' => $template->replaceVariables($template->footer_text ?? '', $sampleData),
            'contact' => $template->replaceVariables($template->contact_info ?? '', $sampleData),
            'notes' => $template->replaceVariables($template->notes_text ?? '', $sampleData),
        ];

        return view('receipt-templates.preview', compact('template', 'preview', 'sampleData'));
    }

    /**
     * Reset to default
     */
    public function reset($id)
    {
        $template = ReceiptTemplate::findOrFail($id);

        if ($template->type === 'whatsapp') {
            $template->update([
                'header_text' => "🛒 *SkyraMart - Digital Receipt*",
                'greeting_text' => "Dear *{customer_name}*,\nThank you for shopping with us! 💚",
                'transaction_section_title' => "📋 *Transaction Details*",
                'items_section_title' => "🛍️ *Order Summary*",
                'payment_section_title' => "💰 *Payment Details*",
                'footer_text' => "Thank you for your trust! 🙏\n_Please save this receipt for your records_",
                'contact_info' => "Need help? Contact us:\n📞 WA: 0889-2114-416\n📍 Jl. Masjid Daruttaqwa No. 123, Depok",
                'notes_text' => "📄 *Your receipt will be sent as PDF*\n_If PDF fails, you'll receive a download link_",
                'store_branding' => "💚 *SkyraMart* - Your Trusted Store",
            ]);
        } else {
            $template->update([
                'header_text' => "SkyraMart\nJl. Masjid Daruttaqwa No. 123\nDepok City, Indonesia\nWA: 0889-2114-416",
                'transaction_section_title' => "Transaction Info:",
                'items_section_title' => "Items Purchased:",
                'payment_section_title' => "Payment Details:",
                'footer_text' => "Thank You for Shopping!\nItems purchased are non-refundable\nPlease save this receipt for your records",
                'contact_info' => "Powered by SkyraMart POS System",
                'store_branding' => "SkyraMart - Your Trusted Store",
            ]);
        }

        return redirect()->back()->with('success', 'Receipt template reset to default successfully!');
    }
}