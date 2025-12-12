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
        }

        // Ecosystem Counts
        $therapist_count = \App\Models\TherapistProfile::count();
        $clinic_count = \App\Models\ClinicProfile::count();
        $appointment_count = \App\Models\Appointment::count();
        $course_count = \App\Models\Course::count();

        // Admin-only enhanced data
        $recentActivity = [];
        $pendingApprovals = [];
        $totalRevenue = 0;
        $todayAppointments = 0;
        $lowStockProducts = 0;

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
                return [
                    'type' => 'order',
                    'icon' => 'fa-receipt',
                    'color' => 'success',
                    'title' => 'New Order',
                    'description' => $order->user->name . ' - $' . number_format($order->total, 2),
                    'time' => $order->created_at,
                    'link' => route('dashboard.orders.show', $order->id)
                ];
            });
            
            // Recent appointments
            $recentAppointments = \App\Models\Appointment::with('patient', 'therapist')->latest()->take(5)->get()->map(function($appt) {
                return [
                    'type' => 'appointment',
                    'icon' => 'fa-calendar-check',
                    'color' => 'warning',
                    'title' => 'New Appointment',
                    'description' => ($appt->patient->name ?? 'N/A') . ' with ' . ($appt->therapist->name ?? 'N/A'),
                    'time' => $appt->created_at,
                    'link' => route('dashboard.appointments.show', $appt->id)
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
            $pendingClinics = \App\Models\ClinicProfile::where('status', 'pending')->count();
            $pendingCourses = \App\Models\Course::where('status', 'pending')->count();
            
            $pendingApprovals = [
                [
                    'title' => 'Therapist Profiles',
                    'count' => $pendingTherapists,
                    'link' => route('dashboard.therapist_profiles.index'),
                    'icon' => 'fa-user-md',
                    'color' => 'warning'
                ],
                [
                    'title' => 'Clinic Profiles',
                    'count' => $pendingClinics,
                    'link' => route('dashboard.clinic_profiles.index'),
                    'icon' => 'fa-clinic-medical',
                    'color' => 'info'
                ],
                [
                    'title' => 'Courses',
                    'count' => $pendingCourses,
                    'link' => route('dashboard.courses.index'),
                    'icon' => 'fa-graduation-cap',
                    'color' => 'danger'
                ]
            ];

            // Total Revenue (all sources)
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

            // Today's Appointments
            $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();

            // Low Stock Products (assuming stock field exists)
            // $lowStockProducts = Product::where('stock', '<', 10)->count();
        }

        return view('dashboard.pages.home', compact(
            'user', 'vendor', 'buyer', 'product', 'order', 'order_card','order_cash',
            'category', 'sub_category', 'tag','order__card_only','order__cash_only',
            'product_only', 'order_only',
            'therapist_count', 'clinic_count', 'appointment_count', 'course_count',
            'recentActivity', 'pendingApprovals', 'totalRevenue', 'todayAppointments', 'lowStockProducts',
            'revenue_only', 'pending_payments', 'completed_orders', 'monthly_sales_data', 'top_products', 'recent_orders'
        ));
    }
}
