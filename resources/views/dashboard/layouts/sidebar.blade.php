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
                      <div class="menu-title">Dashboard</div>
                  </a>
              </li>

              <!-- Catalog Management -->
              @can('products-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                      <div class="menu-title">Catalog</div>
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
                      <div class="menu-title">Inventory</div>
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
                      <div class="menu-title">Pricing</div>
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
                      <div class="menu-title">Orders</div>
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

              <!-- Business Reports & Analytics -->
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-graph-up-arrow"></i></div>
                      <div class="menu-title">Business Reports</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.reports.sales-dashboard') }}"><i class="bi bi-arrow-right-short"></i>Sales Dashboard</a></li>
                      <li><a href="{{ route('dashboard.reports.sales') }}"><i class="bi bi-arrow-right-short"></i>Sales Reports</a></li>
                      <li><a href="{{ route('dashboard.reports.traffic') }}"><i class="bi bi-arrow-right-short"></i>Traffic Reports</a></li>
                      <li><a href="{{ route('dashboard.reports.product-performance') }}"><i class="bi bi-arrow-right-short"></i>Product Performance</a></li>
                      <li><a href="{{ route('dashboard.reports.customers') }}"><i class="bi bi-arrow-right-short"></i>Customer Analytics</a></li>
                  </ul>
              </li>

              <!-- Customers -->
              @can('users-index')
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-people"></i></div>
                      <div class="menu-title">Customers</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.users.index') }}"><i class="bi bi-arrow-right-short"></i>Manage Users</a></li>
                      <li><a href="{{ route('dashboard.reports.customer-insights') }}"><i class="bi bi-arrow-right-short"></i>Customer Insights</a></li>
                  </ul>
              </li>
              @endcan

              <!-- Ecosystem Management -->
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="material-icons-outlined">public</i></div>
                      <div class="menu-title">Ecosystem</div>
                  </a>
                  <ul>
                      <li><a href="{{ route('dashboard.therapist_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>Therapists</a></li>
                      <li><a href="{{ route('dashboard.appointments.index') }}"><i class="bi bi-arrow-right-short"></i>Appointments</a></li>
                      <li><a href="{{ route('dashboard.clinic_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>Clinics</a></li>
                      <li><a href="{{ route('dashboard.courses.index') }}"><i class="bi bi-arrow-right-short"></i>Courses</a></li>
                      <li><a href="{{ route('dashboard.data_points.index') }}"><i class="bi bi-arrow-right-short"></i>Data Points</a></li>
                  </ul>
              </li>

              <!-- Settings -->
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="bi bi-gear"></i></div>
                      <div class="menu-title">Settings</div>
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
              <p>Developed by<a href="https://brmja.tech/" target="_blank"> brmja.tech</a></p>
          </div>

          <!--end navigation-->
      </div>

  </aside>
