<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Verification Center') }}</title>
    <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .document-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .document-card:hover {
            border-color: #02767F;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .document-card.approved {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .document-card.rejected {
            border-color: #dc3545;
            background-color: #fff8f8;
        }
        .document-card.under_review {
            border-color: #ffc107;
            background-color: #fffef5;
        }
        .upload-area {
            border: 2px dashed #02767F;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background-color: #f0f9fa;
        }
        .file-preview {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <section>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-4">
                        <h2 class="mb-2">{{ __('Verification Center') }}</h2>
                        <p class="text-muted">{{ __('Upload and manage your verification documents') }}</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="font-weight-bold">{{ __('Verification Progress') }}</span>
                                <span class="font-weight-bold text-primary">{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 30px; border-radius: 10px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $progress }}%
                                </div>
                            </div>
                            @if($progress == 100 && Auth::user()->verification_status != 'approved')
                                <p class="text-info mt-2 mb-0">
                                    <i class="fas fa-info-circle"></i> {{ __('All documents uploaded. Waiting for admin approval.') }}
                                </p>
                            @elseif(Auth::user()->verification_status == 'approved')
                                <p class="text-success mt-2 mb-0">
                                    <i class="fas fa-check-circle"></i> {{ __('Your account is verified and active!') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Documents List -->
                    @if($requiredDocuments->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> {{ __('No documents required for your account type.') }}
                        </div>
                    @else
                        @foreach($requiredDocuments as $doc)
                            @php
                                $userDoc = $userDocuments->get($doc->document_type);
                                $status = $userDoc ? $userDoc->status : 'missing';
                            @endphp
                            <div class="document-card {{ $status }}">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fas fa-file-alt mr-2"></i>{{ $doc->label }}
                                            @if($doc->mandatory)
                                                <span class="badge badge-danger ml-2">{{ __('Required') }}</span>
                                            @else
                                                <span class="badge badge-secondary ml-2">{{ __('Optional') }}</span>
                                            @endif
                                        </h5>
                                        @if($doc->description)
                                            <p class="text-muted mb-0">{{ $doc->description }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        @if($status === 'missing')
                                            <span class="badge badge-secondary badge-lg">{{ __('Missing') }}</span>
                                        @elseif($status === 'uploaded')
                                            <span class="badge badge-info badge-lg">{{ __('Uploaded') }}</span>
                                        @elseif($status === 'under_review')
                                            <span class="badge badge-warning badge-lg">{{ __('Under Review') }}</span>
                                        @elseif($status === 'approved')
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check"></i> {{ __('Approved') }}
                                            </span>
                                        @elseif($status === 'rejected')
                                            <span class="badge badge-danger badge-lg">{{ __('Rejected') }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($userDoc && $userDoc->status === 'rejected' && $userDoc->admin_note)
                                    <div class="alert alert-danger">
                                        <strong>{{ __('Rejection Reason:') }}</strong> {{ $userDoc->admin_note }}
                                    </div>
                                @endif

                                @if($userDoc && $userDoc->file_path)
                                    <div class="file-preview">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                <a href="{{ asset($userDoc->file_path) }}" target="_blank" class="text-primary">
                                                    {{ __('View Document') }}
                                                </a>
                                            </div>
                                            @if($status !== 'approved')
                                                <form action="{{ route('verification.delete-document', $userDoc->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($status !== 'approved')
                                    <form action="{{ route('verification.upload-document') }}" 
                                          method="POST" 
                                          enctype="multipart/form-data" 
                                          class="mt-3">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $doc->document_type }}">
                                        
                                        <div class="upload-area" onclick="document.getElementById('file_{{ $doc->document_type }}').click()">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                            <p class="mb-0">
                                                @if($userDoc && $userDoc->file_path)
                                                    {{ __('Click to replace document') }}
                                                @else
                                                    {{ __('Click to upload document') }}
                                                @endif
                                            </p>
                                            <small class="text-muted">{{ __('PDF, JPG, PNG (Max 10MB)') }}</small>
                                        </div>
                                        <input type="file" 
                                               id="file_{{ $doc->document_type }}" 
                                               name="document" 
                                               accept=".pdf,.jpg,.jpeg,.png" 
                                               style="display: none;"
                                               onchange="this.form.submit()"
                                               required>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="text-center mt-4">
                        @php
                            $user = Auth::user();
                            $dashboardRoute = 'dashboard.home'; // Default to admin dashboard
                            
                            // Check user type and roles to determine correct dashboard
                            if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
                                $dashboardRoute = 'dashboard.home';
                            } elseif ($user->type === 'therapist') {
                                $dashboardRoute = 'therapist.dashboard';
                            } elseif ($user->type === 'company') {
                                $dashboardRoute = 'company.dashboard';
                            } elseif ($user->hasRole('instructor')) {
                                $dashboardRoute = 'instructor.dashboard';
                            } elseif ($user->hasRole('clinic')) {
                                $dashboardRoute = 'clinic.dashboard';
                            } elseif ($user->type === 'vendor' || $user->type === 'buyer') {
                                $dashboardRoute = 'home.' . app()->getLocale();
                            }
                        @endphp
                        <a href="{{ route($dashboardRoute) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            });
        </script>
    </section>
</body>

</html>

