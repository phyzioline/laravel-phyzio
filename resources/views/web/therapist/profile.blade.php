@extends('web.layouts.app')

@section('title', __('Edit Profile'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white font-weight-bold">{{ __('Edit Therapist Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('therapist.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- User Info -->
                        <h6 class="text-teal-700 font-weight-bold mb-3" style="color: #0d9488;">{{ __('Personal Info') }}</h6>
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        </div>

                        <hr>
                        
                        <!-- Profile Info -->
                        <h6 class="text-teal-700 font-weight-bold mb-3" style="color: #0d9488;">{{ __('Professional Info') }} <small class="text-muted">({{ __('Visible to public') }})</small></h6>
                        
                        <div class="form-group">
                            <label>{{ __('Specialization') }}</label>
                            <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization) }}" placeholder="e.g. Orthopedic, Pediatrics" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Bio') }}</label>
                            <textarea name="bio" class="form-control" rows="4">{{ old('bio', $profile->bio) }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Home Visit Rate (EGP)') }}</label>
                                <input type="number" name="home_visit_rate" class="form-control" value="{{ old('home_visit_rate', $profile->home_visit_rate) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Profile Image') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="profile_image" class="custom-file-input" id="profileImg">
                                    <label class="custom-file-label" for="profileImg">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Available Areas') }}</label>
                            <div class="row">
                                @foreach(['Nasr City', 'New Cairo', 'Maadi', 'Giza', 'Dokki', 'Mohandessin', 'Zamalek', 'Sheikh Zayed', '6th of October'] as $area)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="available_areas[]" value="{{ $area }}" class="custom-control-input" id="area_{{ $loop->index }}" 
                                                {{ in_array($area, $profile->available_areas ?? []) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="area_{{ $loop->index }}">{{ $area }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-teal text-white mt-3" style="background-color: #0d9488;">
                            {{ __('Save Changes') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
