<div class="form-section">
    <h6><i class="fas fa-shield-alt me-2"></i>{{ __('Safety & Compliance Information') }}</h6>
    <p class="text-muted mb-3">{{ __('Provide safety and compliance information for your product.') }}</p>
</div>

<div class="form-section">
    <h6><i class="fas fa-globe me-2"></i>{{ __('Origin & Warranty') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Country/Region of Origin') }}</label>
            <input type="text" class="form-control" name="country_of_origin" 
                   placeholder="{{ __('e.g., China, Egypt') }}" value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}">
            <small class="text-muted">{{ __('Example: China') }}</small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Warranty Description') }}</label>
            <textarea class="form-control" name="warranty_description" rows="3" 
                      placeholder="{{ __('e.g., 2 Year Manufacturer Warranty') }}">{{ old('warranty_description', $product->warranty_description ?? 'No Warranty') }}</textarea>
            <small class="text-muted">{{ __('Example: 2 Year Manufacturer') }}</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Seller Warranty Description') }}</label>
            <textarea class="form-control" name="seller_warranty_description" rows="3" 
                      placeholder="{{ __('e.g., 1 year warranty on parts and labor') }}">{{ old('seller_warranty_description', $product->seller_warranty_description ?? '') }}</textarea>
            <small class="text-muted">{{ __('Example: 1 year warranty on parts and labor') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-battery-full me-2"></i>{{ __('Battery Information') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('Are batteries required?') }} <span class="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="batteries_required" id="batteries_yes" value="1" {{ old('batteries_required', $product->batteries_required ?? 0) == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="batteries_yes">{{ __('Yes') }}</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="batteries_required" id="batteries_no" value="0" {{ old('batteries_required', $product->batteries_required ?? 0) == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="batteries_no">{{ __('No') }}</label>
            </div>
            @error('batteries_required')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6" id="battery-details" style="display: {{ old('batteries_required', $product->batteries_required ?? 0) == '1' ? 'block' : 'none' }};">
            <label class="form-label">{{ __('Battery IEC Code') }}</label>
            <input type="text" class="form-control" name="battery_iec_code" 
                   placeholder="{{ __('e.g., CR2320') }}" value="{{ old('battery_iec_code', $product->battery_iec_code ?? '') }}">
            <small class="text-muted">{{ __('Example: CR2320') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-exclamation-triangle me-2"></i>{{ __('Dangerous Goods & Weight') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Dangerous Goods Regulations') }}</label>
            <select class="form-select" name="dangerous_goods_regulations">
                <option value="not_applicable" {{ old('dangerous_goods_regulations', $product->dangerous_goods_regulations ?? 'not_applicable') == 'not_applicable' ? 'selected' : '' }}>
                    {{ __('Not Applicable') }}
                </option>
                <option value="ghs" {{ old('dangerous_goods_regulations', $product->dangerous_goods_regulations ?? '') == 'ghs' ? 'selected' : '' }}>GHS</option>
                <option value="storage" {{ old('dangerous_goods_regulations', $product->dangerous_goods_regulations ?? '') == 'storage' ? 'selected' : '' }}>Storage</option>
                <option value="transportation" {{ old('dangerous_goods_regulations', $product->dangerous_goods_regulations ?? '') == 'transportation' ? 'selected' : '' }}>Transportation</option>
            </select>
            <small class="text-muted">{{ __('Example: GHS, Storage, Transportation') }}</small>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Item Weight') }}</label>
            <input type="number" step="0.01" class="form-control" name="item_weight" 
                   placeholder="0.0" value="{{ old('item_weight', $product->item_weight ?? '') }}">
            <small class="text-muted">{{ __('Example: 30.0, 1.5') }}</small>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('Weight Unit') }}</label>
            <select class="form-select" name="item_weight_unit">
                <option value="grams" {{ old('item_weight_unit', $product->item_weight_unit ?? 'grams') == 'grams' ? 'selected' : '' }}>Grams</option>
                <option value="kilograms" {{ old('item_weight_unit', $product->item_weight_unit ?? '') == 'kilograms' ? 'selected' : '' }}>Kilograms</option>
                <option value="pounds" {{ old('item_weight_unit', $product->item_weight_unit ?? '') == 'pounds' ? 'selected' : '' }}>Pounds</option>
                <option value="ounces" {{ old('item_weight_unit', $product->item_weight_unit ?? '') == 'ounces' ? 'selected' : '' }}>Ounces</option>
            </select>
            <small class="text-muted">{{ __('Example: Pounds, Grams') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-user-shield me-2"></i>{{ __('Age Restrictions & Contact') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Is This Product Subject To Buyer Age Restrictions?') }}</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="age_restriction_required" id="age_yes" value="1" {{ old('age_restriction_required', $product->age_restriction_required ?? 0) == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="age_yes">{{ __('Yes') }}</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="age_restriction_required" id="age_no" value="0" {{ old('age_restriction_required', $product->age_restriction_required ?? 0) == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="age_no">{{ __('No') }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Responsible Person\'s Email or Electronic Address') }}</label>
            <input type="email" class="form-control" name="responsible_person_email" 
                   placeholder="{{ __('e.g., rsp-email@example.com') }}" value="{{ old('responsible_person_email', $product->responsible_person_email ?? '') }}">
            <small class="text-muted">{{ __('Example: rsp-email@example.com') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-info-circle me-2"></i>{{ __('Additional Information') }}</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('Condition') }}</label>
            <select class="form-select" name="condition">
                <option value="new" {{ old('condition', $product->condition ?? 'new') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                <option value="used" {{ old('condition', $product->condition ?? '') == 'used' ? 'selected' : '' }}>{{ __('Used') }}</option>
                <option value="refurbished" {{ old('condition', $product->condition ?? '') == 'refurbished' ? 'selected' : '' }}>{{ __('Refurbished') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('Special Features') }}</label>
            <input type="text" class="form-control" name="special_features" 
                   placeholder="{{ __('e.g., Adjustable, Waterproof') }}" value="{{ old('special_features', $product->special_features ?? '') }}">
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('input[name="batteries_required"]').change(function() {
        if ($(this).val() == '1') {
            $('#battery-details').slideDown();
        } else {
            $('#battery-details').slideUp();
            $('input[name="battery_iec_code"]').val('');
        }
    });
});
</script>

