<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;


Route::get('/employees', [EmployeeApiController::class, 'index']);


Route::get('/employees/{id}', [EmployeeApiController::class, 'show']);


Route::post('/employees', [EmployeeApiController::class, 'store']);


Route::post('/employees/update/{id}', [EmployeeApiController::class, 'update']);


Route::delete('/employees/{id}', [EmployeeApiController::class, 'destroy']);