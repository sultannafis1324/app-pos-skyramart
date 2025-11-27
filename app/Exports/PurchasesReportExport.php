<?php

namespace App\Exports;

use App\Models\Purchase;
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

class PurchasesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
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
        // PERBAIKAN: Eager load address relationships
        $query = Purchase::with([
            'supplier.address.province',
            'supplier.address.city',
            'supplier.address.district',
            'supplier.address.village',
            'user',
            'purchaseDetails.product'
        ]);
        
        // Apply filters
        if ($this->filterType === 'monthly' && $this->month) {
            $date = \Carbon\Carbon::parse($this->month);
            $query->whereYear('purchase_date', $date->year)
                  ->whereMonth('purchase_date', $date->month);
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            $query->whereBetween('purchase_date', [$this->dateFrom, $this->dateTo]);
        }
        
        return $query->orderBy('purchase_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Purchase Number',
            'Date',
            'Time',
            'Supplier Name',
            'Supplier Phone',
            'Supplier Address',
            'Items Count',
            'Total Quantity',
            'Total Price',
            'Status',
            'Created By',
            'Notes'
        ];
    }

    public function map($purchase): array
    {
        $this->rowNumber++;
        
        // PERBAIKAN: Format address dengan benar
        $address = $this->formatAddress($purchase->supplier);
        
        return [
            $this->rowNumber,
            $purchase->purchase_number,
            $purchase->purchase_date->format('d/m/Y'),
            $purchase->created_at->format('H:i:s'),
            $purchase->supplier->supplier_name,
            $purchase->supplier->phone_number ?? '-',
            $address,
            $purchase->purchaseDetails->count(),
            $purchase->purchaseDetails->sum('quantity'),
            $purchase->total_price,
            strtoupper($purchase->status),
            $purchase->user->name,
            $purchase->notes ?? '-'
        ];
    }

    /**
     * Format supplier address into readable string
     */
    private function formatAddress($supplier): string
    {
        if (!$supplier->address) {
            return '-';
        }

        $addressParts = [];
        
        if ($supplier->address->detail_address) {
            $addressParts[] = $supplier->address->detail_address;
        }
        
        if ($supplier->address->village) {
            $addressParts[] = $supplier->address->village->name;
        }
        
        if ($supplier->address->district) {
            $addressParts[] = $supplier->address->district->name;
        }
        
        if ($supplier->address->city) {
            $addressParts[] = $supplier->address->city->name;
        }
        
        if ($supplier->address->province) {
            $addressParts[] = $supplier->address->province->name;
        }

        return !empty($addressParts) ? implode(', ', $addressParts) : '-';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7C3AED']
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

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Get last row
        $lastRow = $sheet->getHighestRow();

        // Number format for currency column (Column J)
        $sheet->getStyle('J2:J' . $lastRow)->getNumberFormat()
            ->setFormatCode('#,##0');

        // Center align for specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:I' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K2:K' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Text wrap for address column (Column G)
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()
            ->setWrapText(true);

        // Add borders to all cells
        $sheet->getStyle('A1:M' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Zebra striping for better readability
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':M' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB']
                    ]
                ]);
            }
        }

        // Status color coding (Column K)
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('K' . $i)->getValue();
            $color = 'FFFFFF';
            
            if ($status === 'RECEIVED') {
                $color = 'D1FAE5';
            } elseif ($status === 'PENDING') {
                $color = 'FEF3C7';
            } elseif ($status === 'CANCELLED') {
                $color = 'FEE2E2';
            }
            
            $sheet->getStyle('K' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color]
                ],
                'font' => [
                    'bold' => true
                ]
            ]);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,      // No
            'B' => 20,     // Purchase Number
            'C' => 12,     // Date
            'D' => 10,     // Time
            'E' => 25,     // Supplier Name
            'F' => 15,     // Phone
            'G' => 50,     // Address (diperlebar untuk menampung address lengkap)
            'H' => 10,     // Items Count
            'I' => 12,     // Total Quantity
            'J' => 15,     // Total Price
            'K' => 12,     // Status
            'L' => 20,     // Created By
            'M' => 40,     // Notes
        ];
    }

    public function title(): string
    {
        if ($this->filterType === 'monthly' && $this->month) {
            return 'Purchases ' . \Carbon\Carbon::parse($this->month)->format('M Y');
        } elseif ($this->filterType === 'daily' && $this->dateFrom && $this->dateTo) {
            return 'Purchases Report';
        }
        return 'All Purchases';
    }
}