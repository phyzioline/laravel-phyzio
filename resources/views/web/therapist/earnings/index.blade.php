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
             <button class="btn btn-primary shadow-sm"><i class="las la-university"></i> {{ __('Withdraw Funds') }}</button>
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
             <div class="card shadow-sm border-0 border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Pending Payout') }}</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">${{ number_format($pendingPayouts, 2) }}</div>
                             <div class="text-muted small mt-1">{{ __('Scheduled for next Friday') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-hourglass-half fa-2x text-gray-300"></i>
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
                                @forelse($transactions as $trx)
                                 <tr>
                                     <td class="pl-4">
                                         <div class="font-weight-bold text-dark">{{ $trx->patient }}</div>
                                         <div class="small text-muted">{{ $trx->date }}</div>
                                     </td>
                                     <td class="text-right pr-4">
                                          <div class="font-weight-bold {{ $trx->status == 'Completed' ? 'text-success' : 'text-warning' }}">+${{ number_format($trx->amount, 0) }}</div>
                                         <div class="small text-muted badge {{ $trx->status == 'Completed' ? 'badge-light-success' : 'badge-light-warning' }}">{{ $trx->status }}</div>
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
                    <a href="#" class="small font-weight-bold">View All Transactions</a>
                </div>
              </div>
         </div>
     </div>
</div>
@endsection
