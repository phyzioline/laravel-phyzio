@extends('dashboard.layouts.app')
@section('title', __('User Details'))

@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <div class="p-4 rounded shadow-lg bg-white">
                        <div class="card border-0">
                            <div class="card-header bg-primary text-white rounded-top d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('User Details') }}</h5>
                                <div>
                                    @if(auth()->user()->can('users-update') || auth()->id() === $user->id)
                                        <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-light btn-sm">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Name') }}</label>
                                        <p class="form-control-plaintext">{{ $user->name }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Email') }}</label>
                                        <p class="form-control-plaintext">{{ $user->email }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                                        <p class="form-control-plaintext">{{ $user->phone ?? __('N/A') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Status') }}</label>
                                        <p class="form-control-plaintext">
                                            @if(($user->status ?? '') === 'active')
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @elseif(($user->status ?? '') === 'inactive')
                                                <span class="badge bg-danger">{{ __('UnActive') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('N/A') }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Role') }}</label>
                                        <p class="form-control-plaintext">
                                            @if($user->roles->count() > 0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-info">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                {{ __('No role assigned') }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Type') }}</label>
                                        <p class="form-control-plaintext">{{ $user->type ?? __('N/A') }}</p>
                                    </div>

                                    @if($user->image)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Image') }}</label>
                                        <div>
                                            <img src="{{ asset($user->image) }}" alt="{{ $user->name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Created At') }}</label>
                                        <p class="form-control-plaintext">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : __('N/A') }}</p>
                                    </div>

                                    <div class="col-12">
                                        <hr>
                                        <h6 class="mb-3 text-primary">{{ __('Documents') }}</h6>
                                    </div>

                                    @if($user->account_statement)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Account Statement') }}</label>
                                        <div class="mt-2">
                                            <a href="{{ asset($user->account_statement) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                            <a href="{{ asset($user->account_statement) }}" download class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->commercial_register)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Commercial Register') }}</label>
                                        <div class="mt-2">
                                            <a href="{{ asset($user->commercial_register) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                            <a href="{{ asset($user->commercial_register) }}" download class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->tax_card)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Tax Card') }}</label>
                                        <div class="mt-2">
                                            <a href="{{ asset($user->tax_card) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                            <a href="{{ asset($user->tax_card) }}" download class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->card_image)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Card Image') }}</label>
                                        <div class="mt-2">
                                            <a href="{{ asset($user->card_image) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                            <a href="{{ asset($user->card_image) }}" download class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-12">
                                        <hr>
                                        <h6 class="mb-3 text-primary">{{ __('Bank Details') }}</h6>
                                    </div>

                                    @if($user->bank_name || $user->bank_account_name || $user->iban || $user->swift_code)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Bank Name') }}</label>
                                        <p class="form-control-plaintext">{{ $user->bank_name ?? __('N/A') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Bank Account Holder Name') }}</label>
                                        <p class="form-control-plaintext">{{ $user->bank_account_name ?? __('N/A') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('IBAN') }}</label>
                                        <p class="form-control-plaintext">{{ $user->iban ?? __('N/A') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Swift Code') }}</label>
                                        <p class="form-control-plaintext">{{ $user->swift_code ?? __('N/A') }}</p>
                                    </div>
                                    @else
                                    <div class="col-12">
                                        <p class="text-muted">{{ __('No bank details available') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer text-end bg-light rounded-bottom">
                                <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary px-4">{{ __('Back to List') }}</a>
                                @if(auth()->user()->can('users-update') || auth()->id() === $user->id)
                                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary px-4">{{ __('Edit User') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

