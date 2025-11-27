<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loss Report - {{ now()->format('d M Y') }}</title>

    <style>
        body {
            font-family: "Inter", Arial, sans-serif;
            margin: 25px;
            color: #333;
            font-size: 13px;
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #d32f2f;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #d32f2f;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .header p {
            margin: 2px 0;
            font-size: 13px;
            color: #555;
        }

        /* Stats Box */
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fafafa;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #e5e5e5;
        }

        .stat-card strong {
            color: #444;
            font-weight: 600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12.5px;
        }

        th {
            background: #f7dcdc;
            padding: 8px;
            font-weight: 600;
            border: 1px solid #e5b8b8;
            color: #b71c1c;
        }

        td {
            padding: 8px;
            border: 1px solid #e0e0e0;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right { text-align: right; }
        .text-danger { color: #c62828; font-weight: 600; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Loss Report</h1>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>

        @if($filterType === 'monthly' && $month)
            <p>Period: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
        @elseif($filterType === 'daily' && $dateFrom && $dateTo)
            <p>Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
        @else
            <p>Period: All Time</p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-card">
            <strong>Total Expired Batches:</strong><br>
            {{ number_format($statistics['total_expired_batches']) }}
        </div>

        <div class="stat-card">
            <strong>Total Quantity Lost:</strong><br>
            {{ number_format($statistics['total_quantity_lost']) }} units
        </div>

        <div class="stat-card">
            <strong>Total Loss Value:</strong><br>
            Rp {{ number_format($statistics['total_loss_value'], 0, ',', '.') }}
        </div>

        <div class="stat-card">
            <strong>Average Loss/Batch:</strong><br>
            Rp {{ number_format($statistics['average_loss_per_batch'], 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date Recorded</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Batch Number</th>
                <th>Expiry Date</th>
                <th class="text-right">Qty Expired</th>
                <th class="text-right">Purchase Price</th>
                <th class="text-right">Total Loss</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($losses as $loss)
            <tr>
                <td>{{ $loss->created_at->format('d M Y') }}</td>
                <td>{{ $loss->product->product_name ?? 'Unknown Product' }}</td>
                <td>{{ $loss->product->category->name ?? '-' }}</td>
                <td>{{ $loss->batch_number }}</td>
                <td>{{ $loss->expiry_date->format('d M Y') }}</td>
                <td class="text-right">{{ number_format($loss->quantity_expired) }}</td>
                <td class="text-right">Rp {{ number_format($loss->purchase_price, 0, ',', '.') }}</td>
                <td class="text-right text-danger">Rp {{ number_format($loss->total_loss, 0, ',', '.') }}</td>
                <td>{{ $loss->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
