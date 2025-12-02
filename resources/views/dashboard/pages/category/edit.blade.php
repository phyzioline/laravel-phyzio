@extends('dashboard.layouts.app')
@section('title', __('Edit Category'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                        <form method="post" action="{{ route('dashboard.categories.update', $category->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="container">
                                <div class="row my-4">
                                    <div class="col-md-6">
                                        <label for="name_en" class="form-label">{{ __('Enter Name English') }}</label>
                                        <input type="text" id="name_en" class="form-control" name="name_en" value="{{ $category->name_en }}" placeholder="{{ __('Enter Name English') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name_ar" class="form-label">{{ __('Enter Name Arabic') }}</label>
                                        <input type="text" id="name_ar" class="form-control" name="name_ar" value="{{ $category->name_ar }}" placeholder="{{ __('Enter Name Arabic') }}">
                                    </div>
                                </div>

                                     <div class="col-md-6">
                                        <label for="status" class="form-label">{{ __('status') }}</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="" selected>{{ __('Choose status...') }}</option>
                                            <option value="inactive" @selected($category->status == 'inactive')>{{ __('UnActive') }}
                                            </option>
                                            <option value="active" @selected($category->status == 'active')>{{ __('Active') }}
                                            </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary w-100">{{ __('Edit') }}</button>
                                </div>
                            </div>
                        </form>

        </div>
        </div>
    </div>
</main>
@endsection
