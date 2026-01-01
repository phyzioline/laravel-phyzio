@extends('dashboard.layouts.app')

@section('title', __('Earnings Management'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('Financials') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Earnings by Source') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Total Earnings') }}</p>
                                <h4 class="my-1 text-primary">${{ number_format($stats['total_earnings'], 2) }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-light-primary text-primary ms-auto">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Platform Fees') }}</p>
                                <h4 class="my-1 text-info">${{ number_format($stats['total_platform_fees'], 2) }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-light-info text-info ms-auto">
                                <i class="bi bi-percent"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Available') }}</p>
                                <h4 class="my-1 text-success">${{ number_format($stats['available_earnings'], 2) }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-light-success text-success ms-auto">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Pending') }}</p>
                                <h4 class="my-1 text-warning">${{ number_format($stats['pending_earnings'], 2) }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-light-warning text-warning ms-auto">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Cycle Management Section -->
        @if(isset($settlementStats))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">{{ __('Payment Cycle Management') }}</h5>
                        <form action="{{ route('dashboard.earnings.process-settlements') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-repeat"></i> {{ __('Process Settlements Now') }}
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-muted small mb-1">{{ __('Ready to Settle Today') }}</h6>
                                    <h4 class="text-primary mb-0">{{ $settlementStats['ready_today'] }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-muted small mb-1">{{ __('Settling This Week') }}</h6>
                                    <h4 class="text-info mb-0">{{ $settlementStats['settling_this_week'] }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-muted small mb-1">{{ __('Total Pending') }}</h6>
                                    <h4 class="text-warning mb-0">${{ number_format($settlementStats['total_pending'], 2) }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-muted small mb-1">{{ __('Next Settlement') }}</h6>
                                    <h6 class="text-success mb-0">
                                        @if($settlementStats['next_settlement_date'])
                                            {{ \Carbon\Carbon::parse($settlementStats['next_settlement_date'])->format('M d, Y') }}
                                        @else
                                            {{ __('No pending settlements') }}
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                {{ __('Settlements are processed automatically daily at 1:00 AM UTC. You can also process them manually using the button above.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Earnings by Source -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Home Visits') }}</h6>
                        <h3 class="text-primary">${{ number_format($stats['home_visit_earnings'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Courses') }}</h6>
                        <h3 class="text-info">${{ number_format($stats['course_earnings'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Clinic') }}</h6>
                        <h3 class="text-warning">${{ number_format($stats['clinic_earnings'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.earnings.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Source') }}</label>
                        <select name="source" class="form-select">
                            <option value="">{{ __('All Sources') }}</option>
                            <option value="home_visit" {{ request('source') == 'home_visit' ? 'selected' : '' }}>{{ __('Home Visits') }}</option>
                            <option value="course" {{ request('source') == 'course' ? 'selected' : '' }}>{{ __('Courses') }}</option>
                            <option value="clinic" {{ request('source') == 'clinic' ? 'selected' : '' }}>{{ __('Clinic') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                            <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>{{ __('On Hold') }}</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('User') }}</label>
                        <select name="user_id" class="form-select">
                            <option value="">{{ __('All Users') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Source') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Platform Fee') }}</th>
                                <th>{{ __('Net Earnings') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>#{{ $transaction->id }}</td>
                                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->source == 'home_visit' ? 'primary' : ($transaction->source == 'course' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->source)) }}
                                    </span>
                                </td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>${{ number_format($transaction->platform_fee, 2) }}</td>
                                <td class="font-weight-bold">${{ number_format($transaction->net_earnings, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status == 'available' ? 'success' : ($transaction->status == 'pending' ? 'warning' : ($transaction->status == 'paid' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $transaction->created_at->format('M d, Y') }}
                                    @if($transaction->hold_until)
                                        <br><small class="text-muted">{{ __('Hold until') }}: {{ $transaction->hold_until->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.earnings.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($transaction->status === 'pending')
                                            <form action="{{ route('dashboard.earnings.manual-settle', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to manually settle this transaction?') }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('Settle Now') }}">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#holdModal{{ $transaction->id }}" title="{{ __('Put on Hold') }}">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status === 'on_hold')
                                            <form action="{{ route('dashboard.earnings.release-from-hold', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to release this transaction from hold?') }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="{{ __('Release from Hold') }}">
                                                    <i class="bi bi-play-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <!-- Hold Modal -->
                                    @if($transaction->status === 'pending')
                                    <div class="modal fade" id="holdModal{{ $transaction->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('dashboard.earnings.put-on-hold', $transaction) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Put Transaction on Hold') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('Reason') }} <span class="text-danger">*</span></label>
                                                            <textarea name="reason" class="form-control" rows="3" required placeholder="{{ __('Enter reason for putting this transaction on hold...') }}"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="submit" class="btn btn-warning">{{ __('Put on Hold') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __('No transactions found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

