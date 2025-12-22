@extends('dashboard.layouts.app')

@section('content')
<!-- Stats Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">{{ __('Pending Requests') }}</p>
                        <h4 class="my-1 text-warning">{{ $stats['pending_count'] }}</h4>
                        <p class="mb-0 font-13">{{ number_format($stats['pending_amount'], 2) }} {{ __('Pending') }}</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                        <i class="bi bi-hourglass-split"></i>
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
                        <p class="mb-0 text-secondary">{{ __('Processing') }}</p>
                        <h4 class="my-1 text-info">{{ $stats['processing_count'] }}</h4>
                        <p class="mb-0 font-13">{{ number_format($stats['processing_amount'], 2) }} {{ __('Processing') }}</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <i class="bi bi-gear"></i>
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
                        <p class="mb-0 text-secondary">{{ __('Paid Today') }}</p>
                        <h4 class="my-1 text-success">{{ number_format($stats['paid_today'], 2) }}</h4>
                        <p class="mb-0 font-13">{{ __('Disbursed') }}</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">{{ __('Paid This Month') }}</p>
                        <h4 class="my-1 text-primary">{{ number_format($stats['paid_this_month'], 2) }}</h4>
                        <p class="mb-0 font-13">{{ __('Total') }}</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Vendor Payouts') }}</h5>
        <div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-filter"></i> {{ request('status') ? ucfirst(request('status')) : __('All Status') }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dashboard.payouts.index') }}">{{ __('All') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.payouts.index', ['status' => 'pending']) }}">{{ __('Pending') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.payouts.index', ['status' => 'processing']) }}">{{ __('Processing') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.payouts.index', ['status' => 'paid']) }}">{{ __('Paid') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Vendor') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Method') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                    <tr>
                        <td>#{{ $payout->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-info">
                                    <h6 class="mb-0">{{ $payout->vendor->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $payout->vendor->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold">{{ number_format($payout->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}</td>
                        <td>
                            @php
                                $badgeClass = match($payout->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-dark',
                                    'paid' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge rounded-pill {{ $badgeClass }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td>{{ $payout->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('dashboard.payouts.show', $payout->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> {{ __('Details') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-wallet2 display-4 text-muted"></i>
                                <p class="mt-3 text-muted">{{ __('No payout requests found.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
