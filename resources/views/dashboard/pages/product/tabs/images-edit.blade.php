<div class="form-section">
    <h6><i class="fas fa-images me-2"></i>{{ __('Product Images') }}</h6>
    <p class="text-muted mb-3">{{ __('Upload multiple files or drag and drop 1 or more files below. Maximum 9 images are allowed.') }}</p>
    
    <!-- Existing Images -->
    @if($product->productImages && $product->productImages->count() > 0)
    <div class="mb-4">
        <label class="form-label fw-semibold">{{ __('Current Images') }}</label>
        <div class="row g-3" id="existing-images">
            @foreach($product->productImages as $index => $img)
            <div class="col-md-3">
                <div class="card position-relative">
                    <img src="{{ asset($img->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="d-block text-center {{ $index === 0 ? 'fw-bold text-primary' : '' }}">
                            {{ $index === 0 ? __('MAIN') : __('Image') . ' ' . ($index + 1) }}
                        </small>
                        <button type="button" class="btn btn-sm btn-danger w-100 mt-2 remove-image-btn" data-image-id="{{ $img->id }}">
                            <i class="fas fa-trash me-1"></i>{{ __('Remove') }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <input type="hidden" name="remove_images" id="remove_images" value="">
    </div>
    @endif
    
    <div class="mb-3">
        <label class="form-label fw-semibold">{{ __('Upload New Images') }}</label>
        <input type="file" class="form-control" name="images[]" multiple accept="image/*" id="product-images">
        <small class="text-muted">{{ __('Recommended: 1000x1000px, JPG or PNG format') }}</small>
        @error('images')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>

    <div id="image-preview-container" class="row g-3 mt-3">
        <!-- New image previews will be added here -->
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
    let imagesToRemove = [];
    
    // Handle remove existing image
    $(document).on('click', '.remove-image-btn', function() {
        const imageId = $(this).data('image-id');
        imagesToRemove.push(imageId);
        $('#remove_images').val(imagesToRemove.join(','));
        $(this).closest('.col-md-3').fadeOut(300, function() {
            $(this).remove();
        });
    });
    
    // Handle new image preview
    $('#product-images').on('change', function() {
        const files = this.files;
        const container = $('#image-preview-container');
        const existingCount = $('#existing-images .col-md-3').length;
        
        if (files.length + existingCount > 9) {
            alert('{{ __("Maximum 9 images allowed") }}');
            $(this).val('');
            return;
        }
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = `
                        <div class="col-md-3">
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="d-block text-center">{{ __('New Image') }} ${index + 1}</small>
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

