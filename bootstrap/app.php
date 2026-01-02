<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckNotification;
use App\Http\Middleware\NotAdmin;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/dashboard.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configure Authenticate middleware to use locale-aware login route
        $middleware->redirectGuestsTo(function () {
            $locale = app()->getLocale() ?: 'en';
            return route('view_login.' . $locale);
        });
        
        $middleware->alias([
            'not_admin'             => NotAdmin::class,
            'notification'          => CheckNotification::class,
            'web_auth'              => RedirectIfNotAuthenticated::class,
            'admin'                 => AdminMiddleware::class,
            'therapist'             => \App\Http\Middleware\TherapistMiddleware::class,
            'clinic.role'           => \App\Http\Middleware\ClinicRoleMiddleware::class,
            'auto.logout'           => \App\Http\Middleware\AutoLogoutMiddleware::class,
            /**** OTHER MIDDLEWARE ALIASES ****/
            'localize'              => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'  => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'  => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'        => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]);
        
        // Auto-detect currency on web requests
        $middleware->web(append: [
            \App\Http\Middleware\SetCurrencyMiddleware::class,
            \App\Http\Middleware\SetLocaleFromUrl::class, // Set locale from URL before routes
            \App\Http\Middleware\AutoLogoutMiddleware::class, // Auto logout after 30 minutes inactivity
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
