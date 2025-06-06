<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PotentialClientController;
use App\Http\Controllers\TinkerController;
use Illuminate\Support\Facades\Route;


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return redirect()->route('welcome');
        });

        Route::get('/welcome', function () {
            return view('welcome');
        })->name('welcome'); // Define the name for the welcome route

        Route::post('/subscribe', [SubscriptionController::class, 'subscribeToPlan'])->name('subscribe');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/dashboard', function () {
                return view('admin_dashboard.dashboard');
            })->name('dashboard');

            Route::get('/tenant-list', function () {
                return view('admin_dashboard.tenantlist');
            })->name('tenant-list');

            Route::get('/potential_clients', function () {
                return view('admin_dashboard.potential_clients');
            })->name('potential_clients');

            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // Define a route that maps to the destroy method of the PotentialClientController
            Route::delete('/potential-clients/{potentialClient}', [PotentialClientController::class, 'destroy'])->name('potentialClients.destroy');
            Route::get('/potential_clients', [PotentialClientController::class, 'index'])->name('potential_clients');
            Route::post('/execute-tinker', [TinkerController::class, 'execute']);
            Route::get('/fetch-tenants', [TinkerController::class, 'fetchTenants']);
            Route::delete('/delete-tenant/{id}', [TinkerController::class, 'deleteTenant']);
        });
    });
}

Route::prefix('admin')->group(function () {
    Route::get('/tenants', function () {
        // Admin dashboard or tenant management page
    });
});

Route::get("/getMyPlan/{id}", function(){
    return "Your plan is 199";
});

require __DIR__.'/auth.php';
