<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\LeadApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Admin\Master\DesignationController;
use App\Http\Controllers\Admin\Master\ProjectTypeController;
use App\Http\Controllers\Admin\Master\ShiftController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/index', [AuthController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    // Employee routes
    Route::get('/employees',                     [EmployeeApiController::class, 'index']);
    Route::get('/employees/{id}',                [EmployeeApiController::class, 'show']);
    Route::post('/employees',                    [EmployeeApiController::class, 'store']);
    Route::post('/employees/update-multiple',    [EmployeeApiController::class, 'updateMultiple']);
    Route::delete('/employees/destroy-multiple', [EmployeeApiController::class, 'destroyMultiple']);

    // ✅ Static routes PEHLE — {id} se conflict avoid
    Route::get('/leads',                         [LeadApiController::class, 'index']);
    Route::post('/leads',                        [LeadApiController::class, 'store']);
    Route::get('/leads/counts',                  [LeadApiController::class, 'getCounts']);
    Route::get('/leads/search',                  [LeadApiController::class, 'search']);
    Route::get('/leads/all-history',             [LeadApiController::class, 'allHistory']);
    Route::post('/leads/update-multiple',        [LeadApiController::class, 'updateMultiple']);
    Route::delete('/leads/destroy-multiple',     [LeadApiController::class, 'destroyMultiple']);
    Route::get('/follow-ups',                    [LeadApiController::class, 'getAllFollowUps']);

    // ✅ {id} wale routes baad mein
    Route::get('/leads/{id}',                    [LeadApiController::class, 'show']);
    Route::put('/leads/{id}',                    [LeadApiController::class, 'update']);
    Route::get('/leads/{id}/detail',             [LeadApiController::class, 'getLeadDetail']);
    Route::post('/leads/{id}/action',            [LeadApiController::class, 'action']);
    Route::post('/leads/{id}/follow-up',         [LeadApiController::class, 'followUp']);
    Route::get('/leads/{id}/follow-ups',         [LeadApiController::class, 'getFollowUps']);
    Route::post('/leads/{id}/call-schedule',     [LeadApiController::class, 'callSchedule']);
    Route::post('/leads/{id}/call-back',         [LeadApiController::class, 'callBackRequired']);
    Route::post('/leads/{id}/qualified',         [LeadApiController::class, 'qualified']);
    Route::post('/leads/{id}/proposal',          [LeadApiController::class, 'proposalSent']);
    Route::post('/leads/{id}/won',               [LeadApiController::class, 'won']);
    Route::post('/leads/{id}/lost',              [LeadApiController::class, 'lost']);
    Route::post('/leads/{id}/draft',             [LeadApiController::class, 'draft']);
    Route::get('/leads/{id}/history',            [LeadApiController::class, 'history']);

    // Customer routes
    Route::prefix('customers')->group(function () {
        Route::get('/',        [CustomerApiController::class, 'index']);
        Route::post('/',       [CustomerApiController::class, 'store']);
        Route::get('/{id}',    [CustomerApiController::class, 'show']);
        Route::put('/{id}',    [CustomerApiController::class, 'update']);
        Route::delete('/{id}', [CustomerApiController::class, 'destroy']);
    });

    // Master routes
    Route::prefix('master')->group(function () {

        // Designation
        Route::get('/designations',         [DesignationController::class, 'index']);
        Route::post('/designations',        [DesignationController::class, 'store']);
        Route::put('/designations/{id}',    [DesignationController::class, 'update']);
        Route::delete('/designations/{id}', [DesignationController::class, 'destroy']);

        // Project Type
        Route::get('/project-types',         [ProjectTypeController::class, 'index']);
        Route::post('/project-types',        [ProjectTypeController::class, 'store']);
        Route::put('/project-types/{id}',    [ProjectTypeController::class, 'update']);
        Route::delete('/project-types/{id}', [ProjectTypeController::class, 'destroy']);

        // Shift
        Route::get('/shifts',         [ShiftController::class, 'index']);
        Route::post('/shifts',        [ShiftController::class, 'store']);
        Route::put('/shifts/{id}',    [ShiftController::class, 'update']);
        Route::delete('/shifts/{id}', [ShiftController::class, 'destroy']);
    });

});