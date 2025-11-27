<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    protected $filterType;
    protected $month;
    protected $dateFrom;
    protected $dateTo;
    protected $rowNumber = 0;

    public function __construct($filterType = 'all', $month = null, $dateFrom = null, $dateTo = null)
    {
        $this->filterType = $filterType;
        $this->month = $month;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = Sale::with(['customer', 'user', 'saleDetails.product', 'payments']);
        
        // Apply filters
        if ($this->filterType === 'monthly' && $this->month) {
            $date = \Carbon\Carbon::parse($this->month);
            $query->whereYear('sale_date', $date->year)
                  ->whereMonth('sale_date', $date->month);
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            $query->whereBetween('sale_date', [$this->dateFrom, $this->dateTo]);
        }
        
        return $query->orderBy('sale_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Transaction Number',
            'Date',
            'Time',
            'Customer Name',
            'Phone',
            'Cashier',
            'Items Count',
            'Total Quantity',
            'Subtotal',
            'Discount',
            'Tax',
            'Total Price',
            'Payment Method',
            'Status',
            'Notes'
        ];
    }

    public function map($sale): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $sale->transaction_number,
            $sale->sale_date->format('d/m/Y'),
            $sale->sale_date->format('H:i:s'),
            $sale->customer ? $sale->customer->customer_name : 'Walk-in Customer',
            $sale->customer ? $sale->customer->phone_number : '-',
            $sale->user->name,
            $sale->saleDetails->count(),
            $sale->saleDetails->sum('quantity'),
            $sale->subtotal,
            $sale->discount,
            $sale->tax,
            $sale->total_price,
            $sale->payments->first()->payment_method ?? '-',
            strtoupper($sale->status),
            $sale->notes ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Auto-size columns
        foreach(range('A','P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Number format for currency columns
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('J2:M' . $lastRow)->getNumberFormat()
            ->setFormatCode('#,##0');

        // Center align for specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:I' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O2:O' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add borders to all cells
        $sheet->getStyle('A1:P' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Zebra striping
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':P' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB']
                    ]
                ]);
            }
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 12,
            'D' => 10,
            'E' => 25,
            'F' => 15,
            'G' => 20,
            'H' => 12,
            'I' => 12,
            'J' => 15,
            'K' => 15,
            'L' => 12,
            'M' => 15,
            'N' => 15,
            'O' => 12,
            'P' => 30,
        ];
    }

    public function title(): string
    {
        if ($this->filterType === 'monthly' && $this->month) {
            return 'Sales ' . \Carbon\Carbon::parse($this->month)->format('M Y');
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            return 'Sales Report';
        }
        return 'All Sales';
    }
}