@extends('web.layouts.app')

@section('title', 'Licensing Guide | Data Hub')

@section('content')
<div class="container-fluid py-5" style="background-color: #fff;">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="font-weight-bold">Professional Equivalence & Licensing Guide</h1>
            <p class="lead text-muted">Your roadmap to practicing Physical Therapy globally.</p>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4 bg-light">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="font-weight-bold text-uppercase text-muted small">I studied in (Source)</label>
                                <select class="form-control form-control-lg custom-select">
                                    <option>Egypt</option>
                                    <option>Jordan</option>
                                    <option>India</option>
                                    <option>Philippines</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                                <i class="las la-arrow-right display-4 text-primary d-none d-md-block"></i>
                                <i class="las la-arrow-down display-4 text-primary d-md-none"></i>
                            </div>
                            <div class="col-md-5">
                                <label class="font-weight-bold text-uppercase text-muted small">I want to work in (Target)</label>
                                <select class="form-control form-control-lg custom-select">
                                    <option>USA (New York)</option>
                                    <option>Canada (Ontario)</option>
                                    <option>United Kingdom</option>
                                    <option>UAE (DHA)</option>
                                    <option>Saudi Arabia (SCFHS)</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary btn-lg px-5 shadow">Get Licensing Roadmap</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4 font-weight-bold border-bottom pb-2">Requirements Breakdown (Example: Egypt -> USA)</h3>
                
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-req-tab" data-toggle="pill" href="#pills-req" role="tab">1. Official Requirements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-hours-tab" data-toggle="pill" href="#pills-hours" role="tab">2. Hours calculation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-exam-tab" data-toggle="pill" href="#pills-exam" role="tab">3. Exams (NPTE)</a>
                    </li>
                </ul>
                
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-req" role="tabpanel">
                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="las la-info-circle"></i> You need a **DPT (Doctor of Physical Therapy)** or equivalent Master's degree. Bachelor's degrees from Egypt often require a **Credential Evaluation (FCCPT)**.
                        </div>
                        <p>Detailed checklist of documents needed...</p>
                    </div>
                    <div class="tab-pane fade" id="pills-hours" role="tabpanel">
                        <div class="card bg-light border-0 p-3">
                            <h5>Theory vs. Clinical Hours</h5>
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">Theory (Required: 1200 hrs)</div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">Clinical (Required: 800 hrs)</div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-exam" role="tabpanel">
                         <h5>NPTE (National Physical Therapy Examination)</h5>
                         <p>Passing score: 600/800. Only 4 attempts allowed per year.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
