<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f0f8ff;
            padding: 20px;
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4A90E2 0%, #87CEEB 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .greeting strong {
            color: #4A90E2;
            font-size: 18px;
        }
        .intro-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
        }
        .transaction-box {
            background: linear-gradient(to right, #E3F2FD 0%, #BBDEFB 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 5px solid #4A90E2;
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.1);
        }
        .transaction-box h2 {
            margin: 0 0 20px 0;
            color: #1976D2;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 2px solid #4A90E2;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(74, 144, 226, 0.15);
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }
        .value {
            color: #333;
            font-weight: 500;
            text-align: right;
            font-size: 14px;
        }
        .total-row {
            background: linear-gradient(135deg, #4A90E2 0%, #64B5F6 100%);
            padding: 18px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .total-row .label,
        .total-row .value {
            color: white;
            font-size: 18px;
            font-weight: 700;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .items-table thead {
            background: linear-gradient(135deg, #4A90E2 0%, #64B5F6 100%);
            color: white;
        }
        .items-table th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #E3F2FD;
            font-size: 14px;
            color: #333;
        }
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        .items-table tbody tr:hover {
            background: #F5FAFF;
        }
        .promo-badge {
            display: inline-block;
            background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 6px;
            box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #4A90E2 0%, #64B5F6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 25px 0;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #1976D2 0%, #4A90E2 100%);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
            transform: translateY(-2px);
        }
        .info-box {
            background: #E3F2FD;
            border-left: 4px solid #4A90E2;
            padding: 18px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 0;
            color: #1976D2;
            font-size: 14px;
            font-weight: 500;
        }
        .info-box strong {
            color: #0D47A1;
            font-weight: 700;
        }
        .contact-info {
            background: #FAFAFA;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border: 1px solid #E0E0E0;
        }
        .contact-info p {
            margin: 8px 0;
            color: #555;
            font-size: 14px;
        }
        .contact-info strong {
            color: #1976D2;
        }
        .footer {
            background: linear-gradient(135deg, #4A90E2 0%, #87CEEB 100%);
            text-align: center;
            padding: 30px 20px;
            color: white;
        }
        .footer p {
            margin: 8px 0;
            font-size: 14px;
        }
        .footer strong {
            font-size: 18px;
            font-weight: 700;
        }
        .footer .small-text {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 15px;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 25px 20px;
            }
            .items-table th,
            .items-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <table role="presentation" class="header-table">
    <tr style="vertical-align: middle;">

        <!-- LOGO KIRI (UKURAN DIBESARKAN) -->
        <td style="width: 80px;">
            <img src="https://i.ibb.co/SX1dyCL8/Skyra-L1.png"
                 width="64"
                 style="display:block;">
        </td>

        <!-- SPASI -->
        <td style="width:10px;"></td>

        <!-- TULISAN RESET PASSWORD (TENGAH) -->
        <td style="text-align:center;">
            <h1>SkyraMart</h1>
            <p>Konfirmasi Pembayaran</p>
        </td>

        <!-- Spacer kanan -->
        <td style="width:80px;"></td>
    </tr>
</table>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Kepada Yth. <strong>{{ $customerName }}</strong>,</p>
            
            <p class="intro-text">Terima kasih telah berbelanja di SkyraMart! Pembayaran Anda telah berhasil diproses. 💚</p>

            <!-- Transaction Details -->
            <div class="transaction-box">
                <h2>📋 Detail Transaksi</h2>
                
                <div class="detail-row">
                    <span class="label">Nomor Struk:</span>
                    <span class="value"><strong>{{ $transactionNumber }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Tanggal:</span>
                    <span class="value">{{ $saleDate }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Kasir:</span>
                    <span class="value">{{ $sale->user->name }}</span>
                </div>
                
                @if($sale->customer)
                <div class="detail-row">
                    <span class="label">Pelanggan:</span>
                    <span class="value">{{ $sale->customer->customer_name }}</span>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="transaction-box">
                <h2>🛍️ Ringkasan Pesanan</h2>
                
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align: center; width: 60px;">Qty</th>
                            <th style="text-align: right; width: 120px;">Harga</th>
                            <th style="text-align: right; width: 120px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleDetails as $detail)
                        <tr>
                            <td>
                                {{ $detail->product_name }}
                                @if($detail->free_quantity > 0)
                                    <span class="promo-badge">+{{ $detail->free_quantity }} GRATIS</span>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $detail->quantity }}</td>
                            <td style="text-align: right;">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                            <td style="text-align: right;"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payment Summary -->
            <div class="transaction-box">
                <h2>💰 Ringkasan Pembayaran</h2>
                
                <div class="detail-row">
                    <span class="label">Subtotal:</span>
                    <span class="value">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                </div>
                
                @if($sale->discount > 0)
                <div class="detail-row">
                    <span class="label">Diskon ({{ number_format($sale->discount_percentage, 1) }}%):</span>
                    <span class="value" style="color: #66BB6A;">-Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                @if($sale->tax > 0)
                <div class="detail-row">
                    <span class="label">Pajak:</span>
                    <span class="value">Rp {{ number_format($sale->tax, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="total-row">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="label">TOTAL:</span>
                        <span class="value">Rp {{ $totalAmount }}</span>
                    </div>
                </div>
                
                @php
                    $payment = $sale->payments->first();
                @endphp
                
                @if($payment)
                <div class="detail-row" style="margin-top: 15px; padding-top: 15px; border-top: 2px solid rgba(74, 144, 226, 0.3);">
                    <span class="label">Metode Pembayaran:</span>
                    <span class="value">
                        <strong>{{ strtoupper($payment->payment_method) }}</strong>
                        @if($payment->payment_channel)
                            ({{ $payment->payment_channel }})
                        @endif
                    </span>
                </div>
                
                @if($payment->payment_method === 'cash')
                <div class="detail-row">
                    <span class="label">Tunai Dibayar:</span>
                    <span class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Kembalian:</span>
                    <span class="value"><strong>Rp {{ number_format($payment->amount - $sale->total_price, 0, ',', '.') }}</strong></span>
                </div>
                @endif
                @endif
            </div>

            <!-- PDF Info -->
            <div class="info-box">
                <p>📄 <strong>Struk detail Anda terlampir sebagai file PDF.</strong></p>
                <p style="font-size: 13px; margin-top: 8px; color: #555;">Silakan simpan struk ini untuk keperluan Anda.</p>
            </div>

            <!-- Contact Info -->
            <div class="contact-info">
                <p><strong>Butuh bantuan? Hubungi kami:</strong></p>
                <p>📞 WhatsApp: <strong>0889-2114-416</strong></p>
                <p>📍 Alamat: <strong>Jl. Masjid Daruttaqwa No. 123, Depok</strong></p>
                <p>📧 Email: <strong>info@skyramart.com</strong></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda! 🙏</p>
            <p><strong>💚 SkyraMart</strong> - Toko Terpercaya Anda</p>
            <p class="small-text">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>