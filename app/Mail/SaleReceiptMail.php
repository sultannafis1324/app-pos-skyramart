<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SaleReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Sale $sale, $pdfPath)
    {
        $this->sale = $sale;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Struk Pembayaran - ' . $this->sale->transaction_number . ' - SkyraMart',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sale-receipt',
            with: [
                'sale' => $this->sale,
                'customerName' => $this->sale->customer->customer_name ?? 'Valued Customer',
                'transactionNumber' => $this->sale->transaction_number,
                'totalAmount' => number_format($this->sale->total_price, 0, ',', '.'),
                'saleDate' => $this->sale->sale_date->format('d M Y, H:i'),
            ]
        );
    }

    /**
     * ✅ FIXED: Get the attachments for the message
     */
    public function attachments(): array
    {
        // ✅ CRITICAL: Pastikan file exists sebelum attach
        if (!file_exists($this->pdfPath)) {
            \Log::error('PDF file not found for email attachment', [
                'path' => $this->pdfPath,
                'sale_id' => $this->sale->id
            ]);
            return [];
        }

        return [
            Attachment::fromPath($this->pdfPath)
                ->as('struk-' . strtolower($this->sale->transaction_number) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}