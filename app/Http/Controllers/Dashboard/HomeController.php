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

        return view('dashboard.pages.index', compact(
            'user', 'vendor', 'buyer', 'product', 'order', 'order_card','order_cash',
            'category', 'sub_category', 'tag','order__card_only','order__cash_only',
            'therapist_count', 'clinic_count', 'appointment_count', 'course_count',
            'recentActivity', 'pendingApprovals', 'totalRevenue', 'todayAppointments', 'lowStockProducts'
        ));
    }
}
