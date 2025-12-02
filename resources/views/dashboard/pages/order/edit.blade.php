@extends('dashboard.layouts.app')
@section('title', __('Edit Order'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card shadow-sm p-4 border-0">
                    <h4 class="mb-4 text-primary border-bottom pb-2">{{ __('Edit Order') }}</h4>

                    <form action="{{ route('dashboard.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ $order->name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Phone') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ $order->phone }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label">{{ __('Address') }}</label>
                                <input type="text" name="address" class="form-control" value="{{ $order->address }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Status') }}</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>

                            {{-- <div class="col-md-6">
                                <label class="form-label">{{ __('Payment Status') }}</label>
                                <select name="payment_status" class="form-select">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="faild" {{ $order->payment_status == 'faild' ? 'selected' : '' }}>Failed</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div> --}}
                        </div>

                        <button type="submit" class="btn btn-success">{{ __('Update Order') }}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
