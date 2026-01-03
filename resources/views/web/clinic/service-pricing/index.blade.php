@extends('web.layouts.dashboard_master')

@section('title', 'Service Pricing')
@section('header_title', 'Manage Service Pricing')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="las la-info-circle"></i> 
            <strong>{{ __('Service Pricing Management') }}</strong><br>
            {{ __('Add and manage prices for services like Ultrasound, Laser, Shockwave, etc. These prices will appear in the appointment booking pricing preview and be automatically calculated.') }}
        </div>
    </div>
</div>

@foreach(['pediatric', 'orthopedic', 'neurological', 'sports', 'geriatric'] as $specialty)
@php
    $config = $pricingConfigs[$specialty] ?? null;
    $services = $config ? ($config->equipment_pricing ?? []) : [];
    $specialtyName = ucfirst(str_replace('_', ' ', $specialty));
@endphp

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="font-weight-bold mb-0">
            <i class="las la-stethoscope"></i> {{ $specialtyName }} {{ __('Services') }}
        </h5>
        <p class="text-muted small mb-0">{{ __('Manage service prices for') }} {{ strtolower($specialtyName) }} {{ __('specialty') }}</p>
    </div>
    <div class="card-body px-4">
        <div id="services-{{ $specialty }}" class="services-container">
            @if(count($services) > 0)
                @foreach($services as $key => $price)
                <div class="service-item mb-3 p-3 border rounded" data-service-key="{{ $key }}">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control service-name" 
                                   value="{{ $defaultServices[$key]['name'] ?? ucfirst(str_replace('_', ' ', $key)) }}" 
                                   placeholder="Service Name" readonly>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control service-price" 
                                       value="{{ number_format($price, 2, '.', '') }}" 
                                       placeholder="0.00" data-specialty="{{ $specialty }}" data-key="{{ $key }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span class="badge badge-info">{{ __('Active') }}</span>
                        </div>
                        <div class="col-md-2 text-right">
                            <button class="btn btn-sm btn-danger remove-service" 
                                    data-specialty="{{ $specialty }}" 
                                    data-key="{{ $key }}">
                                <i class="las la-trash"></i> {{ __('Remove') }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-4 text-muted">
                    <i class="las la-inbox fa-3x mb-3"></i>
                    <p>{{ __('No services configured. Add services below.') }}</p>
                </div>
            @endif
        </div>

        <!-- Add New Service -->
        <div class="border-top pt-4 mt-4">
            <h6 class="mb-3">{{ __('Add New Service') }}</h6>
            <form class="add-service-form" data-specialty="{{ $specialty }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="service_key" 
                               placeholder="Service Key (e.g., ultrasound)" required>
                        <small class="text-muted">{{ __('Use lowercase, no spaces') }}</small>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="service_name" 
                               placeholder="Service Name (e.g., Ultrasound)" required>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   name="service_price" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="las la-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Save Button -->
        <div class="mt-4 text-right">
            <button class="btn btn-primary save-services" data-specialty="{{ $specialty }}">
                <i class="las la-save"></i> {{ __('Save Changes') }} - {{ $specialtyName }}
            </button>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save on price change (debounced)
    let saveTimeouts = {};
    $('.service-price').on('input', function() {
        const specialty = $(this).data('specialty');
        clearTimeout(saveTimeouts[specialty]);
        saveTimeouts[specialty] = setTimeout(() => {
            saveServices(specialty);
        }, 1000); // Save after 1 second of no typing
    });

    // Save services
    function saveServices(specialty) {
        const services = [];
        $(`#services-${specialty} .service-item`).each(function() {
            const key = $(this).data('service-key');
            const name = $(this).find('.service-name').val();
            const price = parseFloat($(this).find('.service-price').val()) || 0;
            if (key && name && price > 0) {
                services.push({ key: key, name: name, price: price });
            }
        });

        fetch(`{{ route('clinic.servicePricing.update', 'SPECIALTY') }}`.replace('SPECIALTY', specialty), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ services: services })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Services saved successfully!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving services', 'error');
        });
    }

    // Manual save button
    $('.save-services').on('click', function() {
        const specialty = $(this).data('specialty');
        saveServices(specialty);
    });

    // Add new service
    $('.add-service-form').on('submit', function(e) {
        e.preventDefault();
        const specialty = $(this).data('specialty');
        const formData = {
            service_key: $(this).find('[name="service_key"]').val(),
            service_name: $(this).find('[name="service_name"]').val(),
            service_price: parseFloat($(this).find('[name="service_price"]').val())
        };

        fetch(`{{ route('clinic.servicePricing.addService', 'SPECIALTY') }}`.replace('SPECIALTY', specialty), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to show new service
            } else {
                alert(data.message || 'Error adding service');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    });

    // Remove service
    $('.remove-service').on('click', function() {
        if (!confirm('Are you sure you want to remove this service?')) {
            return;
        }

        const specialty = $(this).data('specialty');
        const key = $(this).data('key');

        fetch(`{{ route('clinic.servicePricing.removeService', 'SPECIALTY') }}`.replace('SPECIALTY', specialty), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ service_key: key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error removing service');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    });

    function showNotification(message, type) {
        // Simple notification - you can enhance this
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alert = $(`<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>`);
        $('body').prepend(alert);
        setTimeout(() => alert.fadeOut(), 3000);
    }
});
</script>
@endpush
@endsection

