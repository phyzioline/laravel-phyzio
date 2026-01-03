<div class="form-section">
    <h6><i class="fas fa-layer-group me-2"></i>{{ __('Product Variations') }}</h6>
    <p class="text-muted mb-3">{{ __('If your product has variations (e.g., different sizes, colors), enable this option.') }}</p>
    
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="has_variations" id="has_variations" value="1" {{ old('has_variations', $product->has_variations ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="has_variations">
                {{ __('This product has variations') }}
            </label>
        </div>
    </div>

    <div id="variations-section" style="display: {{ old('has_variations', $product->has_variations ?? false) ? 'block' : 'none' }};">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            {{ __('Variations allow you to sell the same product in different sizes, colors, or other attributes.') }}
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-semibold">{{ __('Variation Attributes') }}</label>
            @php
                $variationAttrs = old('variation_attributes', $product->variation_attributes ? json_decode($product->variation_attributes, true) : []);
            @endphp
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input variation-attr" type="checkbox" name="variation_attributes[]" value="Color" id="var-color" {{ in_array('Color', $variationAttrs) ? 'checked' : '' }}>
                        <label class="form-check-label" for="var-color">{{ __('Color') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input variation-attr" type="checkbox" name="variation_attributes[]" value="Size" id="var-size" {{ in_array('Size', $variationAttrs) ? 'checked' : '' }}>
                        <label class="form-check-label" for="var-size">{{ __('Size') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input variation-attr" type="checkbox" name="variation_attributes[]" value="Material" id="var-material" {{ in_array('Material', $variationAttrs) ? 'checked' : '' }}>
                        <label class="form-check-label" for="var-material">{{ __('Material') }}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input variation-attr" type="checkbox" name="variation_attributes[]" value="Style" id="var-style" {{ in_array('Style', $variationAttrs) ? 'checked' : '' }}>
                        <label class="form-check-label" for="var-style">{{ __('Style') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input variation-attr" type="checkbox" name="variation_attributes[]" value="Pattern" id="var-pattern" {{ in_array('Pattern', $variationAttrs) ? 'checked' : '' }}>
                        <label class="form-check-label" for="var-pattern">{{ __('Pattern') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>{{ __('Note:') }}</strong> {{ __('Variation management will be available after product update. You can manage specific variations in the product edit page.') }}
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#has_variations').change(function() {
        if ($(this).is(':checked')) {
            $('#variations-section').slideDown();
        } else {
            $('#variations-section').slideUp();
            $('.variation-attr').prop('checked', false);
        }
    });
});
</script>

