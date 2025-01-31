<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantControllers\TenantLoginController;
use App\Http\Controllers\TenantControllers\TouristSpotController;
use App\Http\Controllers\TenantControllers\DashboardController;
use App\Http\Controllers\TenantControllers\HomeController;
use App\Http\Controllers\TenantControllers\ProfileController;
use App\Http\Controllers\TenantControllers\SubscriptionController;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route for the login page
    Route::get('/', [HomeController::class, 'showDashboard'])->name('tenanthome');

    // Route for handling the login form submission
    Route::get('/tenantlogin', function () { return view('tenantviews.tenantlogin'); })->name('tenantlogin');
    Route::post('/tenantlogin', [TenantLoginController::class, 'tenantlogin'])->name('tenantlogin_submit');
    Route::get('/logout', [TenantLoginController::class, 'tenantlogout'])->name('tenantlogout');

    // Middleware-like closure to check authentication for dashboard routes
    Route::group(['middleware' => function ($request, $next) {
        // Check if the user is authenticated as a tenant
        if (!Auth::check()) {
            return redirect()->route('tenantlogin'); // Redirect to the login page
        }
        return $next($request);
    }], function () {
        // Route for the dashboard
        Route::get('/tenantdashboard', [DashboardController::class, 'showDashboard'])->name('tenantdashboard');

        // Route for the tenant tour list
        Route::get('/tenantbhlist', [TouristSpotController::class, 'index'])->name('tenantlist');

        // Route for storing a new tourist spot
        Route::post('/touristspot', [TouristSpotController::class, 'store'])->name('touristspot.store');
        Route::get('/touristspot/{id}/delete', [TouristSpotController::class, 'destroy'])->name('touristspot.delete');
        // Route for editing a tourist spot (GET request)
        Route::get('/touristspot/{id}/edit', [TouristSpotController::class, 'edit'])->name('touristspot.edit');
        // Route for updating a tourist spot (PUT/PATCH request)
        Route::put('/touristspot/{touristSpot}', [TouristSpotController::class, 'update'])->name('touristspot.update');
        // Route for viewing a specific tourist spot
        Route::get('/touristspot/{id}', [TouristSpotController::class, 'show'])->name('touristspot.show');

        Route::get('/tenantprofile', [ProfileController::class, 'show'])->name('tenant.profile');
        Route::post('/tenantprofile', [ProfileController::class, 'update'])->name('tenant.profile.update');

        Route::post('/tenant/register', [TenantLoginController::class, 'register'])->name('tenant.register');

        Route::get('/subscriptions', [SubscriptionController::class, 'index']);



    });
});
