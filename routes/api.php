<?php

use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PresenceRecapController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalaryParameterController;
use App\Http\Controllers\WorkAgreementController;
use App\Http\Controllers\WorkingTimeController;
use Illuminate\Support\Facades\Route;

// L
Route::controller(LeaveController::class)->group(function () {
    Route::get('leaves', 'index')->middleware('auth.access');
    Route::get('leaves/{id}', 'show')->middleware('auth.access');
    Route::get('datatables/leaves', 'datatables')->middleware('auth.access');
    Route::post('leaves', 'store')->middleware('auth.access');
    Route::put('leaves/{id}', 'update')->middleware('auth.access');
    Route::delete('leaves/{id}', 'destroy')->middleware('auth.access');
});

// P
Route::controller(PayrollController::class)->group(function () {
    Route::get('payrolls', 'index')->middleware('auth.access');
    Route::get('payrolls/{id}', 'show')->middleware('auth.access');
    Route::get('payrolls-generate', 'generate')->middleware('auth.access');
    Route::get('datatables/payrolls', 'datatables')->middleware('auth.access');
    Route::post('payrolls', 'store')->middleware('auth.access');
    Route::put('payrolls/{id}', 'update')->middleware('auth.access');
    Route::delete('payrolls/{id}', 'destroy')->middleware('auth.access');
});

Route::controller(PresenceRecapController::class)->group(function () {
    Route::get('presence_recaps', 'index')->middleware('auth.access');
    Route::get('presence_recaps/{id}', 'show')->middleware('auth.access');
    Route::get('datatables/presence_recaps', 'datatables')->middleware('auth.access');
    Route::post('presence_recaps', 'store')->middleware('auth.access');
    Route::put('presence_recaps/{id}', 'update')->middleware('auth.access');
    Route::delete('presence_recaps/{id}', 'destroy')->middleware('auth.access');
});

// S
Route::controller(SalaryController::class)->group(function () {
    Route::get('salaries', 'index')->middleware('auth.access');
    Route::get('salaries/{id}', 'show')->middleware('auth.access');
    Route::get('datatables/salaries', 'datatables')->middleware('auth.access');
    Route::post('salaries', 'store')->middleware('auth.access');
    Route::put('salaries/{id}', 'update')->middleware('auth.access');
    Route::put('salaries/{id}/restore', 'restore')->middleware('auth.access');
    Route::delete('salaries/{id}', 'destroy')->middleware('auth.access');
});

Route::controller(SalaryParameterController::class)->group(function () {
    Route::get('salary_parameters', 'index')->middleware('auth.access');
    Route::get('salary_parameters/{id}', 'show')->middleware('auth.access');
    Route::get('datatables/salary_parameters', 'datatables')->middleware('auth.access');
    Route::post('salary_parameters', 'store')->middleware('auth.access');
    Route::put('salary_parameters/{id}', 'update')->middleware('auth.access');
    Route::delete('salary_parameters/{id}', 'destroy')->middleware('auth.access');
});

// W
Route::controller(WorkAgreementController::class)->group(function () {
    Route::get('work_agreements', 'index')->middleware('auth.access');
    Route::get('work_agreements/{id}', 'show')->middleware('auth.access');
    Route::get('work_agreements-get-by-code', 'getByCode')->middleware('auth.access');
    Route::get('work_agreements-get-code', 'getCode')->middleware('auth.access');
    Route::get('datatables/work_agreements', 'datatables')->middleware('auth.access');
    Route::post('work_agreements', 'store')->middleware('auth.access');
    Route::put('work_agreements/{id}', 'update')->middleware('auth.access');
    Route::put('work_agreements/{id}/restore', 'restore')->middleware('auth.access');
    Route::delete('work_agreements/{id}', 'destroy')->middleware('auth.access');
});

Route::controller(WorkingTimeController::class)->group(function () {
    Route::get('working_times', 'index')->middleware('auth.access');
    Route::get('working_times/{id}', 'show')->middleware('auth.access');
    Route::get('datatables/working_times', 'datatables')->middleware('auth.access');
    Route::post('working_times', 'store')->middleware('auth.access');
    Route::put('working_times/{id}', 'update')->middleware('auth.access');
    Route::delete('working_times/{id}', 'destroy')->middleware('auth.access');
});
