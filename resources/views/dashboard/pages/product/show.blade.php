@extends('dashboard.layouts.app')
@section('title', __('View Product'))
@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <div class="card p-4">
                        <p><strong>{{ __('Name Product') }}:</strong> {{ $product->{'product_name_' . app()->getLocale()} }}
                        </p>
                        <p><strong>{{ __('Category') }}:</strong> {{ $product->category?->{'name_' . app()->getLocale()} }}
                        </p>
                        <p><strong>{{ __('Sub Category') }}:</strong>
                            {{ $product->sub_category?->{'name_' . app()->getLocale()} }}</p>
                        <p><strong>{{ __('Price') }}:</strong> {{ $product->product_price }}</p>
                        <p><strong>{{ __('Short Description') }}:</strong>
                            {{ $product->{'short_description_' . app()->getLocale()} }} </p>
                        <p><strong>{{ __('Long Description') }}:</strong>
                            {{ $product->{'long_description_' . app()->getLocale()} }} </p>
                             <p><strong>{{ __('Amount') }}:</strong> {{$product->amount }}</p>
                        <p><strong>{{ __('Status') }}:</strong> {{ ucfirst($product->status) }}</p>

                        <div>
                            <strong>{{ __('Tags') }}:</strong>
                            @foreach ($product->tags as $tag)
                                <span class="badge bg-primary">{{ $tag->{'name_' . app()->getLocale()} }}</span>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <strong>{{ __('Images') }}:</strong><br>
                            @foreach ($product->productImages as $img)
                                <img src="{{ asset($img->image) }}" width="80" class="me-2 mb-2" />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
