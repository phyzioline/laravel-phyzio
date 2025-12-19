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

    // Dashboard & Profile
    Route::get('/dashboard', [\App\Http\Controllers\Therapist\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Therapist\ProfileController::class, 'update'])->name('profile.update');
    
    // Home Visits
    Route::get('/home_visits', [\App\Http\Controllers\Therapist\HomeVisitController::class, 'index'])->name('home_visits.index');
    Route::post('/home_visits/{id}/status', [\App\Http\Controllers\Therapist\HomeVisitController::class, 'updateStatus'])->name('home_visits.status');
    
    // Availability / Schedule (Updated)
    Route::get('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability', [\App\Http\Controllers\Therapist\AvailabilityController::class, 'update'])->name('availability.update');
    Route::get('/schedule', [\App\Http\Controllers\Therapist\ScheduleController::class, 'index'])->name('schedule.index');

    // Patients (New)
    Route::get('/patients', [\App\Http\Controllers\Therapist\PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [\App\Http\Controllers\Therapist\PatientController::class, 'create'])->name('patients.create');
    Route::get('/patients/{id}', [\App\Http\Controllers\Therapist\PatientController::class, 'show'])->name('patients.show');
    
    // Home Visit Details
    Route::get('/home_visits/{id}', [\App\Http\Controllers\Therapist\HomeVisitController::class, 'show'])->name('home_visits.show');

    // Earnings (New)
    Route::get('/earnings', [\App\Http\Controllers\Therapist\EarningsController::class, 'index'])->name('earnings.index');

    // Notifications (New)
    Route::get('/notifications', [\App\Http\Controllers\Therapist\NotificationController::class, 'index'])->name('notifications.index');
    
    // Course Management
    Route::resource('courses', \App\Http\Controllers\Instructor\CourseController::class);
});
