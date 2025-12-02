@extends('dashboard.layouts.app')
@section('title', __('Add Tag'))
@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
              <form method="post" action="{{ route('dashboard.tags.store') }}"  enctype="multipart/form-data">
                @csrf
                  <div class="container">

                      <div class="row my-4">
                          <div class="col-md-6">
                                  <input type="text" class="form-control"  name="name_en"  placeholder="{{ __('Enter Name English') }}">
                          </div>
                          <div class="col-md-6">
                            <input type="text" class="form-control"  name="name_ar"  placeholder="{{ __('Enter Name Arabic') }}">
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
