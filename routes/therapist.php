<?php

Route::group(['middleware' => ['auth', 'therapist'], 'prefix' => 'therapist', 'as' => 'therapist.'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Therapist\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/appointments', [\App\Http\Controllers\Therapist\AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/status', [\App\Http\Controllers\Therapist\AppointmentController::class, 'updateStatus'])->name('appointments.status');
    
    Route::get('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'update'])->name('availability.update');
});
