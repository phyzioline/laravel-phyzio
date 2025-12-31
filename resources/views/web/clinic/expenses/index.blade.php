@extends('web.layouts.dashboard_master')

@section('title', __('Daily Expenses'))
@section('header_title', __('Daily Expenses Management'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first to manage expenses.') }}
</div>
@else
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-danger text-white" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-money-bill-wave fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Total Expenses') }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_expenses'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-calendar fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('This Month') }}</h6>
                        <h3 class="mb-0 text-primary">{{ number_format($stats['this_month'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                        <small class="text-muted">{{ __('Current month expenses') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-chart-line fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Last Month') }}</h6>
                        <h3 class="mb-0 text-info">{{ number_format($stats['last_month'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                        <small class="text-muted">{{ __('Previous month') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-chart-pie fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Categories') }}</h6>
                        <h3 class="mb-0 text-success">{{ count($stats['by_category'] ?? []) }}</h3>
                        <small class="text-muted">{{ __('Active categories') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold">{{ __('Expenses List') }}</h4>
            <div>
                <a href="{{ route('clinic.expenses.analytics') }}" class="btn btn-info">
                    <i class="las la-chart-bar"></i> {{ __('Analytics') }}
                </a>
                <a href="{{ route('clinic.expenses.create') }}" class="btn btn-primary">
                    <i class="las la-plus"></i> {{ __('Add Expense') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">
            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="las la-filter"></i> {{ __('Filters') }}
            </button>
        </h5>
    </div>
    <div id="filtersCollapse" class="collapse {{ request()->hasAny(['date_from', 'date_to', 'category', 'payment_method']) ? 'show' : '' }}">
        <div class="card-body">
            <form method="GET" action="{{ route('clinic.expenses.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('Date From') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Date To') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Category') }}</label>
                    <select name="category" class="form-select">
                        <option value="">{{ __('All Categories') }}</option>
                        <option value="rent" {{ request('category') == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                        <option value="salaries" {{ request('category') == 'salaries' ? 'selected' : '' }}>{{ __('Salaries') }}</option>
                        <option value="utilities" {{ request('category') == 'utilities' ? 'selected' : '' }}>{{ __('Utilities') }}</option>
                        <option value="medical_supplies" {{ request('category') == 'medical_supplies' ? 'selected' : '' }}>{{ __('Medical Supplies') }}</option>
                        <option value="equipment_maintenance" {{ request('category') == 'equipment_maintenance' ? 'selected' : '' }}>{{ __('Equipment Maintenance') }}</option>
                        <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>{{ __('Marketing') }}</option>
                        <option value="transportation" {{ request('category') == 'transportation' ? 'selected' : '' }}>{{ __('Transportation') }}</option>
                        <option value="miscellaneous" {{ request('category') == 'miscellaneous' ? 'selected' : '' }}>{{ __('Miscellaneous') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Payment Method') }}</label>
                    <select name="payment_method" class="form-select">
                        <option value="">{{ __('All Methods') }}</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                        <option value="pos_card" {{ request('payment_method') == 'pos_card' ? 'selected' : '' }}>{{ __('POS / Card') }}</option>
                        <option value="mobile_wallet" {{ request('payment_method') == 'mobile_wallet' ? 'selected' : '' }}>{{ __('Mobile Wallet') }}</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-search"></i> {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('clinic.expenses.index') }}" class="btn btn-secondary">
                        <i class="las la-times"></i> {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Expenses Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Expense #') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Payment Method') }}</th>
                        <th>{{ __('Vendor') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td><strong>{{ $expense->expense_number }}</strong></td>
                        <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td><span class="badge bg-info">{{ $expense->category_name }}</span></td>
                        <td>{{ Str::limit($expense->description, 50) }}</td>
                        <td><strong class="text-danger">{{ number_format($expense->amount, 2) }} {{ __('EGP') }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $expense->payment_method_name }}</span></td>
                        <td>{{ $expense->vendor_supplier ?? '-' }}</td>
                        <td>{{ $expense->creator->name ?? '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clinic.expenses.show', $expense) }}" class="btn btn-info" title="{{ __('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="{{ route('clinic.expenses.edit', $expense) }}" class="btn btn-warning" title="{{ __('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <form action="{{ route('clinic.expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="{{ __('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="alert alert-info mb-0">
                                <i class="las la-info-circle"></i> {{ __('No expenses found.') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($expenses->hasPages())
        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

