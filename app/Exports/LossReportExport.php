<?php

namespace App\Exports;

use App\Models\LossReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LossReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filterType;
    protected $month;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($filterType, $month, $dateFrom, $dateTo)
    {
        $this->filterType = $filterType;
        $this->month = $month;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = LossReport::with(['product.category', 'batch']);

        if ($this->filterType === 'monthly' && $this->month) {
            $date = Carbon::parse($this->month);
            $query->whereYear('expiry_date', $date->year)
                  ->whereMonth('expiry_date', $date->month);
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            $query->whereBetween('expiry_date', [$this->dateFrom, $this->dateTo]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date Recorded',
            'Product Name',
            'Category',
            'Batch Number',
            'Expiry Date',
            'Quantity Expired',
            'Purchase Price',
            'Total Loss',
            'Notes'
        ];
    }

    public function map($loss): array
    {
        return [
            $loss->created_at->format('d M Y'),
            $loss->product->product_name,
            $loss->product->category->name ?? '-',
            $loss->batch_number,
            $loss->expiry_date->format('d M Y'),
            $loss->quantity_expired,
            'Rp ' . number_format($loss->purchase_price, 0, ',', '.'),
            'Rp ' . number_format($loss->total_loss, 0, ',', '.'),
            $loss->notes ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}