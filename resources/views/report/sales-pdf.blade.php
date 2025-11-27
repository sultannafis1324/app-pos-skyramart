<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Report</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 landscape;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.3;
        }
        
        .container {
            width: 100%;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 20px;
            color: #1e40af;
            margin-bottom: 3px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        /* Report Info */
        .report-info {
            background: #f3f4f6;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 3px 5px;
            font-size: 9px;
        }
        
        .info-table strong {
            color: #1f2937;
            font-weight: bold;
        }
        
        /* Statistics */
        .statistics {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        
        .stat-box {
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
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
        
        .stat-sub {
            font-size: 7px;
            color: #9ca3af;
            margin-top: 2px;
        }
        
        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .data-table thead {
            background: #1e40af;
            color: white;
        }
        
        .data-table th {
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid #1e3a8a;
        }
        
        .data-table td {
            padding: 5px 4px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 600;
        }
        
        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Summary */
        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #f3f4f6;
            border-left: 4px solid #2563eb;
        }
        
        .summary h3 {
            font-size: 11px;
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 4px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        
        .summary-table tr:last-child td {
            border-bottom: none;
            font-weight: bold;
            font-size: 10px;
            padding-top: 8px;
            border-top: 2px solid #2563eb;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
        }
        
        /* Utilities */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 7px; }
        .text-gray { color: #6b7280; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>SALES REPORT</h1>
            <p>Comprehensive Sales Analysis & Transaction Details</p>
        </div>

        <!-- Report Info -->
        <div class="report-info">
            <table class="info-table">
                <tr>
                    <td width="15%"><strong>Report Type:</strong></td>
                    <td width="35%">
                        @if($filterType === 'monthly' && $month)
                            Monthly Report - {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                        @elseif($filterType === 'daily' && $dateFrom && $dateTo)
                            Date Range: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                        @else
                            All Time Report
                        @endif
                    </td>
                    <td width="15%"><strong>Generated:</strong></td>
                    <td width="35%">{{ now()->format('d M Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Records:</strong></td>
                    <td>{{ number_format($sales->count()) }} transactions</td>
                    <td><strong>Period:</strong></td>
                    <td>
                        @if($sales->isNotEmpty())
                            {{ $sales->last()->sale_date->format('d M Y') }} - {{ $sales->first()->sale_date->format('d M Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Statistics -->
        <table class="statistics">
            <tr>
                <td class="stat-box">
                    <div class="stat-label">Total Transactions</div>
                    <div class="stat-value">{{ number_format($statistics['total_transactions']) }}</div>
                    <div class="stat-sub">Completed: {{ $statistics['completed_sales'] }}</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">Rp {{ number_format($statistics['total_revenue'], 0, ',', '.') }}</div>
                    <div class="stat-sub">Gross Income</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Total Discount</div>
                    <div class="stat-value">Rp {{ number_format($statistics['total_discount'], 0, ',', '.') }}</div>
                    <div class="stat-sub">Applied Discounts</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Net Revenue</div>
                    <div class="stat-value">Rp {{ number_format($statistics['net_revenue'], 0, ',', '.') }}</div>
                    <div class="stat-sub">After Discounts</div>
                </td>
            </tr>
        </table>

        <!-- Sales Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="12%">Transaction</th>
                    <th width="9%">Date</th>
                    <th width="15%">Customer</th>
                    <th width="6%">Items</th>
                    <th width="12%">Subtotal</th>
                    <th width="10%">Discount</th>
                    <th width="12%">Total</th>
                    <th width="10%">Payment</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $index => $sale)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $sale->transaction_number }}</div>
                        <div class="text-sm text-gray">{{ $sale->user->name }}</div>
                    </td>
                    <td>
                        <div>{{ $sale->sale_date->format('d M Y') }}</div>
                        <div class="text-sm text-gray">{{ $sale->sale_date->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($sale->customer)
                            <div>{{ Str::limit($sale->customer->customer_name, 20) }}</div>
                            <div class="text-sm text-gray">{{ $sale->customer->phone_number }}</div>
                        @else
                            <div class="text-gray">Walk-in</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <div>{{ $sale->saleDetails->count() }}</div>
                        <div class="text-sm text-gray">{{ $sale->saleDetails->sum('quantity') }} qty</div>
                    </td>
                    <td class="text-right">{{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    <td class="text-right text-red">
                        @if($sale->discount > 0)
                            -{{ number_format($sale->discount, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right font-bold">{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="text-sm">{{ $sale->payments->first()->payment_method ?? '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if($sale->status === 'completed')
                            <span class="badge badge-completed">DONE</span>
                        @elseif($sale->status === 'pending')
                            <span class="badge badge-pending">WAIT</span>
                        @else
                            <span class="badge badge-cancelled">VOID</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #9ca3af;">
                        No sales data available for this period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        @if($sales->isNotEmpty())
        <div class="summary">
            <h3>FINANCIAL SUMMARY</h3>
            <table class="summary-table">
                <tr>
                    <td width="70%">Total Subtotal:</td>
                    <td width="30%" class="text-right">Rp {{ number_format($sales->sum('subtotal'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Discount:</td>
                    <td class="text-right text-red">-Rp {{ number_format($sales->sum('discount'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Tax:</td>
                    <td class="text-right">Rp {{ number_format($sales->sum('tax'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>GRAND TOTAL:</td>
                    <td class="text-right">Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated on {{ now()->format('l, d F Y \a\t H:i:s') }}</p>
        </div>

    </div>
</body>
</html>