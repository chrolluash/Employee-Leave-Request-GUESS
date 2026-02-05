<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Guest routes (Login only - NO public registration)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Employee routes
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/leave-request/create', [EmployeeController::class, 'create'])->name('leave.create');
    Route::post('/leave-request', [EmployeeController::class, 'store'])->name('leave.store');
    Route::get('/leave-request/{leaveRequest}', [EmployeeController::class, 'show'])->name('leave.show');
    Route::delete('/leave-request/{leaveRequest}', [EmployeeController::class, 'destroy'])->name('leave.destroy');
    
    // PDF Download for Employee
    Route::get('/leave-request/{id}/download', [EmployeeController::class, 'downloadPdf'])->name('leave.download');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Leave Requests
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/leave-request/{leaveRequest}', [AdminController::class, 'show'])->name('leave.show');
    Route::post('/leave-request/{leaveRequest}/approve', [AdminController::class, 'approve'])->name('leave.approve');
    Route::post('/leave-request/{leaveRequest}/reject', [AdminController::class, 'reject'])->name('leave.reject');
    
    // PDF Download for Admin
    Route::get('/leave-request/{id}/download', [AdminController::class, 'downloadPdf'])->name('leave.download');
    
    // Employee Management
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::get('/employees/create', [AdminController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/employees/{user}/edit', [AdminController::class, 'editEmployee'])->name('employees.edit');
    Route::put('/employees/{user}', [AdminController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{user}', [AdminController::class, 'destroyEmployee'])->name('employees.destroy');
});