@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Earnings & Financials') }}</h2>
            <p class="text-muted">{{ __('Track your income and payment history') }}</p>
        </div>
        <div>
             <button class="btn btn-outline-secondary mr-2 shadow-sm"><i class="las la-download"></i> {{ __('Download Report') }}</button>
             <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#payoutModal" {{ $walletSummary['available_balance'] < 100 ? 'disabled' : '' }}>
                 <i class="las la-university"></i> {{ __('Request Payout') }}
             </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-4">
             <div class="card shadow-sm border-0 border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Total Earnings') }}</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">${{ number_format($totalEarnings, 2) }}</div>
                            <div class="text-muted small mt-1">{{ __('Lifetime earnings on platform') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>

         <div class="col-md-4">
             <div class="card shadow-sm border-0 border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('This Month') }}</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">${{ number_format($monthlyEarnings, 2) }}</div>
                             <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('+12% vs last month') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>

         <div class="col-md-4">
             <div class="card shadow-sm border-0 border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Available Balance') }}</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">${{ number_format($walletSummary['available_balance'] ?? 0, 2) }}</div>
                             <div class="text-muted small mt-1">{{ __('Ready for withdrawal') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <!-- Wallet Details Row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 font-weight-bold">{{ __('Wallet Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="text-muted small mb-1">{{ __('Available Balance') }}</div>
                                <div class="h4 font-weight-bold text-success">${{ number_format($walletSummary['available_balance'] ?? 0, 2) }}</div>
                                <small class="text-muted">{{ __('Ready to withdraw') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="text-muted small mb-1">{{ __('Pending Balance') }}</div>
                                <div class="h4 font-weight-bold text-warning">${{ number_format($walletSummary['pending_balance'] ?? 0, 2) }}</div>
                                <small class="text-muted">{{ __('On hold period') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="text-muted small mb-1">{{ __('On Hold') }}</div>
                                <div class="h4 font-weight-bold text-danger">${{ number_format($walletSummary['on_hold_balance'] ?? 0, 2) }}</div>
                                <small class="text-muted">{{ __('Frozen funds') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="text-muted small mb-1">{{ __('Total Earned') }}</div>
                                <div class="h4 font-weight-bold text-primary">${{ number_format($walletSummary['total_earned'] ?? 0, 2) }}</div>
                                <small class="text-muted">{{ __('Lifetime earnings') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings by Source -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 font-weight-bold">{{ __('Earnings by Source') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Home Visits Earnings -->
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100" style="border-left: 4px solid #02767F !important;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="las la-home text-primary mr-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="mb-0 font-weight-bold">{{ __('Home Visits') }}</h6>
                                </div>
                                <div class="mb-2">
                                    <div class="text-muted small">{{ __('Total Earnings') }}</div>
                                    <div class="h5 font-weight-bold text-dark">${{ number_format($totalHomeVisitEarnings ?? 0, 2) }}</div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('This Month') }}</div>
                                        <div class="font-weight-bold text-success">${{ number_format($monthlyHomeVisitEarnings ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('Available') }}</div>
                                        <div class="font-weight-bold text-info">${{ number_format($homeVisitAvailable ?? 0, 2) }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="las la-clock"></i> {{ __('Pending') }}: ${{ number_format($homeVisitPending ?? 0, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Course Earnings -->
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100" style="border-left: 4px solid #10b8c4 !important;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="las la-book text-info mr-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="mb-0 font-weight-bold">{{ __('Courses') }}</h6>
                                </div>
                                <div class="mb-2">
                                    <div class="text-muted small">{{ __('Total Earnings') }}</div>
                                    <div class="h5 font-weight-bold text-dark">${{ number_format($totalCourseEarnings ?? 0, 2) }}</div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('This Month') }}</div>
                                        <div class="font-weight-bold text-success">${{ number_format($monthlyCourseEarnings ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('Available') }}</div>
                                        <div class="font-weight-bold text-info">${{ number_format($courseAvailable ?? 0, 2) }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="las la-clock"></i> {{ __('Pending') }}: ${{ number_format($coursePending ?? 0, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Clinic Earnings -->
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100" style="border-left: 4px solid #ff9800 !important;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="las la-hospital text-warning mr-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="mb-0 font-weight-bold">{{ __('Clinic') }}</h6>
                                </div>
                                <div class="mb-2">
                                    <div class="text-muted small">{{ __('Total Earnings') }}</div>
                                    <div class="h5 font-weight-bold text-dark">${{ number_format($totalClinicEarnings ?? 0, 2) }}</div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('This Month') }}</div>
                                        <div class="font-weight-bold text-success">${{ number_format($monthlyClinicEarnings ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">{{ __('Available') }}</div>
                                        <div class="font-weight-bold text-info">${{ number_format($clinicAvailable ?? 0, 2) }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="las la-clock"></i> {{ __('Pending') }}: ${{ number_format($clinicPending ?? 0, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Row -->
     <div class="row">
        <!-- Chart (Placeholder as implementation of charts requires JS libs) -->
        <div class="col-lg-8 mb-4">
             <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Earnings Overview') }}</h6>
                    <div class="dropdown no-arrow">
                        <select class="custom-select custom-select-sm shadow-none">
                            <option value="this_year">This See Year</option>
                            <option value="last_year">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                     <div class="chart-area" style="height: 320px; background-color: #fce4ec; display: flex; align-items: center; justify-content: center; color: #880e4f;">
                         <!-- Actual chart implementation would go here -->
                        <div class="text-center">
                            <i class="las la-chart-area" style="font-size: 48px;"></i>
                            <p>Revenue Chart Visualization</p>
                        </div>
                     </div>
                </div>
             </div>
        </div>

        <!-- Recent Transactions -->
         <div class="col-lg-4 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Recent Transactions') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                         <table class="table table-hover mb-0">
                             <tbody>
                                @forelse($recentTransactions as $trx)
                                 <tr>
                                     <td class="pl-4">
                                         <div class="font-weight-bold text-dark">{{ $trx->source }}</div>
                                         <div class="small text-muted">{{ $trx->date }}</div>
                                         <div class="small text-muted">{{ $trx->id }}</div>
                                     </td>
                                     <td class="text-right pr-4">
                                          <div class="font-weight-bold {{ $trx->status == 'Available' ? 'text-success' : ($trx->status == 'Pending' ? 'text-warning' : 'text-muted') }}">+${{ number_format($trx->amount, 2) }}</div>
                                         <div class="small text-muted badge {{ $trx->status == 'Available' ? 'badge-light-success' : ($trx->status == 'Pending' ? 'badge-light-warning' : 'badge-light-secondary') }}">{{ $trx->status }}</div>
                                     </td>
                                 </tr>
                                @empty
                                 <tr><td class="text-center py-4">{{ __('No recent transactions') }}</td></tr>
                                @endforelse
                             </tbody>
                         </table>
                    </div>
                </div>
                  <div class="card-footer bg-white text-center">
                    <a href="{{ route('therapist.earnings.index') }}#transactions" class="small font-weight-bold">{{ __('View All Transactions') }}</a>
                </div>
              </div>
         </div>
     </div>

    <!-- Payout History -->
    @if(isset($payoutHistory) && $payoutHistory->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 font-weight-bold">{{ __('Payout History') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payoutHistory as $payout)
                                <tr>
                                    <td>{{ $payout->created_at->format('M d, Y') }}</td>
                                    <td class="font-weight-bold">${{ number_format($payout->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}</td>
                                    <td>
                                        @if($payout->status == 'pending')
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                        @elseif($payout->status == 'processing')
                                            <span class="badge badge-info">{{ __('Processing') }}</span>
                                        @elseif($payout->status == 'paid')
                                            <span class="badge badge-success">{{ __('Paid') }}</span>
                                        @elseif($payout->status == 'cancelled')
                                            <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($payout->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payout->reference_number ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Payout Request Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Request Payout') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('therapist.earnings.payout.request') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <div class="form-group">
                        <label>{{ __('Available Balance') }}</label>
                        <input type="text" class="form-control" value="${{ number_format($walletSummary['available_balance'] ?? 0, 2) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="amount">{{ __('Amount') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="100" max="{{ $walletSummary['available_balance'] ?? 0 }}" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" 
                               value="{{ old('amount') }}" 
                               required>
                        <small class="form-text text-muted">{{ __('Minimum payout: $100') }}</small>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payout_method">{{ __('Payout Method') }} <span class="text-danger">*</span></label>
                        <select class="form-control @error('payout_method') is-invalid @enderror" 
                                id="payout_method" name="payout_method" required onchange="toggleBankDetails(this.value)">
                            <option value="">{{ __('Select method') }}</option>
                            <option value="bank_transfer" {{ old('payout_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                            <option value="payoneer" {{ old('payout_method') == 'payoneer' ? 'selected' : '' }}>{{ __('Payoneer') }}</option>
                            <option value="wise" {{ old('payout_method') == 'wise' ? 'selected' : '' }}>{{ __('Wise') }}</option>
                        </select>
                        @error('payout_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank Details Display (when bank_transfer is selected) -->
                    <div id="bankDetailsSection" style="display: none;">
                        @if(isset($profile) && $profile->bank_name && $profile->iban)
                            <div class="alert alert-info">
                                <h6 class="font-weight-bold mb-2">{{ __('Bank Account Details') }}</h6>
                                <div class="small">
                                    <div><strong>{{ __('Bank Name') }}:</strong> {{ $profile->bank_name }}</div>
                                    <div><strong>{{ __('Account Name') }}:</strong> {{ $profile->bank_account_name ?? '-' }}</div>
                                    <div><strong>{{ __('IBAN') }}:</strong> {{ $profile->iban }}</div>
                                    @if($profile->swift_code)
                                        <div><strong>{{ __('SWIFT Code') }}:</strong> {{ $profile->swift_code }}</div>
                                    @endif
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="las la-info-circle"></i> {{ __('These details will be used for the bank transfer. Update them in your profile if needed.') }}
                                </small>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="las la-exclamation-triangle"></i> 
                                {{ __('Please complete your bank details in your profile before requesting a bank transfer.') }}
                                <a href="{{ route('therapist.profile.edit') }}" class="alert-link">{{ __('Update Profile') }}</a>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="notes">{{ __('Notes (Optional)') }}</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="500">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Submit Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleBankDetails(method) {
    const bankSection = document.getElementById('bankDetailsSection');
    if (method === 'bank_transfer') {
        bankSection.style.display = 'block';
    } else {
        bankSection.style.display = 'none';
    }
}

// Show bank details if bank_transfer is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const payoutMethod = document.getElementById('payout_method');
    if (payoutMethod.value === 'bank_transfer') {
        toggleBankDetails('bank_transfer');
    }
});
</script>
@endsection
