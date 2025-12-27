 <header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-2">
      <div class="btn-toggle" style="flex-shrink: 0; margin-right: 10px;">
        <i class="material-icons-outlined" style="color: #0d9488 !important; font-size: 26px; cursor: pointer;">menu</i>
      </div>
      {{-- Search removed - placeholder functionality not implemented --}}
      <ul class="navbar-nav gap-1 nav-right-links align-items-center flex-shrink-0">
        {{-- Translation Button - Session-based for Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('dashboard.locale.switch', ['locale' => app()->getLocale() == 'en' ? 'ar' : 'en']) }}" 
               class="nav-link d-flex align-items-center text-dark px-2" 
               style="font-size: 13px; font-weight: 700;">
                <i class="fa fa-globe me-1"></i> {{ app()->getLocale() == 'en' ? 'AR' : 'EN' }}
            </a>
        </li>

        <li class="nav-item dropdown">
            <div class="notify-list">
                <a class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;" style="color: #0F1111;">
                    <i class="material-icons-outlined">notifications</i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill notify-count" style="position: absolute; top: -5px; right: -5px; padding: 2px 6px; font-size: 10px;">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <div class="notify-header d-flex align-items-center justify-content-between border-bottom px-3 py-2">
                   <h6 class="mb-0">Notifications</h6>
                   <a href="{{ route('dashboard.notifications.index') }}" class="text-primary small">View All</a>
                </div>
                <div class="notify-body">
                   @forelse(auth()->user()->unreadNotifications as $notification)
                       <a class="dropdown-item border-bottom py-2" href="{{ route('dashboard.notifications.read', $notification->id) }}">
                           <div class="d-flex align-items-center gap-2">
                               <div class="notify-icon bg-light-primary text-primary rounded-circle p-1">
                                   <i class="material-icons-outlined">shopping_bag</i>
                               </div>
                               <div class="flex-grow-1">
                                  <h6 class="mb-0 small fw-bold">Order #{{ $notification->data['order_id'] ?? 'N/A' }}</h6>
                                  <p class="mb-0 small text-secondary">New order from {{ $notification->data['customer_name'] ?? 'Guest' }}</p>
                                  <p class="mb-0 small text-muted" style="font-size: 10px;">{{ $notification->created_at->diffForHumans() }}</p>
                               </div>
                           </div>
                       </a>
                   @empty
                       <div class="text-center p-3 text-secondary">
                           <i class="material-icons-outlined fs-3">notifications_off</i>
                           <p class="mb-0">No new notifications</p>
                       </div>
                   @endforelse
                </div>
              </div>
            </div>
        </li>
        <li class="nav-item dropdown">
          <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
            <img src="{{ asset('dashboard/images/Frame 127.svg') }}" class="rounded-circle p-1 border" width="45" height="45" alt="">
          </a>
          <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
            <a class="dropdown-item  gap-2 py-2" href="javascript:;">
              <div class="text-center">
                <img src="{{ asset('dashboard/images/Frame 127.svg') }}" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                <h5 class="user-name mb-0 fw-bold">PHYZIOLINE</h5>
              </div>
            </a>

            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('dashboard.users.edit', auth()->id()) }}"><i
                class="material-icons-outlined">person</i>Profile</a>
            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}"><i
                class="material-icons-outlined">power_settings_new</i>Logout</a>
          </div>
        </li>
      </ul>

    </nav>
  </header>
