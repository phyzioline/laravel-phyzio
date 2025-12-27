<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Complete Your Account') }}</title>
    <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section>
        <div class="container">
            <div class="row full-screen align-items-center">
                <div class="left">
                    <img src="{{ asset('web/assets/images/LOGO PHYSIOLINE SVG 1 (2).svg') }}" width="100%" alt="">
                </div>
                <div class="right">
                    <div class="form">
                        <div class="text-center">
                            <h6><span>{{ __('Complete Your Account') }}</span></h6>
                            <div class="card-3d-wrap singup" style="height: fit-content; min-height: 600px;">
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <h4 class="heading">{{ __('Welcome!') }}</h4>
                                            
                                            <div class="mb-4">
                                                <p class="text-muted">{{ __('To activate your account and make it visible to users, please upload the required documents.') }}</p>
                                                
                                                <!-- Progress Bar -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="small">{{ __('Verification Progress') }}</span>
                                                        <span class="small font-weight-bold">{{ $progress }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 25px; border-radius: 10px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: {{ $progress }}%" 
                                                             aria-valuenow="{{ $progress }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            {{ $progress }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h5 class="mb-3">{{ __('Required Documents') }}</h5>
                                                
                                                @if($requiredDocuments->isEmpty())
                                                    <p class="text-success">
                                                        <i class="fas fa-check-circle"></i> {{ __('No documents required for your account type.') }}
                                                    </p>
                                                @else
                                                    <div class="list-group">
                                                        @foreach($requiredDocuments as $doc)
                                                        @php
                                                            $userDoc = $userDocuments->get($doc->document_type ?? '');
                                                            $status = $userDoc ? $userDoc->status : 'missing';
                                                        @endphp
                                                            <div class="list-group-item mb-2 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong>{{ $doc->label }}</strong>
                                                                    @if($doc->mandatory)
                                                                        <span class="badge badge-danger ml-2">{{ __('Required') }}</span>
                                                                    @endif
                                                                    @if($doc->description)
                                                                        <br><small class="text-muted">{{ $doc->description }}</small>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    @if($status === 'missing')
                                                                        <span class="badge badge-secondary">{{ __('Missing') }}</span>
                                                                    @elseif($status === 'uploaded')
                                                                        <span class="badge badge-info">{{ __('Uploaded') }}</span>
                                                                    @elseif($status === 'under_review')
                                                                        <span class="badge badge-warning">{{ __('Under Review') }}</span>
                                                                    @elseif($status === 'approved')
                                                                        <span class="badge badge-success">{{ __('Approved') }}</span>
                                                                    @elseif($status === 'rejected')
                                                                        <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('verification.verification-center') }}" class="btn btn-primary">
                                                    <i class="fas fa-upload mr-2"></i>{{ __('Upload Documents') }}
                                                </a>
                                                <a href="{{ route('dashboard.home') }}" class="btn btn-outline-secondary">
                                                    {{ __('Go to Dashboard') }}
                                                </a>
                                            </div>

                                            <p class="text-center mt-4">
                                                <small class="text-muted">
                                                    {{ __('You can upload documents later from your dashboard.') }}
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "5000",
                };

                @if(Session::has('success'))
                    toastr.success("{{ Session::get('success') }}");
                @endif

                @if(Session::has('message'))
                    var type = "{{ Session::get('message')['type'] }}";
                    var text = "{!! Session::get('message')['text'] !!}";
                    toastr[type](text);
                @endif
            });
        </script>
    </section>
</body>

</html>

