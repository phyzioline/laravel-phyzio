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

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
