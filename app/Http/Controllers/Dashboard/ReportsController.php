<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ItemsOrder;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:reports-index');
    }
    /**
     * Sales Dashboard - Amazon-style
     */
    public function salesDashboard(Request $request)
    {
        // Date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        // KPIs
        $stats = [
            'total_order_items' => ItemsOrder::whereBetween('created_at', [$startDate, $endDate])->count(),
            'units_ordered' => ItemsOrder::whereBetween('created_at', [$startDate, $endDate])->sum('quantity'),
            'ordered_product_sales' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'avg_units_per_order' => round(ItemsOrder::whereBetween('created_at', [$startDate, $endDate])->avg('quantity'), 2),
            'avg_sales_per_order' => round(Order::whereBetween('created_at', [$startDate, $endDate])->avg('total'), 2),
        ];

        // Today vs Yesterday vs Same day last week
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::today()->subWeek();

        $compareSales = [
            'today' => [
                'orders' => Order::whereDate('created_at', $today)->count(),
                'units' => ItemsOrder::whereDate('created_at', $today)->sum('quantity'),
                'revenue' => Order::whereDate('created_at', $today)->sum('total'),
            ],
            'yesterday' => [
                'orders' => Order::whereDate('created_at', $yesterday)->count(),
                'units' => ItemsOrder::whereDate('created_at', $yesterday)->sum('quantity'),
                'revenue' => Order::whereDate('created_at', $yesterday)->sum('total'),
            ],
            'same_day_last_week' => [
                'orders' => Order::whereDate('created_at', $lastWeek)->count(),
                'units' => ItemsOrder::whereDate('created_at', $lastWeek)->sum('quantity'),
                'revenue' => Order::whereDate('created_at', $lastWeek)->sum('total'),
            ],
        ];

        // Sales trend (last 30 days)
        $salesTrend = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.pages.reports.sales-dashboard', compact('stats', 'compareSales', 'salesTrend', 'startDate', 'endDate'));
    }

    /**
     * Sales Reports
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        $orders = Order::with('items.product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        return view('dashboard.pages.reports.sales', compact('orders', 'totalRevenue', 'totalOrders', 'startDate', 'endDate'));
    }

    /**
     * Traffic Reports
     */
    public function traffic()
    {
        // Placeholder - requires analytics integration
        $pageViews = 0;
        $uniqueVisitors = 0;
        $topPages = [];

        return view('dashboard.pages.reports.traffic', compact('pageViews', 'uniqueVisitors', 'topPages'));
    }

    /**
     * Product Performance Report
     */
    public function productPerformance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        // Top selling products
        $topProducts = ItemsOrder::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with('product.productImages')
            ->limit(20)
            ->get();

        // Low stock bestsellers
        $lowStockBestsellers = ItemsOrder::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with(['product' => function($q) {
                $q->where('amount', '<=', 10);
            }])
            ->get()
            ->filter(function ($item) {
                return $item->product !== null;
            });

        return view('dashboard.pages.reports.product-performance', compact('topProducts', 'lowStockBestsellers', 'startDate', 'endDate'));
    }

    /**
     * Customer Analytics
     */
    public function customers(Request $request)
    {
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $newCustomers = User::where('role', '!=', 'admin')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Top customers by orders
        $topCustomers = User::select('users.*', DB::raw('COUNT(orders.id) as order_count'), DB::raw('SUM(orders.total) as total_spent'))
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->groupBy('users.id')
            ->orderBy('total_spent', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.pages.reports.customers', compact('totalCustomers', 'newCustomers', 'topCustomers'));
    }

    /**
     * Customer Insights
     */
    public function customerInsights()
    {
        $avgOrderValue = round(Order::avg('total'), 2);
        $repeatCustomerRate = 0; // Calculate based on customers with >1 order

        $customersWithMultipleOrders = User::select('users.id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id')
            ->havingRaw('COUNT(orders.id) > 1')
            ->count();

        $totalCustomersWithOrders = User::select('users.id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id')
            ->count();

        if ($totalCustomersWithOrders > 0) {
            $repeatCustomerRate = round(($customersWithMultipleOrders / $totalCustomersWithOrders) * 100, 2);
        }

        return view('dashboard.pages.reports.customer-insights', compact('avgOrderValue', 'repeatCustomerRate'));
    }

    /**
     * Order Reports
     */
    public function orders(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_orders' => Order::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_orders' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
        ];

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        return view('dashboard.pages.reports.orders', compact('stats', 'ordersByStatus', 'startDate', 'endDate'));
    }
}
