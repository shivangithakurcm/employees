<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\LeadApiController;

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
    Route::get('/leads/all-history',             [LeadApiController::class, 'allHistory']); // ✅ {id} se pehle
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
    Route::get('/leads/{id}/history',            [LeadApiController::class, 'history']); // ✅ particular lead history
});