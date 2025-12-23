@extends('dashboard.pages.finance.layout')

@section('finance-content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-2">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Reports Repository</h6>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-pdf display-4 text-danger mb-3"></i>
                                <h6>Monthly Earnings Report</h6>
                                <p class="text-muted small">Generate PDF report of monthly earnings</p>
                                <button class="btn btn-sm btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-excel display-4 text-success mb-3"></i>
                                <h6>Transaction Export</h6>
                                <p class="text-muted small">Export all transactions to Excel</p>
                                <button class="btn btn-sm btn-success">Export Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up display-4 text-info mb-3"></i>
                                <h6>Analytics Report</h6>
                                <p class="text-muted small">View detailed analytics and trends</p>
                                <button class="btn btn-sm btn-info">View Analytics</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Period</th>
                                <th>Generated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No reports generated yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

