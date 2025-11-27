<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchases Report</title>
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
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 20px;
            color: #6d28d9;
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
            background: #6d28d9;
            color: white;
        }
        
        .data-table th {
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid #5b21b6;
        }
        
        .data-table td {
            padding: 5px 4px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: top;
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
        
        .badge-received {
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
        
        /* Address styling */
        .address-block {
            font-size: 7px;
            color: #6b7280;
            line-height: 1.4;
            margin-top: 2px;
        }
        
        /* Summary */
        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #f3f4f6;
            border-left: 4px solid #7c3aed;
        }
        
        .summary h3 {
            font-size: 11px;
            color: #6d28d9;
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
            border-top: 2px solid #7c3aed;
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
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>PURCHASES REPORT</h1>
            <p>Comprehensive Purchase Orders & Inventory Analysis</p>
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
                    <td>{{ number_format($purchases->count()) }} purchase orders</td>
                    <td><strong>Period:</strong></td>
                    <td>
                        @if($purchases->isNotEmpty())
                            {{ $purchases->last()->purchase_date->format('d M Y') }} - {{ $purchases->first()->purchase_date->format('d M Y') }}
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
                    <div class="stat-label">Total Purchases</div>
                    <div class="stat-value">{{ number_format($statistics['total_transactions']) }}</div>
                    <div class="stat-sub">Received: {{ $statistics['received_purchases'] }}</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Total Cost</div>
                    <div class="stat-value">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
                    <div class="stat-sub">Total Investment</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Total Items</div>
                    <div class="stat-value">{{ number_format($statistics['total_items']) }}</div>
                    <div class="stat-sub">Units Purchased</div>
                </td>
                <td class="stat-box">
                    <div class="stat-label">Avg Order Value</div>
                    <div class="stat-value">
                        @if($statistics['total_transactions'] > 0)
                            Rp {{ number_format($statistics['total_cost'] / $statistics['total_transactions'], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </div>
                    <div class="stat-sub">Per Transaction</div>
                </td>
            </tr>
        </table>

        <!-- Purchases Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Purchase #</th>
                    <th width="8%">Date</th>
                    <th width="13%">Supplier</th>
                    <th width="20%">Contact & Address</th>
                    <th width="5%">Items</th>
                    <th width="6%">Qty</th>
                    <th width="12%">Total Cost</th>
                    <th width="8%">Status</th>
                    <th width="10%">Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $purchase)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $purchase->purchase_number }}</div>
                    </td>
                    <td>
                        <div>{{ $purchase->purchase_date->format('d M Y') }}</div>
                        <div class="text-sm text-gray">{{ $purchase->created_at->format('H:i') }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ Str::limit($purchase->supplier->supplier_name, 20) }}</div>
                    </td>
                    <td>
                        <div class="text-sm"> {{ $purchase->supplier->phone_number ?? 'N/A' }}</div>
                        @if($purchase->supplier->address)
                        <div class="address-block"> 
                            @if($purchase->supplier->address->detail_address)
                                {{ Str::limit($purchase->supplier->address->detail_address, 30) }},
                            @endif
                            @if($purchase->supplier->address->village)
                                {{ $purchase->supplier->address->village->name }},
                            @endif
                            @if($purchase->supplier->address->district)
                                {{ $purchase->supplier->address->district->name }},
                            @endif
                            @if($purchase->supplier->address->city)
                                {{ $purchase->supplier->address->city->name }}
                            @endif
                        </div>
                        @else
                        <div class="address-block"> No address</div>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $purchase->purchaseDetails->count() }}
                    </td>
                    <td class="text-center">
                        {{ $purchase->purchaseDetails->sum('quantity') }}
                    </td>
                    <td class="text-right font-bold">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($purchase->status === 'received')
                            <span class="badge badge-received">RCVD</span>
                        @elseif($purchase->status === 'pending')
                            <span class="badge badge-pending">PEND</span>
                        @else
                            <span class="badge badge-cancelled">CANC</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ Str::limit($purchase->user->name, 15) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #9ca3af;">
                        No purchase data available for this period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        @if($purchases->isNotEmpty())
        <div class="summary">
            <h3>FINANCIAL SUMMARY</h3>
            <table class="summary-table">
                <tr>
                    <td width="70%">Total Purchase Orders:</td>
                    <td width="30%" class="text-right">{{ number_format($purchases->count()) }} orders</td>
                </tr>
                <tr>
                    <td>Total Units Purchased:</td>
                    <td class="text-right">{{ number_format($purchases->sum(function($p) { return $p->purchaseDetails->sum('quantity'); })) }} units</td>
                </tr>
                <tr>
                    <td>Received Orders:</td>
                    <td class="text-right">{{ $purchases->where('status', 'received')->count() }} orders</td>
                </tr>
                <tr>
                    <td>Pending Orders:</td>
                    <td class="text-right">{{ $purchases->where('status', 'pending')->count() }} orders</td>
                </tr>
                <tr>
                    <td>TOTAL INVESTMENT:</td>
                    <td class="text-right">Rp {{ number_format($purchases->sum('total_price'), 0, ',', '.') }}</td>
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