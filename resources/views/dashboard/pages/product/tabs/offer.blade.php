<div class="form-section">
    <h6><i class="fas fa-tag me-2"></i>{{ __('Pricing & Inventory') }}</h6>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('Price') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">{{ config('currency.default_symbol', 'EGP') }}</span>
                <input type="number" step="0.01" class="form-control" name="product_price" 
                       placeholder="0.00" value="{{ old('product_price') }}" required>
            </div>
            @error('product_price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Compare at Price') }}</label>
            <div class="input-group">
                <span class="input-group-text">{{ config('currency.default_symbol', 'EGP') }}</span>
                <input type="number" step="0.01" class="form-control" name="compare_at_price" 
                       placeholder="0.00" value="{{ old('compare_at_price') }}">
            </div>
            <small class="text-muted">{{ __('Original price for showing discounts') }}</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Cost Price') }}</label>
            <div class="input-group">
                <span class="input-group-text">{{ config('currency.default_symbol', 'EGP') }}</span>
                <input type="number" step="0.01" class="form-control" name="cost_price" 
                       placeholder="0.00" value="{{ old('cost_price') }}">
            </div>
            <small class="text-muted">{{ __('Your cost (for profit calculation)') }}</small>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-boxes me-2"></i>{{ __('Stock Management') }}</h6>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('Quantity') }} <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="amount" 
                   placeholder="{{ __('Enter Amount') }}" value="{{ old('amount', 0) }}" required min="0">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Minimum Quantity') }}</label>
            <input type="number" class="form-control" name="min_quantity" 
                   placeholder="1" value="{{ old('min_quantity', 1) }}" min="1">
            <small class="text-muted">{{ __('Minimum order quantity') }}</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Maximum Quantity') }}</label>
            <input type="number" class="form-control" name="max_quantity" 
                   placeholder="{{ __('No limit') }}" value="{{ old('max_quantity') }}" min="1">
            <small class="text-muted">{{ __('Maximum order quantity (leave empty for no limit)') }}</small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="track_inventory" id="track_inventory" value="1" {{ old('track_inventory', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="track_inventory">
                    {{ __('Track inventory for this product') }}
                </label>
            </div>
        </div>
    </div>
</div>

<div class="form-section">
    <h6><i class="fas fa-barcode me-2"></i>{{ __('Product Identifiers') }}</h6>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('SKU') }}</label>
            <input type="text" class="form-control" name="sku" 
                   placeholder="{{ __('Auto-generated if empty') }}" value="{{ old('sku') }}" readonly>
            <small class="text-muted">{{ __('Automatically generated') }}</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('Barcode') }}</label>
            <input type="text" class="form-control" name="barcode" 
                   placeholder="{{ __('Enter barcode') }}" value="{{ old('barcode') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('EAN') }}</label>
            <input type="text" class="form-control" name="ean" 
                   placeholder="{{ __('European Article Number') }}" value="{{ old('ean') }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('UPC') }}</label>
            <input type="text" class="form-control" name="upc" 
                   placeholder="{{ __('Universal Product Code') }}" value="{{ old('upc') }}">
        </div>
    </div>
</div>

