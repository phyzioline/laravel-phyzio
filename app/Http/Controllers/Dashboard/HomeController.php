<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Exclude admin users from total count to match the users table
        $user         = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->count();
        $vendor       = User::where('type', 'vendor')->count();
        $buyer        = User::where('type', 'buyer')->count();
        $product      = Product::count();
        $order        = Order::count();
        $order_cash        = Order::where('payment_method', 'cash')->count();
        $order_card        = Order::where('payment_method', 'card')->count();
        $category     = Category::count();
        $sub_category = SubCategory::count();
        $tag          = Tag::count();
        $product_only = Product::where('user_id', auth()->user()->id)->count();
        $order_only   = Order::whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $order__card_only = Order::where('payment_method', 'card')->whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();

        $order__cash_only = Order::where('payment_method', 'cash')->whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();

        // Enhanced vendor metrics
        $revenue_only = 0;
        $pending_payments = 0;
        $completed_orders = 0;
        $monthly_sales_data = [];
        $top_products = [];
        $recent_orders = [];
        $lowStockProducts = 0; // Initialize with default value
        
        if (auth()->user()->hasRole('vendor')) {
            // Calculate actual revenue from vendor payments
            $revenue_only = \App\Models\VendorPayment::where('vendor_id', auth()->user()->id)
                ->where('status', 'paid')
                ->sum('vendor_earnings');
            
            // Calculate pending payments
            $pending_payments = \App\Models\VendorPayment::where('vendor_id', auth()->user()->id)
                ->where('status', 'pending')
                ->sum('vendor_earnings');
            
            // Completed orders count
            $completed_orders = Order::where('status', 'completed')
                ->whereHas('items.product', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })->count();
            
            // Monthly sales data for chart (last 6 months)
            $monthly_sales_data = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $earnings = \App\Models\VendorPayment::where('vendor_id', auth()->user()->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('vendor_earnings');
                
                $monthly_sales_data[] = [
                    'month' => $date->format('M Y'),
                    'earnings' => $earnings
                ];
            }
            
            // Top 5 products by sales
            $top_products = Product::where('user_id', auth()->user()->id)
                ->withCount(['orderItems as sales_count'])
                ->orderBy('sales_count', 'desc')
                ->take(5)
                ->get();
            
            // Recent orders (last 10)
            $recent_orders = Order::whereHas('items.product', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })
                ->with(['user', 'items' => function($query) {
                    $query->whereHas('product', function($q) {
                        $q->where('user_id', auth()->user()->id);
                    })->with('product');
                }])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Low Stock Alert (Scoped to Vendor)
            $lowStockProducts = Product::where('user_id', auth()->user()->id)
                ->where('amount', '<', 5) // Warning threshold
                ->count();
        }

        // Ecosystem Counts
        $therapist_count = \App\Models\TherapistProfile::count();
        $clinic_count = \App\Models\ClinicProfile::count();
        $appointment_count = \App\Models\HomeVisit::count();
        $course_count = \App\Models\Course::count();

        // Admin-only enhanced data
        $recentActivity = [];
        $pendingApprovals = [];
        $totalRevenue = 0;
        $todayAppointments = 0;
        
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
            // Recent Activity (last 20 items across platform)
            $recentActivity = collect();
            
            // Recent users
            $recentUsers = User::latest()->take(5)->get()->map(function($user) {
                return [
                    'type' => 'user',
                    'icon' => 'fa-user',
                    'color' => 'primary',
                    'title' => 'New User Registration',
                    'description' => $user->name . ' (' . $user->email . ')',
                    'time' => $user->created_at,
                    'link' => route('dashboard.users.show', $user->id)
                ];
            });
            
            // Recent orders
            $recentOrders = Order::with('user')->latest()->take(5)->get()->map(function($order) {
                // Handle guest orders (user can be null)
                $customerName = $order->user ? $order->user->name : ($order->name ?? 'Guest Customer');
                return [
                    'type' => 'order',
                    'icon' => 'fa-receipt',
                    'color' => 'success',
                    'title' => 'New Order',
                    'description' => $customerName . ' - $' . number_format($order->total, 2),
                    'time' => $order->created_at,
                    'link' => route('dashboard.orders.show', $order->id)
                ];
            });
            
            // Sales trend data for last 30 days
            $salesTrendData = \App\Models\Order::where('created_at', '>=', now()->subDays(30))
                ->where('payment_status', 'paid')
                ->selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [\Carbon\Carbon::parse($item->date)->format('M d') => (float)$item->total];
                })
                ->toArray();

            // Recent appointments
            $recentAppointments = \App\Models\HomeVisit::with('patient', 'therapist')->latest()->take(5)->get()->map(function($appt) {
                return [
                    'type' => 'appointment',
                    'icon' => 'fa-calendar-check',
                    'color' => 'warning',
                    'title' => 'New Home Visit',
                    'description' => ($appt->patient->name ?? 'N/A') . ' with ' . ($appt->therapist->name ?? 'N/A'),
                    'time' => $appt->created_at,
                    'link' => route('dashboard.home_visits.show', $appt->id)
                ];
            });
            
            // Recent therapist profiles
            $recentTherapists = \App\Models\TherapistProfile::with('user')->latest()->take(3)->get()->map(function($profile) {
                return [
                    'type' => 'therapist',
                    'icon' => 'fa-user-md',
                    'color' => 'info',
                    'title' => 'New Therapist Profile',
                    'description' => $profile->user->name . ' - ' . ($profile->specialization ?? 'N/A'),
                    'time' => $profile->created_at,
                    'link' => route('dashboard.therapist_profiles.show', $profile->id)
                ];
            });
            
            // Merge and sort by time
            $recentActivity = $recentUsers
                ->concat($recentOrders)
                ->concat($recentAppointments)
                ->concat($recentTherapists)
                ->sortByDesc('time')
                ->take(15);

            // Pending Approvals
            $pendingTherapists = \App\Models\TherapistProfile::where('status', 'pending')->count();
            $pendingClinics = \App\Models\Clinic::where('is_active', false)->where('is_deleted', false)->count();
            $pendingCourses = \App\Models\Course::where('status', 'review')->count();
            
            // Pending User Verifications (companies, vendors, therapists with documents)
            $pendingVerifications = User::whereIn('type', ['vendor', 'company', 'therapist'])
                ->whereIn('verification_status', ['pending', 'under_review'])
                ->count();
            
            // Pending Module Verifications
            try {
                $pendingModuleVerifications = \App\Models\TherapistModuleVerification::whereIn('status', ['pending', 'under_review'])->count();
            } catch (\Exception $e) {
                $pendingModuleVerifications = 0;
            }
            
            try {
                $pendingCompanyModuleVerifications = \App\Models\CompanyModuleVerification::whereIn('status', ['pending', 'under_review'])->count();
            } catch (\Exception $e) {
                $pendingCompanyModuleVerifications = 0;
            }
            
            $totalPendingModules = $pendingModuleVerifications + $pendingCompanyModuleVerifications;
            
            $pendingApprovals = [
                [
                    'title' => 'User Verifications',
                    'count' => $pendingVerifications,
                    'link' => route('dashboard.verifications.index', ['status' => 'pending']),
                    'icon' => 'fa-shield-check',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Module Verifications',
                    'count' => $totalPendingModules,
                    'link' => route('dashboard.verifications.index'),
                    'icon' => 'fa-puzzle-piece',
                    'color' => 'warning'
                ],
                [
                    'title' => 'Therapist Profiles',
                    'count' => $pendingTherapists,
                    'link' => route('dashboard.therapist_profiles.index'),
                    'icon' => 'fa-user-md',
                    'color' => 'info'
                ],
                [
                    'title' => 'Clinic Profiles',
                    'count' => $pendingClinics,
                    'link' => route('dashboard.clinic_profiles.index', ['status' => 'pending']),
                    'icon' => 'fa-clinic-medical',
                    'color' => 'success'
                ],
                [
                    'title' => 'Courses',
                    'count' => $pendingCourses,
                    'link' => route('dashboard.courses.index', ['status' => 'pending']),
                    'icon' => 'fa-graduation-cap',
                    'color' => 'danger'
                ]
            ];

            // Total Revenue (all sources)
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

            // Today's Appointments
            $todayAppointments = \App\Models\HomeVisit::whereDate('scheduled_at', today())->count();

            // Low Stock Products (Admin View - Global)
            $lowStockProducts = Product::where('amount', '<', 5)->count();
        }

        // Initialize salesTrendData if not set (for vendor view)
        if (!isset($salesTrendData)) {
            $salesTrendData = [];
        }

        return view('dashboard.pages.home', compact(
            'user', 'vendor', 'buyer', 'product', 'order', 'order_card','order_cash',
            'category', 'sub_category', 'tag','order__card_only','order__cash_only',
            'product_only', 'order_only',
            'therapist_count', 'clinic_count', 'appointment_count', 'course_count',
            'recentActivity', 'pendingApprovals', 'totalRevenue', 'todayAppointments', 'lowStockProducts', 'salesTrendData',
            'revenue_only', 'pending_payments', 'completed_orders', 'monthly_sales_data', 'top_products', 'recent_orders'
        ));
    }
}
