@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Payout Request') }} #{{ $payout->id }}</h5>
                <span class="badge bg-primary">{{ ucfirst($payout->status) }}</span>
            </div>
            <div class="card-body">
                <!-- Request Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('Request Details') }}</h6>
                        <dl class="row">
                            <dt class="col-sm-4">{{ __('Amount') }}</dt>
                            <dd class="col-sm-8 fw-bold text-success">{{ number_format($payout->amount, 2) }}</dd>

                            <dt class="col-sm-4">{{ __('Method') }}</dt>
                            <dd class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}</dd>

                            <dt class="col-sm-4">{{ __('Requested') }}</dt>
                            <dd class="col-sm-8">{{ $payout->created_at->format('M d, Y H:i') }}</dd>
                            
                            <dt class="col-sm-4">{{ __('Vendor Notes') }}</dt>
                            <dd class="col-sm-8">{{ $payout->notes ?? 'None' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('Vendor Context') }}</h6>
                        <div class="d-flex align-items-center mb-3">
                             <div class="ms-0">
                                <h6 class="mb-0">{{ $payout->vendor->name ?? 'Unknown' }}</h6>
                                <small class="text-muted">{{ $payout->vendor->email ?? '' }}</small>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0 pe-0">
                                {{ __('Available Balance') }}
                                <span class="fw-bold">{{ number_format($walletSummary['available_balance'], 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0 pe-0">
                                {{ __('Pending Balance') }}
                                <span class="text-secondary">{{ number_format($walletSummary['pending_balance'], 2) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if($payout->status === 'paid')
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                        <div>
                            <strong>{{ __('Paid on') }} {{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y') }}</strong><br>
                            {{ __('Reference:') }} {{ $payout->reference_number }}
                        </div>
                    </div>
                </div>
                @endif
                
                @if($payout->status === 'cancelled')
                <div class="alert alert-danger">
                    <strong>{{ __('Cancelled') }}</strong>: {{ $payout->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        
        <!-- Action: Approve -->
        @if($payout->status === 'pending')
        <div class="card mb-3 border-success mb-3">
             <div class="card-body">
                <h5 class="card-title text-success">{{ __('Approve Request') }}</h5>
                <p class="card-text">{{ __('Review the request details. Clicking approve will move this to "Processing" state.') }}</p>
                <div class="d-grid">
                    <a href="{{ route('dashboard.payouts.approve', $payout->id) }}" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this payout?')">
                        <i class="bi bi-check-lg"></i> {{ __('Approve Payout') }}
                    </a>
                </div>
             </div>
        </div>
        @endif

        <!-- Action: Mark as Paid -->
        @if($payout->status === 'processing')
        <div class="card mb-3 border-primary mb-3">
             <div class="card-body">
                <h5 class="card-title text-primary">{{ __('Process Payment') }}</h5>
                <p class="card-text">{{ __('After checking your bank/gateway, enter the reference number to mark as paid.') }}</p>
                <form action="{{ route('dashboard.payouts.mark-paid', $payout->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Transaction Reference / ID') }}</label>
                        <input type="text" name="reference_number" class="form-control" required placeholder="e.g. TRX-12345678">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">{{ __('Mark as Paid') }}</button>
                    </div>
                </form>
             </div>
        </div>
        @endif

        <!-- Action: Reject/Cancel -->
        @if($payout->status === 'pending' || $payout->status === 'processing')
        <div class="card mb-3 border-danger">
             <div class="card-body">
                <h5 class="card-title text-danger">{{ __('Reject / Cancel') }}</h5>
                <p class="card-text">{{ __('This will return the funds to the vendor wallet.') }}</p>
                <form action="{{ route('dashboard.payouts.cancel', $payout->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Reason') }}</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="Why is this being cancelled?"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this payout?')">
                             {{ __('Reject Request') }}
                        </button>
                    </div>
                </form>
             </div>
        </div>
        @endif

    </div>
</div>
@endsection
