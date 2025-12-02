@extends('web.layouts.app')

@section('content')
  <main>

        <!-- breadcrumb-section - start -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 70vh">
                <div class="container">
                    <div class="text-center mt-5 mb-5">
                        <h1>Payment</h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb-section - end -->
    <div class="container text-center mt-5">
        <h3>ادفع الآن</h3>
        <p>المبلغ: {{ $price }} جنيه</p>

        <iframe
            src="{{ $iframe_url }}"
            width="100%"
            height="750"
            frameborder="0"
            scrolling="no"
            style="border: none; overflow: hidden;">
        </iframe>
    </div>
    </main>
@endsection
