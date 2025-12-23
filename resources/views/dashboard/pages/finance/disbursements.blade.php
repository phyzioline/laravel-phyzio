@extends('dashboard.pages.finance.layout')

@section('finance-content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-2">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Payout Disbursements</h6>
                @if(auth()->user()->hasRole('admin'))
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('dashboard.payments.index', ['view' => 'disbursements']) }}">All</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.payments.index', ['view' => 'disbursements', 'status' => 'pending']) }}">Pending</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.payments.index', ['view' => 'disbursements', 'status' => 'processing']) }}">Processing</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.payments.index', ['view' => 'disbursements', 'status' => 'paid']) }}">Paid</a></li>
                    </ul>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Payout ID</th>
                                @if(auth()->user()->hasRole('admin'))
                                <th>Vendor</th>
                                @endif
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Paid At</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts as $payout)
                            <tr>
                                <td>#{{ $payout->id }}</td>
                                @if(auth()->user()->hasRole('admin'))
                                <td>
                                    <div>
                                        <strong>{{ $payout->vendor->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payout->vendor->email ?? '' }}</small>
                                    </div>
                                </td>
                                @endif
                                <td class="fw-bold text-success">${{ number_format($payout->amount, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payout->payout_method ?? 'N/A')) }}</td>
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
                                    @if($payout->paid_at)
                                        {{ $payout->paid_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->reference_number)
                                        <code>{{ $payout->reference_number }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('dashboard.payouts.show', $payout->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">View Only</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? '9' : '7' }}" class="text-center py-5 text-muted">
                                    No disbursements found.
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

