<?php

use App\Http\Controllers\API\AmbulanceController;
use App\Http\Controllers\API\CrewMemberController;
use App\Http\Controllers\API\CrewSettingsController;
use App\Http\Controllers\API\DispatchController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\HealthFacilityController;
use App\Http\Controllers\API\IncidentController;
use App\Http\Controllers\API\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('register', [UserController::class, 'register']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('/user/list', [UserController::class, 'list']);
    Route::apiResource('/user', UserController::class);

    Route::get('/health-facility/counters', [HealthFacilityController::class, 'counters']);
    Route::get('/health-facility/counters/{id}', [HealthFacilityController::class, 'countersPerFacility']);
    Route::get('/health-facility/options', [HealthFacilityController::class, 'assignablehealthFacilities']);
    Route::apiResource('/health-facility', HealthFacilityController::class);
    
    Route::get('/ambulance/options/{facilityId}', [AmbulanceController::class, 'assignableAmbulances']);
    Route::apiResource('/ambulance', AmbulanceController::class);
    
    Route::get('/driver/options/{facilityId}', [DriverController::class, 'assignableDrivers']);
    Route::apiResource('/driver', DriverController::class);
    
    Route::get('/incident/{id}', [IncidentController::class, 'show']);
    Route::apiResource('/incident', IncidentController::class);
    
    Route::get('/squad/options/{facilityId}', [CrewSettingsController::class, 'assignableSquads']);
    Route::apiResource('/squad', CrewSettingsController::class);
    
    Route::get('/crew/{id}', [CrewMemberController::class, 'index']);
    Route::apiResource('/crew', CrewMemberController::class);
    
    Route::get('/dispatch/active-dispatch', [DispatchController::class, 'getActiveDispatch']);
    Route::get('/dispatch/counters', [DispatchController::class, 'getDispatchCounters']);
    Route::get('/dispatch/reports/counters', [DispatchController::class, 'getReportsCounters']);
    Route::get('/dispatch/reports', [DispatchController::class, 'reports']);
    Route::get('/dispatch/emt-dispatches', [DispatchController::class, 'emtDispatches']);
    Route::get('/dispatch/export', [DispatchController::class, 'export']);
    Route::apiResource('/dispatch', DispatchController::class);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unreadNotifications']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
});
