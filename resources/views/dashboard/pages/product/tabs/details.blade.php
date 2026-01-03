<div class="form-section">
    <h6><i class="fas fa-list me-2"></i>{{ __('Basic Information') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('Categories') }} <span class="text-danger">*</span></label>
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
            <label class="form-label fw-semibold">{{ __('Sub Categories') }} <span class="text-danger">*</span></label>
            <select name="sub_category_id" class="form-select" required>
                <option value="">{{ __('Select Sub Category') }}</option>
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
</div>

<div class="form-section">
    <h6><i class="fas fa-tag me-2"></i>{{ __('Product Names') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Product Name (English)') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="product_name_en" 
                   placeholder="{{ __('Enter Name English') }}" value="{{ old('product_name_en', isset($sourceProduct) ? $sourceProduct->product_name_en : '') }}" required>
            @error('product_name_en')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Product Name (Arabic)') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="product_name_ar" 
                   placeholder="{{ __('Enter Name Arabic') }}" value="{{ old('product_name_ar', isset($sourceProduct) ? $sourceProduct->product_name_ar : '') }}" required>
            @error('product_name_ar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('Brand Name') }}</label>
            <input type="text" class="form-control" name="brand_name" 
                   placeholder="{{ __('e.g., Sony, Nike') }}" value="{{ old('brand_name') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Model Number') }}</label>
            <input type="text" class="form-control" name="model_number" 
                   placeholder="{{ __('e.g., RXZER23') }}" value="{{ old('model_number') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Manufacturer') }}</label>
            <input type="text" class="form-control" name="manufacturer" 
                   placeholder="{{ __('e.g., Nike, Procter & Gamble') }}" value="{{ old('manufacturer') }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-align-left me-2"></i>{{ __('Descriptions') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Short Description (EN)') }}</label>
            <textarea class="form-control" name="short_description_en" rows="3" 
                      placeholder="{{ __('Enter Short Description English') }}">{{ old('short_description_en') }}</textarea>
            @error('short_description_en')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Short Description (AR)') }}</label>
            <textarea class="form-control" name="short_description_ar" rows="3" 
                      placeholder="{{ __('Enter Short Description Arabic') }}">{{ old('short_description_ar') }}</textarea>
            @error('short_description_ar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Long Description (EN)') }}</label>
            <textarea class="form-control" name="long_description_en" rows="5" 
                      placeholder="{{ __('Enter Long Description English') }}">{{ old('long_description_en') }}</textarea>
            @error('long_description_en')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Long Description (AR)') }}</label>
            <textarea class="form-control" name="long_description_ar" rows="5" 
                      placeholder="{{ __('Enter Long Description Arabic') }}">{{ old('long_description_ar') }}</textarea>
            @error('long_description_ar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">{{ __('Bullet Points') }}</label>
            <textarea class="form-control" name="bullet_points" rows="4" 
                      placeholder="{{ __('Enter key features, one per line') }}">{{ old('bullet_points') }}</textarea>
            <small class="text-muted">{{ __('Enter one feature per line') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-key me-2"></i>{{ __('Keywords & Classification') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Generic Keywords') }}</label>
            <input type="text" class="form-control" name="generic_keywords" 
                   placeholder="{{ __('e.g., Water sport shoes; Electric; Wi-Fi') }}" value="{{ old('generic_keywords') }}">
            <small class="text-muted">{{ __('Separate keywords with semicolons') }}</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Product Type') }}</label>
            <input type="text" class="form-control" name="product_type" 
                   placeholder="{{ __('e.g., PORTABLE ELECTRONIC DEVICE STAND') }}" value="{{ old('product_type') }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('Tags') }} <span class="text-danger">*</span></label>
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
            <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="form-select" name="status" id="status" required>
                <option value="inactive" {{ old('status', 'inactive') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive (Draft)') }}</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

{{-- Medical Engineer Service Option --}}
<div class="form-section">
    <h6><i class="fa fa-user-md me-2"></i>{{ __('Medical Engineer Service Option') }}</h6>
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
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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

