@extends('web.layouts.dashboard_master')

@section('title', 'Clinic Profile')
@section('header_title', 'Profile & Settings')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="font-weight-bold" style="color: #333;">{{ __('Clinic Profile') }}</h4>
        <p class="text-muted">{{ __('Manage your clinic information and settings') }}</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Edit Company Information') }}</h5>
            </div>
            <div class="card-body px-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('clinic.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">{{ __('Company / Clinic Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">{{ __('Phone Number') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                <small class="text-muted">{{ __('Contact support to change email.') }}</small>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-size-14 mb-3 mt-4 text-uppercase">{{ __('Legal Documents') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Commercial Register') }}</label>
                                @if($user->commercial_register)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $user->commercial_register) }}" target="_blank" class="text-primary">
                                            <i class="bx bx-file"></i> {{ __('View Current Document') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="commercial_register" name="commercial_register">
                                    <label class="custom-file-label" for="commercial_register">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Tax Card') }}</label>
                                @if($user->tax_card)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $user->tax_card) }}" target="_blank" class="text-primary">
                                            <i class="bx bx-file"></i> {{ __('View Current Document') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="tax_card" name="tax_card">
                                    <label class="custom-file-label" for="tax_card">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-size-14 mb-3 mt-4 text-uppercase">{{ __('Security') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">{{ __('New Password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Leave blank to keep current') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> {{ __('Save Changes') }}
                        </button>
                        <a href="{{ route('clinic.dashboard') }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
