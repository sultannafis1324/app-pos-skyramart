<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfitReportExport;
use Carbon\Carbon;
use App\Exports\PurchasesReportExport;
use App\Exports\SalesReportExport;

class ReportController extends Controller
{
    /**
     * Display profit report page with filters
     */
    public function profit(Request $request)
    {
        try {
            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month', date('Y-m'));
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $perPage = $request->get('per_page', 25);

            // Get profit data
            $profitData = $this->calculateProfit($filterType, $month, $dateFrom, $dateTo, $perPage);

            return view('report.profit', [
                'profits' => $profitData['profits'],
                'statistics' => $profitData['statistics'],
                'topProfitableProducts' => $profitData['topProfitableProducts'],
                'filterType' => $filterType,
                'month' => $month,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate profit report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate profit report: ' . $e->getMessage());
        }
    }

    /**
     * Calculate profit data based on filters
     */
    private function calculateProfit($filterType, $month, $dateFrom, $dateTo, $perPage = null)
    {
        // Build sales query
        $salesQuery = Sale::with(['saleDetails.product', 'customer', 'user', 'payments'])
            ->where('status', 'completed');

        // Apply date filters
        if ($filterType === 'monthly' && $month) {
            $date = Carbon::parse($month);
            $salesQuery->whereYear('sale_date', $date->year)
                      ->whereMonth('sale_date', $date->month);
        } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
            $salesQuery->whereBetween('sale_date', [$dateFrom, $dateTo]);
        }

        // Get sales data
        if ($perPage) {
            $sales = $salesQuery->orderBy('sale_date', 'desc')->paginate($perPage);
        } else {
            $sales = $salesQuery->orderBy('sale_date', 'desc')->get();
        }

        // Calculate totals
        $totalRevenue = 0;
        $totalCost = 0;
        $totalDiscount = 0;
        $totalTax = 0;

        foreach ($sales as $sale) {
            $totalRevenue += $sale->total_price;
            $totalDiscount += $sale->discount;
            $totalTax += $sale->tax;
            
            foreach ($sale->saleDetails as $detail) {
                $product = $detail->product;
                $purchasePrice = $product ? $product->purchase_price : 0;
                $totalCost += $purchasePrice * $detail->quantity;
            }
        }

        $totalProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        $statistics = [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'profit_margin' => $profitMargin,
            'total_transactions' => $sales instanceof \Illuminate\Pagination\LengthAwarePaginator ? $sales->total() : $sales->count(),
            'average_profit_per_transaction' => $sales->count() > 0 ? $totalProfit / $sales->count() : 0,
            'total_discount_given' => $totalDiscount,
            'total_tax_collected' => $totalTax
        ];

        // Get top profitable products
        $topProfitableProducts = $this->getTopProfitableProducts($filterType, $month, $dateFrom, $dateTo);

        return [
            'profits' => $sales,
            'statistics' => $statistics,
            'topProfitableProducts' => $topProfitableProducts
        ];
    }

    /**
     * Get top profitable products
     */
    private function getTopProfitableProducts($filterType, $month, $dateFrom, $dateTo)
    {
        $query = DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->where('sales.status', 'completed');

        // Apply filters
        if ($filterType === 'monthly' && $month) {
            $date = Carbon::parse($month);
            $query->whereYear('sales.sale_date', $date->year)
                  ->whereMonth('sales.sale_date', $date->month);
        } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
            $query->whereBetween('sales.sale_date', [$dateFrom, $dateTo]);
        }

        $products = $query->select(
                'products.product_name',
                'products.purchase_price',
                DB::raw('SUM(sale_details.quantity) as total_quantity'),
                DB::raw('SUM(sale_details.subtotal) as total_revenue'),
                DB::raw('SUM(products.purchase_price * sale_details.quantity) as total_cost'),
                DB::raw('SUM(sale_details.subtotal - (products.purchase_price * sale_details.quantity)) as total_profit')
            )
            ->groupBy('products.id', 'products.product_name', 'products.purchase_price')
            ->orderBy('total_profit', 'desc')
            ->limit(10)
            ->get();

        return $products->map(function($product) {
            $product->profit_margin = $product->total_revenue > 0 
                ? (($product->total_profit / $product->total_revenue) * 100) 
                : 0;
            return $product;
        });
    }

    /**
     * Export profit report to PDF
     */
    public function profitPdf(Request $request)
    {
        try {
            Log::info('Starting profit PDF export', $request->all());

            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $profitData = $this->calculateProfit($filterType, $month, $dateFrom, $dateTo);

            $pdf = Pdf::loadView('report.profit-pdf', [
                'profits' => $profitData['profits'],
                'statistics' => $profitData['statistics'],
                'topProfitableProducts' => $profitData['topProfitableProducts'],
                'filterType' => $filterType,
                'month' => $month,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ])
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

            $filename = 'profit-report-' . now()->format('Ymd-His') . '.pdf';

            Log::info('Profit PDF generated successfully', ['filename' => $filename]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Profit PDF Export Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export profit report to Excel
     */
    public function profitExcel(Request $request)
    {
        try {
            Log::info('Starting profit Excel export', $request->all());

            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $filename = 'profit-report-' . now()->format('Ymd-His') . '.xlsx';

            Log::info('Profit Excel export initiated', ['filename' => $filename]);

            return Excel::download(
                new ProfitReportExport($filterType, $month, $dateFrom, $dateTo),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Profit Excel Export Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    public function purchases(Request $request)
{
    $filterType = $request->get('filter_type', 'all');
    $month = $request->get('month');
    $dateFrom = $request->get('date_from');
    $dateTo = $request->get('date_to');
    $perPage = $request->get('per_page', 25);

    // Build query
    $query = Purchase::with(['supplier', 'user', 'purchaseDetails.product']);

    // Apply filters
    if ($filterType === 'monthly' && $month) {
        $date = \Carbon\Carbon::parse($month);
        $query->whereYear('purchase_date', $date->year)
              ->whereMonth('purchase_date', $date->month);
    } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
        $query->whereBetween('purchase_date', [$dateFrom, $dateTo]);
    }

    // Get paginated purchases
    $purchases = $query->orderBy('purchase_date', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate($perPage)
                      ->appends($request->except('page'));

    // Calculate statistics - PERBAIKAN DI SINI
    $statisticsQuery = Purchase::query();
    
    // Apply same filters for statistics
    if ($filterType === 'monthly' && $month) {
        $date = \Carbon\Carbon::parse($month);
        $statisticsQuery->whereYear('purchase_date', $date->year)
                       ->whereMonth('purchase_date', $date->month);
    } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
        $statisticsQuery->whereBetween('purchase_date', [$dateFrom, $dateTo]);
    }

    // Get purchase IDs for filtered results
    $purchaseIds = $statisticsQuery->pluck('id');
    
    $statistics = [
        'total_transactions' => $statisticsQuery->count(),
        'total_cost' => $statisticsQuery->sum('total_price'),
        'received_purchases' => (clone $statisticsQuery)->where('status', 'received')->count(),
        'pending_purchases' => (clone $statisticsQuery)->where('status', 'pending')->count(),
        'cancelled_purchases' => (clone $statisticsQuery)->where('status', 'cancelled')->count(),
        'total_items' => DB::table('purchase_details')
            ->whereIn('purchase_id', $purchaseIds)
            ->sum('quantity')
    ];

    // Top purchased products
    $topProducts = Product::select('products.*')
        ->join('purchase_details', 'products.id', '=', 'purchase_details.product_id')
        ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
        ->when($filterType === 'monthly' && $month, function($q) use ($month) {
            $date = \Carbon\Carbon::parse($month);
            $q->whereYear('purchases.purchase_date', $date->year)
              ->whereMonth('purchases.purchase_date', $date->month);
        })
        ->when($filterType === 'daily' && $dateFrom && $dateTo, function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('purchases.purchase_date', [$dateFrom, $dateTo]);
        })
        ->selectRaw('SUM(purchase_details.quantity) as total_quantity')
        ->selectRaw('SUM(purchase_details.subtotal) as total_cost')
        ->groupBy('products.id', 'products.product_name', 'products.stock', 'products.purchase_price', 'products.selling_price', 'products.category_id', 'products.barcode', 'products.created_at', 'products.updated_at')
        ->orderByDesc('total_quantity')
        ->limit(5)
        ->get();

    // Top suppliers
    $topSuppliers = DB::table('suppliers')
        ->join('purchases', 'suppliers.id', '=', 'purchases.supplier_id')
        ->when($filterType === 'monthly' && $month, function($q) use ($month) {
            $date = \Carbon\Carbon::parse($month);
            $q->whereYear('purchases.purchase_date', $date->year)
              ->whereMonth('purchases.purchase_date', $date->month);
        })
        ->when($filterType === 'daily' && $dateFrom && $dateTo, function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('purchases.purchase_date', [$dateFrom, $dateTo]);
        })
        ->select('suppliers.supplier_name', 'suppliers.phone_number')
        ->selectRaw('COUNT(purchases.id) as transaction_count')
        ->selectRaw('SUM(purchases.total_price) as total_amount')
        ->groupBy('suppliers.id', 'suppliers.supplier_name', 'suppliers.phone_number')
        ->orderByDesc('total_amount')
        ->limit(5)
        ->get();

    return view('report.purchases', compact(
        'purchases',
        'statistics',
        'topProducts',
        'topSuppliers',
        'filterType',
        'month',
        'dateFrom',
        'dateTo'
    ));
}

    public function purchasesPdf(Request $request)
{
    $filterType = $request->get('filter_type', 'all');
    $month = $request->get('month');
    $dateFrom = $request->get('date_from');
    $dateTo = $request->get('date_to');

    // Build query - EAGER LOAD ADDRESS RELATIONSHIPS
    $query = Purchase::with([
        'supplier.address.province',
        'supplier.address.city', 
        'supplier.address.district',
        'supplier.address.village',
        'user', 
        'purchaseDetails.product'
    ]);

    // Apply filters
    if ($filterType === 'monthly' && $month) {
        $date = \Carbon\Carbon::parse($month);
        $query->whereYear('purchase_date', $date->year)
              ->whereMonth('purchase_date', $date->month);
    } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
        $query->whereBetween('purchase_date', [$dateFrom, $dateTo]);
    }

    $purchases = $query->orderBy('purchase_date', 'desc')->get();

    // Calculate statistics - GUNAKAN DATA YANG SAMA
    $statisticsQuery = Purchase::query();
    
    // Apply same filters for statistics
    if ($filterType === 'monthly' && $month) {
        $date = \Carbon\Carbon::parse($month);
        $statisticsQuery->whereYear('purchase_date', $date->year)
                       ->whereMonth('purchase_date', $date->month);
    } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
        $statisticsQuery->whereBetween('purchase_date', [$dateFrom, $dateTo]);
    }

    // Get purchase IDs for filtered results
    $purchaseIds = $statisticsQuery->pluck('id');
    
    $statistics = [
        'total_transactions' => $statisticsQuery->count(),
        'total_cost' => $statisticsQuery->sum('total_price'),
        'received_purchases' => (clone $statisticsQuery)->where('status', 'received')->count(),
        'pending_purchases' => (clone $statisticsQuery)->where('status', 'pending')->count(),
        'cancelled_purchases' => (clone $statisticsQuery)->where('status', 'cancelled')->count(),
        'total_items' => DB::table('purchase_details')
            ->whereIn('purchase_id', $purchaseIds)
            ->sum('quantity')
    ];

    // Generate PDF
    $pdf = Pdf::loadView('report.purchases-pdf', compact(
        'purchases',
        'statistics',
        'filterType',
        'month',
        'dateFrom',
        'dateTo'
    ))->setPaper('a4', 'landscape');

    $filename = 'purchases-report-' . now()->format('Y-m-d-His') . '.pdf';
    return $pdf->download($filename);
}

    public function purchasesExcel(Request $request)
    {
        $filterType = $request->get('filter_type', 'all');
        $month = $request->get('month');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $filename = 'purchases-report-' . now()->format('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new PurchasesReportExport($filterType, $month, $dateFrom, $dateTo),
            $filename
        );
    }

    public function report(Request $request)
    {
        try {
            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $perPage = $request->get('per_page', 25); // Default 25 items per page

            $query = Sale::with(['customer', 'user', 'saleDetails.product', 'payments']);

            // Apply filters
            if ($filterType === 'monthly' && $month) {
                $date = \Carbon\Carbon::parse($month);
                $query->whereYear('sale_date', $date->year)
                    ->whereMonth('sale_date', $date->month);
            } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
            }

            // Get sales data with pagination
            $sales = $query->orderBy('sale_date', 'desc')->paginate($perPage);

            // Calculate statistics
            $statistics = [
                'total_transactions' => $sales->count(),
                'completed_sales' => $sales->where('status', 'completed')->count(),
                'pending_sales' => $sales->where('status', 'pending')->count(),
                'cancelled_sales' => $sales->where('status', 'cancelled')->count(),
                'total_revenue' => $sales->where('status', 'completed')->sum('total_price'),
                'total_discount' => $sales->where('status', 'completed')->sum('discount'),
                'total_tax' => $sales->where('status', 'completed')->sum('tax'),
                'net_revenue' => $sales->where('status', 'completed')->sum('total_price') -
                    $sales->where('status', 'completed')->sum('discount'),
            ];

            // Top products
            $topProducts = DB::table('sale_details')
                ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->select(
                    'products.product_name',
                    DB::raw('SUM(sale_details.quantity) as total_quantity'),
                    DB::raw('SUM(sale_details.subtotal) as total_revenue')
                )
                ->where('sales.status', 'completed');

            if ($filterType === 'monthly' && $month) {
                $date = \Carbon\Carbon::parse($month);
                $topProducts->whereYear('sales.sale_date', $date->year)
                    ->whereMonth('sales.sale_date', $date->month);
            } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
                $topProducts->whereBetween('sales.sale_date', [$dateFrom, $dateTo]);
            }

            $topProducts = $topProducts->groupBy('products.id', 'products.product_name')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->get();

            // Payment method breakdown
            $paymentMethods = DB::table('payments')
                ->join('sales', 'payments.sale_id', '=', 'sales.id')
                ->select(
                    'payments.payment_method',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(payments.amount) as total_amount')
                )
                ->where('payments.status', 'completed');

            if ($filterType === 'monthly' && $month) {
                $date = \Carbon\Carbon::parse($month);
                $paymentMethods->whereYear('sales.sale_date', $date->year)
                    ->whereMonth('sales.sale_date', $date->month);
            } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
                $paymentMethods->whereBetween('sales.sale_date', [$dateFrom, $dateTo]);
            }

            $paymentMethods = $paymentMethods->groupBy('payments.payment_method')->get();

            return view('report.sales', compact(
                'sales',
                'statistics',
                'topProducts',
                'paymentMethods',
                'filterType',
                'month',
                'dateFrom',
                'dateTo'
            ));
        } catch (\Exception $e) {
            Log::error('Failed to generate report: ' . $e->getMessage());
            return redirect()->route('sales.index')
                ->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    /**
     * Export report to PDF - FIXED VERSION
     */
    public function exportPdf(Request $request)
    {
        try {
            Log::info('Starting PDF export', $request->all());

            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $query = Sale::with(['customer', 'user', 'saleDetails.product', 'payments']);

            // Apply filters
            if ($filterType === 'monthly' && $month) {
                $date = \Carbon\Carbon::parse($month);
                $query->whereYear('sale_date', $date->year)
                    ->whereMonth('sale_date', $date->month);
            } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
            }

            $sales = $query->orderBy('sale_date', 'desc')->get();

            Log::info('Sales data fetched', ['count' => $sales->count()]);

            // Calculate statistics
            $statistics = [
                'total_transactions' => $sales->count(),
                'completed_sales' => $sales->where('status', 'completed')->count(),
                'total_revenue' => $sales->where('status', 'completed')->sum('total_price'),
                'total_discount' => $sales->where('status', 'completed')->sum('discount'),
                'net_revenue' => $sales->where('status', 'completed')->sum('total_price') -
                    $sales->where('status', 'completed')->sum('discount'),
            ];

            // Set paper configuration
            $pdf = Pdf::loadView('report.sales-pdf', compact(
                'sales',
                'statistics',
                'filterType',
                'month',
                'dateFrom',
                'dateTo'
            ))
                ->setPaper('a4', 'landscape')
                ->setOption('margin-top', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('margin-right', 10);

            $filename = 'sales-report-' . now()->format('Ymd-His') . '.pdf';

            Log::info('PDF generated successfully', ['filename' => $filename]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Export Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export report to Excel - FIXED VERSION
     */
    public function exportExcel(Request $request)
    {
        try {
            Log::info('Starting Excel export', $request->all());

            $filterType = $request->get('filter_type', 'all');
            $month = $request->get('month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $filename = 'sales-report-' . now()->format('Ymd-His') . '.xlsx';

            Log::info('Excel export initiated', ['filename' => $filename]);

            return Excel::download(
                new SalesReportExport($filterType, $month, $dateFrom, $dateTo),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Excel Export Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }
}