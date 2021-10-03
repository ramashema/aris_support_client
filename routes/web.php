<?php

use App\Http\Controllers\SupportRequestController;
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
