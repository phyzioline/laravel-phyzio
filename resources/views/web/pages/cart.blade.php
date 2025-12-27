@extends('web.layouts.app')

@section('content')
    <!-- main body - start -->
    <main>
        <!-- breadcrumb-section - start -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 70vh; background-size: cover; background-position: center;">
                <div class="container">
                    <div class="text-center mt-5 mb-5">
                        <h1 class="text-white fw-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">CART</h1>
                        <p class="text-white-50">Review your items and complete your order</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb-section - end -->

        <!-- cart-section - start -->
        <section id="cart-section" class="cart-section sec-ptb-100 clearfix" style="background-color: #f8f9fa;">
            <div class="container">
                <!-- Cart Items Table -->
                <div class="table-wrap bg-white rounded shadow-sm p-4 mb-4">
                    <h4 class="mb-4 text-primary fw-bold">
                        <i class="las la-shopping-cart me-2"></i>Shopping Cart Items
                    </h4>
                    
                    @if(count($items) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Product</th>
                                        <th class="border-0 text-center">Price</th>
                                        <th class="border-0 text-center">Quantity</th>
                                        <th class="border-0 text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr data-item-id="{{ $item->id }}" data-price="{{ $item->product->product_price }}" 
                                            class="align-middle">
                                            <td>
                                                <div class="product-info d-flex align-items-center">
                                                    <form action="{{ route('carts.destroy', $item->id) }}" method="POST"
                                                        class="remove-form me-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm remove-btn" 
                                                                title="Remove item">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <div class="product-image me-3">
                                                        @php
                                                            $productImage = $item->product && $item->product->productImages && $item->product->productImages->first()
                                                                ? asset($item->product->productImages->first()->image)
                                                                : asset('web/assets/images/default-product.png');
                                                        @endphp
                                                        <img style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                                                            src="{{ $productImage }}"
                                                            alt="Product Image" class="img-fluid">
                                                    </div>
                                                    
                                                    <div class="product-details">
                                                        <h6 class="mb-1 fw-bold text-dark">
                                                            {{ $item->product->{'product_name_' . app()->getLocale()} }}
                                                        </h6>
                                                        <small class="text-muted">Available: {{ $item->product->amount }} units</small>
                                                        @php
                                                            $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                                                            $engineerSelected = $options['engineer_selected'] ?? false;
                                                            $engineerPrice = $options['engineer_price'] ?? 0;
                                                        @endphp
                                                        @if($engineerSelected)
                                                        <div class="mt-2">
                                                            <span class="badge bg-info">
                                                                <i class="fa fa-user-md me-1"></i>
                                                                {{ __('Medical Engineer Installation') }} 
                                                                (+{{ number_format($engineerPrice, 2) }} {{ config('currency.default_symbol', 'EGP') }})
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="text-center">
                                                @php
                                                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                                                    $engineerSelected = $options['engineer_selected'] ?? false;
                                                    $engineerPrice = $options['engineer_price'] ?? 0;
                                                    $unitPrice = $item->product->product_price + $engineerPrice;
                                                @endphp
                                                <div>
                                                    <span class="item-price fw-bold text-success">
                                                        {{ number_format($unitPrice, 2) }} EGP
                                                    </span>
                                                    @if($engineerSelected)
                                                    <div class="small text-muted">
                                                        <small>({{ number_format($item->product->product_price, 2) }} + {{ number_format($engineerPrice, 2) }})</small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="text-center">
                                                <div class="quantity-input d-flex align-items-center justify-content-center">
                                                    <input type="number"
                                                           class="form-control text-center mx-2 input-number-1"
                                                           style="width: 80px; border-radius: 20px;"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           max="{{ $item->product->amount }}"
                                                           name="quantity"
                                                           data-id="{{ $item->id }}
                                                </div>
                                            </td>
                                            
                                            <td class="text-center">
                                                @php
                                                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                                                    $engineerPrice = $options['engineer_price'] ?? 0;
                                                    $unitPrice = $item->product->product_price + $engineerPrice;
                                                    $subtotal = $unitPrice * $item->quantity;
                                                @endphp
                                                <strong class="item-subtotal text-primary fs-5">
                                                    {{ number_format($subtotal, 2) }} EGP
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="las la-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Your cart is empty</h5>
                            <p class="text-muted">Add some products to get started!</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="cuponcode-form mb-4">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 mb-lg-0">
                            <a href="{{ route('history_order.index') }}" class="btn btn-outline-primary" 
                               style="border-color: #02767F; color: #02767F;">
                                <i class="las la-shipping-fast me-2"></i>{{ __('Track Your Order') }}
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('carts.flush') }}" class="btn btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to empty your cart?')">
                                    <i class="las la-trash me-2"></i>Empty Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary and Checkout -->
                <div class="row justify-content-center">
                    <!-- Total Cost -->
                    <div class="col-lg-5 col-md-6 col-sm-12 mb-4">
                        <div class="total-cost-bar bg-white rounded shadow-sm p-4">
                            <h4 class="title-text mb-4 text-white text-center fw-bold" 
                                style="background-color: #02767F; padding: 15px; border-radius: 8px;">
                                <i class="las la-calculator me-2"></i>Order Summary
                            </h4>
                            
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-dark">Subtotal:</span>
                                    <span class="fw-semibold">{{ number_format($subtotal, 2) }} {{ config('currency.default_symbol', 'EGP') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-dark">Shipping Cost:</span>
                                    <span class="fw-semibold">{{ number_format($shippingCost, 2) }} {{ config('currency.default_symbol', 'EGP') }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="total-cost d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <strong class="fs-5 text-dark">Total Amount:</strong>
                                    <span id="cart-total" class="fs-4 fw-bold text-primary">
                                        {{ number_format($total, 2) }} {{ config('currency.default_symbol', 'EGP') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="total-cost-bar bg-white rounded shadow-sm p-4">
                            {{-- Progress Indicator - Amazon Style --}}
                            <div class="checkout-progress mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="step completed">
                                        <div class="step-circle bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">1</div>
                                        <div class="step-label mt-2 text-center">
                                            <small class="text-muted">Cart</small>
                                        </div>
                                    </div>
                                    <div class="step-line flex-grow-1 mx-2" style="height: 2px; background: #02767F;"></div>
                                    <div class="step active">
                                        <div class="step-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">2</div>
                                        <div class="step-label mt-2 text-center">
                                            <small class="fw-bold text-primary">Checkout</small>
                                        </div>
                                    </div>
                                    <div class="step-line flex-grow-1 mx-2" style="height: 2px; background: #e0e0e0;"></div>
                                    <div class="step">
                                        <div class="step-circle bg-light text-muted rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">3</div>
                                        <div class="step-label mt-2 text-center">
                                            <small class="text-muted">Confirmation</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4 class="title-text mb-4 text-white text-center fw-bold" 
                                style="background-color: #02767F; padding: 15px; border-radius: 8px;">
                                <i class="las la-credit-card me-2"></i>Complete Order
                            </h4>
                            
                            <form action="{{ route('order.store.' . (app()->getLocale() ?: 'en')) }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                
                                {{-- Guest Checkout Email --}}
                                @guest
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="las la-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               placeholder="your.email@example.com" required>
                                        <small class="text-muted">We'll use this to send order updates and help you create an account later.</small>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                </div>
                                @endguest
                                
                                {{-- Saved Addresses (One-Click Reorder) - Amazon Style --}}
                                @auth
                                @php
                                    $savedAddresses = \App\Models\UserAddress::where('user_id', auth()->id())->get();
                                @endphp
                                @if($savedAddresses->count() > 0)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">
                                            <i class="las la-bookmark me-1"></i>Saved Addresses
                                        </label>
                                        <select id="saved_address_select" class="form-select">
                                            <option value="">Select a saved address</option>
                                            @foreach($savedAddresses as $addr)
                                            <option value="{{ $addr->id }}" 
                                                    data-name="{{ $addr->name }}"
                                                    data-phone="{{ $addr->phone }}"
                                                    data-address="{{ $addr->address }}"
                                                    data-city="{{ $addr->city }}"
                                                    data-governorate="{{ $addr->governorate }}">
                                                {{ $addr->name }} - {{ $addr->address }} {{ $addr->is_default ? '(Default)' : '' }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select a saved address to auto-fill the form</small>
                                    </div>
                                </div>
                                @endif
                                @endauth
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label fw-bold">
                                            <i class="las la-credit-card me-1"></i>Payment Method <span class="text-danger">*</span>
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-select payment-method-select" required style="font-size: 18px; padding: 15px; border: 2px solid #02767F; border-radius: 10px; background: linear-gradient(135deg, #ffffff 0%, #f0f9fa 100%); transition: all 0.3s ease;">
                                            <option value="" disabled selected>Select Payment Method</option>
                                            <option value="card">💳 Credit/Debit Card</option>
                                            <option value="cash">💰 Cash on Delivery</option>
                                        </select>
                                        <div class="invalid-feedback" id="payment_method_error">Please select a payment method.</div>
                                        <div class="text-danger small mt-1" id="payment_method_alert" style="display: none;">
                                            <i class="las la-exclamation-circle me-1"></i>Please select a payment method to continue.
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="Name" class="form-label fw-bold">
                                            <i class="las la-user me-1"></i>Full Name
                                        </label>
                                        <input type="text" id="Name" name="name" class="form-control" 
                                               value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                               placeholder="Enter your full name" required>
                                        <div class="invalid-feedback">Please enter your name.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-bold">
                                            <i class="las la-phone me-1"></i>Phone Number
                                        </label>
                                        <input type="tel" id="phone" name="phone" class="form-control" 
                                               value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                               placeholder="Enter phone number" required>
                                        <div class="invalid-feedback">Please enter a valid phone number.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label fw-bold">
                                            <i class="las la-map-marker me-1"></i>Delivery Address
                                        </label>
                                        <input type="text" id="address" name="address" class="form-control" 
                                               placeholder="Enter delivery address" required>
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label fw-bold">
                                            <i class="las la-city me-1"></i>City
                                        </label>
                                        <input type="text" id="city" name="city" class="form-control" 
                                               placeholder="Enter city">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="governorate" class="form-label fw-bold">
                                            <i class="las la-map me-1"></i>Governorate
                                        </label>
                                        <input type="text" id="governorate" name="governorate" class="form-control" 
                                               placeholder="Enter governorate">
                                    </div>
                                </div>
                                
                                {{-- Save Address Checkbox (One-Click Reorder) - Amazon Style --}}
                                @auth
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="save_address" id="save_address" value="1" checked>
                                        <label class="form-check-label" for="save_address">
                                            <i class="las la-bookmark me-1"></i>Save this address for faster checkout next time
                                        </label>
                                    </div>
                                </div>
                                @endauth

                                <div class="text-center mt-4">
                                    <button type="submit" id="place_order_btn" class="btn btn-primary btn-lg px-5" 
                                            style="background-color: #02767F; border-color: #02767F;">
                                        <span id="btn_text">
                                            <i class="las la-paper-plane me-2"></i>Place Order
                                        </span>
                                        <span id="btn_error" style="display: none; color: #fff;">
                                            <i class="las la-exclamation-circle me-2"></i>Please Select Payment Method
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- cart-section - end -->

        <script>
            $(document).ready(function() {
                // Auto-fill form from saved address (One-Click Reorder) - Amazon Style
                $('#saved_address_select').on('change', function() {
                    const option = $(this).find('option:selected');
                    if (option.val()) {
                        $('#Name').val(option.data('name'));
                        $('#phone').val(option.data('phone'));
                        $('#address').val(option.data('address'));
                        $('#city').val(option.data('city') || '');
                        $('#governorate').val(option.data('governorate') || '');
                    }
                });
                
                // Form validation with payment method check
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        const paymentMethod = document.getElementById('payment_method');
                        const paymentMethodAlert = document.getElementById('payment_method_alert');
                        
                        // Check payment method specifically
                        const placeOrderBtn = document.getElementById('place_order_btn');
                        const btnText = document.getElementById('btn_text');
                        const btnError = document.getElementById('btn_error');
                        
                        if (!paymentMethod.value || paymentMethod.value === '') {
                            event.preventDefault();
                            event.stopPropagation();
                            paymentMethod.classList.add('is-invalid');
                            paymentMethod.style.borderColor = '#dc3545';
                            paymentMethodAlert.style.display = 'block';
                            
                            // Show error in button
                            btnText.style.display = 'none';
                            btnError.style.display = 'inline';
                            placeOrderBtn.style.backgroundColor = '#dc3545';
                            placeOrderBtn.style.borderColor = '#dc3545';
                            
                            paymentMethod.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return false;
                        } else {
                            paymentMethod.classList.remove('is-invalid');
                            paymentMethod.style.borderColor = '#02767F';
                            paymentMethodAlert.style.display = 'none';
                            
                            // Restore button text
                            btnText.style.display = 'inline';
                            btnError.style.display = 'none';
                            placeOrderBtn.style.backgroundColor = '#02767F';
                            placeOrderBtn.style.borderColor = '#02767F';
                        }
                        
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
                
                // Remove error styling when payment method is selected
                document.getElementById('payment_method').addEventListener('change', function() {
                    const placeOrderBtn = document.getElementById('place_order_btn');
                    const btnText = document.getElementById('btn_text');
                    const btnError = document.getElementById('btn_error');
                    
                    // Restore button text when payment method is selected
                    if (this.value) {
                        btnText.style.display = 'inline';
                        btnError.style.display = 'none';
                        placeOrderBtn.style.backgroundColor = '#02767F';
                        placeOrderBtn.style.borderColor = '#02767F';
                    }
                    if (this.value) {
                        this.classList.remove('is-invalid');
                        this.style.borderColor = '#02767F';
                        document.getElementById('payment_method_alert').style.display = 'none';
                    }
                });

                function updateQuantity(itemId, quantity) {
                    $.ajax({
                        url: '{{ url("/") }}/{{ app()->getLocale() ?: "en" }}/update_cart/' + itemId,
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            quantity: quantity
                        },
                        success: function(res) {
                            console.log('Quantity updated on server');
                            updateTotal();
                            // Show success message
                            showNotification('Quantity updated successfully!', 'success');
                        },
                        error: function(xhr) {
                            let errorMessage = 'Error updating quantity. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                                // If insufficient stock, update the max value
                                if (xhr.responseJSON.available) {
                                    $('.input-number-1[data-id="' + itemId + '"]').attr('max', xhr.responseJSON.available);
                                    $('.input-number-1[data-id="' + itemId + '"]').val(xhr.responseJSON.available);
                                }
                            }
                            showNotification(errorMessage, 'error');
                        }
                    });
                }

                function updateTotal() {
                    $.ajax({
                        url: '{{ route('carts.total') }}',
                        method: 'GET',
                        success: function(res) {
                            $('#cart-total').text(res.total + ' EGP');
                        },
                        error: function() {
                            console.error('Error loading total');
                        }
                    });
                }

                function showNotification(message, type) {
                    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                    const alertHtml = `
                        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                            <i class="las ${type === 'success' ? 'la-check-circle' : 'la-exclamation-circle'} me-2"></i>
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('body').append(alertHtml);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        $('.alert').fadeOut();
                    }, 3000);
                }

                $('.input-number-1').off('change').on('change', function() {
                    var itemId = $(this).data('id');
                    var newQty = parseInt($(this).val());
                    var maxQty = parseInt($(this).attr('max')) || 999;
                    
                    if (newQty < 1 || isNaN(newQty)) {
                        newQty = 1;
                        $(this).val(1);
                    }
                    
                    if (newQty > maxQty) {
                        newQty = maxQty;
                        $(this).val(maxQty);
                        var message = maxQty == 1 
                            ? 'Only 1 item available.' 
                            : `Only ${maxQty} items available.`;
                        showNotification(message, 'error');
                    }

                    // Update quantity on server
                    updateQuantity(itemId, newQty);

                    // Update subtotal display (will be corrected by server response if wrong)
                    var $row = $(this).closest('tr');
                    var price = parseFloat($row.data('price'));
                    var newSubtotal = price * newQty;
                    $row.find('strong.item-subtotal').text(newSubtotal.toFixed(2) + ' EGP');
                });

                // Remove item confirmation
                $('.remove-form').on('submit', function(e) {
                    if (!confirm('Are you sure you want to remove this item from your cart?')) {
                        e.preventDefault();
                    }
                });
            });
        </script>

        <style>
            .table th {
                font-weight: 600;
                color: #495057;
                border-bottom: 2px solid #dee2e6;
            }
            
            .table td {
                vertical-align: middle;
                border-color: #f8f9fa;
            }
            
            .form-control, .form-select {
                border-radius: 8px;
                border: 1px solid #ced4da;
                transition: all 0.3s ease;
            }
            
            .form-control:focus, .form-select:focus {
                border-color: #02767F;
                box-shadow: 0 0 0 0.2rem rgba(2, 118, 127, 0.25);
            }
            
            .btn {
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            
            .product-details h6 {
                color: #2c3e50;
                line-height: 1.4;
            }
            
            .quantity-input input {
                border: 2px solid #e9ecef;
            }
            
            .quantity-input input:focus {
                border-color: #02767F;
                box-shadow: 0 0 0 0.2rem rgba(2, 118, 127, 0.25);
            }
            
            .alert {
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            
            /* Payment Method Dropdown Styling */
            .payment-method-select {
                font-size: 18px !important;
                padding: 15px !important;
                border: 2px solid #02767F !important;
                border-radius: 10px !important;
                background: linear-gradient(135deg, #ffffff 0%, #f0f9fa 100%) !important;
                transition: all 0.3s ease !important;
                cursor: pointer !important;
                font-weight: 500 !important;
            }
            
            .payment-method-select:hover {
                border-color: #04b8c4 !important;
                background: linear-gradient(135deg, #f0f9fa 0%, #e0f4f5 100%) !important;
                box-shadow: 0 4px 12px rgba(2, 118, 127, 0.2) !important;
                transform: translateY(-2px) !important;
            }
            
            .payment-method-select:focus {
                border-color: #04b8c4 !important;
                background: linear-gradient(135deg, #ffffff 0%, #e0f4f5 100%) !important;
                box-shadow: 0 0 0 0.3rem rgba(4, 184, 196, 0.3) !important;
                outline: none !important;
            }
            
            .payment-method-select option {
                padding: 12px !important;
                font-size: 16px !important;
                background: #ffffff !important;
            }
            
            .payment-method-select.is-invalid {
                border-color: #dc3545 !important;
                background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%) !important;
            }
                                                                                           .item-image img{
                                                                                           width : 100% !important;
                                                                                           height : 100% !important;
                                                                                           }
        </style>
    </main>
    <!-- main body - end -->
@endsection
