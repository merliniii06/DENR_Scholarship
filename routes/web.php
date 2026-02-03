<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Home routes
Route::get('/', [HomeController::class, 'welcome']);
Route::get('/home', [HomeController::class, 'home']);

// User routes
Route::get('/apply', [UserController::class, 'showApplicationType']);
Route::get('/apply/form', [UserController::class, 'showApplicationForm']);
Route::post('/apply/denr-scholar', [UserController::class, 'submitDenrScholar']);
Route::post('/apply/study-non-study', [UserController::class, 'submitStudyNonStudy']);
Route::post('/apply/permit-to-study', [UserController::class, 'submitPermitToStudy']);
Route::post('/apply/study-leave', [UserController::class, 'submitStudyLeave']);

// Admin routes
Route::get('/admin_login', [AdminController::class, 'showLogin']);
Route::post('/admin_login', [AdminController::class, 'login']);
Route::post('/admin_logout', [AdminController::class, 'logout']);
Route::get('/admin_home', [AdminController::class, 'showHome']);
Route::get('/admin/api/applications', [AdminController::class, 'getApplicationsJson']);
Route::get('/admin_home/today', [AdminController::class, 'viewTodaysApplications'])->name('admin.today');
Route::get('/admin_home/week', [AdminController::class, 'viewThisWeekApplications'])->name('admin.week');
Route::get('/admin_home/month', [AdminController::class, 'viewThisMonthApplications'])->name('admin.month');
Route::post('/admin/applications/{id}/confirm', [AdminController::class, 'confirmApplication'])->name('admin.applications.confirm');
Route::post('/admin/applications/{id}/delete', [AdminController::class, 'deleteApplication'])->name('admin.applications.delete');
