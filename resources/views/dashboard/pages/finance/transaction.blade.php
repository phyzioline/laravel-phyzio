
@extends('dashboard.pages.finance.layout')

@section('finance-content')

{{-- Info Alert --}}
<div class="amazon-alert amazon-alert-info">
    <i class="bi bi-info-circle-fill alert-icon"></i>
    <div>
        <strong>You may now use the 'Download' button</strong> to generate a report of the transactions as shown in the table. This report is limited to 600 transactions. For a complete list of transactions and other columns not displayed here, use the Payment reports on the <strong>All Statements</strong> or <strong>Reports Repository</strong> tabs.
    </div>
    <div class="alert-close"><i class="bi bi-x"></i></div>
</div>

{{-- Filters --}}
<div class="finance-filters">
    <form action="{{ route('dashboard.payments.index') }}" method="GET">
        <input type="hidden" name="view" value="transaction">
        
        <div class="row g-3">
            {{-- Top Row: Search --}}
            <div class="col-12 d-flex justify-content-end mb-2">
                <div class="d-flex align-items-center">
                    <span class="finance-form-label me-2 mb-0">Find a transaction</span>
                    <input type="text" name="search_order" class="finance-input" placeholder="Enter order number" value="{{ request('search_order') }}" style="width: 200px;">
                    <button type="submit" class="btn-amazon-primary ms-2">Search</button>
                </div>
            </div>

            <div class="col-12"><hr class="my-1" style="color: #D5D9D9;"></div>

            {{-- Filter Columns --}}
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <label class="finance-form-label">Account Type</label>
                    <select class="finance-select" disabled style="background-color: #f0f2f2;">
                        <option>Phyzioline Payments Inc</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="finance-form-label">Transaction type</label>
                    <select name="type" class="finance-select">
                        <option value="">All transaction types</option>
                        <option value="Order Payment" {{ request('type') == 'Order Payment' ? 'selected' : '' }}>Order Payment</option>
                        <option value="Refund" {{ request('type') == 'Refund' ? 'selected' : '' }}>Refund</option>
                        <option value="Service Fee" {{ request('type') == 'Service Fee' ? 'selected' : '' }}>Service Fee</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="finance-form-label">Transaction status</label>
                    <select name="status" class="finance-select">
                        <option value="">All transaction statuses</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Released / Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Deferred / Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-2 col-md-3">
                <label class="finance-form-label">Within</label>
                <div class="radio-group">
                    <div class="radio-item">
                        <input type="radio" id="date_custom" name="date_type" value="custom" {{ request('date_type', 'custom') == 'custom' ? 'checked' : '' }}>
                        <label for="date_custom">Customised date range</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="date_past" name="date_type" value="past" {{ request('date_type') == 'past' ? 'checked' : '' }}>
                        <label for="date_past">Past number of days</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="date_settlement" name="date_type" value="settlement" {{ request('date_type') == 'settlement' ? 'checked' : '' }}>
                        <label for="date_settlement">Settlement Period</label>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                 <div class="row align-items-end">
                     <div class="col-5">
                         <label class="finance-form-label">From</label>
                         <input type="date" name="date_from" class="finance-input" value="{{ request('date_from', now()->subDays(30)->format('Y-m-d')) }}">
                     </div>
                     <div class="col-5">
                         <label class="finance-form-label">To</label>
                         <input type="date" name="date_to" class="finance-input" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                     </div>
                     <div class="col-2">
                         <button type="submit" class="btn-amazon-primary">Update</button>
                     </div>
                 </div>
            </div>
            
        </div>
    </form>
</div>

{{-- Transactions Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Transactions</h5>
    <button class="btn-amazon-primary">Download</button>
</div>

{{-- Main Table --}}
<div class="finance-table-container">
    <table class="finance-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction status</th>
                <th>Transaction type</th>
                <th>Order ID</th>
                <th>Product Details</th>
                <th class="text-end">Total product charges</th>
                <th class="text-end">Total promotional rebates</th>
                <th class="text-end">Phyzioline fees</th>
                <th class="text-end">Other</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                <td>
                    @if($payment->status == 'paid')
                        Released
                    @elseif($payment->status == 'pending')
                        Deferred
                    @else
                        Cancelled
                    @endif
                </td>
                <td>Order Payment</td>
                <td>
                    <a href="#" class="text-decoration-none" style="color: #007185;">{{ $payment->order->reference_number ?? ('#' . $payment->order_id) }}</a>
                </td>
                <td>
                    @if($payment->orderItem && $payment->orderItem->product)
                        {{ Str::limit($payment->orderItem->product->product_name_en, 50) }}
                    @else
                        <span class="text-muted">Product information unavailable</span>
                    @endif
                </td>
                <td class="text-end">{{ number_format($payment->subtotal, 2) }}</td>
                <td class="text-end text-danger">-{{ number_format(0, 2) }}</td>
                <td class="text-end text-danger">-{{ number_format($payment->commission_amount, 2) }}</td>
                <td class="text-end">{{ number_format(0, 2) }}</td>
                <td class="text-end fw-bold {{ $payment->vendor_earnings > 0 ? 'amount-positive' : 'amount-neutral' }}">
                    {{ number_format($payment->vendor_earnings, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                    No transactions found for the selected period.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $payments->appends(request()->all())->links() }}
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.alert-close').forEach(item => {
        item.addEventListener('click', event => {
            event.target.closest('.amazon-alert').style.display = 'none';
        })
    })
</script>
@endpush
