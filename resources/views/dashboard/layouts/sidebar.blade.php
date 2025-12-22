  <aside class="sidebar-wrapper" data-simplebar="true">
      <div class="sidebar-header">
          <!-- <div class="logo-icon">
        <img src="assets/images/logo-icon.png" class="logo-img" alt="">
      </div> -->
          <div class="logo-name flex-grow-1 text-center fw-bold">
              <img src="{{ asset('dashboard/images/Frame 131.svg') }}" width="170px" alt="">
          </div>
          <div class="sidebar-close">
              <span class="material-icons-outlined">close</span>
          </div>
      </div>
      <div class="sidebar-nav">
          <!--navigation-->
          <ul class="metismenu" id="sidenav">
              <!-- Dashboard Home -->
              <li>
                  <a href="{{ route('dashboard.home') }}">
                      <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                      <div class="menu-title">{{ __("Dashboard") }}</div>
                  </a>
              </li>

              <!-- Catalog Management -->
              @can('products-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                      <div class="menu-title">{{ __("Catalog") }}</div>
                  </a>
                  <ul>
                      @can('products-index')
                          <li><a href="{{ route('dashboard.products.index') }}"><i class="bi bi-arrow-right-short"></i>All Products</a></li>
                      @endcan
                      @can('products-create')
                          <li><a href="{{ route('dashboard.products.create') }}"><i class="bi bi-arrow-right-short"></i>Add Product</a></li>
                      @endcan
                      @can('categories-index')
                          <li><a href="{{ route('dashboard.categories.index') }}"><i class="bi bi-arrow-right-short"></i>Categories</a></li>
                      @endcan
                      @can('sub_categories-index')
                          <li><a href="{{ route('dashboard.sub_categories.index') }}"><i class="bi bi-arrow-right-short"></i>Sub Categories</a></li>
                      @endcan
                      @can('tags-index')
                          <li><a href="{{ route('dashboard.tags.index') }}"><i class="bi bi-arrow-right-short"></i>Tags</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan

              <!-- Inventory Management -->
              @can('products-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-boxes"></i></div>
                      <div class="menu-title">{{ __("Inventory") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.inventory.manage') }}"><i class="bi bi-arrow-right-short"></i>Manage Inventory</a></li>
                      <li><a href="{{ route('dashboard.inventory.stock-levels') }}"><i class="bi bi-arrow-right-short"></i>Stock Levels</a></li>
                      <li><a href="{{ route('dashboard.inventory.reports') }}"><i class="bi bi-arrow-right-short"></i>Inventory Reports</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Pricing -->
              @can('products-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-tag"></i></div>
                      <div class="menu-title">{{ __("Pricing") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.pricing.manage') }}"><i class="bi bi-arrow-right-short"></i>Manage Pricing</a></li>
                      <li><a href="{{ route('dashboard.pricing.rules') }}"><i class="bi bi-arrow-right-short"></i>Price Rules</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Orders -->
              @can('orders-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-cart-check"></i></div>
                      <div class="menu-title">{{ __("Orders") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.orders.index') }}"><i class="bi bi-arrow-right-short"></i>Manage Orders</a></li>
                      <li><a href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>Pending Orders</a></li>
                      <li><a href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}"><i class="bi bi-arrow-right-short"></i>Shipped Orders</a></li>
                      <li><a href="{{ route('dashboard.order_cash') }}"><i class="bi bi-arrow-right-short"></i>Cash Orders</a></li>
                      <li><a href="{{ route('dashboard.reports.orders') }}"><i class="bi bi-arrow-right-short"></i>Order Reports</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Financials (Vendor & Admin) -->
              @if(auth()->user()->can('financials-index') || auth()->user()->type === 'vendor')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-wallet2"></i></div>
                      <div class="menu-title">{{ __("Financials") }}</div>
                  </a>
                  <ul>
                      @can('financials-index')
                      <li><a href="{{ route('dashboard.payments.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Earnings & Payouts") }}</a></li>
                      @endcan
                      @if(auth()->user()->type === 'vendor')
                      <li><a href="{{ route('dashboard.vendor.wallet') }}"><i class="bi bi-arrow-right-short"></i>{{ __("My Wallet") }}</a></li>
                      @endif
                  </ul>
              </li>
              @endif

              <!-- Vendor Dashboard (Only for Vendors) -->
              @if(auth()->user()->type === 'vendor')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-shop"></i></div>
                      <div class="menu-title">{{ __("Vendor Hub") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.shipments.index') }}"><i class="bi bi-arrow-right-short"></i><i class="bi bi-truck me-1"></i>{{ __("My Shipments") }}</a></li>
                  </ul>
              </li>
              @endif


              <!-- Multi-Vendor Management (Admin Only) -->
              @if(auth()->user()->type !== 'vendor')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-truck"></i></div>
                      <div class="menu-title">{{ __("Multi-Vendor") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.shipments.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("All Shipments") }}</a></li>
                      <li><a href="{{ route('dashboard.payouts.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Vendor Payouts") }}</a></li>
                      <li><a href="{{ route('dashboard.shipments.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Shipments") }}</a></li>
                  </ul>
              </li>
              @endif

              <!-- Business Reports & Analytics -->
              @can('reports-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-graph-up-arrow"></i></div>
                      <div class="menu-title">{{ __("Business Reports") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.reports.sales-dashboard') }}"><i class="bi bi-arrow-right-short"></i>Sales Dashboard</a></li>
                      <li><a href="{{ route('dashboard.reports.sales') }}"><i class="bi bi-arrow-right-short"></i>Sales Reports</a></li>
                      <li><a href="{{ route('dashboard.reports.traffic') }}"><i class="bi bi-arrow-right-short"></i>Traffic Reports</a></li>
                      <li><a href="{{ route('dashboard.reports.product-performance') }}"><i class="bi bi-arrow-right-short"></i>Product Performance</a></li>
                      <li><a href="{{ route('dashboard.reports.customers') }}"><i class="bi bi-arrow-right-short"></i>Customer Analytics</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Customers -->
              @can('users-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-people"></i></div>
                      <div class="menu-title">{{ __("Customers") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.users.index') }}"><i class="bi bi-arrow-right-short"></i>Manage Users</a></li>
                      <li><a href="{{ route('dashboard.reports.customer-insights') }}"><i class="bi bi-arrow-right-short"></i>Customer Insights</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Ecosystem Management -->
              @if(auth()->user()->can('therapist_profiles-index') || auth()->user()->can('appointments-index') || auth()->user()->can('clinic_profiles-index') || auth()->user()->can('courses-index') || auth()->user()->can('jobs-index') || auth()->user()->can('data_points-index'))
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="material-icons-outlined">public</i></div>
                      <div class="menu-title">{{ __("Ecosystem") }}</div>
                  </a>
                  <ul>
                      @can('therapist_profiles-index')
                      <li><a href="{{ route('dashboard.therapist_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Therapists") }}</a></li>
                      @endcan
                      @can('appointments-index')
                      <li><a href="{{ route('dashboard.home_visits.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Home Visits") }}</a></li>
                      @endcan
                      @can('clinic_profiles-index')
                      <li><a href="{{ route('dashboard.clinic_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Clinics") }}</a></li>
                      @endcan
                      @can('courses-index')
                      <li><a href="{{ route('dashboard.courses.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Courses") }}</a></li>
                      @endcan
                      @can('jobs-index')
                      <li><a href="{{ route('dashboard.jobs.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Jobs") }}</a></li>
                      @endcan
                      @can('data_points-index')
                      <li><a href="{{ route('dashboard.data_points.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Data Points") }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endif

              <!-- Settings -->
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-gear"></i></div>
                      <div class="menu-title">{{ __("Settings") }}</div>
                  </a>
                  <ul>
                      @can('roles-index')
                          <li><a href="{{ route('dashboard.roles.index') }}"><i class="bi bi-arrow-right-short"></i>Roles & Permissions</a></li>
                      @endcan
                      @can('setting-update')
                          <li><a href="{{ route('dashboard.settings.show') }}"><i class="bi bi-arrow-right-short"></i>General Settings</a></li>
                      @endcan
                      @can('shipping_policy-update')
                          <li><a href="{{ route('dashboard.shipping_policy.show') }}"><i class="bi bi-arrow-right-short"></i>Shipping Policy</a></li>
                      @endcan
                      @can('tearms_conditions-update')
                          <li><a href="{{ route('dashboard.tearms_conditions.show') }}"><i class="bi bi-arrow-right-short"></i>Terms & Conditions</a></li>
                      @endcan
                      @can('privacy_policy-update')
                          <li><a href="{{ route('dashboard.privacy_policies.show') }}"><i class="bi bi-arrow-right-short"></i>Privacy Policy</a></li>
                      @endcan
                  </ul>
              </li>

          </ul>

          <div id="footer-section" style="padding-top: 300px;" class="text-center">
              <p class="mb-1 p-0">Copyright © 2024. All right reserved.</p>
              <p>Developed by<a href="https://phyzioline.com/" target="_blank"> phyzioline</a></p>
          </div>

          <!--end navigation-->
      </div>

      <style>
/* Sidebar – Keep colored always */
.sidebar-nav .metismenu li > a {
    background-color: #017A82 !important;
    color: #ffffff !important;
    border-radius: 6px;
    margin-bottom: 4px;
}

/* Hover + Active */
.sidebar-nav .metismenu li.mm-active > a,
.sidebar-nav .metismenu li a:hover,
.sidebar-nav .metismenu li a:focus {
    background-color: #00A6B4 !important;
    color: #ffffff !important;
}

/* Submenu */
.sidebar-nav .metismenu ul li a {
    background-color: #02676f !important;
    color: #ffffff !important;
    border-radius: 4px;
}

.sidebar-nav .metismenu ul li a:hover {
    background-color: #00b8c9 !important;
    color: #ffffff !important;
}
</style>


<style>

/* Sidebar Background */
.sidebar-wrapper {
    background: #024f55 !important;
}

/* Menu Items */
.sidebar-nav .metismenu li > a {
    display: flex !important;
    align-items: center;
    gap: 10px;
    background: transparent !important;
    color: #e6f7f8 !important;
    padding: 12px 15px;
    border-radius: 6px;
    transition: 0.25s ease-in-out;
}

/* Hover effect */
.sidebar-nav .metismenu li > a:hover {
    background: rgba(255,255,255,0.07) !important;
    transform: translateX(6px);
}

/* Active Item Highlight */
.sidebar-nav .metismenu li.mm-active > a {
    background: linear-gradient(90deg, #00b8c9 0%, #008d97 100%) !important;
    color: #fff !important;
    font-weight: 600;
    border-left: 4px solid #FFD700 !important;
    padding-left: 12px !important;
}

/* Icon Styling */
.sidebar-nav .metismenu li > a i {
    font-size: 18px;
    transition: 0.25s ease-in-out;
}

.sidebar-nav .metismenu li > a:hover i {
    transform: scale(1.15);
}

/* Submenu styling */
.sidebar-nav .metismenu ul li a {
    background: rgba(255,255,255,0.06) !important;
    margin: 3px 0;
    padding: 10px 20px;
    border-radius: 6px;
    color: #dff5f7 !important;
    font-size: 14px;
}

.sidebar-nav .metismenu ul li a:hover {
    background: rgba(255,255,255,0.15) !important;
    color: #ffffff !important;
    padding-left: 26px;
}

/* Active submenu */
.sidebar-nav .metismenu ul li.mm-active > a {
    background: #00b8c9 !important;
    color: #fff !important;
    font-weight: 600;
}



/* ==== Sidebar Full Theming Fix ==== */

/* خلفية السايدبار بالكامل */
.sidebar-wrapper,
.sidebar-wrapper .sidebar-nav,
.sidebar-wrapper .sidebar-nav ul {
    background: linear-gradient(135deg, #02767f 0%, #04b8c4 100%) !important;
}

/* لون أيقونات وعناوين القائمة */
.sidebar-wrapper .parent-icon i,
.sidebar-wrapper .menu-title {
    color: #ffffff !important;
    font-weight: 600 !important;
}

/* إزالة أي حدود بيضاء */
.sidebar-wrapper .sidebar-nav li {
    border: none !important;
}

/* الروابط الافتراضية */
.sidebar-wrapper .sidebar-nav li a {
    color: #ffffff !important;
    padding: 10px 15px !important;
    border-radius: 6px;
    transition: 0.3s ease-in-out;
}

/* Hover */
.sidebar-wrapper .sidebar-nav li a:hover {
    background: rgba(255, 255, 255, 0.18) !important;
    color: #ffffff !important;
}

/* Active (الصفحة المختارة) */
.sidebar-wrapper .sidebar-nav li.mm-active > a,
.sidebar-wrapper .sidebar-nav li.active > a {
    background: rgba(0, 0, 0, 0.25) !important;
    color: #fff !important;
    font-weight: bold !important;
    border-left: 4px solid #FFD700 !important;
}

/* Sub Menu */
.sidebar-wrapper .sidebar-nav ul ul {
    background: transparent !important;
}

.sidebar-wrapper .sidebar-nav ul ul > li > a {
    font-size: 14px !important;
    opacity: 0.9;
}

/* Sub menu hover */
.sidebar-wrapper .sidebar-nav ul ul > li > a:hover {
    background: rgba(0,0,0,0.22) !important;
    opacity: 1;
}

/* Fix icon color inside sub menu */
.sidebar-wrapper .sidebar-nav ul ul > li > a i {
    color: #fff !important;
}


</style>

  </aside>
<script>
    $(document).ready(function() {
        $('#sidenav').metisMenu();
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
