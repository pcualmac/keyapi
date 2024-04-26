<?php

use Illuminate\Http\Request;

use App\Http\Controllers\AppOne\AppOneAuthAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestAuth;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API admins routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


    Route::post('register',[AppOneAuthAdmin::class, 'registerAdmin']);
    Route::post('login',[AppOneAuthAdmin::class, 'loginAdmin']);
    // Route::get('verify',[AppOneAuthAdmin::class, 'verifyToken']);

    Route::middleware(['auth:appadmin'])->group(function () {
        Route::get('verify',[AppOneAuthAdmin::class, 'verifyToken']);
    });