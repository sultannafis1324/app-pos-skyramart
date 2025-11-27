<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard page
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Get dashboard statistics (API endpoint)
     */
    public function getStats(Request $request)
    {
        try {
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'administrator']);

            // Determine period based on role
            if ($isAdmin) {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodLabel = 'This Month';
                $comparisonStartDate = Carbon::now()->subMonth()->startOfMonth();
                $comparisonEndDate = Carbon::now()->subMonth()->endOfMonth();
                $comparisonLabel = 'last month';
            } else {
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                $periodLabel = 'Today';
                $comparisonStartDate = Carbon::yesterday()->startOfDay();
                $comparisonEndDate = Carbon::yesterday()->endOfDay();
                $comparisonLabel = 'yesterday';
            }

            // Get current period stats
            $currentStats = $this->getPeriodStats($startDate, $endDate);
            
            // Get comparison period stats
            $comparisonStats = $this->getPeriodStats($comparisonStartDate, $comparisonEndDate);

            // Calculate growth percentages
            $revenueGrowth = $this->calculateGrowth(
                $currentStats['revenue'], 
                $comparisonStats['revenue']
            );
            
            $salesGrowth = $this->calculateGrowth(
                $currentStats['sales_count'], 
                $comparisonStats['sales_count']
            );

            // Get low stock products count
            $lowStockCount = Product::lowStock()->count();

            // Get customer stats
            $totalCustomers = Customer::count();
            $activeCustomers = Customer::where('is_active', true)->count();

            // Get recent activity (last 5 transactions)
            $recentActivity = $this->getRecentActivity(5);

            // Get top products
            $topProducts = $this->getTopProducts($startDate, $endDate, 10);

            return response()->json([
                'success' => true,
                'period' => $periodLabel,
                'is_admin' => $isAdmin,
                'stats' => [
                    'revenue' => $currentStats['revenue'],
                    'revenue_growth' => $revenueGrowth,
                    'sales_count' => $currentStats['sales_count'],
                    'sales_growth' => $salesGrowth,
                    'low_stock' => $lowStockCount,
                    'total_customers' => $totalCustomers,
                    'active_customers' => $activeCustomers,
                ],
                'comparison_label' => $comparisonLabel,
                'recent_activity' => $recentActivity,
                'top_products' => $topProducts,
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard statistics'
            ], 500);
        }
    }

    /**
     * Get sales chart data (7, 30, or 90 days)
     */
    public function getChartData(Request $request)
    {
        try {
            $days = $request->input('days', 7);
            
            // Validate days
            if (!in_array($days, [7, 30, 90])) {
                $days = 7;
            }

            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $chartData = Sale::where('status', 'completed')
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(sale_date) as date'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('DATE(sale_date)'))
                ->orderBy('date', 'asc')
                ->get();

            // Fill missing dates with zero values
            $labels = [];
            $revenues = [];
            
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                
                $labels[] = $date->format('M d');
                
                $dayData = $chartData->firstWhere('date', $dateStr);
                $revenues[] = $dayData ? (float)$dayData->revenue : 0;
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'revenues' => $revenues,
                'days' => $days
            ]);

        } catch (\Exception $e) {
            Log::error('Chart data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chart data'
            ], 500);
        }
    }

    /**
     * Get stats for a specific period
     */
    private function getPeriodStats($startDate, $endDate)
    {
        $sales = Sale::where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->get();

        return [
            'revenue' => $sales->sum('total_price'),
            'sales_count' => $sales->count(),
        ];
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get recent activity (latest transactions)
     */
    private function getRecentActivity($limit = 5)
    {
        return Sale::with(['customer', 'payments'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($sale) {
                $statusColor = match($sale->status) {
                    'completed' => 'green',
                    'pending' => 'yellow',
                    'cancelled' => 'red',
                    default => 'gray'
                };

                $customerName = $sale->customer 
                    ? $sale->customer->customer_name 
                    : 'Walk-in Customer';

                return [
                    'id' => $sale->id,
                    'transaction_number' => $sale->transaction_number,
                    'customer_name' => $customerName,
                    'total_price' => (float)$sale->total_price,
                    'status' => $sale->status,
                    'status_color' => $statusColor,
                    'time' => $sale->created_at->format('H:i'),
                    'date' => $sale->created_at->format('Y-m-d'),
                ];
            });
    }

    /**
     * Get top selling products
     */
    private function getTopProducts($startDate, $endDate, $limit = 10)
    {
        $topProducts = SaleDetail::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('SUM(sale_details.quantity) as total_quantity'),
                DB::raw('SUM(sale_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();

        return $topProducts->map(function ($product, $index) {
            return [
                'rank' => $index + 1,
                'product_name' => $product->product_name,
                'total_quantity' => (int)$product->total_quantity,
                'total_revenue' => (float)$product->total_revenue,
            ];
        });
    }

    /**
     * Get quick stats summary (for mobile/widget)
     */
    public function getQuickStats()
    {
        try {
            $todayRevenue = Sale::where('status', 'completed')
                ->whereDate('sale_date', Carbon::today())
                ->sum('total_price');

            $todaySales = Sale::where('status', 'completed')
                ->whereDate('sale_date', Carbon::today())
                ->count();

            $pendingSales = Sale::where('status', 'pending')->count();

            $lowStock = Product::lowStock()->count();

            return response()->json([
                'success' => true,
                'today_revenue' => (float)$todayRevenue,
                'today_sales' => $todaySales,
                'pending_sales' => $pendingSales,
                'low_stock' => $lowStock,
            ]);

        } catch (\Exception $e) {
            Log::error('Quick stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quick stats'
            ], 500);
        }
    }
}