@extends('dashboard.layouts.app')
@section('title', auth()->id() === $user->id ? __('Edit Profile') : __('Edit User'))

@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <form method="post" action="{{ route('dashboard.users.update', $user->id) }}"
                        class="p-4 rounded shadow-lg bg-white" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card border-0">
                            <div class="card-header bg-primary text-white rounded-top">
                                <h5 class="mb-0">{{ auth()->id() === $user->id ? __('Edit Profile') : __('Edit User') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">{{ __('Name') }}</label>
                                        <input type="text" name="name" value="{{ $user->name }}" id="name"
                                            class="form-control" placeholder="{{ __('Enter the user name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" value="{{ $user->email }}"
                                            class="form-control" placeholder="{{ __('Enter the user email') }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                        <input type="number" name="phone" id="phone" value="{{ $user->phone }}"
                                            class="form-control" placeholder="{{ __('Enter the user Phone Number') }}">
                                    </div>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror



                                    <div class="col-md-6">
                                        <label for="account_statement"
                                            class="form-label">{{ __('Account Statement') }}</label>
                                        <input type="file" name="account_statement" id="account_statement"
                                            class="form-control">
                                        @error('account_statement')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if (isset($user) && $user->account_statement)
                                            <div class="mt-2">
                                                <a href="{{ asset($user->account_statement) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    {{ __('عرض الملف') }}
                                                </a>
                                                <a href="{{ asset($user->account_statement) }}" download
                                                    class="btn btn-sm btn-success">
                                                    {{ __('تحميل الملف') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label for="commercial_register"
                                            class="form-label">{{ __('Commercial Register') }}</label>
                                        <input type="file" name="commercial_register" id="commercial_register"
                                            class="form-control">
                                        @error('commercial_register')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if (isset($user) && $user->commercial_register)
                                            <div class="mt-2">
                                                <a href="{{ asset($user->commercial_register) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    {{ __('عرض الملف') }}
                                                </a>
                                                <a href="{{ asset($user->commercial_register) }}" download
                                                    class="btn btn-sm btn-success">
                                                    {{ __('تحميل الملف') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tax_card" class="form-label">{{ __('Tax Card') }}</label>
                                        <input type="file" name="tax_card" id="tax_card" class="form-control">
                                        @error('tax_card')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if (isset($user) && $user->tax_card)
                                            <div class="mt-2">
                                                <a href="{{ asset($user->tax_card) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    {{ __('عرض الملف') }}
                                                </a>
                                                <a href="{{ asset($user->tax_card) }}" download
                                                    class="btn btn-sm btn-success">
                                                    {{ __('تحميل الملف') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label for="card_image" class="form-label">{{ __('Card Image') }}</label>
                                        <input type="file" name="card_image" id="card_image" class="form-control">
                                        @error('card_image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if (isset($user) && $user->card_image)
                                            <div class="mt-2">
                                                <a href="{{ asset($user->card_image) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    {{ __('عرض الملف') }}
                                                </a>
                                                <a href="{{ asset($user->card_image) }}" download
                                                    class="btn btn-sm btn-success">
                                                    {{ __('تحميل الملف') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>




                                    <div class="col-12">
                                        <hr>
                                        <h6 class="mb-3 text-primary">{{ __('Bank Details') }}</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="bank_name" class="form-label">{{ __('Bank Name') }}</label>
                                        <input type="text" name="bank_name" id="bank_name" value="{{ $user->bank_name }}"
                                            class="form-control" placeholder="{{ __('e.g. CIB, QNB') }}">
                                        @error('bank_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="bank_account_name" class="form-label">{{ __('Bank Account Holder Name') }}</label>
                                        <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $user->bank_account_name }}"
                                            class="form-control" placeholder="{{ __('Full name as in bank account') }}">
                                        @error('bank_account_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="iban" class="form-label">{{ __('IBAN') }}</label>
                                        <input type="text" name="iban" id="iban" value="{{ $user->iban }}"
                                            class="form-control" placeholder="{{ __('EG123456789...') }}">
                                        @error('iban')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="swift_code" class="form-label">{{ __('Swift Code') }}</label>
                                        <input type="text" name="swift_code" id="swift_code" value="{{ $user->swift_code }}"
                                            class="form-control" placeholder="{{ __('e.g. CIBEGCAXXX') }}">
                                        @error('swift_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12"><hr></div>
                                    
                                    @if(auth()->user()->can('users-update') && auth()->id() !== $user->id)
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">{{ __('status') }}</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="" selected>{{ __('Choose status...') }}</option>
                                            <option value="inactive" @selected(($user->status ?? '') === 'inactive')>{{ __('UnActive') }}
                                            </option>
                                            <option value="active" @selected(($user->status ?? '') === 'active')>{{ __('Active') }}
                                            </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @else
                                    <!-- Hidden field to maintain current status when user edits own profile -->
                                    <input type="hidden" name="status" value="{{ $user->status }}">
                                    @endif





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



                                    @if(auth()->user()->can('users-update') && auth()->id() !== $user->id)
                                    <div class="col-md-6">
                                        <label for="role_id" class="form-label">{{ __('Role') }}</label>
                                        <select class="form-select" name="role_id" id="role_id">
                                            <option value="" selected>{{ __('Choose the user role') }}</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @else
                                    <!-- Maintain current role when user edits own profile -->
                                    <input type="hidden" name="role_id" value="{{ $user->roles->first()->id ?? '' }}">
                                    @endif
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
