@extends('dashboard.layouts.app')

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payout Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('message'))
        <div class="alert alert-{{ session('message')['type'] === 'error' ? 'danger' : 'success' }} alert-dismissible fade show">
            {{ session('message')['text'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            <!-- Settings Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Auto-Payout Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.payouts.settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Hold Period (Days)</label>
                                <input type="number" name="hold_period_days" class="form-control" 
                                       value="{{ $settings->hold_period_days }}" min="1" max="30" required>
                                <small class="text-muted">Number of days funds remain in pending before becoming available</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Minimum Payout Amount</label>
                                <input type="number" name="minimum_payout" class="form-control" 
                                       value="{{ $settings->minimum_payout }}" step="0.01" min="1" required>
                                <small class="text-muted">Minimum amount required to create a payout request</small>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_payout_enabled" 
                                           id="auto_payout_enabled" {{ $settings->auto_payout_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="auto_payout_enabled">
                                        Enable Auto-Payout
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, system automatically creates payout requests for eligible vendors</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Auto-Payout Frequency</label>
                                <select name="auto_payout_frequency" class="form-select" required>
                                    <option value="weekly" {{ $settings->auto_payout_frequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="biweekly" {{ $settings->auto_payout_frequency === 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
                                    <option value="monthly" {{ $settings->auto_payout_frequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                                <small class="text-muted">How often auto-payouts are processed</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats & Actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-2 mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Pending Payouts</small>
                            <h5 class="mb-0">{{ $stats['pending_count'] }}</h5>
                            <small class="text-success">${{ number_format($stats['pending_amount'], 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Processing</small>
                            <h5 class="mb-0">{{ $stats['processing_count'] }}</h5>
                            <small class="text-info">${{ number_format($stats['processing_amount'], 2) }}</small>
                        </div>
                        <div>
                            <small class="text-muted">Paid This Month</small>
                            <h5 class="mb-0 text-success">${{ number_format($stats['paid_this_month'], 2) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.payouts.settings.trigger') }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to trigger auto-payout process now?')">
                                <i class="bi bi-play-circle"></i> Trigger Auto-Payout Now
                            </button>
                        </form>
                        <a href="{{ route('dashboard.payouts.index') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-list"></i> View All Payouts
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Auto-Payouts -->
        <div class="card border-0 shadow-sm rounded-2 mt-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Recent Auto-Payouts</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Payout ID</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAutoPayouts as $payout)
                            <tr>
                                <td>#{{ $payout->id }}</td>
                                <td>{{ $payout->vendor->name ?? 'N/A' }}</td>
                                <td class="fw-bold">${{ number_format($payout->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payout->status === 'pending' ? 'warning' : ($payout->status === 'paid' ? 'success' : 'info') }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No auto-payouts created yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

