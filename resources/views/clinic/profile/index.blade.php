@extends('web.layouts.dashboard_master')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

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

                @if(!isset($clinic) || !$clinic)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>{{ __('Clinic Setup Required') }}</strong><br>
                        {{ __('Please fill out the form below to set up your clinic. Once saved, you will be able to add doctors, patients, and manage appointments.') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

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
                                <label for="name">{{ __('Company / Clinic Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">{{ __('City') }}</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $clinic->city ?? '') }}" placeholder="{{ __('e.g. Cairo') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">{{ __('Address') }}</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $clinic->address ?? '') }}" placeholder="{{ __('Street, Building, Area...') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">{{ __('Country') }}</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $clinic->country ?? 'Egypt') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="{{ __('Brief description of your clinic...') }}">{{ old('description', $clinic->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-size-14 mb-3 mt-4 text-uppercase">{{ __('Legal Documents') }}</h5>
                    <div class="alert alert-warning">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>{{ __('Document Upload for Approval') }}</strong><br>
                        {{ __('You can upload documents here for quick access, but for official approval, please visit the') }} 
                        <a href="{{ route('verification.verification-center.' . app()->getLocale()) }}" class="alert-link font-weight-bold">{{ __('Verification Center') }}</a> 
                        {{ __('to upload clinic documents (Clinic License, Commercial Register, Tax Card) for admin review and approval.') }}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Commercial Register') }} <small class="text-muted">({{ __('Quick Upload') }})</small></label>
                                @if($user->commercial_register)
                                    <div class="mb-2">
                                        @php
                                            $docPath = $user->commercial_register;
                                            // Handle different path formats
                                            if (strpos($docPath, 'http') === 0) {
                                                // Already a full URL
                                                $docUrl = $docPath;
                                            } elseif (strpos($docPath, 'storage/') === 0) {
                                                // Path starts with storage/, use asset
                                                $docUrl = asset($docPath);
                                            } elseif (strpos($docPath, 'documents/') === 0 || strpos($docPath, '/') === false) {
                                                // Path is in documents folder or just filename, use Storage::url
                                                // If just filename, prepend documents/
                                                if (strpos($docPath, '/') === false) {
                                                    $docPath = 'documents/' . $docPath;
                                                }
                                                $docUrl = Storage::url($docPath);
                                            } else {
                                                // Relative path, use Storage::url
                                                $docUrl = Storage::url($docPath);
                                            }
                                        @endphp
                                        <a href="{{ $docUrl }}" target="_blank" class="text-primary">
                                            <i class="bx bx-file"></i> {{ __('View Current Document') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="commercial_register" name="commercial_register">
                                    <label class="custom-file-label" for="commercial_register">{{ __('Choose file') }}</label>
                                </div>
                                <small class="text-muted">{{ __('For official approval, upload at Verification Center') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Tax Card') }} <small class="text-muted">({{ __('Quick Upload') }})</small></label>
                                @if($user->tax_card)
                                    <div class="mb-2">
                                        @php
                                            $docPath = $user->tax_card;
                                            // Handle different path formats
                                            if (strpos($docPath, 'http') === 0) {
                                                // Already a full URL
                                                $docUrl = $docPath;
                                            } elseif (strpos($docPath, 'storage/') === 0) {
                                                // Path starts with storage/, use asset
                                                $docUrl = asset($docPath);
                                            } elseif (strpos($docPath, 'documents/') === 0 || strpos($docPath, '/') === false) {
                                                // Path is in documents folder or just filename, use Storage::url
                                                // If just filename, prepend documents/
                                                if (strpos($docPath, '/') === false) {
                                                    $docPath = 'documents/' . $docPath;
                                                }
                                                $docUrl = Storage::url($docPath);
                                            } else {
                                                // Relative path, use Storage::url
                                                $docUrl = Storage::url($docPath);
                                            }
                                        @endphp
                                        <a href="{{ $docUrl }}" target="_blank" class="text-primary">
                                            <i class="bx bx-file"></i> {{ __('View Current Document') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="tax_card" name="tax_card">
                                    <label class="custom-file-label" for="tax_card">{{ __('Choose file') }}</label>
                                </div>
                                <small class="text-muted">{{ __('For official approval, upload at Verification Center') }}</small>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-size-14 mb-3 mt-4 text-uppercase">{{ __('Bank Details') }}</h5>
                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i> 
                        <strong>{{ __('Payment Information') }}</strong><br>
                        {{ __('Add your bank details to receive payments. This information is kept secure and confidential.') }}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_name">{{ __('Bank Name') }}</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->bank_name ?? '') }}" placeholder="{{ __('e.g. CIB, NBE, QNB') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_account_name">{{ __('Account Holder Name') }}</label>
                                <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name ?? '') }}" placeholder="{{ __('Full name as in bank account') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iban">{{ __('IBAN') }}</label>
                                <input type="text" class="form-control" id="iban" name="iban" value="{{ old('iban', $user->iban ?? '') }}" placeholder="{{ __('EG123456789...') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="swift_code">{{ __('SWIFT Code') }} <small class="text-muted">({{ __('Optional') }})</small></label>
                                <input type="text" class="form-control" id="swift_code" name="swift_code" value="{{ old('swift_code', $user->swift_code ?? '') }}" placeholder="{{ __('For international transfers') }}">
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
