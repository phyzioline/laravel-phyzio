@extends('dashboard.layouts.app')
@section('title', __('Add Category'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
              <form method="post" action="{{ route('dashboard.sub_categories.store') }}"  enctype="multipart/form-data">
                @csrf
                  <div class="container">

                    <div class="col-md-6">
                                    <label class="fw-semibold">{{ __('Categories') }}</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</option>
                                        @endforeach
                                    </select>
                        @error('category_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                </div>

                      <div class="row my-4">
                          <div class="col-md-6">
                                  <input type="text" class="form-control"  name="name_en"  placeholder="{{ __('Enter Name English') }}">
  @error('name_en')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                </div>
                          <div class="col-md-6">
                            <input type="text" class="form-control"  name="name_ar"  placeholder="{{ __('Enter Name Arabic') }}">
                      @error('name_ar')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                        </div>
                       <div class="col-md-6">
                                        <label for="status" class="form-label">{{ __('status') }}</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="" selected>{{ __('Choose status...') }}</option>
                                            <option value="inactive">{{ __('UnActive') }}</option>
                                            <option value="active">{{ __('Active') }}</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                      </div>
                      <div class="modal-footer">
                        <button  class="btn btn-primary w-100">Add</button>
                      </div>
                  </div>
                </form>

        </div>
        </div>
    </div>
</main>
@endsection
