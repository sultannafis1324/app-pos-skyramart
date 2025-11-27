@php
    use App\Models\ReceiptTemplate;
    $template = ReceiptTemplate::getActive('print');
    
    if (!$template) {
        $template = (object)[
            'header_text' => "SkyraMart\nJl. Masjid Daruttaqwa No. 123\nDepok City, Indonesia\nWA: 0895-3265-81316",
            'transaction_section_title' => "TRANSACTION INFO",
            'items_section_title' => "ITEMS PURCHASED",
            'payment_section_title' => "PAYMENT DETAILS",
            'footer_text' => "Thank You For Shopping!\nItems purchased are non-refundable\nPlease save this receipt for your records",
            'contact_info' => "Powered by SkyraMart POS System"
        ];
    }
    
    // Hitung item count untuk dynamic sizing
    $itemCount = $sale->saleDetails->count();
    
    // Dynamic font size berdasarkan jumlah item
    if ($itemCount <= 5) {
        $baseFontSize = '10px';
        $itemFontSize = '9px';
    } elseif ($itemCount <= 10) {
        $baseFontSize = '9px';
        $itemFontSize = '8px';
    } elseif ($itemCount <= 20) {
        $baseFontSize = '8px';
        $itemFontSize = '7px';
    } else {
        $baseFontSize = '7px';
        $itemFontSize = '6px';
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
            size: 80mm 200mm;
        }
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        body { 
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $baseFontSize }};
            line-height: 1.1;
            color: #000;
            width: 80mm;
            max-height: 200mm;
            padding: 2mm 3mm;
            margin: 0 auto;
            background: #fff;
            overflow: hidden;
        }
        
        /* HEADER */
        .header { 
            text-align: center; 
            margin-bottom: 1mm;
            padding-bottom: 1mm;
        }
        .store-name {
            font-size: calc({{ $baseFontSize }} + 4px);
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 0.5mm;
        }
        .header-line {
            font-size: calc({{ $baseFontSize }} - 1px);
            line-height: 1.1;
            margin: 0;
        }
        
        /* DIVIDERS */
        .divider-dash { 
            border-top: 1px dashed #000; 
            margin: 0.8mm 0;
            height: 0;
        }
        .divider-solid { 
            border-top: 1px solid #000; 
            margin: 0.8mm 0;
            height: 0;
        }
        
        /* SECTION TITLES */
        .section-title {
            font-weight: bold;
            font-size: {{ $baseFontSize }};
            margin: 0.8mm 0 0.4mm 0;
            letter-spacing: 0.2px;
        }
        
        /* INFO TABLE */
        .info-table {
            width: 100%;
            font-size: calc({{ $baseFontSize }} - 1px);
            margin: 0.4mm 0 0.8mm 0;
        }
        .info-table td {
            padding: 0.2mm 0;
            vertical-align: top;
            line-height: 1.1;
        }
        .info-table td:first-child {
            width: 28%;
        }
        .info-table td:nth-child(2) {
            width: 2%;
        }
        .info-table td:last-child {
            width: 70%;
            word-wrap: break-word;
        }
        
        /* ITEMS TABLE */
        .items-table { 
            width: 100%; 
            border-collapse: collapse;
            font-size: {{ $itemFontSize }};
            margin: 0.4mm 0;
        }
        .items-table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .items-table th { 
            padding: 0.4mm 0;
            font-weight: bold;
            font-size: {{ $itemFontSize }};
            line-height: 1.05;
        }
        .items-table td { 
            padding: 0.25mm 0;
            vertical-align: top;
            line-height: 1.05;
        }
        
        .product-name {
            word-wrap: break-word;
            max-width: 38mm;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .promo-line { 
            font-size: calc({{ $itemFontSize }} - 1px);
            color: #333;
            font-style: italic;
            padding-left: 2mm;
            line-height: 1.05;
        }
        
        /* TOTALS */
        .totals-table {
            width: 100%;
            font-size: {{ $baseFontSize }};
            margin: 0.4mm 0;
        }
        .totals-table td {
            padding: 0.25mm 0;
            line-height: 1.1;
        }
        .totals-table td:first-child {
            width: 55%;
        }
        .totals-table td:last-child {
            width: 45%;
            text-align: right;
        }
        
        .grand-total { 
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 0.6mm 0 !important;
            margin: 0.4mm 0;
            font-weight: bold;
            font-size: calc({{ $baseFontSize }} + 2px);
        }
        
        /* PAYMENT SECTION */
        .payment-section {
            margin-top: 0.8mm;
            padding-top: 0.8mm;
            border-top: 1px dashed #000;
        }
        
        /* FOOTER */
        .footer { 
            text-align: center; 
            margin-top: 1.5mm;
            padding-top: 0.8mm;
            border-top: 1px dashed #000;
            font-size: calc({{ $baseFontSize }} - 2px);
            line-height: 1.2;
        }
        .footer-line {
            margin: 0.2mm 0;
        }
        
        /* UTILITIES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        @media print {
            body { 
                margin: 0; 
                padding: 2mm 3mm;
                page-break-inside: avoid;
            }
            * {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <div class="header">
        @foreach(explode("\n", $template->header_text ?? 'SkyraMart') as $line)
            @if($loop->first)
                <div class="store-name">{{ strtoupper(trim($line)) }}</div>
            @else
                <div class="header-line">{{ trim($line) }}</div>
            @endif
        @endforeach
    </div>

    {{-- TRANSACTION INFO --}}
    <div class="divider-dash"></div>
    @if($template->transaction_section_title)
    <div class="section-title">{{ strtoupper($template->transaction_section_title) }}</div>
    @endif
    
    <table class="info-table">
        <tr>
            <td>Receipt No</td>
            <td>:</td>
            <td class="font-bold">{{ $sale->transaction_number }}</td>
        </tr>
        <tr>
            <td>Date</td>
            <td>:</td>
            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Cashier</td>
            <td>:</td>
            <td>{{ $sale->user->name }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>:</td>
            <td>{{ $sale->customer->customer_name ?? 'Walk-in' }}</td>
        </tr>
    </table>

    {{-- ITEMS TABLE --}}
    <div class="divider-solid"></div>
    @if($template->items_section_title)
    <div class="section-title">{{ strtoupper($template->items_section_title) }}</div>
    @endif
    
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 45%;">Item</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 22%;">Price</th>
                <th class="text-right" style="width: 23%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleDetails as $detail)
            <tr>
                <td class="product-name">{{ $detail->product_name }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-right">{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            
            @if($detail->price_promotion_id || $detail->quantity_promotion_id)
            <tr>
                <td colspan="4" class="promo-line">
                    @if($detail->price_promotion_id)
                        Disc: -Rp {{ number_format($detail->price_discount_amount, 0, ',', '.') }}
                    @endif
                    @if($detail->quantity_promotion_id && $detail->free_quantity > 0)
                        @if($detail->price_promotion_id) | @endif
                        +{{ $detail->free_quantity }}
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="divider-solid"></div>
    @if($template->payment_section_title)
    <div class="section-title">{{ strtoupper($template->payment_section_title) }}</div>
    @endif
    
    <table class="totals-table">
        <tr>
            <td>Subtotal</td>
            <td>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
        </tr>
        
        @if($sale->discount > 0)
        <tr>
            <td>Discount ({{ number_format($sale->discount_percentage, 1) }}%)</td>
            <td>-Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
        </tr>
        @endif

        @if($sale->tax > 0)
        <tr>
            <td>Tax</td>
            <td>Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <table class="totals-table grand-total">
        <tr>
            <td class="font-bold">TOTAL</td>
            <td class="font-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- PAYMENT INFO --}}
    @php
        $payment = $sale->payments->first();
        $totalPaid = $sale->payments->sum('amount');
        $change = $totalPaid - $sale->total_price;
    @endphp

    @if($payment)
    <div class="payment-section">
        <table class="totals-table">
            <tr>
                <td>Payment Method</td>
                <td class="font-bold">{{ strtoupper($payment->payment_method) }}</td>
            </tr>

            @if($payment->payment_channel)
            <tr>
                <td>Channel</td>
                <td>{{ $payment->payment_channel }}</td>
            </tr>
            @endif

            @if($payment->payment_method === 'cash')
            <tr>
                <td>Amount Paid</td>
                <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Change</td>
                <td>Rp {{ number_format($change, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        @if($template->footer_text)
            @foreach(explode("\n", $template->footer_text) as $line)
                <div class="footer-line">{{ trim($line) }}</div>
            @endforeach
        @endif
        
        @if($template->contact_info)
            <div style="margin-top: 0.8mm; font-size: calc({{ $baseFontSize }} - 3px); color: #555;">
                @foreach(explode("\n", $template->contact_info) as $line)
                    <div class="footer-line">{{ trim($line) }}</div>
                @endforeach
            </div>
        @endif

        @if($template->store_branding)
        <div style="margin-top: 0.8mm; font-weight: bold;">
            {{ $template->store_branding }}
        </div>
        @endif
    </div>
</body>
</html>