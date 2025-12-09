@extends('web.layouts.dashboard_master')

@section('title', 'Pricing & Policy')
@section('header_title', 'Pricing & Policy')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Wizard Progress -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between position-relative">
                    <div class="text-center text-muted opacity-50">
                         <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">1</div>
                        <div class="small">{{ __('Basic Info') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">2</div>
                        <div class="small">{{ __('Curriculum') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="btn btn-primary rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">3</div>
                        <div class="font-weight-bold text-primary">{{ __('Pricing') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">4</div>
                        <div class="small">{{ __('Review') }}</div>
                    </div>
                     <div style="position: absolute; top: 20px; left: 50px; right: 50px; height: 2px; background: #eee; z-index: -1;"></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 font-weight-bold">{{ __('Step 3: Pricing & Policy') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('instructor.courses.update', ['course' => $course->id, 'step' => 3]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('Course Price (EGP)') }}</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">EGP</span>
                                </div>
                                <input type="number" name="price" class="form-control" placeholder="0.00" value="{{ $course->price }}" min="0" step="0.01">
                            </div>
                            <small class="text-muted">{{ __('Leave 0 for Free course.') }}</small>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                             <label class="font-weight-bold">{{ __('Discounted Price (Optional)') }}</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">EGP</span>
                                </div>
                                <input type="number" name="discount_price" class="form-control" placeholder="0.00" value="{{ $course->discount_price }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="font-weight-bold">{{ __('Refund Policy') }}</label>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="refund1" name="refund_policy" class="custom-control-input" value="30_days" checked>
                                <label class="custom-control-label" for="refund1">30-Day Money-Back Guarantee (Recommended)</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="refund2" name="refund_policy" class="custom-control-input" value="no_refund">
                                <label class="custom-control-label" for="refund2">No Refunds</label>
                            </div>
                        </div>

                         <div class="col-md-12">
                             <div class="alert alert-info">
                                 <i class="las la-info-circle mr-1"></i> Phyzioline charges a <strong>15% platform fee</strong> on all paid enrollments.
                             </div>
                         </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('instructor.courses.edit', ['course' => $course->id, 'step' => 2]) }}" class="btn btn-light px-4 btn-lg">
                            <i class="las la-arrow-left mr-2"></i> {{ __('Back') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4 btn-lg">
                            {{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
