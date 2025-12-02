@extends('web.layouts.app')
@section('title', __('Shipping Policy'))
@section('content')
 <!-- main body - start
		================================================== -->
        <main>
            <!-- Existing Slider Section -->
            <section id="slider-section" class="slider-section clearfix">
                <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}" style="height: 100vh">
                    <div class="container">
                        <div class="d-flex flex-column align-items-center">
                            <h1 class="hero-text">Physicaltherapy Software Solutions</h1>
                        </div>
                        <div class="hero-size">
                            <h5 class="hero-praph">All Physical Therapist Needs is Our Mission From PT to PT</h5>
                        </div>
                    </div>
                </div>
            </section>

            <!-- New Delivery Policies Section -->
            <section id="delivery-policies-section" class="delivery-policies-section">
                <div class="container">
                    <h2 style="color: #000;" class="section-title">Shipping Policies</h2>
                    <ul class="delivery-policies-list">
                        {{ $privacy_policy->{'description_' . app()->getLocale()} ?? 'No shipping policy available.' }}
                        {{-- <li>Free delivery on orders over $100.</li>
                        <li>Delivery typically takes 3-5 business days.</li>
                        <li>Orders will be shipped within 2 business days of order placement.</li>
                        <li>We only ship to the continental United States.</li>
                        <li>Tracking numbers will be provided once the order has been shipped.</li>
                        <li>Returns are accepted within 30 days of delivery.</li> --}}
                    </ul>
                </div>
            </section>
        </main>

  <!-- main body - end

        ================================================== -->
@endsection
