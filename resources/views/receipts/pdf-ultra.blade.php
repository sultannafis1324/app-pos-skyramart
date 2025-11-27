@php
    use App\Models\ReceiptTemplate;
    $template = ReceiptTemplate::getActive('print');
    
    if (!$template) {
        $template = (object)[
            'header_text' => "SkyraMart\nJl. Masjid Daruttaqwa No. 123\nDepok City, Indonesia\nWA: 0889-2114-416",
            'transaction_section_title' => "Transaction Info:",
            'items_section_title' => "Items Purchased:",
            'payment_section_title' => "Payment Details:",
            'footer_text' => "Thank You for Shopping!\nItems purchased are non-refundable\nPlease save this receipt for your records",
            'contact_info' => "Powered by SkyraMart POS System"
        ];
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt #{{ $sale->transaction_number }}</title>
    <style>
        @page { 
            margin: 0;
            size: 80mm 297mm; /* Ukuran A4 portrait dalam mm untuk satu halaman */
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Courier New', monospace;
        }
        
        body { 
            font-size: 10px; 
            line-height: 1.2;
            color: #000;
            width: 72mm; 
            padding: 2mm 4mm;
            margin: 0 auto;
            background: #fff;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 6px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #000;
        }
        
        .header h1 { 
            font-size: 16px;
            margin-bottom: 3px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .header-info {
            font-size: 9px;
            line-height: 1.3;
        }
        
        .info { 
            margin: 4px 0;
            font-size: 10px;
        }
        
        .info-row { 
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 9px;
            table-layout: fixed;
        }
        
        thead {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        th { 
            padding: 3px 0;
            font-weight: bold;
            text-align: left;
        }
        
        td { 
            padding: 3px 0;
            vertical-align: top;
            word-wrap: break-word;
        }
        
        .promo-line { 
            font-size: 8px; 
            color: #000;
            font-style: italic;
            padding: 1px 0 2px 0;
        }
        
        .totals { 
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #000;
            font-size: 10px;
        }
        
        .total-row { 
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .grand-total { 
            border-top: 1px dashed #000;
            border-bottom: 2px double #000;
            padding: 4px 0;
            margin: 4px 0;
            font-weight: bold;
            font-size: 13px;
        }
        
        .payment-info {
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        
        .footer { 
            text-align: center; 
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid #000;
            font-size: 9px;
            line-height: 1.3;
        }
        
        .footer-text {
            margin-bottom: 4px;
        }
        
        .contact-info {
            font-size: 8px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <div class="header">
        <h1>SkyraMart</h1>
        <div class="header-info">
            @php
                $headerLines = explode("\n", $template->header_text);
                $filteredLines = array_filter($headerLines, function($line) {
                    return trim($line) !== '' && stripos($line, 'skyramart') === false;
                });
            @endphp
            @foreach($filteredLines as $line)
                {{ trim($line) }}<br>
            @endforeach
        </div>
    </div>

    {{-- TRANSACTION INFO --}}
    <div class="info">
        <div class="info-row">
            <span>Receipt No:</span>
            <span>{{ $sale->transaction_number }}</span>
        </div>
        <div class="info-row">
            <span>Date:</span>
            <span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Cashier:</span>
            <span>{{ $sale->user->name }}</span>
        </div>
        <div class="info-row">
            <span>Customer:</span>
            <span>{{ $sale->customer->customer_name ?? 'Walk-in' }}</span>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <div style="border-bottom: 1px dashed #000; margin: 3px 0;"></div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 45%;">Item</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 20%; text-align: right;">Price</th>
                <th style="width: 25%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleDetails as $detail)
            <tr>
                <td>{{ $detail->product_name }}</td>
                <td style="text-align: center;">{{ $detail->quantity }}</td>
                <td style="text-align: right;">{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            
            @if($detail->price_promotion_id || $detail->quantity_promotion_id)
            <tr>
                <td colspan="4" class="promo-line">
                    @if($detail->price_promotion_id)
                        Disc: -{{ number_format($detail->price_discount_amount, 0, ',', '.') }}
                    @endif
                    @if($detail->quantity_promotion_id && $detail->free_quantity > 0)
                        @if($detail->price_promotion_id) | @endif
                        Free: +{{ $detail->free_quantity }}
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
        </div>
        
        @if($sale->discount > 0)
        <div class="total-row">
            <span>Disc ({{ number_format($sale->discount_percentage, 0) }}%):</span>
            <span>-{{ number_format($sale->discount, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($sale->tax > 0)
        <div class="total-row">
            <span>Tax:</span>
            <span>{{ number_format($sale->tax, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- PAYMENT INFO --}}
    @php
        $payment = $sale->payments->first();
        $totalPaid = $sale->payments->sum('amount');
        $change = $totalPaid - $sale->total_price;
    @endphp

    @if($payment)
    <div class="payment-info">
        <div class="total-row">
            <span>Pay Method:</span>
            <span>{{ strtoupper($payment->payment_method) }}</span>
        </div>

        @if($payment->payment_channel)
        <div class="total-row">
            <span>Channel:</span>
            <span>{{ $payment->payment_channel }}</span>
        </div>
        @endif

        <div class="total-row">
            <span>Paid:</span>
            <span>{{ number_format($totalPaid, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Change:</span>
            <span>{{ number_format($change, 0, ',', '.') }}</span>
        </div>
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-text">
            @php
                $footerLines = explode("\n", $template->footer_text);
                $filteredFooter = array_filter($footerLines, function($line) {
                    return trim($line) !== '';
                });
            @endphp
            @foreach($filteredFooter as $line)
                {{ trim($line) }}<br>
            @endforeach
        </div>
        
        <div class="contact-info">
            {{ $template->contact_info ?? 'Powered by SkyraMart' }}
        </div>
    </div>
</body>
</html>