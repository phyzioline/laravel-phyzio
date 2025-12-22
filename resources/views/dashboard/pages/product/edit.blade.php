@extends('dashboard.layouts.app')
@section('title', __('Edit Product'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                <form method="post" action="{{ route('dashboard.products.update', $product->id) }}" enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-white">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('Categories') }}</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $category->{'name_' . app()->getLocale()} }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('Sub Categories') }}</label>
                            <select name="sub_category_id" class="form-select" required>
                                <option value="">{{ __('Select Sub Category') }}</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}" {{ $sub_category->id == $product->sub_category_id ? 'selected' : '' }}>
                                        {{ $sub_category->{'name_' . app()->getLocale()} }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Product Name (English)') }}</label>
                            <input type="text" class="form-control" name="product_name_en" value="{{ old('product_name_en', $product->product_name_en) }}" placeholder="{{ __('Enter Name English') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Product Name (Arabic)') }}</label>
                            <input type="text" class="form-control" name="product_name_ar" value="{{ old('product_name_ar', $product->product_name_ar) }}" placeholder="{{ __('Enter Name Arabic') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Price') }}</label>
                            <input type="text" class="form-control" name="product_price" value="{{ old('product_price', $product->product_price) }}" placeholder="{{ __('Enter Price') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Short Description (EN)') }}</label>
                            <input type="text" class="form-control" name="short_description_en" value="{{ old('short_description_en', $product->short_description_en) }}" placeholder="{{ __('Enter Short Description English') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Short Description (AR)') }}</label>
                            <input type="text" class="form-control" name="short_description_ar" value="{{ old('short_description_ar', $product->short_description_ar) }}" placeholder="{{ __('Enter Short Description Arabic') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Long Description (EN)') }}</label>
                            <input type="text" class="form-control" name="long_description_en" value="{{ old('long_description_en', $product->long_description_en) }}" placeholder="{{ __('Enter Long Description English') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Long Description (AR)') }}</label>
                            <input type="text" class="form-control" name="long_description_ar" value="{{ old('long_description_ar', $product->long_description_ar) }}" placeholder="{{ __('Enter Long Description Arabic') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Images') }}</label>
                            <input type="file" class="form-control" name="images[]" multiple>
                            <div class="mt-2">
                                @foreach ($product->productImages as $img)
                                    <img src="{{ asset($img->image) }}" width="60" class="me-2 mb-2 rounded border" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                       <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Amount') }}</label>
                            <input type="number" value="{{ old('amount', $product->amount) }}"  class="form-control" name="amount" placeholder="{{ __('Enter Amount') }}">
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Tags') }}</label>
                            <select name="tags[]" class="form-select" multiple required>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $tag->{'name_' . app()->getLocale()} }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" name="status" id="status">
                                <option value="inactive" @selected((old('status', $product->status ?? '') === 'inactive'))>{{ __('UnActive') }}</option>
                                <option value="active" @selected((old('status', $product->status ?? '') === 'active'))>{{ __('Active') }}</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ __('Status') }}</small>
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
                                        <input class="form-check-input" type="checkbox" name="has_engineer_option" id="has_engineer_option" value="1" {{ old('has_engineer_option', $product->has_engineer_option) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="has_engineer_option">
                                            {{ __('Offer Medical Engineer Service') }}
                                        </label>
                                        <small class="d-block text-muted">{{ __('Enable this to allow customers to request a medical engineer for installation/setup') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div id="engineer_options" style="display: {{ old('has_engineer_option', $product->has_engineer_option) ? 'block' : 'none' }};">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Engineer Service Price') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ config('currency.default_symbol', 'EGP') }}</span>
                                            <input type="number" step="0.01" class="form-control" name="engineer_price" id="engineer_price" value="{{ old('engineer_price', $product->engineer_price) }}" placeholder="0.00">
                                        </div>
                                        <small class="text-muted">{{ __('Extra charge for medical engineer service') }}</small>
                                        @error('engineer_price')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('Service Type') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="engineer_required" id="engineer_optional" value="0" {{ old('engineer_required', $product->engineer_required) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="engineer_optional">
                                                <strong>{{ __('Optional') }}</strong> - {{ __('Customer can choose') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="engineer_required" id="engineer_mandatory" value="1" {{ old('engineer_required', $product->engineer_required) == 1 ? 'checked' : '' }}>
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
                        <button class="btn btn-success">{{ __('Update') }}</button>
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
});
</script>
@endpush
