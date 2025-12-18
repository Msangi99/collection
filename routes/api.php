<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SpecialHireApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Special Hire API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('special-hire')->middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SpecialHireApiController::class, 'dashboard']);
    Route::get('/dashboard/earnings', [SpecialHireApiController::class, 'earnings']);

    // Coasters CRUD
    Route::get('/coasters', [SpecialHireApiController::class, 'indexCoasters']);
    Route::get('/coasters/{id}', [SpecialHireApiController::class, 'showCoaster']);
    Route::post('/coasters', [SpecialHireApiController::class, 'storeCoaster']);
    Route::put('/coasters/{id}', [SpecialHireApiController::class, 'updateCoaster']);
    Route::delete('/coasters/{id}', [SpecialHireApiController::class, 'destroyCoaster']);

    // Location Tracking
    Route::get('/coasters/locations/all', [SpecialHireApiController::class, 'allLocations']);
    Route::get('/coasters/{id}/location', [SpecialHireApiController::class, 'getLocation']);
    Route::put('/coasters/{id}/location', [SpecialHireApiController::class, 'updateLocation']);

    // Orders CRUD
    Route::get('/orders', [SpecialHireApiController::class, 'indexOrders']);
    Route::get('/orders/{id}', [SpecialHireApiController::class, 'showOrder']);
    Route::post('/orders', [SpecialHireApiController::class, 'storeOrder']);
    Route::put('/orders/{id}', [SpecialHireApiController::class, 'updateOrder']);
    Route::delete('/orders/{id}', [SpecialHireApiController::class, 'destroyOrder']);

    // Price Calculation
    Route::post('/calculate-price', [SpecialHireApiController::class, 'calculatePrice']);

    // Pricing Management
    Route::get('/pricing/{coasterId}', [SpecialHireApiController::class, 'getPricing']);
    Route::put('/pricing/{coasterId}', [SpecialHireApiController::class, 'updatePricing']);
});
