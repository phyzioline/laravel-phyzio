@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('My Schedule') }}</h2>
            <p class="text-muted">{{ __('Manage your availability and time slots') }}</p>
        </div>
        <div>
             <button class="btn btn-white shadow-sm mr-2"><i class="las la-cog"></i> {{ __('Settings') }}</button>
             <button class="btn btn-primary shadow-sm"><i class="las la-plus"></i> {{ __('Add Slot') }}</button>
        </div>
    </div>

    <!-- Calendar Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-clock text-primary mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $availableSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Available Slots') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-calendar-check text-success mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $bookedSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Booked Slots') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-ban text-warning mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $blockedSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Blocked Slots') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-percent text-info mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $utilizationRate }}%</h3>
                    <p class="text-muted small mb-0">{{ __('Utilization Rate') }}</p>
                </div>
             </div>
        </div>
    </div>

    <!-- Calendar View (Mockup of Weekly View) -->
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                 <button class="btn btn-light btn-sm mr-2"><i class="las la-chevron-left"></i></button>
                 <h5 class="mb-0 font-weight-bold text-dark mx-2">December 2024</h5>
                 <button class="btn btn-light btn-sm ml-2"><i class="las la-chevron-right"></i></button>
            </div>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary">Week</button>
                <button type="button" class="btn btn-primary">Month</button>
                <button type="button" class="btn btn-outline-secondary">Day</button>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Simple Responsive Calendar Grid -->
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center table-calendar">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 14%;">Sun</th>
                            <th style="width: 14%;">Mon</th>
                            <th style="width: 14%;">Tue</th>
                            <th style="width: 14%;">Wed</th>
                            <th style="width: 14%;">Thu</th>
                            <th style="width: 14%;">Fri</th>
                            <th style="width: 14%;">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                         <!-- Week 1 -->
                        <tr style="height: 100px;">
                            <td class="text-muted bg-light">
                                <div class="text-left small mb-1">30</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">1</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">9:00 AM</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">2:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">2</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">10:00 AM</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">3:00 PM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">3</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">11:00 AM</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">12:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">4</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">10:00 AM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">5</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">2:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">6</div>
                            </td>
                        </tr>

                        <!-- Week 2 -->
                         <tr style="height: 100px;">
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">7</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">8</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">9:00 AM</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">1:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">9</div>
                                 <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">10:00 AM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">10</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">11:00 AM</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">3:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">11</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">9:00 AM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">12</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">2:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">13</div>
                            </td>
                        </tr>
                        
                         <!-- Week 3 -->
                         <tr style="height: 100px;">
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">14</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">15</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">10:00 AM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">16</div>
                                 <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">9:00 AM</div>
                                 <div class="badge badge-success d-block mb-1 py-1 text-left px-2">2:00 PM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">17</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">11:00 AM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">18</div>
                                <div class="badge badge-danger d-block mb-1 py-1 text-left px-2">10:00 AM</div>
                            </td>
                             <td>
                                <div class="text-left small mb-1 font-weight-bold">19</div>
                                <div class="badge badge-success d-block mb-1 py-1 text-left px-2">3:00 PM</div>
                            </td>
                            <td>
                                <div class="text-left small mb-1 font-weight-bold">20</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
