<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\LmsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\Master\DesignationController;
use App\Http\Controllers\Admin\Master\ProjectTypeController;
use App\Http\Controllers\Admin\Master\ShiftController;
use App\Http\Controllers\Admin\FollowUpController; // ✅ Admin namespace

Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Auth Routes ─────────────────────────────────────────────────────────────
Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');

// ─── Admin Protected Routes ───────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/index', fn() => view('index'))->name('index');
    // Admin group ke andar, dashboard route ke neeche add karo
Route::get('/employee/dashboard', [App\Http\Controllers\Admin\EmployeeDashboardController::class, 'index'])->name('employee.dashboard');

    // ✅ Follow-up Routes — admin group ke andar
    Route::prefix('followups')->name('followups.')->group(function () {
        Route::get('/today',        [FollowUpController::class, 'today'])->name('today');
        Route::post('/store',       [FollowUpController::class, 'store'])->name('store');
        Route::patch('/{id}/done',  [FollowUpController::class, 'markDone'])->name('done');
    });

    // ── Employee Routes ───────────────────────────────────────────────────────
    Route::get('/employees',              [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create',       [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees/store-basic', [EmployeeController::class, 'storeBasicInfo'])->name('employees.storeBasicInfo');
    Route::get('/employees/{id}/show',    [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{id}/edit',    [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}',         [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('/employees/{id}/toggle', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle');
    Route::get('/employees/{id}/step2',   [EmployeeController::class, 'step2'])->name('employees.step2');
    Route::post('/employees/{id}/step2',  [EmployeeController::class, 'saveStep2'])->name('employees.saveStep2');
    Route::get('/employees/{id}/step3',   [EmployeeController::class, 'step3'])->name('employees.step3');
    Route::post('/employees/{id}/step3',  [EmployeeController::class, 'saveStep3'])->name('employees.saveStep3');
    Route::get('/employees/{id}/step4',   [EmployeeController::class, 'step4'])->name('employees.step4');
    Route::post('/employees/{id}/step4',  [EmployeeController::class, 'saveStep4'])->name('employees.saveStep4');
    Route::get('/employees/{id}/step5',   [EmployeeController::class, 'step5'])->name('employees.step5');
    Route::post('/employees/{id}/step5',  [EmployeeController::class, 'saveStep5'])->name('employees.saveStep5');

    // ── LMS Routes ────────────────────────────────────────────────────────────
    Route::get('/lms',                [LmsController::class, 'index'])->name('lms.index');
    Route::post('/lms',               [LmsController::class, 'store'])->name('lms.store');
    Route::post('/lms/action',        [LmsController::class, 'action'])->name('lms.action');
    Route::get('/lms/history/{id}',   [LmsController::class, 'history'])->name('lms.history');
    Route::get('/lms/{lm}',           [LmsController::class, 'show'])->name('lms.show');
    Route::get('/lms/{lm}/edit',      [LmsController::class, 'edit'])->name('lms.edit');
    Route::put('/lms/{lm}',           [LmsController::class, 'update'])->name('lms.update');
    Route::delete('/lms/{lm}',        [LmsController::class, 'destroy'])->name('lms.destroy');
    Route::patch('/lms/{lm}/assign',  [LmsController::class, 'assign'])->name('lms.assign');

    // ── Customer Routes ───────────────────────────────────────────────────────
    Route::resource('customers', CustomerController::class)
          ->only(['index', 'show', 'edit', 'update', 'destroy']);

    // ── Master Routes ─────────────────────────────────────────────────────────
    Route::prefix('master')->name('master.')->group(function () {

        Route::get('/designation',         [DesignationController::class, 'index'])->name('designation.index');
        Route::post('/designation',        [DesignationController::class, 'store'])->name('designation.store');
        Route::put('/designation/{id}',    [DesignationController::class, 'update'])->name('designation.update');
        Route::delete('/designation/{id}', [DesignationController::class, 'destroy'])->name('designation.destroy');

        Route::get('/project-type',         [ProjectTypeController::class, 'index'])->name('project_type.index');
        Route::post('/project-type',        [ProjectTypeController::class, 'store'])->name('project_type.store');
        Route::put('/project-type/{id}',    [ProjectTypeController::class, 'update'])->name('project_type.update');
        Route::delete('/project-type/{id}', [ProjectTypeController::class, 'destroy'])->name('project_type.destroy');

        Route::get('/shift',                [ShiftController::class, 'index'])->name('shift.index');
        Route::get('/shift/create',         [ShiftController::class, 'create'])->name('shift.create');
        Route::post('/shift',               [ShiftController::class, 'store'])->name('shift.store');
        Route::get('/shift/{id}/edit',      [ShiftController::class, 'edit'])->name('shift.edit');
        Route::put('/shift/{id}',           [ShiftController::class, 'update'])->name('shift.update');
        Route::delete('/shift/{id}',        [ShiftController::class, 'destroy'])->name('shift.destroy');

    });

});