@extends('dashboard.layouts.app')
@section('title', __('Edit Settings'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                <form method="post" action="{{ route('dashboard.privacy_policies.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="row my-4">

                            <div class="col-md-6">
                                <label for="description_en" class="form-label">{{ __('Description (English)') }}</label>
                                <input type="text" id="description_en" class="form-control" name="description_en"
                                    value="{{ old('description_en', $privacy_policy->description_en ?? '') }}" placeholder="{{ __('Enter description in English') }}">
                                @error('description_en')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="description_ar" class="form-label">{{ __('Description (Arabic)') }}</label>
                                <input type="text" id="description_ar" class="form-control" name="description_ar"
                                    value="{{ old('description_ar', $privacy_policy->description_ar ?? '') }}" placeholder="{{ __('Enter description in Arabic') }}">
                                @error('description_ar')
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
