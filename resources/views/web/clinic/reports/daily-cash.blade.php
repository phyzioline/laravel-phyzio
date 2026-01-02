@extends('web.layouts.dashboard_master')

@section('title', __('Daily Cash Report'))
@section('header_title', __('Daily Cash Report'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Date Selector & Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="{{ route('clinic.reports.daily-cash') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Date') }}</label>
                        <input type="date" name="date" class="form-control" value="{{ $reportDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-search"></i> {{ __('Load Report') }}
                        </button>
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="las la-print"></i> {{ __('Print Report') }}
                        </button>
                        <a href="{{ route('clinic.reports.index') }}" class="btn btn-secondary">
                            <i class="las la-arrow-left"></i> {{ __('Back to Reports') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Daily Cash Report (Print-Friendly) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;" id="dailyCashReport">
            <!-- Print Header (Hidden on screen, visible when printing) -->
            <div class="d-none d-print-block text-center mb-4">
                <h2 class="mb-0">{{ $clinic->name ?? 'Clinic' }}</h2>
                <h4 class="text-muted">{{ __('Daily Cash Report') }}</h4>
                <p class="mb-0"><strong>{{ __('Date') }}:</strong> {{ $reportDate->format('F d, Y') }}</p>
                <p class="mb-0"><strong>{{ __('Generated') }}:</strong> {{ now()->format('F d, Y h:i A') }}</p>
                <hr>
            </div>

            <!-- Screen Header -->
            <div class="card-header bg-white border-0 pt-4 px-4 d-print-none">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">{{ __('Daily Cash Report') }}</h5>
                        <p class="text-muted mb-0">{{ $reportDate->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <span class="badge badge-primary badge-lg">{{ $clinic->name ?? 'Clinic' }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body px-4">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-success text-white rounded">
                            <h6>{{ __('Total Cash Collected') }}</h6>
                            <h3 class="mb-0">${{ number_format($totalCash, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-primary text-white rounded">
                            <h6>{{ __('Cash Payments') }}</h6>
                            <h3 class="mb-0">${{ number_format($cashPayments, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-danger text-white rounded">
                            <h6>{{ __('Cash Expenses') }}</h6>
                            <h3 class="mb-0">${{ number_format($cashExpenses, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 {{ $netCash >= 0 ? 'bg-info' : 'bg-warning' }} text-white rounded">
                            <h6>{{ __('Net Cash') }}</h6>
                            <h3 class="mb-0">${{ number_format($netCash, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Breakdown -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">{{ __('Payments by Method') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Payment Method') }}</th>
                                        <th class="text-right">{{ __('Count') }}</th>
                                        <th class="text-right">{{ __('Total Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($totalsByMethod as $method => $data)
                                    <tr>
                                        <td><strong>{{ ucfirst(str_replace('_', ' ', $method)) }}</strong></td>
                                        <td class="text-right">{{ $data['count'] }}</td>
                                        <td class="text-right"><strong>${{ number_format($data['total'], 2) }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('No payments recorded for this date') }}</td>
                                    </tr>
                                    @endforelse
                                    <tr class="table-primary font-weight-bold">
                                        <td><strong>{{ __('TOTAL') }}</strong></td>
                                        <td class="text-right"><strong>{{ $payments->count() }}</strong></td>
                                        <td class="text-right"><strong>${{ number_format($totalCash, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Detailed Payment List -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">{{ __('Payment Details') }}</h6>
                        @if($payments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Patient') }}</th>
                                        <th>{{ __('Payment #') }}</th>
                                        <th>{{ __('Invoice #') }}</th>
                                        <th>{{ __('Method') }}</th>
                                        <th class="text-right">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('h:i A') }}</td>
                                        <td>
                                            <strong>{{ $payment->patient->full_name ?? 'N/A' }}</strong>
                                            @if($payment->patient)
                                                <br><small class="text-muted">{{ $payment->patient->phone }}</small>
                                            @endif
                                        </td>
                                        <td><code>{{ $payment->payment_number }}</code></td>
                                        <td>
                                            @if($payment->invoice)
                                                <code>{{ $payment->invoice->invoice_number }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="text-right"><strong>${{ number_format($payment->payment_amount, 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <td colspan="5" class="text-right"><strong>{{ __('TOTAL') }}</strong></td>
                                        <td class="text-right"><strong>${{ number_format($totalCash, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="las la-info-circle"></i> {{ __('No payments recorded for') }} {{ $reportDate->format('F d, Y') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Cash Reconciliation -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0 font-weight-bold">{{ __('Cash Drawer Reconciliation') }}</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td><strong>{{ __('Cash Payments Received') }}</strong></td>
                                        <td class="text-right">${{ number_format($cashPayments, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Cash Expenses Paid') }}</strong></td>
                                        <td class="text-right">-${{ number_format($cashExpenses, 2) }}</td>
                                    </tr>
                                    <tr class="table-primary font-weight-bold">
                                        <td><strong>{{ __('Net Cash in Drawer') }}</strong></td>
                                        <td class="text-right">${{ number_format($netCash, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0 font-weight-bold">{{ __('Notes') }}</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-0">
                                    {{ __('This report shows all payments received on') }} {{ $reportDate->format('F d, Y') }}.
                                    {{ __('Cash reconciliation includes only cash payments and cash expenses.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #dailyCashReport, #dailyCashReport * {
            visibility: visible;
        }
        #dailyCashReport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .d-print-none {
            display: none !important;
        }
        .d-print-block {
            display: block !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table {
            border-collapse: collapse !important;
        }
        .table td, .table th {
            border: 1px solid #dee2e6 !important;
        }
        .btn {
            display: none !important;
        }
    }
    @media screen {
        .d-print-block {
            display: none !important;
        }
    }
</style>
@endpush
@endsection

