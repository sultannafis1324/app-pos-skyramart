<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .badge-in {
            color: #155724;
            background-color: #d4edda;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .badge-out {
            color: #721c24;
            background-color: #f8d7da;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .badge-adjustment {
            color: #004085;
            background-color: #cce5ff;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Stock History Report</h2>
        @if($dateFrom || $dateTo)
            <p>
                Period: 
                @if($dateFrom) {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} @else All Time @endif
                - 
                @if($dateTo) {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }} @else Present @endif
            </p>
        @endif
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Before</th>
                <th>After</th>
                <th>Reference</th>
                <th>User</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockHistories as $history)
            <tr>
                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{ $history->product->product_name ?? 'N/A' }}<br>
                    <small>{{ $history->product->sku ?? '' }}</small>
                </td>
                <td>
                    @if($history->type == 'in')
                        <span class="badge-in">Stock In</span>
                    @elseif($history->type == 'out')
                        <span class="badge-out">Stock Out</span>
                    @else
                        <span class="badge-adjustment">Adjustment</span>
                    @endif
                </td>
                <td>
                    @if($history->type == 'out')
                        -{{ $history->quantity }}
                    @elseif($history->type == 'in')
                        +{{ $history->quantity }}
                    @else
                        {{ ($history->stock_after - $history->stock_before >= 0 ? '+' : '') . ($history->stock_after - $history->stock_before) }}
                    @endif
                </td>
                <td>{{ $history->stock_before }}</td>
                <td>{{ $history->stock_after }}</td>
                <td>
                    {{ $history->reference_type ?? '-' }}
                    @if($history->reference_id)
                        #{{ $history->reference_id }}
                    @endif
                </td>
                <td>{{ $history->user->name ?? 'System' }}</td>
                <td>{{ $history->description ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">No stock history found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $stockHistories->count() }}</p>
    </div>
</body>
</html>