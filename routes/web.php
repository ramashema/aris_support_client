<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
Route::get('auth/login', [UserController::class, 'index'])->name('auth.login');

// process the login
Route::post('auth/login', [UserController::class, 'login'])->name('auth.login');

// show user registration form
Route::get('auth/register', [UserController::class, 'user_registration'])->name('auth.register');

// process user registration
Route::post('auth/register', [UserController::class, 'process_user_registration'])->name('auth.register');

// logout
Route::post('auth/logout', [UserController::class, 'logout'])->name('auth.logout');

// launch the dashboard to see unattended requests
Route::get('private/dashboard/unattended', [DashboardController::class, 'index'])->name('private.dashboard');

// launch the dashboard to see attended requests
Route::get('private/dashboard/attended', [DashboardController::class, 'attended'])->name('private.dashboard.attended');

// individual request
Route::get('private/dashboard/open_request/{support_request}', [DashboardController::class, 'open_request'])->name('private.open_request');

// reset user/student password
Route::post('auth/user/{support_request}/password_reset', [UserController::class, 'reset_student_password'])->name('user.password_reset');

// open aris
Route::post('support_request/{support_request}/open_aris/', [SupportRequestController::class, 'attend_other_support'])->name('attend_other_support');

//// routes that contain necessary logic for user email verification
//Auth::routes(['verify' => true]);
////Auth::routes();

// user create password
Route::get('auth/user/create_password', [UserController::class, 'create_user_password_page'])->name('auth.create_user_password_page');
Route::post('auth/user/create_password/{user}', [UserController::class, 'create_user_password'])->name('auth.create_user_password');


// get all users
Route::get('private/users', [UserController::class, 'all_users'])->name('private.users_list');

// get individual user page
Route::get('private/user/{user}', [UserController::class, 'show_user'])->name('private.user');

// get activate deactivate confirmation page
Route::get('private/user/{user}/activate_deactivate', [UserController::class, 'activation_deactivation_confirmation'])->name('user.activate_deactivate');

// process user activation or deactivation
Route::post('private/user/{user}/activate_deactivate', [UserController::class, 'activation_deactivation'])->name('user.activate_deactivate');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
