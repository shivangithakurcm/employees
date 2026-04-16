<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\AuthController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Employee routes


// Protected routes - Sanctum token chahiye
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', function (Request $request) {
        return $request->user();

        
    });

    Route::get('/employees', [EmployeeApiController::class, 'index']);
Route::get('/employees/{id}', [EmployeeApiController::class, 'show']);
Route::post('/employees', [EmployeeApiController::class, 'store']);
Route::post('/employees/update-multiple', [EmployeeApiController::class, 'updateMultiple']);
Route::delete('/employees/destroy-multiple', [EmployeeApiController::class, 'destroyMultiple']);

});


Route::post('/index', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});