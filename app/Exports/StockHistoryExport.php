<?php

namespace App\Exports;

use App\Models\StockHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockHistoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dateFrom;
    protected $dateTo;
    protected $type;

    public function __construct($dateFrom = null, $dateTo = null, $type = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->type = $type;
    }

    public function collection()
    {
        $query = StockHistory::with(['product', 'user']);

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        if ($this->type) {
            $query->where('type', $this->type);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Time',
            'Product Name',
            'SKU',
            'Type',
            'Quantity',
            'Stock Before',
            'Stock After',
            'Reference Type',
            'Reference ID',
            'User',
            'Description'
        ];
    }

    public function map($stockHistory): array
    {
        $quantityDisplay = $stockHistory->quantity;
        
        // Format quantity with sign
        if ($stockHistory->type === 'out') {
            $quantityDisplay = '-' . $stockHistory->quantity;
        } elseif ($stockHistory->type === 'in') {
            $quantityDisplay = '+' . $stockHistory->quantity;
        } elseif ($stockHistory->type === 'adjustment') {
            // For adjustment, check if stock increased or decreased
            $diff = $stockHistory->stock_after - $stockHistory->stock_before;
            $quantityDisplay = ($diff >= 0 ? '+' : '') . $diff;
        }

        return [
            $stockHistory->created_at->format('Y-m-d'),
            $stockHistory->created_at->format('H:i:s'),
            $stockHistory->product->product_name ?? 'N/A',
            $stockHistory->product->sku ?? 'N/A',
            ucfirst($stockHistory->type),
            $quantityDisplay,
            $stockHistory->stock_before,
            $stockHistory->stock_after,
            $stockHistory->reference_type ?? '-',
            $stockHistory->reference_id ?? '-',
            $stockHistory->user->name ?? 'System',
            $stockHistory->description ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}