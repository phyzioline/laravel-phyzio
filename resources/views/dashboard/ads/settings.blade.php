@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3 text-uppercase fw-bold text-secondary">Ads Settings</h4>
        
        <div class="card">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs nav-primary" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#ad-accounts" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bi bi-briefcase font-18 me-1"></i></div>
                                <div class="tab-title">Ad accounts</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#lead-syncing" role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bi bi-arrow-repeat font-18 me-1"></i></div>
                                <div class="tab-title">Lead syncing</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#pixels" role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bi bi-code-square font-18 me-1"></i></div>
                                <div class="tab-title">Pixels</div>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="ad-accounts" role="tabpanel">
                        
                        <!-- Header / Search -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Ad accounts</h5>
                            <div class="d-flex gap-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search ad accounts">
                                </div>
                                <button class="btn btn-dark px-4">Connect account</button>
                            </div>
                        </div>

                        <!-- Facebook Section -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-facebook text-primary fs-4 me-2"></i>
                                <h6 class="mb-0 fw-bold">Facebook</h6>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ad Account</th>
                                            <th>Connected By</th>
                                            <th>Connected Date</th>
                                            <th>Limit Data Use <i class="bi bi-info-circle text-secondary" title="Info"></i></th>
                                            <th>Auto Tracking</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($facebookAccounts as $account)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge rounded-pill bg-light text-dark border">{{ $account->account_id }}</span>
                                                    <div>
                                                        <div class="fw-bold">{{ $account->account_name ?? 'Phyzio Line' }}</div>
                                                        <small class="text-success"><i class="bi bi-circle-fill font-10"></i> {{ ucfirst($account->status) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $account->connected_by ?? '1 person' }}</td>
                                            <td>{{ $account->connected_at ? $account->connected_at->format('F d, Y') : 'November 7, 2025' }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" disabled>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-tracking" type="checkbox" 
                                                           data-id="{{ $account->id }}" {{ $account->auto_tracking ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Manage</a></li>
                                                        <li><a class="dropdown-item text-danger" href="#">Disconnect</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <!-- Mock Row for Visualization when empty -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge rounded-pill bg-light text-dark border">1415200879266790</span>
                                                    <div>
                                                        <div class="fw-bold">Phyzio Line</div>
                                                        <small class="text-success"><i class="bi bi-circle-fill font-10"></i> Active</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>1 person</td>
                                            <td>November 7, 2025</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" checked>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Manage</a></li>
                                                        <li><a class="dropdown-item text-danger" href="#">Disconnect</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Google Ads Section -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Google_Ads_logo.svg" width="24" class="me-2">
                                <h6 class="mb-0 fw-bold">Google Ads</h6>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ad Account</th>
                                            <th>Connected By</th>
                                            <th>Connected Date</th>
                                            <th>Auto Tracking</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($googleAccounts as $account)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $account->account_name ?? 'phyzioline Google Ads' }} ({{ $account->account_id }})</div>
                                                <small class="text-success"><i class="bi bi-circle-fill font-10"></i> {{ ucfirst($account->status) }}</small>
                                            </td>
                                            <td>{{ $account->connected_by ?? '1 person' }}</td>
                                            <td>{{ $account->connected_at ? $account->connected_at->format('F d, Y') : 'November 7, 2025' }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-tracking" type="checkbox" 
                                                           data-id="{{ $account->id }}" {{ $account->auto_tracking ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Manage</a></li>
                                                        <li><a class="dropdown-item text-danger" href="#">Disconnect</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <!-- Mock Row -->
                                        <tr>
                                            <td>
                                                <div class="fw-bold">phyzioline Google Ads (758-830-5362)</div>
                                                <small class="text-success"><i class="bi bi-circle-fill font-10"></i> Active</small>
                                            </td>
                                            <td>1 person</td>
                                            <td>November 7, 2025</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" checked>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Manage</a></li>
                                                        <li><a class="dropdown-item text-danger" href="#">Disconnect</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="lead-syncing" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="bi bi-arrow-repeat fs-1 text-secondary"></i>
                            <h5 class="mt-3">Lead Syncing</h5>
                            <p class="text-muted">Sync your leads automatically across platforms.</p>
                            <button class="btn btn-primary">Setup Sync</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pixels" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="bi bi-code-square fs-1 text-secondary"></i>
                            <h5 class="mt-3">Tracking Pixels</h5>
                            <p class="text-muted">Manage your tracking pixels for better analytics.</p>
                            <button class="btn btn-primary">Add Pixel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Auto Tracking logic (Mock)
        const toggles = document.querySelectorAll('.toggle-tracking');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                // In a real app, send AJAX request to update DB
                // Logic already exists in Controller: toggleTracking
                const id = this.getAttribute('data-id');
                if(!id) return; // Skip for mock rows

                fetch(`/dashboard/ads/account/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ auto_tracking: this.checked })
                });
            });
        });
    });
</script>
@endsection
