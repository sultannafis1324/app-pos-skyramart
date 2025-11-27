<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #1f2937;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #10b981;
        }
        
        .header h1 {
            font-size: 20px;
            color: #10b981;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header p {
            font-size: 10px;
            color: #6b7280;
        }
        
        .info-section {
            margin-bottom: 15px;
            background: #f9fafb;
            padding: 10px;
            border-radius: 5px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-item {
            display: table-cell;
            width: 25%;
            padding: 5px;
        }
        
        .info-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .info-value {
            font-size: 11px;
            color: #1f2937;
            font-weight: bold;
            margin-top: 2px;
        }
        
        .statistics {
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e5e7eb;
            background: #ffffff;
        }
        
        .stat-item.revenue {
            background: #3be0f6ff;
            border-color: #064e3b;
        }
        
        .stat-item.cost {
            background: #3be0f6ff;
            border-color: #064e3b;
        }
        
        .stat-item.profit {
            background: #3be0f6ff;
            border-color: #064e3b;
        }
        
        .stat-item.margin {
            background: #3be0f6ff;
            border-color: #064e3b;
        }
        
        .stat-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th {
            background: #10b981;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .badge-green {
            background: #d1fae5;
            color: #047857;
        }
        
        .badge-yellow {
            background: #fef3c7;
            color: #b45309;
        }
        
        .badge-red {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>PROFIT REPORT</h1>
        <p>
            @if($filterType === 'monthly' && $month)
                Period: {{ \Carbon\Carbon::parse($month)->format('F Y') }}
            @elseif($filterType === 'daily' && $dateFrom && $dateTo)
                Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            @else
                All Time Report
            @endif
        </p>
        <p style="font-size: 8px;">Generated: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <!-- Statistics Summary -->
    <div class="statistics">
        <div class="stats-grid">
            <div class="stat-item revenue">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-item cost">
                <div class="stat-label">Total Cost</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-item profit">
                <div class="stat-label">Total Profit</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_profit'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-item margin">
                <div class="stat-label">Profit Margin</div>
                <div class="stat-value">{{ number_format($statistics['profit_margin'], 2) }}%</div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Transactions</div>
                <div class="info-value">{{ number_format($statistics['total_transactions']) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Avg Profit/Trans</div>
                <div class="info-value">Rp {{ number_format($statistics['average_profit_per_transaction'], 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Discount</div>
                <div class="info-value">Rp {{ number_format($statistics['total_discount_given'], 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Tax</div>
                <div class="info-value">Rp {{ number_format($statistics['total_tax_collected'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Top Profitable Products -->
    @if($topProfitableProducts->isNotEmpty())
    <div class="section-title">TOP 10 PROFITABLE PRODUCTS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Product</th>
                <th style="width: 10%;" class="text-right">Qty Sold</th>
                <th style="width: 18%;" class="text-right">Revenue</th>
                <th style="width: 18%;" class="text-right">Cost</th>
                <th style="width: 15%;" class="text-right">Profit</th>
                <th style="width: 10%;" class="text-center">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProfitableProducts as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td class="text-right">{{ number_format($product->total_quantity, 0) }}</td>
                <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($product->total_cost, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold; color: #10b981;">
                    Rp {{ number_format($product->total_profit, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="badge {{ $product->profit_margin >= 30 ? 'badge-green' : ($product->profit_margin >= 15 ? 'badge-yellow' : 'badge-red') }}">
                        {{ number_format($product->profit_margin, 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Transaction Details -->
    <div class="section-title">TRANSACTION DETAILS</div>
    @if($profits->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Transaction #</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 8%;" class="text-center">Items</th>
                <th style="width: 15%;" class="text-right">Revenue</th>
                <th style="width: 12%;" class="text-right">Cost</th>
                <th style="width: 12%;" class="text-right">Profit</th>
                <th style="width: 8%;" class="text-center">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profits->take(50) as $sale)
                @php
                    $totalCost = 0;
                    foreach($sale->saleDetails as $detail) {
                        $product = $detail->product;
                        $purchasePrice = $product ? $product->purchase_price : 0;
                        $totalCost += $purchasePrice * $detail->quantity;
                    }
                    $revenue = $sale->total_price;
                    $profit = $revenue - $totalCost;
                    $profitMargin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;
                @endphp
            <tr>
                <td>{{ $sale->transaction_number }}</td>
                <td>{{ $sale->sale_date->format('d M Y') }}</td>
                <td>{{ $sale->customer ? $sale->customer->customer_name : 'Walk-in' }}</td>
                <td class="text-center">{{ $sale->saleDetails->sum('quantity') }}</td>
                <td class="text-right">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold; color: {{ $profit >= 0 ? '#10b981' : '#dc2626' }};">
                    Rp {{ number_format($profit, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="badge {{ $profitMargin >= 30 ? 'badge-green' : ($profitMargin >= 15 ? 'badge-yellow' : 'badge-red') }}">
                        {{ number_format($profitMargin, 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($profits->count() > 50)
    <p style="text-align: center; color: #6b7280; font-size: 8px; margin-top: -10px;">
        Showing first 50 transactions of {{ number_format($profits->count()) }} total
    </p>
    @endif
    @else
    <div class="no-data">
        <p>No transaction data available for the selected period</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated report and does not require a signature</p>
        <p>© {{ date('Y') }} SkyraMart. All rights reserved.</p>
    </div>
</body>
</html>