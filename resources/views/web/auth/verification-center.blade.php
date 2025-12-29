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

                    @if(Auth::user()->type === 'therapist' && $therapistProfile)
                    <!-- Module-Specific Documents for Therapists -->
                    <div class="mt-5 mb-4">
                        <h3 class="mb-4">{{ __('Module Access Documents') }}</h3>
                        <p class="text-muted mb-4">{{ __('Upload documents to gain access to specific modules. Each module requires separate approval.') }}</p>
                    </div>

                    @foreach(['clinic' => ['name' => 'Clinic Access', 'icon' => 'hospital', 'description' => 'Upload clinic-related documents to access clinic features'], 'courses' => ['name' => 'Instructor/Course Access', 'icon' => 'book', 'description' => 'Upload instructor documents to create and publish courses'], 'home_visit' => ['name' => 'Home Visit Access', 'icon' => 'home', 'description' => 'Upload documents to provide home visit services']] as $moduleType => $moduleInfo)
                        @php
                            $moduleVerification = $moduleVerifications ? $moduleVerifications->get($moduleType) : null;
                            $isVerified = $therapistProfile->canAccessModule($moduleType);
                            $moduleStatus = $moduleVerification ? $moduleVerification->status : 'pending';
                            
                            // Get module-specific documents
                            $moduleDocuments = Auth::user()->documents()->where('module_type', $moduleType)->get();
                        @endphp
                        <div class="card mb-4 border-{{ $isVerified ? 'success' : ($moduleStatus === 'rejected' ? 'danger' : ($moduleStatus === 'under_review' ? 'warning' : 'secondary')) }}">
                            <div class="card-header bg-{{ $isVerified ? 'success' : ($moduleStatus === 'rejected' ? 'danger' : ($moduleStatus === 'under_review' ? 'warning' : 'light')) }} text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">
                                            <i class="fas fa-{{ $moduleInfo['icon'] }} mr-2"></i>
                                            {{ $moduleInfo['name'] }}
                                        </h5>
                                        <small>{{ $moduleInfo['description'] }}</small>
                                    </div>
                                    <div>
                                        @if($isVerified)
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check-circle"></i> {{ __('Approved') }}
                                            </span>
                                        @elseif($moduleStatus === 'under_review')
                                            <span class="badge badge-warning badge-lg">
                                                <i class="fas fa-clock"></i> {{ __('Under Review') }}
                                            </span>
                                        @elseif($moduleStatus === 'rejected')
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-times-circle"></i> {{ __('Rejected') }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary badge-lg">
                                                <i class="fas fa-hourglass-half"></i> {{ __('Not Applied') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($moduleVerification && $moduleVerification->admin_note)
                                    <div class="alert alert-{{ $moduleStatus === 'rejected' ? 'danger' : 'info' }}">
                                        <strong>{{ __('Admin Note:') }}</strong> {{ $moduleVerification->admin_note }}
                                    </div>
                                @endif

                                @if($moduleDocuments->count() > 0)
                                    <div class="mb-3">
                                        <strong>{{ __('Uploaded Documents:') }}</strong>
                                        <ul class="list-group mt-2">
                                            @foreach($moduleDocuments as $doc)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                        <a href="{{ asset($doc->file_path) }}" target="_blank" class="text-primary">
                                                            {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                                        </a>
                                                        <span class="badge badge-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'rejected' ? 'danger' : ($doc->status === 'under_review' ? 'warning' : 'info')) }} ml-2">
                                                            {{ ucfirst($doc->status) }}
                                                        </span>
                                                    </div>
                                                    @if($doc->status !== 'approved')
                                                        <form action="{{ route('verification.delete-document', $doc->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!$isVerified)
                                    <div class="mt-3">
                                        <h6 class="mb-2">{{ __('Upload Documents for :module Access', ['module' => $moduleInfo['name']]) }}</h6>
                                        <form action="{{ route('verification.upload-document') }}" 
                                              method="POST" 
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="module_type" value="{{ $moduleType }}">
                                            
                                            <div class="form-group">
                                                <label>{{ __('Document Type') }}</label>
                                                <select name="document_type" class="form-control" required>
                                                    <option value="">{{ __('Select document type') }}</option>
                                                    @if($moduleType === 'clinic')
                                                        <option value="clinic_license">{{ __('Clinic License') }}</option>
                                                        <option value="clinic_registration">{{ __('Clinic Registration') }}</option>
                                                        <option value="commercial_register">{{ __('Commercial Register') }}</option>
                                                        <option value="tax_card">{{ __('Tax Card') }}</option>
                                                    @elseif($moduleType === 'courses')
                                                        <option value="instructor_certificate">{{ __('Instructor Certificate') }}</option>
                                                        <option value="teaching_license">{{ __('Teaching License') }}</option>
                                                        <option value="education_degree">{{ __('Education Degree') }}</option>
                                                    @elseif($moduleType === 'home_visit')
                                                        <option value="home_visit_license">{{ __('Home Visit License') }}</option>
                                                        <option value="professional_certificate">{{ __('Professional Certificate') }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <div class="upload-area" onclick="document.getElementById('module_file_{{ $moduleType }}').click()">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                                <p class="mb-0">{{ __('Click to upload document') }}</p>
                                                <small class="text-muted">{{ __('PDF, JPG, PNG (Max 10MB)') }}</small>
                                            </div>
                                            <input type="file" 
                                                   id="module_file_{{ $moduleType }}" 
                                                   name="document" 
                                                   accept=".pdf,.jpg,.jpeg,.png" 
                                                   style="display: none;"
                                                   onchange="this.form.submit()"
                                                   required>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if(Auth::user()->type === 'company' && $companyProfile)
                    <!-- Module-Specific Documents for Companies -->
                    <div class="mt-5 mb-4">
                        <h3 class="mb-4">{{ __('Clinic Access Documents') }}</h3>
                        <p class="text-muted mb-4">{{ __('Upload clinic-related documents to gain access to clinic features.') }}</p>
                    </div>

                    @php
                        $moduleType = 'clinic';
                        $moduleVerification = $companyModuleVerifications ? $companyModuleVerifications->get($moduleType) : null;
                        $isVerified = $companyProfile->canAccessClinic();
                        $moduleStatus = $moduleVerification ? $moduleVerification->status : 'pending';
                        
                        // Get module-specific documents
                        $moduleDocuments = Auth::user()->documents()->where('module_type', $moduleType)->get();
                    @endphp
                    <div class="card mb-4 border-{{ $isVerified ? 'success' : ($moduleStatus === 'rejected' ? 'danger' : ($moduleStatus === 'under_review' ? 'warning' : 'secondary')) }}">
                        <div class="card-header bg-{{ $isVerified ? 'success' : ($moduleStatus === 'rejected' ? 'danger' : ($moduleStatus === 'under_review' ? 'warning' : 'light')) }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-hospital mr-2"></i>
                                        {{ __('Clinic Access') }}
                                    </h5>
                                    <small>{{ __('Upload clinic-related documents to access clinic features') }}</small>
                                </div>
                                <div>
                                    @if($isVerified)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check-circle"></i> {{ __('Approved') }}
                                        </span>
                                    @elseif($moduleStatus === 'under_review')
                                        <span class="badge badge-warning badge-lg">
                                            <i class="fas fa-clock"></i> {{ __('Under Review') }}
                                        </span>
                                    @elseif($moduleStatus === 'rejected')
                                        <span class="badge badge-danger badge-lg">
                                            <i class="fas fa-times-circle"></i> {{ __('Rejected') }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="fas fa-hourglass-half"></i> {{ __('Not Applied') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($moduleVerification && $moduleVerification->admin_note)
                                <div class="alert alert-{{ $moduleStatus === 'rejected' ? 'danger' : 'info' }}">
                                    <strong>{{ __('Admin Note:') }}</strong> {{ $moduleVerification->admin_note }}
                                </div>
                            @endif

                            @if($moduleDocuments->count() > 0)
                                <div class="mb-3">
                                    <strong>{{ __('Uploaded Documents:') }}</strong>
                                    <ul class="list-group mt-2">
                                        @foreach($moduleDocuments as $doc)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                    <a href="{{ asset($doc->file_path) }}" target="_blank" class="text-primary">
                                                        {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                                    </a>
                                                    <span class="badge badge-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'rejected' ? 'danger' : ($doc->status === 'under_review' ? 'warning' : 'info')) }} ml-2">
                                                        {{ ucfirst($doc->status) }}
                                                    </span>
                                                </div>
                                                @if($doc->status !== 'approved')
                                                    <form action="{{ route('verification.delete-document', $doc->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(!$isVerified)
                                <div class="mt-3">
                                    <h6 class="mb-2">{{ __('Upload Documents for Clinic Access') }}</h6>
                                    <form action="{{ route('verification.upload-document') }}" 
                                          method="POST" 
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="module_type" value="clinic">
                                        
                                        <div class="form-group">
                                            <label>{{ __('Document Type') }}</label>
                                            <select name="document_type" class="form-control" required>
                                                <option value="">{{ __('Select document type') }}</option>
                                                <option value="clinic_license">{{ __('Clinic License') }}</option>
                                                <option value="clinic_registration">{{ __('Clinic Registration') }}</option>
                                                <option value="commercial_register">{{ __('Commercial Register') }}</option>
                                                <option value="tax_card">{{ __('Tax Card') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="upload-area" onclick="document.getElementById('company_clinic_file').click()">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                            <p class="mb-0">{{ __('Click to upload document') }}</p>
                                            <small class="text-muted">{{ __('PDF, JPG, PNG (Max 10MB)') }}</small>
                                        </div>
                                        <input type="file" 
                                               id="company_clinic_file" 
                                               name="document" 
                                               accept=".pdf,.jpg,.jpeg,.png" 
                                               style="display: none;"
                                               onchange="this.form.submit()"
                                               required>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
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

