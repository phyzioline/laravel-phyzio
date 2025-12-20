 <header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4">
      <div class="btn-toggle" id="toggle-button">
        <a href="javascript:;" style="color: #0F1111;"><i class="material-icons-outlined">menu</i></a>
      </div>
      <div class="flex-grow-1 search-content">
          <div class="input-group" style="max-width: 600px;">
              <input type="text" class="form-control" placeholder="Search..." style="border-radius: 4px 0 0 4px !important;">
              <button class="btn btn-warning" type="button" style="background: #febd69; border: none; color: #000; border-radius: 0 4px 4px 0 !important;">
                  <i class="material-icons-outlined" style="font-size: 20px; vertical-align: middle;">search</i>
              </button>
          </div>
      </div>
      <ul class="navbar-nav gap-1 nav-right-links align-items-center">
        {{-- Translation Button --}}
        <li class="nav-item">
            <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale() == 'en' ? 'ar' : 'en') }}" 
               class="nav-link d-flex align-items-center text-dark px-2" 
               style="font-size: 13px; font-weight: 700;">
                <i class="fa fa-globe me-1"></i> {{ LaravelLocalization::getCurrentLocale() == 'en' ? 'AR' : 'EN' }}
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
                   <!-- <a href="javascript:;" class="text-secondary small">Mark all as read</a> -->
                </div>
                <div class="notify-body">
                   @forelse(auth()->user()->unreadNotifications as $notification)
                       <a class="dropdown-item border-bottom py-2" href="{{ isset($notification->data['order_id']) ? route('dashboard.orders.show', $notification->data['order_id']) : 'javascript:;' }}">
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
