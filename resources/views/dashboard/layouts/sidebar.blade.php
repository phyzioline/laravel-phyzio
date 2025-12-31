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
              <!-- 1. Dashboard Home -->
              <li>
                  <a href="{{ route('dashboard.home') }}">
                      <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                      <div class="menu-title">{{ __("Dashboard") }}</div>
                  </a>
              </li>

              <!-- 2. Verifications & Approvals (High Priority) -->
              @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-shield-check"></i></div>
                      <div class="menu-title">{{ __("Verifications") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.verifications.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("User Verifications") }}</a></li>
                      <li><a href="{{ route('dashboard.verifications.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Verifications") }}</a></li>
                      <li><a href="{{ route('dashboard.verifications.index', ['status' => 'under_review']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Under Review") }}</a></li>
                      <li><a href="{{ route('dashboard.clinic_profiles.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Clinics") }}</a></li>
                      <li><a href="{{ route('dashboard.courses.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Courses") }}</a></li>
                  </ul>
              </li>
              @endif

              <!-- 3. Catalog Management -->
              @can('products-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                      <div class="menu-title">{{ __("Catalog") }}</div>
                  </a>
                  <ul>
                      @can('products-index')
                          <li><a href="{{ route('dashboard.products.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("All Products") }}</a></li>
                      @endcan
                      @can('products-create')
                          <li><a href="{{ route('dashboard.products.create') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Add Product") }}</a></li>
                      @endcan
                      @can('categories-index')
                          <li><a href="{{ route('dashboard.categories.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Categories") }}</a></li>
                      @endcan
                      @can('sub_categories-index')
                          <li><a href="{{ route('dashboard.sub_categories.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Sub Categories") }}</a></li>
                      @endcan
                      @can('tags-index')
                          <li><a href="{{ route('dashboard.tags.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Tags") }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan

              <!-- 4. Orders (Core Business) -->
              @can('orders-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-cart-check"></i></div>
                      <div class="menu-title">{{ __("Orders") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.orders.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Manage Orders") }}</a></li>
                      <li><a href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Orders") }}</a></li>
                      <li><a href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Shipped Orders") }}</a></li>
                      <li><a href="{{ route('dashboard.order_cash') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Cash Orders") }}</a></li>
                      <li><a href="{{ route('dashboard.reports.orders') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Order Reports") }}</a></li>
                      @can('orders-index')
                      <li><a href="{{ route('dashboard.returns.index') }}"><i class="bi bi-arrow-right-short"></i><i class="bi bi-arrow-return-left me-1"></i>{{ __("Return Management") }}</a></li>
                      @endcan
                      @if(auth()->user()->type === 'vendor')
                      <li><a href="{{ route('dashboard.shipments.index') }}"><i class="bi bi-arrow-right-short"></i><i class="bi bi-truck me-1"></i>{{ __("My Shipments") }}</a></li>
                      @endif
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
                  <ul style="font-size: 0.9em;">
                      @can('financials-index')
                      <li><a href="{{ route('dashboard.payments.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Earnings & Payouts") }}</a></li>
                      <li><a href="{{ route('dashboard.earnings.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("All Earnings by Source") }}</a></li>
                      @endcan
                      @if(auth()->user()->type === 'vendor')
                      <li><a href="{{ route('dashboard.vendor.wallet') }}"><i class="bi bi-arrow-right-short"></i>{{ __("My Wallet") }}</a></li>
                      @endif
                      @if(auth()->user()->hasRole('admin'))
                      <li><a href="{{ route('dashboard.payouts.settings') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Payout Settings") }}</a></li>
                      @endif
                  </ul>
              </li>
              @endif

              <!-- 8. Multi-Vendor Management (Admin Only) -->
              @if(auth()->user()->type !== 'vendor')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-truck"></i></div>
                      <div class="menu-title">{{ __("Multi-Vendor") }}</div>
                  </a>
                  <ul style="font-size: 0.9em;">
                      <li><a href="{{ route('dashboard.shipments.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("All Shipments") }}</a></li>
                      <li><a href="{{ route('dashboard.shipping-management.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Shipping Management") }}</a></li>
                      <li><a href="{{ route('dashboard.payouts.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Vendor Payouts") }}</a></li>
                      <li><a href="{{ route('dashboard.shipments.index', ['status' => 'pending']) }}"><i class="bi bi-arrow-right-short"></i>{{ __("Pending Shipments") }}</a></li>
                      <li><a href="{{ route('dashboard.payouts.settings') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Payout Settings") }}</a></li>
                  </ul>
              </li>
              @endif


              <!-- 10. Business Reports & Analytics -->
              @can('reports-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-graph-up-arrow"></i></div>
                      <div class="menu-title">{{ __("Business Reports") }}</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.reports.sales-dashboard') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Sales Dashboard") }}</a></li>
                      <li><a href="{{ route('dashboard.reports.sales') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Sales Reports") }}</a></li>
                      <li><a href="{{ route('dashboard.reports.product-performance') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Product Performance") }}</a></li>
                  </ul>
              </li>
              @endcan

              <!-- 11. Ecosystem Management -->
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

              <!-- 12. AI & Modules (Admin Only) -->
              @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-cpu"></i></div>
                      <div class="menu-title">{{ __("AI & Modules") }}</div>
                  </a>
                  <ul>
                      <li>
                          <a href="javascript:;" class="has-arrow"><i class="bi bi-arrow-right-short"></i>{{ __("Ads Manager") }}</a>
                          <ul>
                              <li><a href="{{ route('dashboard.ads.index') }}"><i class="bi bi-circle"></i>{{ __("Dashboard") }}</a></li>
                              <li><a href="{{ route('dashboard.ads.settings') }}"><i class="bi bi-circle"></i>{{ __("Settings") }}</a></li>
                          </ul>
                      </li>
                      <!-- Customers moved here below Ads Manager -->
                      @can('users-index')
                      <li>
                          <a href="javascript:;" class="has-arrow"><i class="bi bi-arrow-right-short"></i>{{ __("Customers") }}</a>
                          <ul>
                              <li><a href="{{ route('dashboard.users.index') }}"><i class="bi bi-circle"></i>{{ __("Manage Users") }}</a></li>
                              <li><a href="{{ route('dashboard.reports.customer-insights') }}"><i class="bi bi-circle"></i>{{ __("Customer Insights") }}</a></li>
                              <li><a href="{{ route('dashboard.reports.customers') }}"><i class="bi bi-circle"></i>{{ __("Customer Analytics") }}</a></li>
                              <li><a href="{{ route('dashboard.reports.traffic') }}"><i class="bi bi-circle"></i>{{ __("Traffic Reports") }}</a></li>
                          </ul>
                      </li>
                      @endcan
                      <li>
                          <a href="javascript:;" class="has-arrow"><i class="bi bi-arrow-right-short"></i>{{ __("CRM") }}</a>
                          <ul>
                              <li><a href="{{ route('dashboard.crm.dashboard') }}"><i class="bi bi-circle"></i>{{ __("Dashboard") }}</a></li>
                              <li><a href="{{ route('dashboard.crm.contacts') }}"><i class="bi bi-circle"></i>{{ __("Contacts List") }}</a></li>
                          </ul>
                      </li>
                      <li><a href="{{ route('dashboard.plans.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Treatment Plans") }}</a></li>
                      <li><a href="{{ route('dashboard.exercises.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Exercise Lib") }}</a></li>
                  </ul>
              </li>
              @endif

              <!-- 13. Settings (Last) -->
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-gear"></i></div>
                      <div class="menu-title">{{ __("Settings") }}</div>
                  </a>
                  <ul>
                      @can('roles-index')
                          <li><a href="{{ route('dashboard.roles.index') }}"><i class="bi bi-arrow-right-short"></i>{{ __('Roles & Permissions') }}</a></li>
                      @endcan
                      @can('setting-update')
                          <li><a href="{{ route('dashboard.settings.show') }}"><i class="bi bi-arrow-right-short"></i>{{ __("General Settings") }}</a></li>
                      @endcan
                      @can('shipping_policy-update')
                          <li><a href="{{ route('dashboard.shipping_policy.show') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Shipping Policy") }}</a></li>
                      @endcan
                      @can('tearms_conditions-update')
                          <li><a href="{{ route('dashboard.tearms_conditions.show') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Terms & Conditions") }}</a></li>
                      @endcan
                      @can('privacy_policy-update')
                          <li><a href="{{ route('dashboard.privacy_policies.show') }}"><i class="bi bi-arrow-right-short"></i>{{ __("Privacy Policy") }}</a></li>
                      @endcan
                  </ul>
              </li>

          </ul>

          <div id="footer-section" style="padding-top: 20px; padding-bottom: 20px; margin-top: auto; border-top: 1px solid #e0e0e0;" class="text-center">
              <p class="mb-1 p-0" style="font-size: 11px; color: #6c757d;">Copyright © 2024. All right reserved.</p>
              <p style="font-size: 11px; color: #6c757d;">Developed by <a href="https://phyzioline.com/" target="_blank" style="color: #017A82;">phyzioline</a></p>
          </div>

          <!--end navigation-->
      </div>

      {{-- Sidebar styles consolidated in app.blade.php and teal-theme.css --}}

  </aside>
<script>
    $(document).ready(function() {
        $('#sidenav').metisMenu();
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
