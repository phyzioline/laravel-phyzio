@extends('dashboard.layouts.app')
@section('title', __('Add User'))

@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <form method="post" action="{{ route('dashboard.users.store') }}" class="p-4 rounded shadow-lg bg-white"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card border-0">
                            <div class="card-header bg-primary text-white rounded-top">
                                <h5 class="mb-0">{{ __('Add a new Blood Bank') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="{{ __('Enter the user name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="{{ __('Enter the user email') }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                        <input type="number" name="phone" id="phone" class="form-control"
                                            placeholder="{{ __('Enter the user Phone Number') }}">
                                    </div>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="col-md-6">
                                        <label for="account_statement" class="form-label">{{ __('Whats App') }}</label>
                                        <input type="file" name="account_statement" id="account_statement"
                                            class="form-control" placeholder="{{ __('Enter the user name') }}">
                                        @error('account_statement')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="commercial_register"
                                            class="form-label">{{ __('Commercial Register') }}</label>
                                        <input type="file" name="commercial_register" id="commercial_register"
                                            class="form-control" placeholder="{{ __('Enter the user name') }}">
                                        @error('commercial_register')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tax_card" class="form-label">{{ __('Tax Card') }}</label>
                                        <input type="file" name="tax_card" id="tax_card" class="form-control"
                                            placeholder="{{ __('Enter the user name') }}">
                                        @error('tax_card')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="card_image" class="form-label">{{ __('Card Image') }}</label>
                                        <input type="file" name="card_image" id="card_image" class="form-control"
                                            placeholder="{{ __('Enter the user name') }}">
                                        @error('card_image')
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






                                    <div class="col-md-6">
                                        <label for="image" class="form-label">{{ __('Image') }}</label>
                                        <input type="file" name="image" id="image" class="form-control"
                                            placeholder="{{ __('Enter the user image') }}">
                                    </div>
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror




                                    <div class="col-md-6">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="{{ __('Enter the user password') }}" autocomplete="new-password">
                                    </div>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror



                                    <div class="col-md-6">
                                        <label for="role_id" class="form-label">{{ __('Role') }}</label>
                                        <select class="form-select" name="role_id" id="role_id">
                                            <option value="" selected>{{ __('Choose the user role') }}</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end bg-light rounded-bottom">
                                <button type="submit" class="btn btn-primary px-4">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.getElementById('check').addEventListener('change', function() {
            document.querySelectorAll('.checkbox').forEach(checkbox => checkbox.checked = this.checked);
        });

        document.querySelectorAll('.checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                document.getElementById('check').checked = [...document.querySelectorAll('.checkbox')]
                    .every(cb => cb.checked);
            });
        });
    </script>
@endpush
