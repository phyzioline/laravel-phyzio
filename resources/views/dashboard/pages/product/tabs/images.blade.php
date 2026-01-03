<div class="form-section">
    <h6><i class="fas fa-images me-2"></i>{{ __('Product Images') }}</h6>
    <p class="text-muted mb-3">{{ __('Upload multiple files or drag and drop 1 or more files below. Maximum 9 images are allowed.') }}</p>
    
    <div class="mb-3">
        <label class="form-label fw-semibold">{{ __('Upload Images') }}</label>
        <input type="file" class="form-control" name="images[]" multiple accept="image/*" id="product-images">
        <small class="text-muted">{{ __('Recommended: 1000x1000px, JPG or PNG format') }}</small>
        @error('images')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>

    <div id="image-preview-container" class="row g-3 mt-3">
        <!-- Image previews will be added here -->
    </div>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle me-2"></i>
        <strong>{{ __('Image Guidelines:') }}</strong>
        <ul class="mb-0 mt-2">
            <li>{{ __('Main image should be on white background') }}</li>
            <li>{{ __('Images should show product from different angles') }}</li>
            <li>{{ __('Minimum resolution: 500x500px') }}</li>
            <li>{{ __('Maximum file size: 5MB per image') }}</li>
        </ul>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#product-images').on('change', function() {
        const files = this.files;
        const container = $('#image-preview-container');
        container.empty();
        
        if (files.length > 9) {
            alert('{{ __("Maximum 9 images allowed") }}');
            $(this).val('');
            return;
        }
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const isMain = index === 0;
                    const preview = `
                        <div class="col-md-3">
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="d-block text-center ${isMain ? 'fw-bold text-primary' : ''}">
                                        ${isMain ? '{{ __("MAIN") }}' : '{{ __("Image") }} ' + (index + 1)}
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>

