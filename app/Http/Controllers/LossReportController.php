<?php

namespace App\Http\Controllers;

use App\Models\LossReport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LossReportExport;
use Carbon\Carbon;

class LossReportController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'all');
        $month = $request->get('month', date('Y-m'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 25);

        // Build query
        $query = LossReport::with(['product.category', 'batch', 'recordedBy']);

        // Apply filters
        if ($filterType === 'monthly' && $month) {
            $date = Carbon::parse($month);
            $query->whereYear('expiry_date', $date->year)
                  ->whereMonth('expiry_date', $date->month);
        } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
            $query->whereBetween('expiry_date', [$dateFrom, $dateTo]);
        }

        $losses = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Calculate statistics
        $statisticsQuery = LossReport::query();
        
        if ($filterType === 'monthly' && $month) {
            $date = Carbon::parse($month);
            $statisticsQuery->whereYear('expiry_date', $date->year)
                           ->whereMonth('expiry_date', $date->month);
        } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
            $statisticsQuery->whereBetween('expiry_date', [$dateFrom, $dateTo]);
        }

        $statistics = [
            'total_expired_batches' => $statisticsQuery->count(),
            'total_quantity_lost' => $statisticsQuery->sum('quantity_expired'),
            'total_loss_value' => $statisticsQuery->sum('total_loss'),
            'average_loss_per_batch' => $statisticsQuery->count() > 0 
                ? $statisticsQuery->sum('total_loss') / $statisticsQuery->count() 
                : 0,
        ];

        // Top loss products
        $topLossProducts = LossReport::select(
                'product_id',
                DB::raw('SUM(quantity_expired) as total_quantity_lost'),
                DB::raw('SUM(total_loss) as total_loss_amount')
            )
            ->when($filterType === 'monthly' && $month, function($q) use ($month) {
                $date = Carbon::parse($month);
                $q->whereYear('expiry_date', $date->year)
                  ->whereMonth('expiry_date', $date->month);
            })
            ->when($filterType === 'daily' && $dateFrom && $dateTo, function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('expiry_date', [$dateFrom, $dateTo]);
            })
            ->with('product.category')
            ->groupBy('product_id')
            ->orderByDesc('total_loss_amount')
            ->limit(10)
            ->get();

        return view('report.loss', compact(
            'losses',
            'statistics',
            'topLossProducts',
            'filterType',
            'month',
            'dateFrom',
            'dateTo'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filterType = $request->get('filter_type', 'all');
        $month = $request->get('month');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = LossReport::with(['product.category', 'batch', 'recordedBy']);

        if ($filterType === 'monthly' && $month) {
            $date = Carbon::parse($month);
            $query->whereYear('expiry_date', $date->year)
                  ->whereMonth('expiry_date', $date->month);
        } elseif ($filterType === 'daily' && $dateFrom && $dateTo) {
            $query->whereBetween('expiry_date', [$dateFrom, $dateTo]);
        }

        $losses = $query->orderBy('created_at', 'desc')->get();

        $statistics = [
    'total_expired_batches' => $losses->count(),
    'total_quantity_lost' => $losses->sum('quantity_expired'),
    'total_loss_value' => $losses->sum('total_loss'),
    'average_loss_per_batch' => $losses->count() > 0
        ? $losses->sum('total_loss') / $losses->count()
        : 0,
];


        $pdf = Pdf::loadView('report.loss-pdf', compact(
            'losses',
            'statistics',
            'filterType',
            'month',
            'dateFrom',
            'dateTo'
        ))->setPaper('a4', 'landscape');

        $filename = 'loss-report-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $filterType = $request->get('filter_type', 'all');
        $month = $request->get('month');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $filename = 'loss-report-' . now()->format('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new LossReportExport($filterType, $month, $dateFrom, $dateTo),
            $filename
        );
    }
}