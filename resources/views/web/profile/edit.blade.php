@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 text-center section_tittle section_tittle_area font-weight-bold">{{ __('My Profile') }}</h2>
            
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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

                    <form method="POST" action="{{ route('web.profile.update.' . app()->getLocale()) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="text-uppercase text-muted mb-4 border-bottom pb-2">{{ __('Personal Information') }}</h5>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">{{ __('Full Name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">{{ __('Phone Number') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">{{ __('Email cannot be changed.') }}</small>
                        </div>

                        <!-- Vendor Specific Fields -->
                        @if($user->type === 'vendor')
                             <h5 class="text-uppercase text-muted mb-4 mt-4 border-bottom pb-2">{{ __('Vendor Documents') }}</h5>
                             
                             <div class="row">
                                 <div class="col-md-4 mb-3">
                                     <div class="card h-100">
                                         <div class="card-body text-center">
                                             <h6>{{ __('Profile Image') }}</h6>
                                             @if($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}" class="img-fluid mb-2" style="max-height: 100px;">
                                             @endif
                                             <input type="file" name="image" class="form-control-file mt-2">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-4 mb-3">
                                     <div class="card h-100">
                                         <div class="card-body text-center">
                                             <h6>{{ __('ID Card') }}</h6>
                                             @if($user->card_image)
                                                <a href="{{ asset('storage/' . $user->card_image) }}" target="_blank" class="d-block mb-2 text-primary">View Current</a>
                                             @endif
                                              <input type="file" name="card_image" class="form-control-file mt-2">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-4 mb-3">
                                     <div class="card h-100">
                                         <div class="card-body text-center">
                                             <h6>{{ __('Account Statement') }}</h6>
                                             @if($user->account_statement)
                                                <a href="{{ asset('storage/' . $user->account_statement) }}" target="_blank" class="d-block mb-2 text-primary">View Current</a>
                                             @endif
                                              <input type="file" name="account_statement" class="form-control-file mt-2">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                        @endif

                        <!-- Business Documents (Vendor OR Company) -->
                         @if($user->type === 'vendor' || $user->type === 'company')
                            <h5 class="text-uppercase text-muted mb-4 mt-4 border-bottom pb-2">{{ __('Business Documents') }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                     <div class="card">
                                         <div class="card-body">
                                             <h6 class="font-weight-bold">{{ __('Commercial Register') }}</h6>
                                              @if($user->commercial_register)
                                                <a href="{{ asset('storage/' . $user->commercial_register) }}" target="_blank" class="badge badge-info mb-2 p-2">View Current Document</a>
                                             @endif
                                             <input type="file" name="commercial_register" class="form-control-file">
                                         </div>
                                     </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                     <div class="card">
                                         <div class="card-body">
                                             <h6 class="font-weight-bold">{{ __('Tax Card') }}</h6>
                                              @if($user->tax_card)
                                                <a href="{{ asset('storage/' . $user->tax_card) }}" target="_blank" class="badge badge-info mb-2 p-2">View Current Document</a>
                                             @endif
                                             <input type="file" name="tax_card" class="form-control-file">
                                         </div>
                                     </div>
                                </div>
                            </div>
                         @endif


                        <h5 class="text-uppercase text-muted mb-4 mt-4 border-bottom pb-2">{{ __('Security') }}</h5>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">{{ __('New Password') }}</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                            </div>
                             <div class="form-group col-md-6">
                                <label class="font-weight-bold">{{ __('Confirm Password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2">{{ __('Save Changes') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
