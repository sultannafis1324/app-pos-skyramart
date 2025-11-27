<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class ProfitReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $filterType;
    protected $month;
    protected $dateFrom;
    protected $dateTo;
    protected $statistics;

    public function __construct($filterType, $month, $dateFrom, $dateTo)
    {
        $this->filterType = $filterType;
        $this->month = $month;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = Sale::with(['saleDetails.product', 'customer', 'user'])
            ->where('status', 'completed');

        // Apply filters
        if ($this->filterType === 'monthly' && $this->month) {
            $date = Carbon::parse($this->month);
            $query->whereYear('sale_date', $date->year)
                  ->whereMonth('sale_date', $date->month);
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            $query->whereBetween('sale_date', [$this->dateFrom, $this->dateTo]);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        // Calculate statistics for summary
        $totalRevenue = 0;
        $totalCost = 0;
        $totalDiscount = 0;
        $totalTax = 0;

        $data = collect();

        foreach ($sales as $sale) {
            $saleCost = 0;
            foreach ($sale->saleDetails as $detail) {
                $product = $detail->product;
                $purchasePrice = $product ? $product->purchase_price : 0;
                $saleCost += $purchasePrice * $detail->quantity;
            }

            $revenue = $sale->total_price;
            $profit = $revenue - $saleCost;
            $profitMargin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

            $totalRevenue += $revenue;
            $totalCost += $saleCost;
            $totalDiscount += $sale->discount;
            $totalTax += $sale->tax;

            $data->push([
                'transaction_number' => $sale->transaction_number,
                'date' => $sale->sale_date->format('d M Y H:i'),
                'customer' => $sale->customer ? $sale->customer->customer_name : 'Walk-in Customer',
                'items' => $sale->saleDetails->sum('quantity'),
                'revenue' => $revenue,
                'cost' => $saleCost,
                'profit' => $profit,
                'margin' => number_format($profitMargin, 2) . '%',
                'discount' => $sale->discount,
                'tax' => $sale->tax,
                'user' => $sale->user->name
            ]);
        }

        // Store statistics for use in afterSheet
        $this->statistics = [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalRevenue - $totalCost,
            'total_discount' => $totalDiscount,
            'total_tax' => $totalTax,
            'total_transactions' => $sales->count(),
            'profit_margin' => $totalRevenue > 0 ? (($totalRevenue - $totalCost) / $totalRevenue) * 100 : 0
        ];

        return $data;
    }

    public function headings(): array
    {
        return [
            'Transaction #',
            'Date & Time',
            'Customer',
            'Items Qty',
            'Revenue (Rp)',
            'Cost (Rp)',
            'Profit (Rp)',
            'Margin (%)',
            'Discount (Rp)',
            'Tax (Rp)',
            'Created By'
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Profit Report';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // Transaction #
            'B' => 18,  // Date
            'C' => 25,  // Customer
            'D' => 12,  // Items
            'E' => 16,  // Revenue
            'F' => 16,  // Cost
            'G' => 16,  // Profit
            'H' => 12,  // Margin
            'I' => 15,  // Discount
            'J' => 12,  // Tax
            'K' => 20   // User
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Get the last row
                $lastRow = $sheet->getHighestRow();
                
                // Add title row
                $sheet->insertNewRowBefore(1, 3);
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'PROFIT REPORT');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '10B981']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Add filter period
                $sheet->mergeCells('A2:K2');
                $periodText = 'Period: ';
                if ($this->filterType === 'monthly' && $this->month) {
                    $periodText .= Carbon::parse($this->month)->format('F Y');
                } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
                    $periodText .= Carbon::parse($this->dateFrom)->format('d M Y') . ' - ' . Carbon::parse($this->dateTo)->format('d M Y');
                } else {
                    $periodText .= 'All Time';
                }
                $periodText .= ' | Generated: ' . now()->format('d M Y H:i');
                
                $sheet->setCellValue('A2', $periodText);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'italic' => true,
                        'color' => ['rgb' => '6B7280']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);
                
                // Row 3 is empty separator
                
                // Headers are now on row 4
                $sheet->getStyle('A4:K4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981']
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
                
                // Apply borders and alternating colors to data rows
                $dataStartRow = 5;
                $dataEndRow = $lastRow + 3;
                
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    // Alternating row colors
                    if (($row - $dataStartRow) % 2 == 0) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB']
                            ]
                        ]);
                    }
                    
                    // Borders
                    $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'E5E7EB']
                            ]
                        ]
                    ]);
                    
                    // Number formatting
                    $sheet->getStyle("E{$row}:G{$row}")->getNumberFormat()
                        ->setFormatCode('#,##0');
                    $sheet->getStyle("I{$row}:J{$row}")->getNumberFormat()
                        ->setFormatCode('#,##0');
                    
                    // Right align numbers
                    $sheet->getStyle("D{$row}:J{$row}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    
                    // Center align margin
                    $sheet->getStyle("H{$row}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Color profit cells
                    $profitValue = $sheet->getCell("G{$row}")->getValue();
                    if (is_numeric($profitValue)) {
                        if ($profitValue >= 0) {
                            $sheet->getStyle("G{$row}")->getFont()->getColor()->setRGB('10B981');
                        } else {
                            $sheet->getStyle("G{$row}")->getFont()->getColor()->setRGB('DC2626');
                        }
                        $sheet->getStyle("G{$row}")->getFont()->setBold(true);
                    }
                }
                
                // Add summary section
                $summaryRow = $dataEndRow + 2;
                
                $sheet->mergeCells("A{$summaryRow}:K{$summaryRow}");
                $sheet->setCellValue("A{$summaryRow}", 'SUMMARY STATISTICS');
                $sheet->getStyle("A{$summaryRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '10B981']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5']
                    ]
                ]);
                
                $summaryRow++;
                
                // Summary data
                $summaryData = [
                    ['Total Transactions', $this->statistics['total_transactions'], 'Total Revenue', 'Rp ' . number_format($this->statistics['total_revenue'], 0, ',', '.')],
                    ['Total Cost', 'Rp ' . number_format($this->statistics['total_cost'], 0, ',', '.'), 'Total Profit', 'Rp ' . number_format($this->statistics['total_profit'], 0, ',', '.')],
                    ['Profit Margin', number_format($this->statistics['profit_margin'], 2) . '%', 'Total Discount', 'Rp ' . number_format($this->statistics['total_discount'], 0, ',', '.')],
                    ['Total Tax', 'Rp ' . number_format($this->statistics['total_tax'], 0, ',', '.'), '', '']
                ];
                
                foreach ($summaryData as $data) {
                    $sheet->setCellValue("A{$summaryRow}", $data[0]);
                    $sheet->setCellValue("B{$summaryRow}", $data[1]);
                    $sheet->setCellValue("C{$summaryRow}", $data[2]);
                    $sheet->setCellValue("D{$summaryRow}", $data[3]);
                    
                    $sheet->getStyle("A{$summaryRow}:D{$summaryRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'E5E7EB']
                            ]
                        ]
                    ]);
                    
                    $summaryRow++;
                }
                
                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(20);
                
                // Freeze header rows
                $sheet->freezePane('A5');
            }
        ];
    }
}