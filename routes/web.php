<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('request');
//});

// launch the support page in browser
Route::get('/', [SupportRequestController::class, 'index'])->name('request.homepage');

// process the request after user form submission
Route::post('process', [SupportRequestController::class, 'process_request'])->name('request.process');

// launch the login page
Route::get('auth/login', [UserController::class, 'index'])->name('login');

// process the login
Route::post('auth/login', [UserController::class, 'login'])->name('login');

// logout
Route::post('auth/logout', [UserController::class, 'logout'])->name('auth.logout');

// launch the dashboard to see unattended requests
Route::get('private/dashboard/unattended', [DashboardController::class, 'index'])->name('private.dashboard');

// launch the dashboard to see attended requests
Route::get('private/dashboard/attended', [DashboardController::class, 'attended'])->name('private.dashboard.attended');

// individual request
Route::get('private/dashboard/open_request/{support_request}', [DashboardController::class, 'open_request'])->name('private.open_request');

// reset user/student password
Route::post('auth/user/{user}/{support_request}/password_reset', [UserController::class, 'reset_student_password'])->name('user.password_reset');

// open aris
Route::post('support_request/{support_request}/open_aris/', [SupportRequestController::class, 'attend_other_support'])->name('attend_other_support');
