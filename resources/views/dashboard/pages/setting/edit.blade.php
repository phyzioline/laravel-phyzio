@extends('dashboard.layouts.app')
@section('title', __('Edit Settings'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                <form method="post" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="row my-4">

                            <div class="col-md-6">
                                <label for="description_en" class="form-label">{{ __('Description (English)') }}</label>
                                <input type="text" id="description_en" class="form-control" name="description_en"
                                    value="{{ old('description_en', $setting->description_en ?? '') }}" placeholder="{{ __('Enter description in English') }}">
                                @error('description_en')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="description_ar" class="form-label">{{ __('Description (Arabic)') }}</label>
                                <input type="text" id="description_ar" class="form-control" name="description_ar"
                                    value="{{ old('description_ar', $setting->description_ar ?? '') }}" placeholder="{{ __('Enter description in Arabic') }}">
                                @error('description_ar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="address_en" class="form-label">{{ __('Address (English)') }}</label>
                                <input type="text" id="address_en" class="form-control" name="address_en"
                                    value="{{ old('address_en', $setting->address_en ?? '') }}" placeholder="{{ __('Enter address in English') }}">
                                @error('address_en')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="address_ar" class="form-label">{{ __('Address (Arabic)') }}</label>
                                <input type="text" id="address_ar" class="form-control" name="address_ar"
                                    value="{{ old('address_ar', $setting->address_ar ?? '') }}" placeholder="{{ __('Enter address in Arabic') }}">
                                @error('address_ar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" id="email" class="form-control" name="email"
                                    value="{{ old('email', $setting->email ?? '') }}" placeholder="{{ __('Enter email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <input type="text" id="phone" class="form-control" name="phone"
                                    value="{{ old('phone', $setting->phone ?? '') }}" placeholder="{{ __('Enter phone number') }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="facebook" class="form-label">{{ __('Facebook') }}</label>
                                <input type="text" id="facebook" class="form-control" name="facebook"
                                    value="{{ old('facebook', $setting->facebook ?? '') }}" placeholder="https://facebook.com/yourpage">
                                @error('facebook')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="twitter" class="form-label">{{ __('Twitter') }}</label>
                                <input type="text" id="twitter" class="form-control" name="twitter"
                                    value="{{ old('twitter', $setting->twitter ?? '') }}" placeholder="https://twitter.com/yourpage">
                                @error('twitter')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="instagram" class="form-label">{{ __('Instagram') }}</label>
                                <input type="text" id="instagram" class="form-control" name="instagram"
                                    value="{{ old('instagram', $setting->instagram ?? '') }}" placeholder="https://instagram.com/yourpage">
                                @error('instagram')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="image" class="form-label">{{ __('Logo / Image') }}</label>
                                <input type="file" class="form-control" id="image" name="image">
                               @if(isset($setting->image))
                                    <img src="{{ asset($setting->image) }}" alt="Logo" class="mt-2" style="height: 60px;">
                                @endif
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary w-100">{{ __('Update Settings') }}</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
