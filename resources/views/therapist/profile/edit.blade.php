@extends('therapist.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Edit Profile</h5>
                <form action="{{ route('therapist.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization ?? '') }}">
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Home Visit Rate (EGP)</label>
                        <input type="number" name="home_visit_rate" class="form-control" value="{{ old('home_visit_rate', $profile->home_visit_rate ?? '') }}">
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3 text-primary"><i class="las la-university"></i> {{ __('Bank Details (For Payments)') }}</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Bank Name') }}</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $user->bank_name) }}" placeholder="e.g. CIB">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Account Holder Name') }}</label>
                            <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name', $user->bank_account_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('IBAN') }}</label>
                            <input type="text" name="iban" class="form-control" value="{{ old('iban', $user->iban) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Swift Code') }}</label>
                            <input type="text" name="swift_code" class="form-control" value="{{ old('swift_code', $user->swift_code) }}">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
