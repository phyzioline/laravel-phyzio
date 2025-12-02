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
                                <option value="inactive" @selected(old('status', $product->status) == 'inactive')>{{ __('UnActive') }}</option>
                                <option value="active" @selected(old('status', $product->status) == 'active')>{{ __('Active') }}</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
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
