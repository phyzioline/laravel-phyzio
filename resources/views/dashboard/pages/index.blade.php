@extends('dashboard.layouts.app')
@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">PHYZIOLINE | Dashboard</div>
            </div>



            @if (auth()->user()->hasRole('admin'))
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-user text-primary"></i> {{ __('Number of User') }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $user ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-store text-primary"></i> {{ __('Number of Vendor')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $vendor ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-cart-shopping text-primary"></i> {{ __('Number of Buyer')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $buyer ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-box text-primary"></i> {{ __('Number of Product')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $product ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order Card')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_card ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order Cash')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_cash ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                  


                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Tag')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $tag ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
            @if (auth()->user()->hasRole('vendor'))
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Product') }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $product_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order Card')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order__card_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order Cash')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order__cash_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </main>
@endsection
