<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes (no middleware)
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/index', function () {
        return view('index');
    })->name('index');

    // Employee Routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees/store-basic', [EmployeeController::class, 'storeBasicInfo'])->name('employees.storeBasicInfo');
    Route::get('/employees/{id}/show', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    // Step 2 - Educational Qualification
    Route::get('/employees/{id}/step2', [EmployeeController::class, 'step2'])->name('employees.step2');
    Route::post('/employees/{id}/step2', [EmployeeController::class, 'saveStep2'])->name('employees.saveStep2');

    // Step 3 - Previous Employer
    Route::get('/employees/{id}/step3', [EmployeeController::class, 'step3'])->name('employees.step3');
    Route::post('/employees/{id}/step3', [EmployeeController::class, 'saveStep3'])->name('employees.saveStep3');

    // Step 4 - Bank Details
    Route::get('/employees/{id}/step4', [EmployeeController::class, 'step4'])->name('employees.step4');
    Route::post('/employees/{id}/step4', [EmployeeController::class, 'saveStep4'])->name('employees.saveStep4');

    // Step 5 - Official Details
    Route::get('/employees/{id}/step5', [EmployeeController::class, 'step5'])->name('employees.step5');
    Route::post('/employees/{id}/step5', [EmployeeController::class, 'saveStep5'])->name('employees.saveStep5');

    Route::post('/employees/{id}/toggle', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle');
});