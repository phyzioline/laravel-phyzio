@extends('dashboard.layouts.app')

@section('title', __('Return Request Details'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="font-weight-bold">
                        <i class="las la-undo-alt mr-2"></i>{{ __('Return Request #') }}{{ $return->id }}
                    </h2>
                    <a href="{{ route('dashboard.returns.index') }}" class="btn btn-outline-primary">
                        <i class="las la-arrow-left mr-1"></i>{{ __('Back to Returns') }}
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Main Details -->
            <div class="col-lg-8">
                <!-- Product Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold">{{ __('Product Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($return->orderItem->product && $return->orderItem->product->productImages->first())
                                    <img src="{{ asset($return->orderItem->product->productImages->first()->image) }}" 
                                         alt="{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 150px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="mb-2">{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} ?? __('Product') }}</h5>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Order Number') }}:</strong> {{ $return->orderItem->order->order_number }}
                                </p>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Quantity') }}:</strong> {{ $return->orderItem->quantity }}
                                </p>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Original Price') }}:</strong> {{ number_format($return->orderItem->total, 2) }} {{ __('EGP') }}
                                </p>
                                <p class="text-muted mb-0">
                                    <strong>{{ __('Order Date') }}:</strong> {{ $return->orderItem->order->created_at->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold">{{ __('Return Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('Reason for Return') }}:</strong>
                            <p class="text-muted mt-2">{{ $return->reason }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Requested Date') }}:</strong>
                            <p class="text-muted mb-0">{{ $return->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        @if($return->admin_notes)
                            <div class="mb-3 p-3 bg-light rounded">
                                <strong>{{ __('Admin Notes') }}:</strong>
                                <p class="text-muted mb-0 mt-2">{{ $return->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold">{{ __('Customer Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>{{ __('Name') }}:</strong> {{ $return->orderItem->order->user->name ?? $return->orderItem->order->name ?? __('Guest') }}
                        </p>
                        <p class="mb-2">
                            <strong>{{ __('Email') }}:</strong> {{ $return->orderItem->order->user->email ?? $return->orderItem->order->email ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>{{ __('Phone') }}:</strong> {{ $return->orderItem->order->phone ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Actions -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold">{{ __('Status') }}</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statusColors = [
                                'requested' => 'warning',
                                'approved' => 'info',
                                'rejected' => 'danger',
                                'refunded' => 'success',
                                'cancelled' => 'secondary'
                            ];
                        @endphp
                        <div class="text-center mb-3">
                            <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }} badge-lg" style="font-size: 1rem; padding: 8px 16px;">
                                {{ ucfirst($return->status) }}
                            </span>
                        </div>

                        @if($return->approved_at)
                            <p class="mb-2">
                                <strong>{{ __('Processed Date') }}:</strong><br>
                                <span class="text-muted">{{ $return->approved_at->format('Y-m-d H:i') }}</span>
                            </p>
                        @endif

                        @if($return->approver)
                            <p class="mb-0">
                                <strong>{{ __('Processed By') }}:</strong><br>
                                <span class="text-muted">{{ $return->approver->name }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Refund Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold">{{ __('Refund Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('Refund Amount') }}:</strong><br>
                            <span class="h4 text-success">{{ number_format($return->refund_amount ?? 0, 2) }} {{ __('EGP') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($return->status === 'requested')
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 font-weight-bold">{{ __('Actions') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Approve Form -->
                            <form action="{{ route('dashboard.returns.approve', $return->id) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="form-group">
                                    <label for="refund_amount_approve">{{ __('Refund Amount') }} ({{ __('EGP') }})</label>
                                    <input type="number" 
                                           name="refund_amount" 
                                           id="refund_amount_approve" 
                                           class="form-control" 
                                           value="{{ $return->refund_amount ?? $return->orderItem->total }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="admin_notes_approve">{{ __('Admin Notes') }} ({{ __('Optional') }})</label>
                                    <textarea name="admin_notes" 
                                              id="admin_notes_approve" 
                                              class="form-control" 
                                              rows="3"
                                              placeholder="{{ __('Add notes about this approval...') }}"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="las la-check mr-1"></i>{{ __('Approve Return') }}
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('dashboard.returns.reject', $return->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="admin_notes_reject">{{ __('Rejection Reason') }} <span class="text-danger">*</span></label>
                                    <textarea name="admin_notes" 
                                              id="admin_notes_reject" 
                                              class="form-control @error('admin_notes') is-invalid @enderror" 
                                              rows="3"
                                              required
                                              placeholder="{{ __('Please provide a reason for rejection...') }}">{{ old('admin_notes') }}</textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger btn-block" 
                                        onclick="return confirm('{{ __('Are you sure you want to reject this return request?') }}');">
                                    <i class="las la-times mr-1"></i>{{ __('Reject Return') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($return->status === 'approved')
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 font-weight-bold">{{ __('Process Refund') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.returns.refund', $return->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="refund_method">{{ __('Refund Method') }} <span class="text-danger">*</span></label>
                                    <select name="refund_method" 
                                            id="refund_method" 
                                            class="form-control" 
                                            required>
                                        <option value="">{{ __('Select method...') }}</option>
                                        <option value="original_payment">{{ __('Original Payment Method') }}</option>
                                        <option value="wallet">{{ __('Wallet Credit') }}</option>
                                        <option value="manual">{{ __('Manual Processing') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admin_notes_refund">{{ __('Notes') }} ({{ __('Optional') }})</label>
                                    <textarea name="admin_notes" 
                                              id="admin_notes_refund" 
                                              class="form-control" 
                                              rows="3"
                                              placeholder="{{ __('Add refund processing notes...') }}"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="las la-money-bill-wave mr-1"></i>{{ __('Process Refund') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

