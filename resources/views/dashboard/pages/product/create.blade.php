@extends('dashboard.layouts.app')
@section('title', __('Add Product'))

@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <form method="post" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data"
                        class="p-4 border rounded shadow-sm bg-white">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Categories') }}</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->{'name_' . app()->getLocale()} }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Sub Categories') }}</label>
                                <select name="sub_category_id" class="form-select" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($sub_categories as $category)
                                        <option value="{{ $category->id }}" {{ old('sub_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->{'name_' . app()->getLocale()} }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Product Name (English)') }}</label>
                                <input type="text" class="form-control" name="product_name_en"
                                    placeholder="{{ __('Enter Name English') }}" value="{{ old('product_name_en') }}">
                                @error('product_name_en')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Product Name (Arabic)') }}</label>
                                <input type="text" class="form-control" name="product_name_ar"
                                    placeholder="{{ __('Enter Name Arabic') }}" value="{{ old('product_name_ar') }}">
                                @error('product_name_ar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Price') }}</label>
                                <input type="number" class="form-control" name="product_price"
                                    placeholder="{{ __('Enter Price') }}" value="{{ old('product_price') }}">
                                @error('product_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Short Description (EN)') }}</label>
                                <input type="text" class="form-control" name="short_description_en"
                                    placeholder="{{ __('Enter Short Description English') }}" value="{{ old('short_description_en') }}">
                                @error('short_description_en')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Short Description (AR)') }}</label>
                                <input type="text" class="form-control" name="short_description_ar"
                                    placeholder="{{ __('Enter Short Description Arabic') }}" value="{{ old('short_description_ar') }}">
                                @error('short_description_ar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Long Description (EN)') }}</label>
                                <input type="text" class="form-control" name="long_description_en"
                                    placeholder="{{ __('Enter Long Description English') }}" value="{{ old('long_description_en') }}">
                                @error('long_description_en')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Long Description (AR)') }}</label>
                                <input type="text" class="form-control" name="long_description_ar"
                                    placeholder="{{ __('Enter Long Description Arabic') }}" value="{{ old('long_description_ar') }}">
                                @error('long_description_ar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Images') }}</label>
                                <input type="file" class="form-control" name="images[]" multiple>
                                @error('images')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Amount') }}</label>
                                <input type="number" class="form-control" name="amount"
                                    placeholder="{{ __('Enter Amount') }}" value="{{ old('amount') }}">
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Tags') }}</label>
                                <select name="tags[]" multiple class="form-select" required>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ collect(old('tags'))->contains($tag->id) ? 'selected' : '' }}>
                                            {{ $tag->{'name_' . app()->getLocale()} }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tags')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('Status') }}</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="" {{ old('status') == '' ? 'selected' : '' }}>{{ __('Choose status...') }}</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('UnActive') }}</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Medical Engineer Service Option --}}
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fa fa-user-md me-2"></i>{{ __('Medical Engineer Service Option') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="has_engineer_option" id="has_engineer_option" value="1" {{ old('has_engineer_option') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="has_engineer_option">
                                                {{ __('Offer Medical Engineer Service') }}
                                            </label>
                                            <small class="d-block text-muted">{{ __('Enable this to allow customers to request a medical engineer for installation/setup') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div id="engineer_options" style="display: {{ old('has_engineer_option') ? 'block' : 'none' }};">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Engineer Service Price') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ config('currency.default_symbol', 'EGP') }}</span>
                                                <input type="number" step="0.01" class="form-control" name="engineer_price" id="engineer_price" value="{{ old('engineer_price') }}" placeholder="0.00">
                                            </div>
                                            <small class="text-muted">{{ __('Extra charge for medical engineer service') }}</small>
                                            @error('engineer_price')
                                                <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Service Type') }}</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="engineer_required" id="engineer_optional" value="0" {{ old('engineer_required', 0) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="engineer_optional">
                                                    <strong>{{ __('Optional') }}</strong> - {{ __('Customer can choose') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="engineer_required" id="engineer_mandatory" value="1" {{ old('engineer_required') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="engineer_mandatory">
                                                    <strong class="text-danger">{{ __('Mandatory') }}</strong> - {{ __('Cannot buy without engineer') }}
                                                </label>
                                            </div>
                                            <small class="text-muted">{{ __('Choose if engineer service is optional or required') }}</small>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle me-2"></i>
                                        <strong>{{ __('Note:') }}</strong> {{ __('If mandatory, customers MUST select engineer service to purchase this product.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle engineer options visibility
    $('#has_engineer_option').change(function() {
        if ($(this).is(':checked')) {
            $('#engineer_options').slideDown();
            $('#engineer_price').attr('required', true);
        } else {
            $('#engineer_options').slideUp();
            $('#engineer_price').attr('required', false);
            $('#engineer_price').val('');
            $('#engineer_optional').prop('checked', true);
        }
    });

    // Prevent scroll to top on form submission
    $('form').on('submit', function(e) {
        // Save current scroll position
        var scrollPosition = $(window).scrollTop();
        sessionStorage.setItem('productFormScrollPosition', scrollPosition);
    });

    // Restore scroll position after page load (if redirected back with errors)
    var savedScrollPosition = sessionStorage.getItem('productFormScrollPosition');
    if (savedScrollPosition !== null) {
        // Clear the saved position
        sessionStorage.removeItem('productFormScrollPosition');
        // Restore scroll position after a short delay to ensure page is fully loaded
        setTimeout(function() {
            $(window).scrollTop(savedScrollPosition);
        }, 100);
    }
});
</script>
@endpush
