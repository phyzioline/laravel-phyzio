<?php

Route::group(['middleware' => ['auth', 'therapist'], 'prefix' => 'therapist', 'as' => 'therapist.'], function () {
    // Therapist Onboarding Routes
    Route::group(['prefix' => 'onboarding', 'as' => 'onboarding.'], function () {
        Route::get('/step1', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep1'])->name('step1');
        Route::post('/step1', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'postStep1'])->name('step1.post');
        
        Route::get('/step2', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep2'])->name('step2');
        Route::post('/step2', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'postStep2'])->name('step2.post');
        
        Route::get('/step3', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep3'])->name('step3');
        Route::post('/step3', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'postStep3'])->name('step3.post');
        
        Route::get('/step4', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep4'])->name('step4');
        Route::post('/step4', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'postStep4'])->name('step4.post');
        
        Route::get('/step5', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep5'])->name('step5');
        Route::post('/step5', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'postStep5'])->name('step5.post');
        
        Route::get('/step6', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'showStep6'])->name('step6');
        Route::post('/submit', [\App\Http\Controllers\Therapist\TherapistOnboardingController::class, 'submit'])->name('submit');
    });

    Route::get('/dashboard', [\App\Http\Controllers\Therapist\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/appointments', [\App\Http\Controllers\Therapist\AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/status', [\App\Http\Controllers\Therapist\AppointmentController::class, 'updateStatus'])->name('appointments.status');
    
    Route::get('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'update'])->name('availability.update');
});
