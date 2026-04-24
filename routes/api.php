<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\LeadApiController;

// Public routes
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/index',  [AuthController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/user',     [AuthController::class, 'user']);
    Route::get('/profile',  function (Request $request) {
        return $request->user();
    });

    // Employee routes
    Route::get('/employees',                     [EmployeeApiController::class, 'index']);
    Route::get('/employees/{id}',                [EmployeeApiController::class, 'show']);
    Route::post('/employees',                    [EmployeeApiController::class, 'store']);
    Route::post('/employees/update-multiple',    [EmployeeApiController::class, 'updateMultiple']);
    Route::delete('/employees/destroy-multiple', [EmployeeApiController::class, 'destroyMultiple']);

    // ✅ Leads routes — specific routes pehle, dynamic baad me
    Route::get('/leads',                      [LeadApiController::class, 'index']);
    Route::post('/leads',                     [LeadApiController::class, 'store']);
    Route::post('/leads/update-multiple',     [LeadApiController::class, 'updateMultiple']);
    Route::delete('/leads/destroy-multiple',  [LeadApiController::class, 'destroyMultiple']); // ✅ body me ids[]
    Route::get('/leads/{id}',                 [LeadApiController::class, 'show']);
    Route::put('/leads/{id}',                 [LeadApiController::class, 'update']);
    Route::post('/leads/{id}/action',                 [LeadApiController::class, 'action']);


});