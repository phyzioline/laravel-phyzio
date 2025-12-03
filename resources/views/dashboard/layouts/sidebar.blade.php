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
              <li>
                  <a href="{{ route('dashboard.home') }}">
                      <div class="parent-icon"><i class="material-icons-outlined">home</i>
                      </div>
                      <div class="menu-title">Dashboard</div>
                  </a>
              </li>
              @can('roles-index')
                  <li>
                      <a href="{{ route('dashboard.roles.index') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">person</i>
                          </div>
                          <div class="menu-title">roles</div>
                      </a>
                  </li>
              @endcan
              @can('users-index')
                  <li>
                      <a href="{{ route('dashboard.users.index') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">person</i>
                          </div>
                          <div class="menu-title">users</div>
                      </a>
                  </li>
              @endcan
              {{-- <li>
          <a href="supplier.html">
            <div class="parent-icon"><i class="fa-solid fa-user-circle"></i>
            </div>
            <div class="menu-title">Supplier</div>
          </a>
        </li> --}}
              @can('categories-index')
                  <li>
                      <a href="{{ route('dashboard.categories.index') }}">
                          <div class="parent-icon"><i class="fa-solid fa-list"></i>
                          </div>
                          <div class="menu-title">categories</div>
                      </a>
                  </li>
              @endcan
              @can('sub_categories-index')
                  <li>
                      <a href="{{ route('dashboard.sub_categories.index') }}">
                          <div class="parent-icon"><i class="fa-solid fa-list-alt"></i>
                          </div>
                          <div class="menu-title">SubCategories</div>
                      </a>
                  </li>
              @endcan
              @can('tags-index')
                  <li>
                      <a href="{{ route('dashboard.tags.index') }}">
                          <div class="parent-icon"><i class="fa-solid fa-list-alt"></i>
                          </div>
                          <div class="menu-title">Tag</div>
                      </a>
                  </li>
              @endcan
              @can('products-index')
                  <li>
                      <a href="{{ route('dashboard.products.index') }}">
                          <div class="parent-icon"><i class="bi bi-box-seam"></i>
                          </div>
                          <div class="menu-title">products</div>
                      </a>
                  </li>
              @endcan
              @can('orders-index')
                  <li>
                      <a href="{{ route('dashboard.orders.index') }}">
                          <div class="parent-icon"><i class="bi bi-cart"></i>
                          </div>
                          <div class="menu-title">orders</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('dashboard.order_cash') }}">
                          <div class="parent-icon"><i class="bi bi-cart"></i>
                          </div>
                          <div class="menu-title">Order Cash</div>
                      </a>
                  </li>
              @endcan

              {{-- Ecosystem Management --}}
              <li>
                  <a href="javascript:;" class="has-arrow">
                      <div class="parent-icon"><i class="material-icons-outlined">public</i>
                      </div>
                      <div class="menu-title">Ecosystem</div>
                  </a>
                  <ul>
                      <li> <a href="{{ route('dashboard.therapist_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>Therapists</a>
                      </li>
                      <li> <a href="{{ route('dashboard.appointments.index') }}"><i class="bi bi-arrow-right-short"></i>Appointments</a>
                      </li>
                      <li> <a href="{{ route('dashboard.clinic_profiles.index') }}"><i class="bi bi-arrow-right-short"></i>Clinics</a>
                      </li>
                      <li> <a href="{{ route('dashboard.courses.index') }}"><i class="bi bi-arrow-right-short"></i>Courses</a>
                      </li>
                      <li> <a href="{{ route('dashboard.data_points.index') }}"><i class="bi bi-arrow-right-short"></i>Data Points</a>
                      </li>
                  </ul>
              </li>

                @can('setting-update')
                    <li>
                        <a href="{{ route('dashboard.settings.show') }}">
                            <div class="parent-icon"><i class="bi bi-gear"></i>
                            </div>
                            <div class="menu-title">Settings</div>
                        </a>
                    </li>
                @endcan
              @can('shipping_policy-update')
                  <li>
                      <a href="{{ route('dashboard.shipping_policy.show') }}">
                          <div class="parent-icon"><i class="bi bi-truck"></i></div>
                          <div class="menu-title">Shipping Policy</div>
                      </a>
                  </li>
              @endcan
            
             @can('tearms_conditions-update')
                  <li>
                      <a href="{{ route('dashboard.tearms_conditions.show') }}">
                          <div class="parent-icon"><i class="bi bi-file-text"></i></div>
                          <div class="menu-title">Terms & Conditions</div>
                      </a>
                  </li>
              @endcan

              @can('privacy_policy-update')
                  <li>
                      <a href="{{ route('dashboard.privacy_policies.show') }}">
                          <div class="parent-icon"><i class="bi bi-shield-lock"></i></div>
                          <div class="menu-title">Privacy Policy</div>
                      </a>
                  </li>
              @endcan

          </ul>

          <div id="footer-section" style="padding-top: 300px;" class="text-center">
              <p class="mb-1 p-0">Copyright © 2024. All right reserved.</p>
              <p>Developed by<a href="https://brmja.tech/" target="_blank"> brmja.tech</a></p>
          </div>

          <!--end navigation-->
      </div>

  </aside>
